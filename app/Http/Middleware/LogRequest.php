<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\LogController;
use Exception;

class LogRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $response = null;

        try{
            $startTime = microtime(true);

            $response = $next($request);

            return $response;

            $endTime = microtime(true);

            $executionTime = $endTime - $startTime;

            $controller = new LogController();

            $controller->add($request, $response, $executionTime);

            return $response;
        } catch(Exception $e){
            return $response;
        }
    }
}
