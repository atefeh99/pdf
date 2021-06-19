<?php

namespace App\Models\Daftarche;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $connection = 'pgsql2';
    protected $table = 'province';

    public static function getName($id)
    {
        $item = self::where('id', $id)->first();
        return $item->name;
    }
}
