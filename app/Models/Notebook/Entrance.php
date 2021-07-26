<?php

namespace App\Models\Notebook;

use Illuminate\Database\Eloquent\Model;

class Entrance extends Model
{
    protected $connection = 'gnaf';
    protected $table = 'entrance';

    public static function index($address_id)
    {
        $query = self::id($address_id)->get(['id', 'plate_no']);
        return $query->toArray();
    }
//    public static function getId($address_id)
//    {
//        $ids = array();
//        $query = self::id($address_id)->get(['id']);
//        foreach ($query as $q) {
//            array_push($ids,$q->id);
//        }
//        return $ids;
//    }
    public function scopeId($query, $address_id)
    {
        return $query->where('address_id', $address_id);
    }
}
