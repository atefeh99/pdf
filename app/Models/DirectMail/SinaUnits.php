<?php


namespace App\Models\DirectMail;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
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

    public static function index($data)
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
            'activity',
            'province_id',
            'county_id',
            'parish_id',
            'act_type_id'
        ];

        Log::info("#making indexes ");

        $items = self::where('act_type_id', $data['class_id'])
            ->where(function ($q) use ($data) {
                if (isset($data['divisions']['province'])) {
                    $q->whereIn('province_id', $data['divisions']['province']);

                }
                if (isset($data['divisions']['province']) && isset($data['divisions']['county'])) {
                    $q = $q->orWhereIn('county_id', $data['divisions']['county']);
                }
                if (!(isset($data['divisions']['province'])) && isset($data['divisions']['county'])) {
                    $q = $q->whereIn('county_id', $data['divisions']['county']);

                }
                if ((isset($data['divisions']['province']) || isset($data['divisions']['county']))
                    && isset($data['divisions']['parish'])) {
                    $q = $q->orWhereIn('parish_id', $data['divisions']['parish']);

                }
                if (!(isset($data['divisions']['province']) || isset($data['divisions']['county']))
                    && isset($data['divisions']['parish'])) {
                    $q = $q->whereIn('parish_id', $data['divisions']['parish']);
                }
                if ((isset($data['divisions']['province']) || isset($data['divisions']['county']) || isset($data['divisions']['parish']))
                    && isset($data['divisions']['population_point'])) {
                    $q = $q->orWhereIn('population_point_id', $data['divisions']['population_point']);

                }
                if (!(isset($data['divisions']['province']) || isset($data['divisions']['county']) || isset($data['divisions']['parish']))
                    && isset($data['divisions']['population_point'])) {
                    $q = $q->whereIn('population_point_id', $data['divisions']['population_point']);
                }
                return $q;
            })
            ->get($out_fields)->toArray();

        Log::info("#indexed");

        if (count($items) == 0) return null;
        return $items;

    }
}
