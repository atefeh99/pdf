<?php

namespace App\Models\Notebook;

use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    protected $connection = 'gnaf';
    protected $table = 'part';

    public static function get($id)
    {
        return self::find($id)->toArray();
    }

    public function blocks()
    {
        return $this->hasMany(Block::class, 'part_id', 'id');
    }
}
