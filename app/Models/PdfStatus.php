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
        'status'
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

    public static function show($job_id, $user_id, $output)
    {
//        dd($job_id);
        $item = self::where([
            ['job_id', '=', $job_id],
            ['user_id', '=', $user_id],
        ])->get(
            [$output]
        );
        if (count($item) > 0) {
            if ($item->toArray()[0]['status'] == 'success') {
                return $item->toArray()[0];
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    public static function updateInfo($job_id, $info)
    {
        $item = self::where('job_id', $job_id)->firstOrFail();
        $item->update(['info' => $info]);
    }
}
