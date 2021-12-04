<?php

namespace App\Modules\SendSms;

use Illuminate\Support\Facades\Log;
use Kavenegar;
use Kavenegar\Exceptions\ApiException;
use Kavenegar\Exceptions\HttpException;

class SendSmsModules
{

    public static function sendKavenegar($mobile, $postalcodes, $link,$tracking_code,$expiration_time)
    {
        try {
            $sender = env('KAVENEGAR_SENDER');        //This is the Sender number
            $message = self::makeMessage($postalcodes, $link,$tracking_code,$expiration_time);
            $receptor = array($mobile);            //Receptors number
            $sms_api = new Kavenegar\KavenegarApi(env('KAVENEGAR_API_KEY'));
            $sms_api->Send($sender, $receptor, $message);

        } catch (\Kavenegar\Exceptions\ApiException $e) {
            // در صورتی که خروجی وب سرویس 200 نباشد این خطا رخ می دهد
            throw new ApiException($e->getMessage());

        } catch (\Kavenegar\Exceptions\HttpException $e) {
            // در زمانی که مشکلی در برقرای ارتباط با وب سرویس وجود داشته باشد این خطا رخ می دهد
            throw new HttpException($e->getMessage());
        }

    }

    public static function sendPost($mobile, $postalcodes, $link,$tracking_code,$expiration_time)
    {
        $message = self::makeMessage($postalcodes, $link,$tracking_code,$expiration_time);

        $mobile = str_replace('+98', '98', $mobile);
        $mobile = str_starts_with($mobile, '0098')?
            str_replace('0098', '98', $mobile):$mobile;
        $mobile = str_starts_with($mobile, '980')?
            str_replace('980', '98', $mobile):$mobile;

        $opts = [
            'http' => [
                'user_agent' => 'PHPSoapClient'
            ]
        ];
        $context = stream_context_create($opts);
        $soapClientOptions = [
            'stream_context' => $context,
            'cache_wsdl' => WSDL_CACHE_NONE
        ];
        try{
            $client = new \SoapClient(env('POST_SMS_URI'), $soapClientOptions);

        }catch(\Exception $e){
            Log::error($e->getMessage());
//            dd($e->getMessage());
        }

        $params = [
            'ContractID' => (int)env('POST_SMS_CONTRACT_ID'),
            'Username' => env('POST_SMS_USERNAME'),
            'Password' => env('POST_SMS_PASSWORD'),
            'SourceMobile' => '',
            'DesctinationMobile' => $mobile,
            'SMS_Body' => $message
        ];
        try {
            $client->SendSMS($params);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

        return [
            true,
            'message has been sent'
        ];

    }

    public static function makeMessage($postalcodes, $link, $tracking_code,$expiration_time)
    {
        $message = trans('messages.custom.sms_part1') . "\n" . trans('messages.custom.sms_part2');

        foreach ($postalcodes as $k => $p) {
            $message .= $p;
            if ($k != count($postalcodes) - 1) {
                $message .= ",\n";
            }
        }
        $message .= "\n" . trans('messages.custom.sms_part3');
        $message .= $tracking_code;
        $message .= "\n" . trans('messages.custom.sms_part4');
        $message .= $expiration_time;
        $message .= "\n" . trans('messages.custom.sms_part5');
        $message .= "\n" . $link;
        $message .= "\n" . trans('messages.custom.sms_part6');

        return $message;
    }
}

