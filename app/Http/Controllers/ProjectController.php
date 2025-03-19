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

    public function getPages(int $projectId)
    {
        $project = Project::findOrFail($projectId); // Находим проект по ID
        $pages = $project->pages; // Получаем связанные страницы

        return $this->sendResponse($pages);
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

        return $this->sendResponse($element, 'Элемент успешно создан', 201);
    }
}
