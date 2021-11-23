<?php


namespace App\Models\DirectMail;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SinaUnits extends Model
{
    protected $connection = 'sina';
    protected $table = 'sina_units';
    protected static $_table = 'sina_units';

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
            'population_point_id'
        ];
        $items = self::WhereIn('population_point_id', $population_point_ids)
            ->get($out_fields)
            ->unique(function ($item) use ($out_fields) {
                $temp = "";
                foreach ($out_fields as $out_field) {
                    $temp .= $item[$out_field];
                }
                return $temp;
            })
            ->keyby('population_point_id')
            ->toArray();
        if (count($items) == 0) return null;
        return $items;

    }
}
