<?php

namespace App\Models\Notebook;

use Illuminate\Database\Eloquent\Model;

class Way extends Model
{
    protected $connection = 'gnaf';
    protected $table = 'road';

    public function road_type()
    {
        return $this->hasOne(RoadType::class, 'id', 'road_type_id');
    }

}
