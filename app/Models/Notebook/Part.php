<?php

namespace App\Models\Notebook;

use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    protected $connection = 'gnaf';
    protected $table = 'part';

    protected $with = [
        'blocks'
    ];

    public function blocks()
    {
        return $this->hasMany(Block::class,'part_id','id');
    }
}
