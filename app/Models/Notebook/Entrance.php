<?php

namespace App\Models\Notebook;

use Illuminate\Database\Eloquent\Model;

class Entrance extends Model
{
    protected $connection = 'gnaf';
    protected $table = 'plate';

    public function units()
    {
        return $this->hasMany(Unit::class, 'plate_id', 'id');
    }
}
