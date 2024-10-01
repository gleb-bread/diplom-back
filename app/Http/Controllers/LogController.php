<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class LogController extends Controller
{
    public function add(Request $request, Response $response, float $time): void
    {
        $newLog = new Log();

        $user = Auth::guard('sanctum')->user();

        $newLog->user_id = $user instanceof User ? $user->id : null;
        $newLog->method = $request->getMethod();
        $newLog->title = $request->url();
        $newLog->time = $time;
        $newLog->status = $response->status();
        $newLog->payload = json_encode($request->getContent());
        $newLog->headers = json_encode($request->headers->all());

        $newLog->save();
    }
}
