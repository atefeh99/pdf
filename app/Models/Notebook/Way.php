<?php

namespace App\Models\Notebook;

use Illuminate\Database\Eloquent\Model;

class Way extends Model
{
    protected $connection = 'gnaf';
    protected $table = 'way';


    public static function getName($way_id)
    {
        if ($way_id) {
            $query = self::id($way_id)->first();
            return $query->name;
        } else {
            return '';
        }

    }

    public function scopeId($query, $way_id)
    {
        return $query->where('id', $way_id);
    }
}
