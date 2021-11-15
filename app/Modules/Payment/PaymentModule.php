<?php


namespace App\Modules\Payment;


class PaymentModule
{

    public static function getServices()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => env('PAYMENT_SERVICES_URL'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                "x-api-key: " . env('API_KEY'),
                "token: " . env('ACCESS_TOKEN'),
            )
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response);

    }


}
