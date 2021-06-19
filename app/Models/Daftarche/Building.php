<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    protected $connection = 'pgsql2';
    protected $table = 'building';

//    public static function count($block_id)
//    {
//        $blocks = self::id($block_id)->get();
//        return $blocks->count();
//    }
    public static function index($block_id)
    {
        $query = self::id($block_id)->get(['id', 'flour_count', 'building_no', 'neighbourhood_id']);
        return $query->toArray();
    }
//    public static function getId($block_id)
//    {
//        $ids = array();
//        $query = self::id($block_id)->get(['id']);
//        foreach ($query as $q) {
//            array_push($ids,$q->id);
//        }
//        return $ids;
//    }
    public function scopeId($query, $block_id)
    {
        return $query->where('block_id', $block_id);
    }
}
