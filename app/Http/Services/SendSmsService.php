<?php

namespace App\Http\Services;

use App\Models\File;
use App\Modules\otp\UsersModule;
use App\Modules\SendSms\SendSmsModules;
use Illuminate\Support\Facades\Log;

class SendSmsService
{
    public static function sendSms($identifier, $data, $link, $user_id)
    {
        $postalcodes = [];
        if ($identifier == 'gavahi_with_info') {
            $postalcodes = collect($data['Postcodes'])->pluck('PostCode')->all();
        } elseif ($identifier == 'gavahi') {
            $postalcodes = $data['postalcode'];
        }

        Log::info('try to get mobile');
        $mobile = UsersModule::getMobile($user_id);
        Log::info("mobile: $mobile");

        $expiration_time = self::getExpiration($link, $user_id);

        if ($data['geo'] == 0) {
            $data['geo'] = false;
        } else {
            $data['geo'] = true;
        }

        if (!empty($mobile)) {
            $sms_module = env('SmsModule');
            if ($sms_module == "KAVENEGAR") {
                SendSmsModules::sendKavenegar(
                    $mobile,
                    $postalcodes,
                    $link,
                    $data['tracking_code'],
                    $expiration_time,
                    $data['geo']);
            } elseif ($sms_module == "Post") {

                SendSmsModules::sendPost(
                    $mobile,
                    $postalcodes,
                    $link,
                    $data['tracking_code'],
                    $expiration_time,
                    $data['geo']);
            }
        } else {
            Log::info(trans('messages.custom.sms_notSent') . $user_id);
        }

    }

    public static function getExpiration($link, $user_id)
    {
 
        $a = explode('/', $link);
        $b = explode('.', $a[6]);
        return File::getEx($b[0], $user_id);

    }

}
