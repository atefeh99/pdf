<?php

namespace App\Models\Daftarche;

use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    protected $connection = 'gnaf';
    protected $table = 'tour';

    public static function getId($tour_no)
    {
        $item = self::id($tour_no)->first();
        return $item->id;
    }

    public static function getProvinceId($tour_no)
    {
        $item = self::id($tour_no)->first();
        return $item->province_id;
    }

    public function scopeId($query, $tour_no)
    {
        return $query->where('name', $tour_no);
    }
}
