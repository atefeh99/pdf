<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Way extends Model
{
    protected $connection = 'pgsql2';
    protected $table = 'way';


    public static function getName($way_id)
    {
        $query = self::id($way_id)->first();
        return $query->name;
    }

    public function scopeId($query, $way_id)
    {
        return $query->where('id', $way_id);
    }
}
