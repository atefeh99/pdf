<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuccessJobs extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'success_jobs';

    protected $fillable = [
        'queue_name',
        'job_id',
        'data',
    ];
    public static function createItem($data)
    {
        $item = self::create($data);
        return $item;
    }
    public function setDataAttribute($value)
    {
        $this->attributes['data'] = json_encode($value);
    }

    public function getDataAttribute($value)
    {
        return json_decode($value, true);
    }

}
