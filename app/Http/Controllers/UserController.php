<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;


class UserController extends Controller {
    public function get(int $id){
        $user = User::find($id);
        $success = $user;
        return $this->sendResponse($success);
    }

    public function getUserProjects(Request $request) {
        // Получаем текущего аутентифицированного пользователя
        $user = Auth::user();

        // Получаем проекты пользователя
        $projects =  $user->projects;

        return $this->sendResponse($projects);
    }
}
