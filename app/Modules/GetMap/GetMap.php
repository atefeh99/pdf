<?php

namespace App\Modules\GetMap;

use App\Models\Sina\PostData;

class GetMap
{
    public static function vectorMap($postalcode)
    {
        $lat_lon = PostData::getGeom($postalcode);
        $lon = $lat_lon['lon'];
        $lat = $lat_lon['lat'];
        if (!$lon or !$lat) {
            return null;
        }
//        try {
//            $response = Http::get(env('GEO_URL'), [
//                'width' => 1400,
//                'height' => 800,
//                'zoom_level' => 14,
//                'style' => 'light',
//                'type' => 'vector',
//                'markers' => 'color:red|label:a|' . $lon . ',' . $lat
//
//            ]);
//            dd($response);
//        } catch (\Exception $e) {
//dd($e->getMessage());
//            $response = null;
//        }
        $curl = curl_init();

        curl_setopt_array($curl, array(
//            CURLOPT_URL => 'localhost:8080?width=1400&height=800&markers=color:red%7Clabel:a%7C51.394912,35.72164&zoom_level=14&type=vector&style=light',
            CURLOPT_URL => 'localhost:8080?width=1400&height=800&markers=color:red%7Clabel:a%7C51.394912,35.72164&zoom_level=14&type=vector&style=light',

            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);
        curl_close($curl);

//dd($response);
return $response;
    }
}
