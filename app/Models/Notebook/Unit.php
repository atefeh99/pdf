<?php

namespace App\Models\Notebook;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $connection = 'gnaf';
    protected $table = 'recognition_code_info';

    protected $appends = [
//        'recog_code',
//        'row_no',
//        'isic_id',
//        'location_type_id',
//        'name'

    ];


    public static function index($entrance_id)
    {
        $query = self::id($entrance_id)
            ->whereNotNull('unit_identifier')
            ->get();
//        ['id', 'floor_no', 'recog_code', 'unit_no', 'row_no', 'name', 'isic_id', 'location_type_id']

        return $query->toArray();
    }

    public function scopeId($query, $entrance_id)
    {
        return $query->where('plate_id', $entrance_id);
    }
//    public function getRecogCodeAttribute()
//    {
//        return $this->attributes['unit_identifier'];
//    }
//    public function getRowNoAttribute()
//    {
//        return $this->attributes['part_row_no'];
//    }
//    public function getIsicIdAttribute()
//    {
//        return $this->attributes['act_type_id1'];
//    }
//    public function getLocationTypeIdAttribute()
//    {
//        return $this->attributes['poi_type_id'];
//    }
//    public function getNameAttribute()
//    {
//        return $this->attributes['first_name'];
//    }

}
