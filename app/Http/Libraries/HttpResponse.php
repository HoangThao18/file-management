<?php

namespace App\Http\Libraries;


class HttpResponse
{

  public function __construct()
  {
  }

  public static function resJsonSuccess($data, $message = "Success", $code = 200)
  {
    return response()->json(['success' => true, 'data' => $data, 'code' => $code, 'message' => $message], $code);
  }

  public static function resJsonCreated($data, $message = "Data created successfully", $code = 201)
  {
    return response()->json(['success' => true, 'data' => $data, 'code' => $code, 'message' => $message], $code);
  }

  public static function resJsonFail($errors = [], $code = 401, $message = "An error occurred")
  {
    return response()->json(['success' => false,  'code' => $code, 'message' => $message, 'errors' => $errors], $code);
  }

  public static function resJsonNotFond($message = 'Not Found')
  {
    return response()->json(['success' => false,  'code' => 404, 'message' => $message], 404);
  }
}
