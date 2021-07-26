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
                'getPdf' => [
                    'notebook' => [
//                        'notebook_1' => 'required',
//                        'notebook_2' => 'required',
//                        'notebook_3' => 'required',
                        //  'second.data' => 'array|required',
                        'tour_id' => 'integer|required_if: block_id,null',
                        'block_id' => 'integer|required_if: tour_id,null'
                    ],
                    'gavahi' => [
                        'postalcode.*' => 'required|size:10'
                    ]
                ]

            ]
        ];
    }

    public static function checkRules($data, $function, $identifier, $code)
    {
        $controller = __CLASS__;
        if (is_object($data)) {
            if ($identifier) {
                $validation = Validator::make(
                    $data->all(),
                    self::rules()[$controller][$function][$identifier]
                );
            } else $validation = Validator::make(
                $data->all(),
                self::rules()[$controller][$function]
            );


        } else {
            if ($identifier) {
                $validation = Validator::make(
                    $data,
                    self::rules()[$controller][$function][$identifier]
                );
            } else {
                $validation = Validator::make(
                    $data,
                    self::rules()[$controller][$function]
                );
            }


        }
        if ($validation->fails()) {
            throw new RequestRulesException($validation->errors()->getMessages(), $code);
        }
        return $validation->validated();
    }
}
