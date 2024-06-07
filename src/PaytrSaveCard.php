<?php

namespace Yoeb\Paytr;


class PaytrSaveCard
{
    protected static $utoken = null;
    protected static $ctoken = null;

    public static function utoken($utoken)
    {
        self::$utoken = $utoken;
        return (new static);
    }

    public static function ctoken($ctoken)
    {
        self::$ctoken = $ctoken;
        return (new static);
    }

    public static function list()
    {
        Paytr::validateEnv();

        $merchant_id = env("PAYTR_MERCHANT_ID");
        $merchant_key = env("PAYTR_MERCHANT_KEY");
        $merchant_salt = env("PAYTR_MERCHANT_SALT");


        $hash_str = self::$utoken . $merchant_salt;
        $paytr_token = base64_encode(hash_hmac('sha256', $hash_str, $merchant_key, true));

        $response = Paytr::post('/odeme/capi/list', [
            'merchant_id'       => $merchant_id,
            'paytr_token'       => $paytr_token,
            'utoken'            => self::$utoken,
        ]);

        $res = $response->json();

        return $res;
    }

    public static function delete()
    {
        Paytr::validateEnv();

        $merchant_id = env("PAYTR_MERCHANT_ID");
        $merchant_key = env("PAYTR_MERCHANT_KEY");
        $merchant_salt = env("PAYTR_MERCHANT_SALT");


        $hash_str = self::$ctoken . self::$utoken . $merchant_salt;
        $paytr_token=base64_encode(hash_hmac('sha256', $hash_str, $merchant_key, true));

        $response = Paytr::post('/odeme/capi/delete', [
            'merchant_id'       => $merchant_id,
            'paytr_token'       => $paytr_token,
            'ctoken'            => self::$ctoken,
            'utoken'            => self::$utoken,
        ]);

        $res = $response->json();

        return $res;
    }

}
