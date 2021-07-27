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
    protected $table = 'post_data_integrated';
    protected static $_table = 'post_data_integrated';

    protected $appends = [
        'address'
    ];
    protected $fillable = [
        'postalcode',
        'statename',
        'townname',
        'zonename',
        'villagename',
        'locationname',
        'locationtype',
        'parish',
        'avenue',
        'preaven',
        'pelak',
        'blockno',
        'floorno',
        'building_type',
        'tour',
        'preaventypename',
        'avenuetypename',
        'unit'
    ];

    public static function getInfo($postalcode)
    {


        try {
            $item = self::where('postalcode', '=', $postalcode)->get([
                'postalcode',
                'statename',
                'townname',
                'zonename',
                'villagename',
                'locationname',
                'locationtype',
                'parish',
                'avenue',
                'preaven',
                'pelak',
                'blockno',
                'floorno',
                'building_type',
                'tour',
                'preaventypename',
                'avenuetypename',
                'unit'

            ]);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }

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


}
