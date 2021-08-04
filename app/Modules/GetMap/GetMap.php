<?php

namespace App\Modules\GetMap;

use App\Models\Gavahi\PostData;
use Illuminate\Support\Facades\Http;
use function PHPUnit\Framework\isNull;

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

        return Http::get(env('GEO_URL'), [
            'width' => 1400,
            'height' => 800,
            'zoom_level' => 14,
            'style' => 'light',
            'type' => 'vector',
            'markers' => 'color:red|label:a|' . $lon . ',' . $lat

        ]);
    }
}
