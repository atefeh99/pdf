<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Exceptions\RequestRulesException;
use App\Http\Controllers\Process\SynchronizationController;
use App\Http\Controllers\Task\TaskManagementController;
use App\Http\Controllers\Task\CommentController;
use Illuminate\Validation\Rule;

trait RulesTrait
{


    public static function rules()
    {
        return [
            InterpreterController::class => [
                'show' => [
                    'id' => 'integer'
                ],
                'store' => [
                    'identifier' => 'string',
                    'description' => 'string',
                    'html' => 'string',
                ],
                'update' => [
                    'id' => 'integer',
                ],
                'remove' => [
                    'id' => 'integer',
                ]
            ],
            PdfMakerController::class => [
                'daftarche' => [
                    'first' => 'required',
                    'second' => 'required',
                    'third' => 'required',
                    'second.data' => 'array|required',

                ],
                'gavahi' => [
                    'postalcode' => 'array|required|max:6',
                    'postalcode.*' => 'required|integer|max:10'
                ]
            ]
        ];
    }

    public static function checkRules($data, $function, $code)
    {
        $controller = __CLASS__;
        if (is_object($data)) {
            $validation = Validator::make(
                $data->all(),
                self::rules()[$controller][$function]
            );

        } else {
            $validation = Validator::make(
                $data,
                self::rules()[$controller][$function]
            );
        }
        if ($validation->fails()) {
            dd($validation->errors());
            throw new RequestRulesException($validation->errors()->getMessages(), $code);
        }
        return $validation->validated();
    }
}
