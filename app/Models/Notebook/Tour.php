<?php

namespace App\Models\Notebook;

use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    protected $connection = 'gnaf';
    protected $table = 'tour';

//    protected $with = [
//        'parts',
//        'province'
//    ];


    public static function getData($id){
        return self::with([
            'parts', 'province',
            'parts.blocks',
            'parts.blocks.buildings',
            'parts.blocks.buildings.neighbourhood','parts.blocks.buildings.addresses',
            'parts.blocks.buildings.addresses.entrances', 'parts.blocks.buildings.addresses.street',
            'parts.blocks.buildings.addresses.secondary_street',
            'parts.blocks.buildings.addresses.entrances.units', 'parts.blocks.buildings.addresses.street.road_type',
        ])->find($id);
    }

    public function parts()
    {
        return $this->hasMany(Part::class, 'tour_id', 'id');
    }
    public function province()
    {
        return $this->hasOne(Province::class,'id','province_id');
    }

}
