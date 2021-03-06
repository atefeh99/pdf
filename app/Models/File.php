<?php

namespace App\Models;


use Carbon\Carbon;
use App\Models\OdataTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class File extends Model
{
    use Common;
    use OdataTrait;

    protected $connection = 'pgsql';
    protected $table = 'files';

    protected $fillable = [
        'user_id',
        'filename',
        'barcodes',
        'expired_at'
    ];
    protected $casts = [
        'barcodes' => 'array'
    ];

    public static function checkExpiration($filename, $user_id)
    {
        $query = self::where([
            ['filename', '=', $filename],
            ['user_id', '=', $user_id]
        ])->get();
        if ($query->count() > 0) {
            $expiraton_dateTime = $query->toArray()[0]['expired_at'];
            $now = Carbon::now()->toDateTimeString();
            if (isset($expiraton_dateTime)) {
                if ($expiraton_dateTime > $now) return false;
                else return true;
            } else return false;
        } else throw new ModelNotFoundException();
    }

    public static function isUniqueBarcode($barcode)
    {
        $query = self::whereRaw('jsonb_contains(barcodes, \'["' . $barcode . '"]\')')->get(['barcodes']);
        if ($query->count() > 0) return false;
        else return true;
    }

    public static function store($data)
    {
        return self::create($data);
    }

    public static function getEx($filename, $user_id)
    {
        Log::info('filename: '.$filename.' userid: '.$user_id);
        $query = self::where([
            ['filename', '=', $filename],
            ['user_id', '=', $user_id]
        ])->get('expired_at')->toArray();
        if (count($query) > 0) return $query[0]['expired_at'];
        else throw new ModelNotFoundException();
    }

}
