<?php

namespace App\Models\Notebook;

use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    protected $connection = 'gnaf';
    protected $table = 'tour';

    public static function getName($tour_id)
    {
        $item = self::id($tour_id)->first();
        return $item->name;
    }

    public static function getProvinceId($tour_id)
    {
        $item = self::id($tour_id)->first();
        return $item->province_id;
    }

    public function scopeId($query, $tour_id)
    {
        return $query->where('id', $tour_id);
    }
}
