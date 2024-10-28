<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Requests\PostUser;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProjectController; 
use App\Http\Controllers\PageController;

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    Route::get('/user/{id}', [UserController::class, 'get']);
    Route::post('/projects', [ProjectController::class, 'createProject']);
    Route::get('/projects/{projectId}/pages', [ProjectController::class, 'getPages']);
    Route::get('/user/projects', [UserController::class, 'getUserProjects']);
    Route::get('/pages/{pageId}/components', [PageController::class, 'getComponents']);
});

