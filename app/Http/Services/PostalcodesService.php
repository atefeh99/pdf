<?php

namespace App\Http\Services;

use App\Models\File;
use Ramsey\Uuid\Uuid;
use App\Helpers\Random;
use App\Models\Postalcodes;

class PostalcodesService
{
    public static function getItems($data, $user_id )
    {
        $barcodes = [];
        $items = Postalcodes::getItems($data['plate_id']);
        $uuid = Uuid::uuid4();

        $d = [
            'user_id' => $user_id,
            'filename' => $uuid,
            'barcodes' => $barcodes,
        ];
        // File::store($d);
    //  dd($barcodes);
    }

}