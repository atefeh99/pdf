<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PdfStatus extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'pdf_status';

    protected $fillable = [
        'job_id',
        'link',
        'user_id',
        'status',
        'identifier'
    ];

    public static function changeStatus($job_id, $status)
    {
        $item = self::where('job_id', $job_id)->firstOrFail();
        $item->update(['status' => $status]);
    }

    public static function store($data)
    {
        return self::create($data);
    }

    public static function getStatus($job_id, $user_id)
    {

        $item = self::where([
            ['job_id', '=', $job_id],
            ['user_id', '=', $user_id]])
            ->get(['job_id', 'status']);
        if (count($item) > 0) {
            return $item->toArray()[0];
        } else {
            return null;
        }
    }

    public static function show($job_id, $user_id)
    {
//        dd($job_id, $user_id);
        $item = self::where([
            ['job_id', '=', $job_id],
            ['user_id', '=', $user_id],
        ])->get(
            ['job_id', 'status', 'link', 'identifier']
        );

        if (count($item) > 0) {
            return $item->toArray()[0];
        } else {
            return null;
        }
    }

    public static function updateInfo($job_id, $info)
    {
        $item = self::where('job_id', $job_id)->firstOrFail();
        $item->update(['info' => $info]);
    }

    public static function updateRecord($job_id, $data)
    {

        $item = self::where('job_id', $job_id)->firstOrFail();
        $item->update($data);
        $item->save();
    }

}
