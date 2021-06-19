<?php

namespace App\Models\Gavahi;

use Illuminate\Database\Eloquent\Model;

class PostDataIntegrated extends Model
{
    protected $connection = 'pgsql2';
    protected $table = 'post_data_integrated';

    public static function getSummery($postalcode)
    {
        $item = self::where('postalcode', $postalcode)->get([
            'postalcode',
            'pelak',
            '', '', '']);
    }
}
