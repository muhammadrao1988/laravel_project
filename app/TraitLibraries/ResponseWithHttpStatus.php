<?php

namespace App\TraitLibraries;

trait ResponseWithHttpStatus
{
    protected static function responseSuccess($message, $data = [], $status = 200)
    {
        return response([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    protected static function responseFailure($message, $status = 422)
    {
        return response([
            'success' => false,
            'message' => $message,
        ], $status);
    }
}