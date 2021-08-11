<?php

namespace App\Models\Notebook;

use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    protected $connection = 'gnaf';
    protected $table = 'tour';

    protected $with = [
        'parts'
    ];

    public static function getName($tour_id)
    {
//        dd(self::id($tour_id)->join);
        $item = self::id($tour_id)
            ->join('part', 'part.tour_id', '=', 'tour.id')
            ->first();
        return $item;
    }
    public static function getData($id){
        return self::find($id);
    }

    public static function getProvinceId($tour_id)
    {
        $item = self::id($tour_id)->first();
        return $item->province_id;
    }

    public function scopeId($query, $tour_id)
    {
        return $query->where('tour.id', $tour_id);
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
