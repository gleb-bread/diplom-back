<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Str;

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
}
