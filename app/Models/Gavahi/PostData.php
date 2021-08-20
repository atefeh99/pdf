<?php


namespace App\Models\Gavahi;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PostData extends Model
{
    use Common;

    protected $connection = 'postalcode';
    protected $table = 'sina_units';
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

    public static function getInfo($postalcode)
    {


//        try {
            $item = self::where('postalcode', '=', $postalcode)->get([

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
                'postalcode'

            ]);
//        } catch (\Exception $exception) {
//            Log::error($exception->getMessage());
//        }

        if ($item->count() > 0) {
            $item = $item->toArray()[0];
            if ($item['locationtype'] != 'روستا') {
                $item['zonename'] = $item['villagename'] = '(فقط برای روستاها)';
            }
            return $item;
        } else {
            throw new ModelNotFoundException();
        }
    }
    public static function getGeom($postalcode){
        $item = self::where('postalcode', '=', $postalcode)->get([
            DB::raw('ST_X (ST_Transform (geom, 4326)) AS lon,
                           ST_Y (ST_Transform (geom, 4326)) AS lat')
        ]);

        return  $item->toArray()[0];
}


}
