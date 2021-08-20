<?php

namespace App\Models\Notebook;

use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    protected $connection = 'gnaf';
    protected $table = 'block';

//    protected $with = [
//        'buildings',
////        'tour',
////        'part',
//        'province',
//        'county',
//        'zone'
//    ];
    public static function getData($id)
    {
        return self::with([
                'buildings', 'province', 'county', 'zone',
                'buildings.neighbourhood','buildings.addresses',
                'buildings.addresses.entrances', 'buildings.addresses.street','buildings.addresses.secondary_street',
                'buildings.addresses.entrances.units', 'buildings.addresses.street.road_type','buildings.addresses.secondary_street.road_type',
            ])->find($id);
    }

    public function buildings()
    {
        return $this->hasMany(Building::class,'block_id','id');
    }
    public function tour()
    {
        return $this->hasOne(Tour::class,'id','tour_id');
    }
    public function part()
    {
        return $this->hasOne(Part::class,'id','part_id');
    }
    public function province()
    {
        return $this->hasOne(Province::class,'id','province_id');
    }
    public function county()
    {
        return $this->hasOne(County::class,'id','county_id');
    }
    public function zone()
    {
        return $this->hasOne(Zone::class,'id','zone_id');
    }
}
