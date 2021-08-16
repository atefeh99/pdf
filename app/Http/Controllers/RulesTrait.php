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
//        dd();
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
                        'tour_id' => 'numeric|nullable',
                        'block_id' => 'numeric|nullable',
                    ],
                    'gavahi' => [
                        'postalcode.*' => 'required|size:10',
                        'geo' => 'boolean'
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
        if (isset($data['tour_id']) and isset($data['block_id'])) {
            throw new RequestRulesException("both tour_id and block_id can't be filled together", $code);
        } elseif (isset($data['tour_id']) and !$data['tour_id']) {
            throw new RequestRulesException("tour_id can't be null", $code);
        } elseif (isset($data['block_id']) and !$data['block_id']) {
            throw new RequestRulesException("block_id can't be null", $code);
        } elseif (!(isset($data['block_id'])) or !(isset($data['tour_id']))) {
            throw new RequestRulesException("both block_id or tour_id can't be empty", $code);
        }


        return $validation->validated();
    }
}
