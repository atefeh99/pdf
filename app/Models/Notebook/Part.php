<?php

namespace App\Models\Notebook;

use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    protected $connection = 'gnaf';
    protected $table = 'part';

    public static function index($tour_id)
    {
        $query = self::id($tour_id)->get(['id', 'tour_id']);
        return $query->toArray();
    }


    public function scopeId($query, $tour_id)
    {
        return $query->where('tour_id', $tour_id);
    }
}
