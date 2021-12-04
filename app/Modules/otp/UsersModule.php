<?php

namespace App\Modules\otp;

class UsersModule
{
    public static function getMobile($user_id)
    {
//        $user_id = 'b7895798-5acc-47f1-8914-86a57573208e';

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => env('OTP_URL') . $user_id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'x-scopes: admin',
//                'x-api-key:'.env('API_KEY'),
            ),

        ));


//        if (!env('OFFLINE')) {
//            $headers = [
//                "x-api-key: " . env('OTP_API_KEY'),
//                "token: " . env('OTP_ACCESS_TOKEN'),
//            ];
//            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
//        }

        $response = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $response = json_decode($response);

        curl_close($curl);
//dd($httpcode);
        if ($httpcode != 200) {
            return null;
        }
        if (isset($response->data)) {
            return $response->data->mobile;

        }


    }

}
