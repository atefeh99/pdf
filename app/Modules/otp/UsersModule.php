<?php

namespace App\Modules\otp;

class UsersModule
{
    public static function getMobile($user_id)
    {

//        $user_id='31c5d23a-2882-44e7-ba85-3ac9cd15bcdc';
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => env('OTP_URL').$user_id,
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

        $response = curl_exec($curl);
        $response = json_decode($response);

        curl_close($curl);
        if(isset($response->data)){
            return $response->data->mobile;
        }
        else{
            return null;
        }

    }

}
