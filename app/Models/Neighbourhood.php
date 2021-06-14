<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Neighbourhood extends Model
{
    protected $connection = 'pgsql2';
    protected $table = 'neighbourhood';

    public static function getName($neighbourhood_id)
    {
        $query = self::id($neighbourhood_id)->first();
        return $query->name;
    }

    public function scopeId($query, $neighbourhood_id)
    {
        return $query->where('id', $neighbourhood_id);
    }
}
