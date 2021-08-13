<?php

namespace App\Models\Notebook;

use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    protected $connection = 'gnaf';
    protected $table = 'tour';

    protected $with = [
        'parts',
        'province'
    ];

   
    public static function getData($id){
        return self::find($id);
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
