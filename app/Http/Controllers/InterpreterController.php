<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\InterpreterService;

class InterpreterController extends ApiController
{
    use RulesTrait;

    public function index()
    {
        $interpreter_index = InterpreterService::index();
        return $this->respondArrayResult($interpreter_index['data'], $interpreter_index['count']);
    }

    public function show(Request $request, $id)
    {
        $id = self::checkRules(
            array_merge($request->all(), array('id' => $id)),
            __FUNCTION__,
            1002
        );
        $interpreter = InterpreterService::show($id);
        return $this->respondItemResult($interpreter);

    }

    public function update(Request $request, $id)
    {
        if ($request->has('data'))

            $data = self::checkRules(
                array_merge($request->all(), array('id' => $id)),
                __FUNCTION__,
                1004
            );
        $interpreters_updated = InterpreterService::update($id, $data);
        return $this->respondSuccessUpdate($interpreters_updated);

    }

    public function store(Request $request)
    {
        $data = self::checkRules($request, __FUNCTION__, 1003);
        $interpreter_creation = InterpreterService::store($data);
        return $this->respondSuccessCreate($interpreter_creation);
    }

    public function remove(Request $request, $id)
    {
        InterpreterService::remove($id);
        return $this->respondSuccessDelete($id);
    }
}
