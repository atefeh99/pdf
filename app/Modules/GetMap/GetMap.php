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
//
        $curl = curl_init();

        curl_setopt_array($curl, array(
//            CURLOPT_URL => "localhost:8080?width=1400&height=800&markers=color:gavahi_blue|$lon,$lat&zoom_level=18&type=vector",
//            CURLOPT_URL => "https://dev.map.ir/static?width=1400&height=800&markers=color:gavahi_blue|$lon,$lat&zoom_level=15&type=vector&style=light",

            CURLOPT_URL => "https://dev.map.ir/static?width=1400&height=800&markers=color:gavahi_blue|$lon,$lat&zoom_level=18&type=vector",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                "x-api-key: " . env('DEV_API_KEY'),

            ),
        ));

        $response = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);curl_close($curl);

        if ($httpcode != 200) {
            return null;
        }
        return $response;

    }
}
