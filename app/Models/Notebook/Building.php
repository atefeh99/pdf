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
