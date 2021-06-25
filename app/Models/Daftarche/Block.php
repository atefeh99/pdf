<?php

namespace App\Models\Daftarche;

use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    protected $connection = 'pgsql2';
    protected $table = 'block';

    public static function index($part_id)
    {
        $query = self::id($part_id)->get(['id']);
        return $query->toArray();
    }
//    public static function count($part_id)
//    {
//       $blocks = self::id($part_id)->get();
//       return $blocks->count();
//    }
//    public static function getId($part_id)
//    {
//        $ids = array();
//        $query = self::id($part_id)->get(['id']);
//        foreach ($query as $q) {
//            array_push($ids,$q->id);
//        }
//        return $ids;
//    }

    public function scopeId($query, $part_id)
    {
        return $query->where('part_id', $part_id);
    }
}
