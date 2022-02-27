<?php

namespace App\Models\Notebook;

use Illuminate\Database\Eloquent\Model;

class Neighbourhood extends Model
{
    protected $connection = 'gnaf';
    protected $table = 'parish';
}
