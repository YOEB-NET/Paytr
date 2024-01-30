<?php

namespace Yoeb\Paytr;

use Yoeb\Paytr\Enum\PaytrBank;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Yoeb\Paytr\Models\PaytrPlatformTransfer as ModelsPaytrPlatformTransfer;
use Illuminate\Http\Exceptions\HttpResponseException;

class PaytrPlatformTransfer
{

    protected static $user_ip = "";
    protected static $merchant_oid = "";
    protected static $trans_id = "";
    protected static $submerchant_amount = null;
    protected static $total_amount = null;
    protected static $transfer_name = "";
    protected static $transfer_iban = "";

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

    public static function transId($trans_id)
    {
        self::$trans_id = $trans_id;
        return (new static);
    }

    public static function submerchantAmount($submerchant_amount)
    {
        self::$submerchant_amount = $submerchant_amount;
        return (new static);
    }

    public static function totalAmount($total_amount)
    {
        self::$total_amount = $total_amount;
        return (new static);
    }

    public static function transferName($transfer_name)
    {
        self::$transfer_name = $transfer_name;
        return (new static);
    }

    public static function transferIban($transfer_iban)
    {
        self::$transfer_iban = $transfer_iban;
        return (new static);
    }



    public static function send() {
        $merchant_id = env("PAYTR_MERCHANT_ID");
        $merchant_key = env("PAYTR_MERCHANT_KEY");
        $merchant_salt = env("PAYTR_MERCHANT_SALT");
        if(empty(self::$user_ip)){
            self::$user_ip = request()->ip();
        }

        $hash_str = $merchant_id . self::$merchant_oid . self::$trans_id . self::$submerchant_amount . self::$total_amount . self::$transfer_name . self::$transfer_iban;
        $paytr_token = base64_encode(hash_hmac('sha256',$hash_str.$merchant_salt,$merchant_key,true));


        $response = Paytr::post('/odeme/platform/transfer',[
            'merchant_id'   => $merchant_id,
            'merchant_oid'  => self::$merchant_oid,
            'trans_id'      => self::$trans_id,
            'total_amount'  => self::$total_amount,
            'transfer_name' => self::$transfer_name,
            'transfer_iban' => self::$transfer_iban,
            'paytr_token'   => $paytr_token,
        ]);

        $res = $response->json();
        if($res["status"] == "success"){
            $db = ModelsPaytrPlatformTransfer::create([
                    'user_ip'               => self::$user_ip,
                    'merchant_oid'          => self::$merchant_oid,
                    'trans_id'              => self::$trans_id,
                    'submerchant_amount'    => self::$submerchant_amount,
                    'total_amount'          => self::$total_amount,
                    'transfer_name'         => self::$transfer_name,
                    'transfer_iban'         => self::$transfer_iban,
                    'paytr_token'           => $paytr_token,
                ]);
                return Paytr::data("Payment created.", [
                    "token"     => $res["token"],
                    "html"      => '<script src="https://www.paytr.com/js/iframeResizer.min.js"></script>
                    <iframe src="https://www.paytr.com/odeme/api/'.$res["token"].'" id="paytriframe" frameborder="0" scrolling="no" style="width: 100%;"></iframe>
                    <script>iFrameResize({},\'#paytriframe\');</script>',
                    "db_data"   => $db,
                ]);
        }

        return Paytr::error($res["err_msg"], "PTR0");
    }


    // ------------------- Validate --------------------

    public static function validate(){
        $merchant_key = env("PAYTR_MERCHANT_KEY");
        $merchant_salt = env("PAYTR_MERCHANT_SALT");



        $hash = base64_encode( hash_hmac('sha256', request()->merchant_id .request()->trans_id .$merchant_salt, $merchant_key, true) );

        if($hash != request()->hash) {
            return Paytr::error("Hash does not match.", "HNM0");
        }

        foreach (request()->processed_result as $key => $value) {
            Log::info( $value);

            // if( request()->status == 'success' ) {
            //     ModelsPaytrPlatformTransfer::where("merchant_oid", request()->merchant_oid)->update([
            //         "status"    => true
            //     ]);
            //     return Paytr::data("Payment success.");
            // } else {
            //     ModelsPaytrPlatformTransfer::where("merchant_oid", request()->merchant_oid)->update([
            //         "status"        => false,
            //         "error_code"    => request()->failed_reason_code,
            //         "error_message" => request()->failed_reason_msg,
            //     ]);
            //     return Paytr::error("Payment error: " . request()->failed_reason_msg, request()->failed_reason_code);
            // }
        }


    }

}
