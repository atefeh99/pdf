<?php

namespace App\Modules\GetMap;

use App\Models\Gavahi\PostData;

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

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => env('STATIC_MAP_URL') . "?width=1400&height=800&markers=color:gavahi_blue|$lon,$lat&zoom_level=19&type=vector&style=light",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(),
        ]);
        if (!env('OFFLINE')) {

            $headers = [
                "x-api-key: " . env('API_KEY'),
                "token: " . env('ACCESS_TOKEN'),

            ];
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }
        $response = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($httpcode > 299 || $httpcode < 200) return null;
        return $response;
    }
}
