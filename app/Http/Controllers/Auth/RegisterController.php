<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\PostUser;
use Illuminate\Support\Str;

use function PHPUnit\Framework\isEmpty;

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
        $name =  $user->name;

        $success = [
            'token' => $token,
            'name' => $name,
        ];

        return $this->sendResponse($success, 'User registered successfully');
    }
}
