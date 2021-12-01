<?php

namespace App\Http\Services;

use App\Models\File;
use App\Modules\otp\UsersModule;
use App\Modules\SendSms\SendSmsModules;
use Illuminate\Support\Facades\Log;

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
        $mobile = UsersModule::getMobile($user_id);
        $expiration_time = self::getExpiration($link,$user_id);
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
        $b = explode('.',$a[4]);
        return File::getEx($b[0],$user_id);

    }

}
