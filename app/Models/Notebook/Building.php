<?php

namespace App\Models\Notebook;

use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    protected $connection = 'gnaf';
    protected $table = 'building';

    protected $appends = [
        'building_no',
        'neighbourhood_id'
    ];
    protected $fillable = [
        'building_no'
    ];
    protected $with = [
        'addresses',
        'neighbourhood'
    ];

    public static function index($block_id)
    {
        $query = self::id($block_id)->get();
        // dd($query->toArray());
//        ['id', 'floor_count', 'building_no', 'neighbourhood_id']
        return $query->toArray();
    }
//    public static function getId($block_id)
//    {
//        $ids = array();
//        $query = self::id($block_id)->get(['id']);
//        foreach ($query as $q) {
//            array_push($ids,$q->id);
//        }
//        return $ids;
//    }
    public function scopeId($query, $block_id)
    {
        return $query->where('block_id', $block_id);
    }

    /*
     * accessors
     */
    public function getBuildingNoAttribute()
    {
        return $this->attributes['building_number'];
    }

    public function getNeighbourhoodIdAttribute()
    {
        return $this->attributes['parish_id'];
    }

    public function addresses()
    {
    return $this->hasMany(Address::class,'building_id','id');
    }
    public function neighbourhood()
    {
        return $this->hasOne(Neighbourhood::class,'id','neighbourhood_id');
    }
}
