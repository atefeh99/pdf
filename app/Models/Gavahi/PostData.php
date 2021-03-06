<?php


namespace App\Models\Gavahi;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PostData extends Model
{
    use Common;

    protected $connection = 'sina';
    protected $table = 'sina_units_table';
    protected static $_table = 'sina_units_table';

    protected $appends = [
        'address',
        'country_division',
        'post_address'
    ];
    protected $fillable = [

        'statename',
        'townname',
        'zonename',
        'villagename',
        'locationname',
        'locationtype',
        'parish',
        'avenue',
        'preaven',
        'plate_no',
        'blockno',
        'floorno',
        'building_type',
        'tour',
        'preaventypename',
        'avenuetypename',
        'unit',
        'geom',
        'postalcode',
    ];

    public static function getGavahiInfo($postalcodes)
    {
        $out_fields = [
            'statename',
            'townname',
            'zonename',
            'villagename',
            'locationname',
            'locationtype',
//                'localitycode',
            'building_name',
            'parish',
            'avenue',
            'preaven',
            'plate_no',
            'blockno',
            'building',
            'floorno',
            'building_type',
            'tour',
            'preaventypename',
            'avenuetypename',
            'unit',
            'postalcode',
            'activity_type',
            'entrance',
            'poi_type_name'
        ];

        $items = self::whereIn('postalcode', $postalcodes)->get($out_fields)
            ->unique(function ($item) use ($out_fields) {
                $temp = "";
                foreach ($out_fields as $out_field) {
                    $temp .= $item[$out_field];
                }
                return $temp;
            })
            ->keyby('postalcode')
            ->toArray();
        if (count($items) > 0) {
            foreach ($items as $key=>$item) {
                if ($item['locationtype'] != '??????????') $item['zonename'] = $item['villagename'] = '(?????? ???????? ??????????????)';
                if (str_contains($item['statename'], trans('words.Tehran', [], 'fa'))) {
                    $items[$key]['statename'] = trans('words.Tehran', [], 'fa');
                }
            }
            return $items;
        } else return null;

    }

    public static function getGeom($postalcode)
    {
        $item = self::where('postalcode', '=', $postalcode)->get([
            DB::raw('ST_X (ST_Transform (geom, 4326)) AS lon,
                           ST_Y (ST_Transform (geom, 4326)) AS lat')
        ]);

        return $item->toArray()[0];
    }

}
