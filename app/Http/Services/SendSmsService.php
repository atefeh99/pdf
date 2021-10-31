<?php

namespace App\Http\Services;

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
        }
        $mobile = UsersModule::getMobile($user_id);
        if (!$mobile) {
            $sms_module = env('SmsModule');
            if ($sms_module == "KAVENEGAR") {
                SendSmsModules::sendKavenegar($mobile, $postalcodes, $link);
            } elseif ($sms_module == "Post") {
                SendSmsModules::sendPost($mobile, $postalcodes, $link);
            }
        }else{
            Log::info(trans('messages.custom.sms_notSent') .$user_id);
        }
    }

}
