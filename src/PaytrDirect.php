<?php

namespace Yoeb\Paytr;

use Yoeb\Paytr\Models\PaytrDirect as ModelsPaytrDirect;
use Yoeb\Paytr\Enum\PaytrCurrency;
use Illuminate\Support\Facades\Http;

class PaytrDirect
{
    protected static $paytr_token = "";
    protected static $user_ip = "";
    protected static $merchant_oid = "";
    protected static $email = "";
    protected static $payment_type = "card";
    protected static $payment_amount = 0;
    protected static $installment_count = 0;
    protected static $card_type = "";
    protected static $currency = PaytrCurrency::TL->value ?? PaytrCurrency::TL;
    protected static $client_lang = "tr";
    protected static $test_mode = 0;
    protected static $non_3d = 0;
    protected static $non3d_test_failed = 0;
    protected static $cc_owner = "";
    protected static $card_number = "";
    protected static $expiry_month = "";
    protected static $expiry_year = "";
    protected static $cvv = "";
    protected static $merchant_ok_url = "";
    protected static $merchant_fail_url = "";
    protected static $user_name = "";
    protected static $user_phone = "";
    protected static $user_address = "";
    protected static $user_basket = [];
    protected static $debug_on = 0;
    protected static $sync_mode = 0;

    public static function userIp($user_ip)
    {
        self::$user_ip = $user_ip;
        return (new static);
    }

    public static function merchantOid($merchant_oid)
    {
        self::$merchant_oid = $merchant_oid;
        return (new static);
    }

    public static function email($email)
    {
        self::$email = $email;
        return (new static);
    }

    public static function userBasket($user_basket)
    {
        self::$user_basket = $user_basket;
        return (new static);
    }

    public static function userBasketAdd(String $title, float $price, int $quantity)
    {
        self::$user_basket[] = [$title, (string) $price, $quantity];
        return (new static);
    }

    public static function installmentCount($installment_count)
    {
        self::$installment_count = $installment_count;
        return (new static);
    }

    public static function paytrToken($paytr_token)
    {
        self::$paytr_token = $paytr_token;
        return (new static);
    }

    public static function userName($user_name)
    {
        self::$user_name = $user_name;
        return (new static);
    }

    public static function userAddress($user_address)
    {
        self::$user_address = $user_address;
        return (new static);
    }

    public static function userPhone($user_phone)
    {
        self::$user_phone = $user_phone;
        return (new static);
    }

    public static function merchantOkUrl($merchant_ok_url)
    {
        self::$merchant_ok_url = $merchant_ok_url;
        return (new static);
    }

    public static function merchantFailUrl($merchant_fail_url)
    {
        self::$merchant_fail_url = $merchant_fail_url;
        return (new static);
    }

    public static function testMode($test_mode)
    {
        self::$test_mode = $test_mode;
        return (new static);
    }

    public static function debugOn($debug_on)
    {
        self::$debug_on = $debug_on;
        return (new static);
    }

    public static function syncMode($sync_mode)
    {
        self::$sync_mode = $sync_mode;
        return (new static);
    }

    public static function ccOwner($cc_owner)
    {
        self::$cc_owner = $cc_owner;
        return (new static);
    }

    public static function cardNumber($card_number)
    {
        self::$card_number = $card_number;
        return (new static);
    }

    public static function cvv($cvv)
    {
        self::$cvv = $cvv;
        return (new static);
    }

    public static function expiryMonth($expiry_month)
    {
        self::$expiry_month = $expiry_month;
        return (new static);
    }

    public static function expiryYear($expiry_year)
    {
        self::$expiry_year = $expiry_year;
        return (new static);
    }

    public static function currency(PaytrCurrency $currency)
    {
        self::$currency = $currency->value ?? $currency;
        return (new static);
    }

    public static function paymentAmount($payment_amount)
    {
        self::$payment_amount = $payment_amount;
        return (new static);
    }

    public static function paymentType($payment_type)
    {
        self::$payment_type = $payment_type;
        return (new static);
    }

    public static function non3d($non_3d)
    {
        self::$non_3d = $non_3d;
        return (new static);
    }

    public static function create()
    {
        $merchant_id = env("PAYTR_MERCHANT_ID");
        $merchant_key = env("PAYTR_MERCHANT_KEY");
        $merchant_salt = env("PAYTR_MERCHANT_SALT");

        if (empty(self::$user_ip)) {
            self::$user_ip = request()->ip();
        }

        $hash_str = $merchant_id . self::$user_ip . self::$merchant_oid . self::$email . self::$payment_amount . self::$payment_type . self::$installment_count . self::$currency . self::$test_mode . self::$non_3d;
        $paytr_token = base64_encode(hash_hmac('sha256', $hash_str . $merchant_salt, $merchant_key, true));

        $response = Http::asForm()->post('https://www.paytr.com/odeme', [
            'merchant_id'       => $merchant_id,
            'user_ip'           => self::$user_ip,
            'merchant_oid'      => self::$merchant_oid,
            'email'             => self::$email,
            'payment_amount'    => self::$payment_amount,
            'payment_type'      => self::$payment_type,
            'paytr_token'       => $paytr_token,
            'installment_count' => self::$installment_count,
            'card_type'         => self::$card_type,
            'currency'          => self::$currency,
            'client_lang'       => self::$client_lang,
            'test_mode'         => self::$test_mode,
            'non_3d'            => self::$non_3d,
            'non3d_test_failed' => self::$non3d_test_failed,
            'cc_owner'          => self::$cc_owner,
            'card_number'       => self::$card_number,
            'expiry_month'      => self::$expiry_month,
            'expiry_year'       => self::$expiry_year,
            'cvv'               => self::$cvv,
            'merchant_ok_url'   => self::$merchant_ok_url,
            'merchant_fail_url' => self::$merchant_fail_url,
            'user_name'         => self::$user_name,
            'user_phone'        => self::$user_phone,
            'user_address'      => self::$user_address,
            'user_basket'       => json_encode(self::$user_basket),
            'debug_on'          => self::$debug_on,
            'sync_mode'         => self::$sync_mode,
        ]);

        if(!self::$sync_mode){
            $res = $response->body();
        }else{
            $res = $response->json();
        }
        if (($res['status'] ?? "failed") == 'success' || self::$sync_mode != 1) {
            $db = ModelsPaytrDirect::create([
                'merchant_id'       => $merchant_id,
                'user_ip'           => self::$user_ip,
                'merchant_oid'      => self::$merchant_oid,
                'email'             => self::$email,
                'payment_amount'    => self::$payment_amount,
                'payment_type'      => self::$payment_type,
                'paytr_token'       => $paytr_token,
                'installment_count' => self::$installment_count,
                'card_type'         => self::$card_type,
                'currency'          => self::$currency,
                'client_lang'       => self::$client_lang,
                'test_mode'         => self::$test_mode,
                'non_3d'            => self::$non_3d,
                'non3d_test_failed' => self::$non3d_test_failed,
                'cc_owner'          => self::$cc_owner,
                'card_number'       => substr(self::$card_number, -4),
                'expiry_month'      => self::$expiry_month,
                'expiry_year'       => self::$expiry_year,
                'merchant_ok_url'   => self::$merchant_ok_url,
                'merchant_fail_url' => self::$merchant_fail_url,
                'user_name'         => self::$user_name,
                'user_phone'        => self::$user_phone,
                'user_address'      => self::$user_address,
                'user_basket'       => self::$user_basket,
                'debug_on'          => self::$debug_on,
                'sync_mode'         => self::$sync_mode,
            ]);
        }

        return $res;
    }

    // ------------------- Validate --------------------

    public static function validate()
    {
        $merchant_key = env("PAYTR_MERCHANT_KEY");
        $merchant_salt = env("PAYTR_MERCHANT_SALT");

        $hash = base64_encode(hash_hmac('sha256', request()->merchant_oid . $merchant_salt . request()->status . request()->total_amount, $merchant_key, true));

        if ($hash != request()->hash) {
            return Paytr::error("Hash does not match.", "HNM0")->getData();
        }

        if (request()->status == 'success') {
            ModelsPaytrDirect::where("merchant_oid", request()->merchant_oid)->update([
                "status"    => true
            ]);
            return Paytr::data("Payment success.")->getData();
        } else {
            ModelsPaytrDirect::where("merchant_oid", request()->merchant_oid)->update([
                "status"        => false,
                "error_code"    => request()->failed_reason_code,
                "error_message" => request()->failed_reason_msg,
            ]);
            return Paytr::error("Payment error: " . request()->failed_reason_msg, request()->failed_reason_code)->getData();
        }
    }
}
