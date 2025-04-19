<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Log;

abstract class Controller
{
    public function company(): Company
    {
        return request()->user();
    }

    public function user(): User
    {
        return request()->user();
    }

    public function response($data, $customRespKey = 'resp', $code = 200)
    {
        return $this->reponseChecked($data, $customRespKey, $code);
    }

    public function log($message = '', $data = [])
    {
        Log::debug($message, $data);
    }

    private function reponseChecked($response, $customRespKey, $code)
    {
        if (isset($response['error']) && $response['error']) {
            return $this->returnError($response['message'], $response['code']);
        }

        return $this->returnSucces($response, $customRespKey, $code);
    }

    private function returnSucces($response, $customRespKey, $code)
    {
        return response()->json(['success' => true, $customRespKey => $response], $code);
    }

    private function returnError($message, $code = 400)
    {
        return response()->json(['error' => true, 'message' => $message], $code);
    }
}
