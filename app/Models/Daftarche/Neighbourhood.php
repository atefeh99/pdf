<?php

namespace App\Models\Daftarche;

use Illuminate\Database\Eloquent\Model;

class Neighbourhood extends Model
{
    protected $connection = 'gnaf';
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
