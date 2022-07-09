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

        foreach($items as $key => $item){
            $barcode = '';
            $barcode_unique = false;
            while (!$barcode_unique) {
                $barcode = Random::randomNumber(20);
                if (File::isUniqueBarcode($barcode)) $barcode_unique = true;
            }
            $items[$key]['barcode'] = $barcode;
            array_push($barcodes, $barcode);
        }
        $d = [
            'user_id' => $user_id,
            'filename' => $uuid,
            'barcodes' => $barcodes,
        ];
        // File::store($d);
    //  dd($barcodes);
    }

}