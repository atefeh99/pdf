<?php


namespace App\Modules\Payment;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class PaymentModule
{

    public static function getServices()
    {
        $client = new Client();
//        dd(env('PAYMENT_URL'),env('API_KEY'),env('ACCESS_TOKEN'));
        try {
            $resp = $client->createRequest(
                'GET',
                env('PAYMENT_URL'),
                [
                    'headers' => [
                        'x-api-key' => env('API_KEY'),
                        'token' => env('ACCESS_TOKEN'),
                        'x-scopes' => 'admin'
                    ]
                ]
            );
        } catch (ClientException  $e) {
            dd($e->getMessage());
        }
        dd($resp);
        return $resp;
    }


}
