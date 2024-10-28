<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\PostUser;
use Illuminate\Support\Str;
use App\Models\Project;
use App\Models\Page;
use App\Models\TextComponent;

class RegisterController extends Controller
{
    public function register(PostUser $request)
    {
        $validatedData = $request->validated();

        $user = User::whereEmail($validatedData['email'])->get();

        if (!$user->isEmpty()) return $this->sendError('Email exist.', ['email' => 'Email exist'], 400);

        $user = User::create([
            'login' => $validatedData['login'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        $token =  $user->createToken(Str::uuid())->plainTextToken;
        $user->save();

        $project = $this->createFirstProject($user);
        $page = $this->createFirstPage($project); // Создание первой страницы
        $this->createFirstTextComponent($page);

        $success = [
            'token' => $token,
            ...$user->toArray()
        ];

        return $this->sendResponse($success, 'User registered successfully');
    }

    private function createFirstProject(User $user)
    {
        return Project::create([
            'name' => 'Проект 1',
            'user_id' => $user->id,
            'private' => true, 
            'archive' => false, 
            'hash' => Str::random(64),
            'ref' => Str::random(64), 
            'logo' => null, 
            'type' => null, 
        ]);
    }

    private function createFirstPage(Project $project)
    {
        return Page::create([
            'name' => 'Страница 1',
            'user_id' => $project->user_id, // Связь с пользователем
            'private' => true, 
            'archive' => false,
            'hash' => Str::random(64),
            'type' => null,
            'project_id' => $project->id, // Связь с проектом
        ]);
    }

    private function createFirstTextComponent(Page $page)
    {
        TextComponent::create([
            'text' => '# Привет мир!',
            'page_id' => $page->id,
        ]);
    }
}
