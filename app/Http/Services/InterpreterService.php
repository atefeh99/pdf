<?php

namespace App\Http\Services;

use App\Models\Interpreter;

class InterpreterService
{
    public static function index()
    {
        return Interpreter::index();

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
