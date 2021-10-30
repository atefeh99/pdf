<?php

namespace App\Modules\SendSms;

use Illuminate\Support\Facades\Log;
use Kavenegar;
use Kavenegar\Exceptions\ApiException;
use Kavenegar\Exceptions\HttpException;

class SendSmsModules
{

    public static function sendKavenegar($mobile, $postalcodes, $link)
    {
        try {
            $sender = env('KAVENEGAR_SENDER');        //This is the Sender number
            $message = self::makeMessage($postalcodes, $link);
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

    public static function sendPost($mobile, $postalcodes, $link)
    {

        $message = self::makeMessage($postalcodes, $link);
        $opts = [
            'http' => [
                'user_agent' => 'PHPSoapClient'
            ]
        ];
        $context = stream_context_create($opts);
        dd($context);
        $soapClientOptions = [
            'stream_context' => $context,
            'cache_wsdl' => WSDL_CACHE_NONE
        ];
        $client = new \SoapClient(env('POST_SMS_URI'), $soapClientOptions);
        $params = [
            'ContractID' => (int)env('POST_SMS_CONTRACT_ID'),
            'Username' => env('POST_SMS_USERNAME'),
            'Password' => env('POST_SMS_PASSWORD'),
            'SourceMobile' => '',
            'DesctinationMobile' => $mobile,
            'SMS_Body' => "$text\n " . trans('messages.custom.post')
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

    public static function makeMessage($postalcodes, $link)
    {
        $message = trans('messages.custom.sms_part1') . "\n";
        foreach ($postalcodes as $k => $p) {
            $message .= $p;
            if ($k != count($postalcodes) - 1) {
                $message .= ",\n";
            }
        }
        $message .= "\n" . trans('messages.custom.sms_part2') .
            "\n" . $link . "\n"
            . trans('messages.custom.sms_part3');
        return $message;
    }
}

