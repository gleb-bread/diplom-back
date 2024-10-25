<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostUser;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\TokenController;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller {
    public function get(int $id){
        $user = User::get($id);
        $success = $user;
        return $this->sendResponse($success);
    }
}
