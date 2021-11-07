<?php


namespace App\Models\Sina;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PostData extends Model
{
    use Common;

    protected $connection = 'gnaf';
    protected $table = 'sina_unit';
    protected static $_table = 'sina_units';
//    protected $postgisFields = [
//        'geom'
//    ];
//    protected $postgisTypes = [
//        'geom' => [
//            'geomtype' => 'geometry',
//            'srid' => 4326
//        ]
//    ];

    protected $appends = [
        'address'
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
            'activity_type'


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
            foreach ($items as $item) {

                if ($item['locationtype'] != 'روستا') {
                    $item['zonename'] = $item['villagename'] = '(فقط برای روستاها)';
                }
            }
            return $items;
        } else {
            return null;
        }
    }

    public static function getGeom($postalcode)
    {
        $item = self::where('postalcode', '=', $postalcode)->get([
            DB::raw('ST_X (ST_Transform (geom, 4326)) AS lon,
                           ST_Y (ST_Transform (geom, 4326)) AS lat')
        ]);

        return $item->toArray()[0];
    }
    public static function getDirectMailInfo($ids)
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
            'id',
            'mainavenue',
            'building',
            'blockno',
            'unit',
           'activity_type',
           'activity_name'

        ];
        $items = self::whereIn('id', $ids)->get($out_fields)
            ->unique(function ($item) use ($out_fields) {
                $temp = "";
                foreach ($out_fields as $out_field) {
                    $temp .= $item[$out_field];
                }
                return $temp;
            })
            ->keyby('id')
            ->toArray();
        if (count($items) == 0) return null;
        return $items;


    }



}
