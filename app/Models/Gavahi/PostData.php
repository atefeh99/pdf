<?php


namespace App\Models\Gavahi;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PostData extends Model
{
    use Common;

    protected $connection = 'postalcode';
    protected $table = 'post_data_integrated';

    protected $appends = [
        'address'
    ];

    public static function getInfo($postalcode)
    {

        $item = self::where('postalcode', $postalcode)->get([
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
