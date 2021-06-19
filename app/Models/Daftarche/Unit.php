<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $connection = 'pgsql2';
    protected $table = 'unit';


    public static function index($entrance_id)
    {
        $query = self::id($entrance_id)
            ->whereNotNull('recog_code')
            ->get(['id', 'floor_no', 'recog_code', 'unit_no', 'row_no', 'name', 'isic_id', 'location_type_id']);
        return $query->toArray();
    }

    public function scopeId($query, $entrance_id)
    {
        return $query->where('entrance_id', $entrance_id);
    }
}
