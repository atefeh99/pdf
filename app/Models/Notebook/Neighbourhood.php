<?php

namespace App\Models\Notebook;

use Illuminate\Database\Eloquent\Model;

class Neighbourhood extends Model
{
    protected $connection = 'gnaf';
    protected $table = 'parish';


    public static function getName($neighbourhood_id)
    {
        if ($neighbourhood_id) {
            $query = self::id($neighbourhood_id)->first();
            return $query->name;
        } else {
            return '';
        }

    }

    public function scopeId($query, $neighbourhood_id)
    {
        return $query->where('id', $neighbourhood_id);
    }

}
