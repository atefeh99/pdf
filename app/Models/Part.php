<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    protected $connection = 'pgsql2';
    protected $table = 'part';

    public static function index($tour_id)
    {
        $query = self::id($tour_id)->get(['id', 'tour_id']);
        return $query->toArray();
    }
//    public static function getId($tour_id)
//    {
//        $ids = array();
//        $query = self::id($tour_id)->get(['id']);
//        foreach ($query as $q) {
//          array_push($ids,$q->id);
//        }
//       return $ids;
//    }

    public function scopeId($query, $tour_id)
    {
        return $query->where('tour_id', $tour_id);
    }
}
