<?php

namespace Yoeb\Paytr;

use Yoeb\Paytr\Models\PaytrFrame as ModelsPaytrFrame;
use Yoeb\Paytr\Enum\PaytrCurrency;
use Illuminate\Support\Facades\Http;
use Yoeb\Paytr\Exception\PaytrExceptions;

class PaytrFrame
{
    protected static $user_ip = "";
    protected static $merchant_oid = "";
    protected static $email = "";
    protected static $payment_amount = 0;
    protected static $currency = PaytrCurrency::TL->value ?? PaytrCurrency::TL;
    protected static $user_basket = [];
    protected static $no_installment = 0;
    protected static $max_installment = 0;
    protected static $paytr_token = "";
    protected static $user_name = "";
    protected static $user_address = "";
    protected static $user_phone = "";
    protected static $merchant_ok_url = "";
    protected static $merchant_fail_url = "";
    protected static $test_mode = 0;
    protected static $debug_on = 0;
    protected static $timeout_limit = 30;
    protected static $lang = "tr";


    public static function userIp($user_ip) {
        self::$user_ip = $user_ip;
        return (new static);
    }

    public static function merchantOid($merchant_oid) {
        self::$merchant_oid = $merchant_oid;
        return (new static);
    }

    public static function email($email) {
        self::$email = $email;
        return (new static);
    }

    public static function paymentAmount($payment_amount) {
        self::$payment_amount = $payment_amount;
        return (new static);
    }

    public static function currency(PaytrCurrency $currency) {
        self::$currency = $currency->value ?? $currency;
        return (new static);
    }

    public static function userBasket($user_basket) {
        self::$user_basket = $user_basket;
        return (new static);
    }

    public static function userBasketAdd(String $title, float $price, int $quantity) {
        self::$user_basket[] = [$title, (string) $price, $quantity];
        return (new static);
    }

    public static function noInstallment($no_installment) {
        self::$no_installment = $no_installment;
        return (new static);
    }

    public static function maxInstallment($max_installment) {
        self::$max_installment = $max_installment;
        return (new static);
    }

    public static function paytrToken($paytr_token) {
        self::$paytr_token = $paytr_token;
        return (new static);
    }

    public static function userName($user_name) {
        self::$user_name = $user_name;
        return (new static);
    }

    public static function userAddress($user_address) {
        self::$user_address = $user_address;
        return (new static);
    }

    public static function userPhone($user_phone) {
        self::$user_phone = $user_phone;
        return (new static);
    }

    public static function merchantOkUrl($merchant_ok_url) {
        self::$merchant_ok_url = $merchant_ok_url;
        return (new static);
    }

    public static function merchantFailUrl($merchant_fail_url) {
        self::$merchant_fail_url = $merchant_fail_url;
        return (new static);
    }

    public static function testMode($test_mode) {
        self::$test_mode = $test_mode;
        return (new static);
    }

    public static function debugOn($debug_on) {
        self::$debug_on = $debug_on;
        return (new static);
    }

    public static function timeoutLimit($timeout_limit) {
        self::$timeout_limit = $timeout_limit;
        return (new static);
    }

    public static function lang($lang) {
        self::$lang = $lang;
        return (new static);
    }

    public static function create() {
        $validateEnv = Paytr::validateEnv();

        $merchant_id = env("PAYTR_MERCHANT_ID");
        $merchant_key = env("PAYTR_MERCHANT_KEY");
        $merchant_salt = env("PAYTR_MERCHANT_SALT");
        if(empty(self::$user_ip)){
            self::$user_ip = request()->ip();
        }

        $user_basket_encoded = base64_encode(json_encode(self::$user_basket));


        $hash_str = $merchant_id . self::$user_ip . self::$merchant_oid . self::$email . self::$payment_amount . $user_basket_encoded . self::$no_installment . self::$max_installment . self::$currency . self::$test_mode;
        $paytr_token = base64_encode(hash_hmac('sha256', $hash_str . $merchant_salt, $merchant_key, true));

        $response = Paytr::post('/odeme/api/get-token', [
            'merchant_id'      => $merchant_id,
            'user_ip'          => self::$user_ip,
            'merchant_oid'     => self::$merchant_oid,
            'email'            => self::$email,
            'payment_amount'   => self::$payment_amount,
            'paytr_token'      => $paytr_token,
            'user_basket'      => $user_basket_encoded,
            'debug_on'         => self::$debug_on,
            'no_installment'   => self::$no_installment,
            'max_installment'  => self::$max_installment,
            'user_name'        => self::$user_name,
            'user_address'     => self::$user_address,
            'user_phone'       => self::$user_phone,
            'merchant_ok_url'  => self::$merchant_ok_url,
            'merchant_fail_url'=> self::$merchant_fail_url,
            'timeout_limit'    => self::$timeout_limit,
            'currency'         => self::$currency,
            'lang'             => self::$lang,
            'test_mode'        => self::$test_mode,
        ]);
        $res = $response->json();
        if($res["status"] == "success"){
            $db = ModelsPaytrFrame::create([
                    'merchant_id'      => $merchant_id,
                    'user_ip'          => self::$user_ip,
                    'merchant_oid'     => self::$merchant_oid,
                    'email'            => self::$email,
                    'payment_amount'   => self::$payment_amount,
                    'paytr_token'      => $res["token"],
                    'user_basket'      => self::$user_basket,
                    'debug_on'         => self::$debug_on,
                    'no_installment'   => self::$no_installment,
                    'max_installment'  => self::$max_installment,
                    'user_name'        => self::$user_name,
                    'user_address'     => self::$user_address,
                    'user_phone'       => self::$user_phone,
                    'merchant_ok_url'  => self::$merchant_ok_url,
                    'merchant_fail_url'=> self::$merchant_fail_url,
                    'timeout_limit'    => self::$timeout_limit,
                    'currency'         => self::$currency,
                    'lang'             => self::$lang,
                    'test_mode'        => self::$test_mode
                ]);
                return Paytr::data("Payment created.", [
                    "token"     => $res["token"],
                    "html"      => '<script src="https://www.paytr.com/js/iframeResizer.min.js"></script>
                    <iframe src="https://www.paytr.com/odeme/guvenli/'.$res["token"].'" id="paytriframe" frameborder="0" scrolling="no" style="width: 100%;"></iframe>
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
            ModelsPaytrFrame::where("merchant_oid", request()->merchant_oid)->update([
                "status"    => true
            ]);
            return Paytr::data("Payment success.");
        } else {
            ModelsPaytrFrame::where("merchant_oid", request()->merchant_oid)->update([
                "status"        => false,
                "error_code"    => request()->failed_reason_code,
                "error_message" => request()->failed_reason_msg,
            ]);
            return Paytr::error("Payment error: " . request()->failed_reason_msg, request()->failed_reason_code);
        }

    }

}
