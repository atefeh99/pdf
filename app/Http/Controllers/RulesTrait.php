<?php

namespace App\Http\Controllers;

use App\Exceptions\UnauthorizedUserException;
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
                        'tour_id' => 'numeric|nullable',
                        'block_id' => 'numeric|nullable',
                    ],
                    'gavahi' => [
                        'postalcode' => 'required|array',
                        'geo' => 'boolean',
                    ],
                ],
                'getAsyncPdf' => [
                    'notebook' => [
                        'tour_id' => 'numeric|nullable',
                        'block_id' => 'numeric|nullable',
                    ],
                    'gavahi' => [
                        'postalcode' => 'required|array',
                        'geo' => 'boolean',
                    ],
                    'direct_mail'=> [
                        "class_id" => 'integer|required',
                        "population_point_id" => 'array|required',
                        "population_point_id.*" => 'int|required'
                    ]
                ],
                'pdfStatus' => [
                    'job_id' => 'numeric|min:0|required'
                ],
                'pdfLink' => [
                    'job_id' => 'numeric|min:0|required'
                ],
                'gavahiPdfWithInfo'=> [
                    'ClientBatchID'=> 'numeric|required',
                    'Postcodes' => 'required|array',
                    'Postcodes.*'=> 'required|array',
                    'Postcodes.*.ClientRowID' => 'required|numeric',
                    'Postcodes.*.PostCode'=> 'required',
                    'Signature'=> 'string',
                    'geo' => 'boolean',
                ]

            ]
        ];
    }

    public static function checkRules($data, $function, $code)
    {
        $controller = __CLASS__;
        if (is_object($data)) {
            if (isset($data['identifier'])) {
                $validation = Validator::make(
                    $data->all(),
                    self::rules()[$controller][$function][$data['identifier']]
                );
            } else $validation = Validator::make(
                $data->all(),
                self::rules()[$controller][$function]
            );


        } else {
            if (isset($data['identifier'])) {
                $validation = Validator::make(
                    $data,
                    self::rules()[$controller][$function][$data['identifier']]
                );

            } else {
                $validation = Validator::make(
                    $data,
                    self::rules()[$controller][$function]
                );
            }


        }
        if ($validation->fails()) {
            dd($validation->errors()->getMessages());
            throw new RequestRulesException($validation->errors()->getMessages(), $code);
        }

        if (isset($data['identifier']) && $data['identifier'] == 'notebook') {

            if (isset($data['tour_id']) and isset($data['block_id'])) {
                throw new RequestRulesException(trans('messages.custom.both_filled'), $code);
            } elseif (isset($data['tour_id']) and !$data['tour_id']) {
                throw new RequestRulesException(trans('messages.custom.null_field'), $code);
            } elseif (isset($data['block_id']) and !$data['block_id']) {
                throw new RequestRulesException(trans('messages.custom.null_field'), $code);
            } elseif (!isset($data['block_id']) and !isset($data['tour_id'])) {
                throw new RequestRulesException(trans('messages.custom.both_empty'), $code);
            }
        }

            return $validation->validated();
    }
}
