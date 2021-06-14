<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $connection = 'pgsql2';
    protected $table = 'address';

    public static function index($building_id)
    {
        $query = self::id($building_id)->get(['id', 'way_id', 'secondary_way_id', 'plate_no']);
        return $query->toArray();
    }

    public function scopeId($query, $building_id)
    {
        return $query->where('building_id', $building_id);
    }
}
