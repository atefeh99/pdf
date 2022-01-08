<?php

namespace App\Http\Services;

use App\Models\File;
use App\Modules\otp\UsersModule;
use App\Modules\SendSms\SendSmsModules;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


class SendSmsService
{
    public static function sendSms($identifier, $data, $link,$user_id)
    {

        $postalcodes = [];
        if ($identifier == 'gavahi_with_info') {
            $postalcodes = collect($data['Postcodes'])->pluck('PostCode')->all();
        } elseif ($identifier == 'gavahi') {
            $postalcodes = $data['postalcode'];
//            dd($postalcodes);
        }
        Log::info('get mobile');

        $mobile = UsersModule::getMobile($user_id);
        Log::info($mobile);
        $expiration_time = self::getExpiration($link,$user_id);
        Log::info($expiration_time);


        if (!empty($mobile)) {
            $sms_module = env('SmsModule');
//            dd($sms_module);
            if ($sms_module == "KAVENEGAR") {
                SendSmsModules::sendKavenegar($mobile, $postalcodes, $link,$data['tracking_code'],$expiration_time);
            } elseif ($sms_module == "Post") {

                SendSmsModules::sendPost($mobile, $postalcodes, $link,$data['tracking_code'],$expiration_time);
            }
        }else{
            Log::info(trans('messages.custom.sms_notSent') .$user_id);
        }

    }
    public static function getExpiration($link,$user_id)
    {
        $a = explode('/',$link);
        Log::info($a);
        $b = explode('.',$a[6]);
        return File::getEx($b[0],$user_id);

    }

}
