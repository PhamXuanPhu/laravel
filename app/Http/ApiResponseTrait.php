<?php

namespace App\Http;

trait ApiResponseTrait
{
    public function apiSuccessResponse($data, $status_code = 200)
    {
        return response()->json([
            'error_code' => null,
            'error' => '',
            'error_message' => '',
            'data' => $data,
        ], $status_code);
    }

    public function apiErrorResponse($error_message = "System error", $error_code = 500, $error = 'system')
    {
        return response()->json([
            'error_code' => $error_code,
            'error' => $error,
            'error_message' => $error_message,
            'data' => '',
        ], $error_code);
    }

    public function apiExceptionResponse($exception)
    {
        return response()->json([
            'error_code' => 500,
            'error' => 'error',
            'error_message' => $exception,
            'data' => '',
        ], 500);
    }
}
