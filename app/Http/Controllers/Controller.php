<?php

namespace App\Http\Controllers;

abstract class Controller
{
  public function __construct() {}

  private function reponseChecked($response, $customRespKey, $code)
  {
    if (isset($response['error']) && $response['error']) {
      return $this->responseError($response['message'], $response['code']);
    }
    return $this->responseSucces($response, $customRespKey, $code);
  }

  private function responseSucces($response, $customRespKey, $code)
  {
    return response()->json(['success' => true, $customRespKey => $response], $code);
  }

  public function responseError($message, $code = 400)
  {
    return response()->json(['error' => true, 'message' => $message], $code);
  }

  public function response($data, $customRespKey = 'resp', $code = 200)
  {
    return $this->reponseChecked($data, $customRespKey, $code);
  }
}
