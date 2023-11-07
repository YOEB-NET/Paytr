<?php

namespace Yoeb\Paytr;

class Paytr
{

    public static function data($message, $data = null)
    {
        if($data === null){
            $data = new \stdClass;
        }

        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data
        ], 200, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    public static function error($message = "", $errorCode = "V000")
    {
        return response()->json([
            'status'       => false,
            'message'       => $message,
            'error_code'    => $errorCode,
        ], 422);
    }

}