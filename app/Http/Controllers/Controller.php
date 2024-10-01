<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

abstract class Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result, $message): Response
    {
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];

        return response($response, 200);
    }

    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = 404): Response
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response($response, $code);
    }
}
