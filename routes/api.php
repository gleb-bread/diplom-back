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
use App\Http\Controllers\PageComponentController;

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    Route::get('/user', [UserController::class, 'get']);
    Route::post('/project', [ProjectController::class, 'createProject']);
    Route::get('/project/{projectId}/structure', [ProjectController::class, 'getStructure']);
    Route::get('/project/{projectId}', [ProjectController::class, 'getProject']);
    Route::get('/user/project', [UserController::class, 'getUserProjects']);
    Route::get('/pages/{pageId}/components', [PageController::class, 'getComponents']);
    Route::post('/pages/component', [PageController::class, 'createComponent']);
    Route::patch('/component', [PageComponentController::class, 'update']);
    Route::post('/project/element', [ProjectController::class, 'createElement']);
});

