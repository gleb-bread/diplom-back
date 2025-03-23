<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Str;
use App\Http\Requests\CreateNewComponentProjectRequest;
use App\Models\Page;
use App\Models\Folder;
use Illuminate\Support\Facades\Auth;


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

        return $this->sendResponse($project);
    }

    public function getStructure(int $projectId)
    {
        // Получаем все папки и страницы верхнего уровня (без parent_id или folder_id)
        $topLevelFolders = Folder::where('project_id', $projectId)
            ->whereNull('parent_id')
            ->get();
        $topLevelPages = Page::where('project_id', $projectId)
            ->whereNull('folder_id')
            ->get();

        // Функция для рекурсивного построения структуры папок
        $buildFolderStructure = function (Folder $folder) use (&$buildFolderStructure) {
            $items = [];

            // Добавляем вложенные страницы
            $pages = $folder->pages;
            foreach ($pages as $page) {
                $pageData = $page->toArray();
                $pageData['type'] = 'page';
                $items[] = $pageData;
            }

            // Добавляем вложенные папки
            $subFolders = $folder->children;
            foreach ($subFolders as $subFolder) {

                $folderData = $subFolder->toArray();

                $folderData['type'] = 'folder';

                $folderData['items'] = $buildFolderStructure($subFolder);

                $items[] = $folderData;
            }

            return $items;
        };

        // Собираем итоговый результат
        $result = [];

        // Добавляем верхние папки
        foreach ($topLevelFolders as $folder) {
            $folderData = $folder->toArray();
            $folderData['type'] = 'folder';
            $folderData['items'] = $buildFolderStructure($folder);

            $result[] = $folderData;
        }

        // Добавляем верхние страницы
        foreach ($topLevelPages as $page) {

            $pageData = $page->toArray();
            $pageData['type'] = 'page';

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

        return $this->sendResponse($element, 'Элемент успешно создан', 201);
    }

    public function getProject(Request $request, int $projectId){
        $project = Project::find($projectId);

        return $this->sendResponse($project);
    }
}
