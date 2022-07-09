<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Postalcodes extends Model
{
    protected $connection = 'sina';
    protected $table = 'sina_units_table';
    protected static $_table = 'sina_units_table';
    public static function getItems($plate_id)
    {
        $items = self::where('plate_id', $plate_id)->get(['postalcode','unit','floorno'])->toArray();

        if(count($items) < 0 ){
            throw new ModelNotFoundException();
        }
        return $items;
    }

}