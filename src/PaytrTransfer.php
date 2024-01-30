<?php

namespace Yoeb\Paytr;

use Yoeb\Paytr\Models\PaytrTransfer as ModelsPaytrTransfer;
use Yoeb\Paytr\Enum\PaytrBank;
use Illuminate\Support\Facades\Http;


class PaytrTransfer
{
    protected static $user_ip = "";
    protected static $merchant_oid = "";
    protected static $email = "";
    protected static $payment_amount = 0;
    protected static $user_name = "";
    protected static $user_phone = "";
    protected static $tc_no_last5 = "";
    protected static $bank = "";
    protected static $test_mode = 0;
    protected static $debug_on = 0;
    protected static $timeout_limit = 30;

    public static function userIp($user_ip) {
        self::$user_ip = $user_ip;
        return new static;
    }

    public static function merchantOid($merchant_oid) {
        self::$merchant_oid = $merchant_oid;
        return new static;
    }

    public static function email($email) {
        self::$email = $email;
        return new static;
    }

    public static function paymentAmount($payment_amount) {
        self::$payment_amount = $payment_amount;
        return new static;
    }

    public static function userName($user_name) {
        self::$user_name = $user_name;
        return new static;
    }

    public static function userPhone($user_phone) {
        self::$user_phone = $user_phone;
        return new static;
    }

    public static function tcNoLast5($tc_no_last5) {
        self::$tc_no_last5 = $tc_no_last5;
        return new static;
    }

    public static function bank(PaytrBank $bank) {
        self::$bank = $bank->value ?? $bank;
        return new static;
    }

    public static function testMode($test_mode) {
        self::$test_mode = $test_mode;
        return new static;
    }

    public static function debugOn($debug_on) {
        self::$debug_on = $debug_on;
        return new static;
    }


    public static function create() {
        $merchant_id = env("PAYTR_MERCHANT_ID");
        $merchant_key = env("PAYTR_MERCHANT_KEY");
        $merchant_salt = env("PAYTR_MERCHANT_SALT");
        if(empty(self::$user_ip)){
            self::$user_ip = request()->ip();
        }

        $hash_str=$merchant_id.self::$user_ip.self::$merchant_oid.self::$email.self::$payment_amount."eft".self::$test_mode;
        $paytr_token=base64_encode(hash_hmac('sha256',$hash_str.$merchant_salt,$merchant_key,true));

        $response = Paytr::post('/odeme/api/get-token',[
            'merchant_id'   => $merchant_id,
            'user_ip'       => self::$user_ip,
            'merchant_oid'  => self::$merchant_oid,
            'email'         => self::$email,
            'payment_type'  => "eft",
            'payment_amount'=> self::$payment_amount,
            'paytr_token'   => $paytr_token,
            'debug_on'      => self::$debug_on,
            'timeout_limit' => self::$timeout_limit,
            'test_mode'     => self::$test_mode,
        ]);
        $res = $response->json();
        if($res["status"] == "success"){
            $db = ModelsPaytrTransfer::create([
                    'user_ip'        => self::$user_ip,
                    'merchant_oid'   => self::$merchant_oid,
                    'email'          => self::$email,
                    'payment_amount' => self::$payment_amount,
                    'paytr_token'    => $paytr_token,
                    'user_name'      => self::$user_name,
                    'user_phone'     => self::$user_phone,
                    'tc_no_last5'    => self::$tc_no_last5,
                    'bank'           => self::$bank,
                    'payment_type'  => "eft",
                    'debug_on'       => self::$debug_on,
                    'timeout_limit'  => self::$timeout_limit,
                    'test_mode'      => self::$test_mode,

                ]);
                return Paytr::data("Payment created.", [
                    "token"     => $res["token"],
                    "html"      => '<script src="https://www.paytr.com/js/iframeResizer.min.js"></script>
                    <iframe src="https://www.paytr.com/odeme/api/'.$res["token"].'" id="paytriframe" frameborder="0" scrolling="no" style="width: 100%;"></iframe>
                    <script>iFrameResize({},\'#paytriframe\');</script>',
                    "db_data"   => $db,
                ]);
        }

                return Paytr::error($res["reason"], "PTR0");
    }


    // ------------------- Validate --------------------

    public static function validate(){
        $merchant_key = env("PAYTR_MERCHANT_KEY");
        $merchant_salt = env("PAYTR_MERCHANT_SALT");

        $hash = base64_encode( hash_hmac('sha256', request()->merchant_oid . $merchant_salt . request()->status . request()->total_amount, $merchant_key, true) );

        if($hash != request()->hash) {
            return Paytr::error("Hash does not match.", "HNM0");
        }

        if( request()->status == 'success' ) {
            ModelsPaytrTransfer::where("merchant_oid", request()->merchant_oid)->update([
                "status"    => true
            ]);
            return Paytr::data("Payment success.");
        } else {
            ModelsPaytrTransfer::where("merchant_oid", request()->merchant_oid)->update([
                "status"        => false,
                "error_code"    => request()->failed_reason_code,
                "error_message" => request()->failed_reason_msg,
            ]);
            return Paytr::error("Payment error: " . request()->failed_reason_msg, request()->failed_reason_code);
        }

    }

}
