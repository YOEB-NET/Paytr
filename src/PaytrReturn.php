<?php

namespace Yoeb\Paytr;

use Yoeb\Paytr\Enum\PaytrBank;
use Illuminate\Support\Facades\Http;
use Yoeb\Paytr\Models\PaytrReturn as ModelsPaytrReturn;

class PaytrReturn
{
    protected static $user_ip = "";
    protected static $merchant_oid = "";
    protected static $return_amount = 0;
    protected static $is_test = 0;


    public static function userIp($user_ip) {
        self::$user_ip = $user_ip;
        return new static;
    }

    public static function merchantOid($merchant_oid) {
        self::$merchant_oid = $merchant_oid;
        return new static;
    }

    public static function returnAmount($return_amount) {
        self::$return_amount = $return_amount;
        return new static;
    }

    public static function isTest($is_test) {
        self::$is_test = $is_test;
        return new static;
    }


    public static function create() {
        $merchant_id = env("PAYTR_MERCHANT_ID");
        $merchant_key = env("PAYTR_MERCHANT_KEY");
        $merchant_salt = env("PAYTR_MERCHANT_SALT");
        if(empty(self::$user_ip)){
            self::$user_ip = request()->ip();
        }

        $paytr_token=base64_encode(hash_hmac('sha256',$merchant_id.self::$merchant_oid.self::$return_amount.$merchant_salt,$merchant_key,true));

        $response = Paytr::post('/odeme/iade',[
            'merchant_id'   => $merchant_id,
            'merchant_oid'  => self::$merchant_oid,
            'return_amount' => self::$return_amount,
            'is_test'       => self::$is_test,
            'paytr_token'   => $paytr_token
        ]);
        $res = $response->json();
        if($res["status"] == "success"){
            $db = ModelsPaytrReturn::create([
                    'user_ip'           => self::$user_ip,
                    'merchant_oid'      => self::$merchant_oid,
                    'return_amount'     => self::$return_amount,
                    'status'            => 1,
                ]);
                return Paytr::data("Return created.", [
                    "merchant_oid"     => $res["merchant_oid"],
                    "return_amount"    => $res["return_amount"],
                ]);
        }else{
            ModelsPaytrReturn::create([
                'user_ip'           => self::$user_ip,
                'merchant_oid'      => self::$merchant_oid,
                'return_amount'     => self::$return_amount,
                'status'            => 0,
                'error_message'     => $res["err_msg"],
                'error_code'        => $res["err_no"],
            ]);
        }

        return Paytr::error($res["reason"], "PTR0");
    }


}
