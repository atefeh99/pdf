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
        'entrances',
        'street',
        'secondary_street'
    ];
   

   
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
    public function street()
    {
        return $this->hasOne(Way::class, 'id', 'street_id');

    }
    public function secondary_street()
    {
        return $this->hasOne(Way::class, 'id', 'secondary_street_id');

    }
}
