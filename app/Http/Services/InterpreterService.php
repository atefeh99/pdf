<?php

namespace App\Http\Services;

use App\Models\Interpreter;

class InterpreterService
{
    public static function index()
    {
        $data['data'] = Interpreter::index();
        $data['count'] = count($data['data']);
        return $data;

    }

    public static function show($id)
    {
        return Interpreter::show($id);
    }

    public static function update($identifier, $list)
    {
        Interpreter::updateItem($identifier, $list);
        return self::show($identifier);
    }

    public static function store($data)
    {
        return Interpreter::store($data);

    }

    public static function remove($id)
    {
        Interpreter::remove($id);
    }
}
