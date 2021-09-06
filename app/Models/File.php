<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use Common;

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

    public function getItem()
    {

    }

    public static function isUniqueBarcode($barcode)
    {
        $query = self::whereRaw('jsonb_contains(barcodes, \'["' . $barcode . '"]\')')->get(['barcodes']);
        if ($query->count() > 0) {
            return false;
        } else {
            return true;
        }

    }

    public static function store($data)
    {
        return self::create($data);
    }
}
