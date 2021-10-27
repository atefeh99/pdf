<?php

namespace App\Exceptions;

use App\Modules\Slack;
use ErrorException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param \Throwable $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Throwable $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $e)
    {
        $response = parent::render($request, $e);
        $debug = env('APP_DEBUG');
        if (!$debug) {
            $return_object = [
                'data' => [
                    'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => trans('messages.custom.' . Response::HTTP_INTERNAL_SERVER_ERROR),
                    'code' => 101
                ],
                'status' => [
                    Response::HTTP_INTERNAL_SERVER_ERROR
                ]
            ];

            if ($e instanceof UnauthorizedException) {
                $return_object = [
                    'data' => [
                        'status' => Response::HTTP_UNAUTHORIZED,
                        'messages' => trans('messages.custom.error.unauthorized'),
                        'code' => 102
                    ],
                    'status' => Response::HTTP_UNAUTHORIZED
                ];
            } elseif ($e instanceof UnauthorizedUserException) {
                $return_object = [
                    'data' => [
                        'status' => Response::HTTP_UNAUTHORIZED,
                        'message' => $e->getMessage(),
                        'code' => $e->getErrorCode()
                    ],
                    'status' => Response::HTTP_UNAUTHORIZED
                ];
            } elseif ($e instanceof MethodNotAllowedHttpException) {
                $return_object = [
                    'data' => [
                        'status' => $e->getStatusCode(),
                        'message' => trans('messages.custom.' . Response::HTTP_METHOD_NOT_ALLOWED),
                        'code' => 103
                    ],
                    'status' => $e->getStatusCode()
                ];
            } elseif ($e instanceof RequestRulesException) {
                $return_object = [
                    'data' => [
                        'status' => Response::HTTP_BAD_REQUEST,
                        'message' => trans('messages.custom.' . Response::HTTP_BAD_REQUEST),
                        'fields' => $e->getFields(),
                        'code' => $e->getErrorCode()
                    ],
                    'status' => Response::HTTP_BAD_REQUEST
                ];
            } elseif ($e instanceof ModelNotFoundException) {
                $return_object = [
                    'data' => [
                        'status' => Response::HTTP_NOT_FOUND,
                        'message' => trans('messages.custom.' . Response::HTTP_NOT_FOUND),
                        'code' => 105
                    ],
                    'status' => Response::HTTP_NOT_FOUND
                ];
            } elseif ($e instanceof ValidationException) {
                $return_object = [
                    'data' => [
                        'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
                        'message' => $e->validator->errors()->getMessages(),
                        'code' => 106,
                    ],
                    'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
                ];
            } elseif ($e instanceof NotFoundHttpException) {
                $return_object = [
                    'data' => [
                        'status' => Response::HTTP_NOT_FOUND,
                        'message' => trans('messages.custom.' . Response::HTTP_NOT_FOUND),
                        'code' => 107
                    ],
                    'status' => Response::HTTP_NOT_FOUND
                ];
            } elseif ($e instanceof PaymentException) {
                $return_object = [
                    'data' => [
                        'status' => Response::HTTP_NOT_FOUND,
                        'message' => $e->getErrorMessage(),
                        'code' => 108
                    ],
                    'status' => Response::HTTP_NOT_FOUND
                ];
            }


//            if ($return_object['status'] >= 500) {
//                $offline = env('OFFLINE');
//                if ($offline) {
//                    $slack = new Slack();
//                    $slack->sendErrorLog($e, $request, $response);
//                } else {
//                    Log::error($e->getMessage());
//                }
//            }

            return response()
                ->json($return_object['data'], $return_object['status'])
                ->header('Access-Control-Allow-Origin', '*');
        }

        return $response;
    }


}
