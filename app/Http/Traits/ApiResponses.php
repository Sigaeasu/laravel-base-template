<?php

namespace App\Http\Traits;

use Illuminate\Http\Response;

trait ApiResponses
{
    public function successRes($data, $message, $statusCode = Response::HTTP_OK)
    {
        return response()->json([
            'status' => true,
            'data' => $data,
            'message' => $message
        ], $statusCode);
    }

    public function errorRes($message = 'Bad Request', $statusCode = 400)
    {
        return response()->json([
            'status' => false,
            'data' => null,
            'message' => $message,
        ], $statusCode);
    }
}