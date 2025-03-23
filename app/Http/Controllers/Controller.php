<?php

namespace App\Http\Controllers;

abstract class Controller
{
  public function __construct() {}

  private function reponseChecked($response)
  {
    if (isset($response['error']) && $response['error']) {
      return $this->responseError($response['message'], $response['code']);
    }
    return $this->responseSucces($response);
  }

  private function responseSucces($resp, $code = 200)
  {
    return response()->json(['success' => true, 'resp' => $resp], $code);
  }

  public function responseError($message, $code = 400)
  {
    return response()->json(['error' => true, 'message' => $message], $code);
  }

  public function response($data)
  {
    return $this->reponseChecked($data);
  }
}
