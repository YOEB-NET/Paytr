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

        return (object) [
            'status' => true,
            'message' => $message,
            'data' => $data
        ];
    }

    public static function error($message = "", $errorCode = "V000")
    {
        return (object) [
            'status'        => false,
            'message'       => $message,
            'error_code'    => $errorCode,
        ];
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
        $envError = null;
        if(empty(env("PAYTR_MERCHANT_ID"))){
            $envError = self::errorThrow("PAYTR_MERCHANT_ID not found in .env file.", "ENV0");
        }
        if(empty(env("PAYTR_MERCHANT_KEY"))){
            $envError = self::errorThrow("PAYTR_MERCHANT_KEY not found in .env file.", "ENV0");
        }
        if(empty(env("PAYTR_MERCHANT_SALT"))){
            $envError = self::errorThrow("PAYTR_MERCHANT_SALT not found in .env file.", "ENV0");
        }

        if(!empty($envError)){
            return $envError;
        }

    }


}
