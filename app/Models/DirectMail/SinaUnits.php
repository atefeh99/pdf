<?php


namespace App\Models\DirectMail;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class SinaUnits extends Model
{
    use Common;
    protected $connection = 'sina';
    protected $table = 'sina_units';
    protected static $_table = 'sina_units';

    protected $appends = [
        'country_division',
        'parish_and_way',
        'pelak_and_entrance'
    ];

    public static function index($population_point_ids)
    {
        $out_fields = [
            'postalcode',
            'statename',
            'townname',
            'zonename',
            'villagename',
            'locationname',
            'locationtype',
            'parish',
            'preaventypename',
            'preaven',
            'avenuetypename',
            'avenue',
            'plate_no',
            'floorno',
            'mainavenue',
            'building',
            'blockno',
            'unit',
            'population_point_id',
            'activity'
        ];
        Log::info("#making indexes " );

        $items = self::WhereIn('population_point_id',$population_point_ids)
//            ->toSql();
            ->get($out_fields)
            ->keyby('population_point_id')
            ->toArray();
        Log::info("#indexed" );

        if (count($items) == 0) return null;
        return $items;

    }
}
