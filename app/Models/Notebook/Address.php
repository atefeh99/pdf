<?php

namespace App\Models\Notebook;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $connection = 'gnaf';
    protected $table = 'address';
    protected $appends = [
        'way_id',
        'secondary_way_id'

    ];
    protected $with=[
        'entrances'
    ];
    public static function index($building_id)
    {
        $query = self::id($building_id)->get();
//        ['id', 'way_id', 'secondary_way_id']
        return $query->toArray();
    }

    public function scopeId($query, $building_id)
    {
        return $query->where('building_id', $building_id);
    }
    public function getWayIdAttribute()
    {
        return $this->attributes['street_id'];
    }
    public function getSecondaryWayIdAttribute()
    {
        return $this->attributes['secondary_street_id'];
    }
    public function entrances()
    {
        return $this->hasMany(Entrance::class,'address_id','id');
    }
}
