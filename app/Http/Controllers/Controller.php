<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function responseError($message, $code = 400) {
        return response()->json([
            'error' => true,
            'message' => $message,
        ], $code);
    }

    public function responseSuccess($message, $user, $data = []) {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'user' => $user
        ]);
        
    }
}
