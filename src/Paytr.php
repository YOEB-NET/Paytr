<?php

namespace Yoeb\Paytr;

use Illuminate\Http\Exceptions\HttpResponseException;

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
            'status'        => false,
            'message'       => $message,
            'error_code'    => $errorCode,
        ]);
    }

    public static function errorThrow($message = "", $errorCode = "V000")
    {
        throw new HttpResponseException(response()->json([
            'status'        => false,
            'message'       => $message,
            'error_code'    => $errorCode,
        ]));
    }

    public static function validateEnv() {
        if(empty(env("PAYTR_MERCHANT_ID", null))){
            return self::error("PAYTR_MERCHANT_ID not found in .env file.", "ENV0");
        }
        if(empty(env("PAYTR_MERCHANT_KEY", null))){
            return self::error("PAYTR_MERCHANT_KEY not found in .env file.", "ENV0");
        }
        if(empty(env("PAYTR_MERCHANT_SALT", null))){
            return self::error("PAYTR_MERCHANT_SALT not found in .env file.", "ENV0");
        }

    }


}
