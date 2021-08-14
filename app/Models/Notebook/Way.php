<?php

namespace App\Models\Notebook;

use Illuminate\Database\Eloquent\Model;

class Way extends Model
{
    protected $connection = 'gnaf';
    protected $table = 'road';

//    protected $with = [
//        'road_type'
//    ];


    // public static function getName($way_id)
    // {
    //     if ($way_id) {
    //         $query = self::id($way_id)->first();
    //         return $query->name;
    //     } else {
    //         return '';
    //     }

    // }
    public function road_type()
    {
        return $this->hasOne(RoadType::class,'id','road_type_id');
    }


}
