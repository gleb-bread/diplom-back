<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Str;
use App\Http\Requests\CreateNewComponentProjectRequest;
use App\Models\Page;
use App\Models\Folder;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProjectRightsController;
use App\Enums\ProjectRightEnum;
use App\Services\ProjectRights\ProjectRightsService;


class ProjectController extends Controller
{
    /**
     * Создать новый проект.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createProject(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:64',
            'user_id' => 'required|integer|exists:users,id',
            'private' => 'boolean',
            'archive' => 'boolean',
            'logo' => 'nullable|string',
            'type' => 'nullable|string|max:100',
        ]);

        $project = Project::create(array_merge($data, [
            'hash' => Str::random(64),
            'ref' => Str::random(64),
        ]));

        $user = Auth::user();

        ProjectRightsController::createRightAtProject($user, $project, ProjectRightEnum::Admin);

        return $this->sendResponse($project);
    }

    public function getStructure(int $projectId)
        {
            $user = Auth::user();
            $project = Project::find($projectId);

            if (!$project) {
                return $this->sendError('Проект не найден', 404);
            }

            // Проверка на право просмотра проекта
            if (!ProjectRightsService::hasPermissionShowProject($user, $project)) {
                return $this->sendError('У вас нет доступа к этому проекту', 403);
            }

            // Проверка на право редактирования
            $canEdit = ProjectRightsService::hasPermissionEditProject($user, $project);

            // Получаем все папки и страницы верхнего уровня
            $topLevelFolders = Folder::where('project_id', $projectId)
                ->whereNull('parent_id')
                ->get();

            $topLevelPages = Page::where('project_id', $projectId)
                ->whereNull('folder_id')
                ->get();

            // Рекурсивное построение структуры
            $buildFolderStructure = function (Folder $folder) use (&$buildFolderStructure, $canEdit) {
                $items = [];

                foreach ($folder->pages as $page) {
                    $pageData = $page->toArray();
                    $pageData['type'] = 'page';
                    $pageData['edit'] = $canEdit;
                    $items[] = $pageData;
                }

                foreach ($folder->children as $subFolder) {
                    $folderData = $subFolder->toArray();
                    $folderData['type'] = 'folder';
                    $folderData['edit'] = $canEdit;
                    $folderData['items'] = $buildFolderStructure($subFolder);
                    $items[] = $folderData;
                }

                return $items;
            };

            $result = [];

            foreach ($topLevelFolders as $folder) {
                $folderData = $folder->toArray();
                $folderData['type'] = 'folder';
                $folderData['edit'] = $canEdit;
                $folderData['items'] = $buildFolderStructure($folder);
                $result[] = $folderData;
            }

            foreach ($topLevelPages as $page) {
                $pageData = $page->toArray();
                $pageData['type'] = 'page';
                $pageData['edit'] = $canEdit;
                $result[] = $pageData;
            }

            return $this->sendResponse($result);
        }

    /**
     * Создать новый элемент (страницу или папку) в проекте.
     *
     * @param CreateNewComponentProjectRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createElement(CreateNewComponentProjectRequest $request)
    {
        $validated = $request->validated(); // Получаем валидированные данные

        $type = $validated['type']; // Тип элемента (page или folder)
        $folderId = $validated['folder_id'] ?? null; // ID папки (может быть null)
        $projectId = $validated['project_id'];
        $user = Auth::user();
        $userId = $user->id;

        // Проверяем, существует ли folder_id, если он передан
        if ($folderId) {
            $folder = Folder::find($folderId);
            if (!$folder) {
                return $this->sendError('Папка с указанным ID не найдена', 404);
            }
        }

        // Создаем элемент в зависимости от типа
        switch ($type) {
            case 'page':
                $element = Page::create([
                    'name' => 'Новая страница', // Можно сделать настраиваемым через запрос
                    'private' => $request->input('private', false),
                    'archive' => $request->input('archive', false),
                    'user_id' => $userId,
                    'project_id' => $projectId,
                    'folder_id' => $folderId,
                    'hash' => Str::random(64),
                ]);
                break;

            case 'folder':
                $element = Folder::create([
                    'name' => 'Новая папка', // Можно сделать настраиваемым через запрос
                    'project_id' => $projectId,
                    'parent_id' => $folderId, // Если folder_id есть, это родительская папка
                    'user_id' => $userId,
                    'private' => $request->input('private', false),
                    'archive' => $request->input('archive', false),
                    'hash' => Str::random(64),
                ]);
                break;

            default:
                return $this->sendError('Недопустимый тип элемента. Используйте "page" или "folder"', 400);
        }

        $element->type = $type;
        $element->items = [];

        return $this->sendResponse($element, 'Элемент успешно создан', 201);
    }

    /**
     * Обновить существующий проект.
     *
     * @param Request $request
     * @param int $projectId
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateElement(Request $request)
    {
        $data = $request->validate([
            'name' => 'string|max:255',
            'type' => 'required|string',
            'project_id' => 'required|integer|exists:projects,id',
            'id' => 'required|integer'
        ]);


        $project = Project::find($data['project_id']);

        if (!$project) {
            return $this->sendError('Проект с указанным ID не найден', 404);
        }

        // Проверяем, имеет ли пользователь право редактировать проект
        if ($project->user_id !== Auth::id()) {
            return $this->sendError('У вас нет прав для редактирования этого проекта', 403);
        }

        // Создаем элемент в зависимости от типа
        switch ($data['type']) {
            case 'page':{

                $element = Page::find($data['id']);

                if(!$element){
                    return $this->sendError('Page с указанным ID не найден', 404);
                }

                $element->update([
                    'name' => $data['name'],
                ]);

                break;
            }

            case 'folder': {
                $element = Folder::find($data['id']);

                if(!$element){
                    return $this->sendError('Folder с указанным ID не найден', 404);
                }

                $element->update([
                    'name' => $data['name'],
                ]);

                break;
            }

            default:
                return $this->sendError('Недопустимый тип элемента. Используйте "page" или "folder"', 400);
        }

        $element->refresh();

        return $this->sendResponse($element, 'Элемент успешно обновлен');
    }

    /**
     * Удалить существующий элемент (страницу или папку) в проекте.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteElement(Request $request)
    {
        $data = $request->validate([
            'type' => 'required|string',
            'project_id' => 'required|integer|exists:projects,id',
            'id' => 'required|integer'
        ]);

        $project = Project::find($data['project_id']);

        if (!$project) {
            return $this->sendError('Проект с указанным ID не найден', 404);
        }

        // Проверяем, имеет ли пользователь право удалять элементы в проекте
        if ($project->user_id !== Auth::id()) {
            return $this->sendError('У вас нет прав для удаления элементов в этом проекте', 403);
        }

        // Удаляем элемент в зависимости от типа
        switch ($data['type']) {
            case 'page':
                $element = Page::find($data['id']);

                if (!$element) {
                    return $this->sendError('Page с указанным ID не найден', 404);
                }

                $element->delete();
                break;

            case 'folder':
                $element = Folder::find($data['id']);

                if (!$element) {
                    return $this->sendError('Folder с указанным ID не найден', 404);
                }

                $element->pages()->delete();
                $element->children()->delete();

                $element->delete();
                break;

            default:
                return $this->sendError('Недопустимый тип элемента. Используйте "page" или "folder"', 400);
        }

        return $this->sendResponse(null, 'Элемент успешно удален', 204);
    }

    public function getProject(Request $request, int $projectId){
        $project = Project::find($projectId);

        return $this->sendResponse($project);
    }

    /**
     * Получить список проектов пользователя.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserProjects(Request $request)
    {
        // Получаем user_id из запроса или от аутентифицированного пользователя
        $userId = $request->input('user_id', Auth::id());

        if (!$userId) {
            return $this->sendError('Пользователь не аутентифицирован и user_id не указан', 401);
        }

        // Запрашиваем проекты, принадлежащие пользователю
        $projects = Project::where('user_id', $userId)->where('archive', false)->get();

        // Если проектов нет, можно вернуть пустой массив или сообщение
        if ($projects->isEmpty()) {
            return $this->sendResponse([], 'У пользователя пока нет проектов');
        }

        return $this->sendResponse($projects, 'Список проектов успешно получен');
    }

    /**
 * Обновить существующий проект.
    *
    * @param Request $request
    * @param int $projectId
    * @return \Illuminate\Http\JsonResponse
    */
    public function updateProject(Request $request, int $projectId)
    {
        // Находим проект по ID
        $project = Project::find($projectId);

        if (!$project) {
            return $this->sendError('Проект с указанным ID не найден', 404);
        }

        // Проверяем, имеет ли пользователь право редактировать проект
        if ($project->user_id !== Auth::id()) {
            return $this->sendError('У вас нет прав для редактирования этого проекта', 403);
        }

        // Валидация данных
        $data = $request->validate([
            'name' => 'sometimes|string|max:64',
            'private' => 'sometimes|boolean',
            'archive' => 'sometimes|boolean',
            'logo' => 'nullable|string',
            'type' => 'nullable|string|max:100',
        ]);

        // Обновляем только переданные поля
        $project->update($data);

        // Обновляем модель из базы данных
        $project->refresh();

        return $this->sendResponse($project, 'Проект успешно обновлен');
    }
}
