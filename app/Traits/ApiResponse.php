<?php

namespace App\Traits;

trait ApiResponse
{
    public function responseSuccess($message, $code = 200)
    {
        if (is_array($message)) return response()->json($message, $code);

        return response()->json(["message"=> $message], $code);
    }

    public function responseError($message, $code = 400)
    {
        if (is_array($message)) return response()->json($message, $code);

        return response()->json(["message"=> $message], $code);
    }
}