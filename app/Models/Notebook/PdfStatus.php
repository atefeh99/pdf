<?php

namespace App\Models\Notebook;

use Illuminate\Database\Eloquent\Model;

class PdfStatus extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'pdf_status';

    public static function store($data)
    {

        return self::create($data);
    }

}
