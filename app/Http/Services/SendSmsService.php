<?php

namespace App\Http\Services;

use App\Modules\otp\UsersModule;
use App\Modules\SendSms\SendSmsModules;

class SendSmsService
{
    public static function sendSms($identifier,$data,$link)
    {
        $postalcodes = [];
        if ($identifier == 'gavahi_with_info') {
            $postalcodes = collect($data['Postcodes'])->pluck('PostCode')->all();
        } elseif ($identifier == 'gavahi') {
            $postalcodes = $data['postalcode'];
        }
        $mobile = UsersModule::getMobile();
        if(env('SmsModule')=="KAVENEGAR"){
            SendSmsModules::sendKavenegar($mobile,$postalcodes,$link);
        }
        else{
            SendSmsModules::sendPost($mobile,$postalcodes,$link);
        }
    }

}
