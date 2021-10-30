<?php

namespace App\Http\Controllers;

use App\Exceptions\UnauthorizedUserException;
use App\Helpers\OdataQueryParser;
use App\Http\Services\PdfMakerService;
use App\Http\Services\SendSmsService;
use App\Modules\SendSms\SendSmsModules;
use Illuminate\Http\Request;
use App\Http\Controllers\RulesTrait;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class PdfMakerController extends ApiController
{
    use RulesTrait;


    public function getPdf(Request $request, $identifier)
    {
        $user_id = $request->header('x-user-id');
        if (!isset($user_id)) {
            throw new UnauthorizedUserException(trans('messages.custom.unauthorized_user'), 1001);
        }
        $input = $request->all();
        $input['identifier'] = $identifier;

        $data = self::checkRules(
            $input,
            __FUNCTION__,
            1000,
        );
        if (!isset($data['geo'])) {
            $data['geo'] = 0;
        }
        $result = PdfMakerService::getPdf($identifier, $user_id, $data);
//        return view('gavahi_1', $result['gavahi_1']);
        if ($result){
            SendSmsService::sendSms($identifier,$data,$result);
            return $this->respondItemResult($result);
        } else {
            return $this->respondNoFound(trans('messages.custom.404'), 1002);
        }
    }

    public function getAsyncPdf(Request $request, $identifier)
    {
        $user_id = $request->header('x-user-id');

        if (!isset($user_id)) {
            throw new UnauthorizedUserException(trans('messages.custom.unauthorized_user'), 4001);
        }
        if ($identifier != 'notebook' && $identifier != 'gavahi') {
            throw new NotFoundHttpException(trans('messages.custom.error.route_not_found'));
        }
        $input = $request->all();
        $input['identifier'] = $identifier;


        $data = self::checkRules(
            $input,
            __FUNCTION__,
            4000,
        );
        if (!isset($data['geo'])) {
            $data['geo'] = 0;
        }

        $result = PdfMakerService::asyncPdf($identifier, $user_id, $data);
        if ($result)
            return $this->respondItemResult($result);
        else
            return $this->respondNoFound(trans('messages.custom.404'), 1002);

    }

    public function pdfStatus(Request $request, $job_id)
    {
        $user_id = $request->header('x-user-id');

        if (!isset($user_id)) {
            throw new UnauthorizedUserException(trans('messages.custom.unauthorized_user'), 2001);
        }
        $input = $request->all();
        $input['job_id'] = $job_id;
        self::checkRules(
            $input,
            __FUNCTION__,
            2000,
        );
        $status = PdfMakerService::pdfStatus($job_id, $user_id);
        if ($status) {
            return $this->respondItemResult($status);
        } else {
            return $this->respondNoFound(trans('messages.custom.404'), 2002);
        }
    }

    public function pdfLink(Request $request, $job_id)
    {
        $user_id = $request->header('x-user-id');

        if (!isset($user_id)) {
            throw new UnauthorizedUserException(trans('messages.custom.unauthorized_user'), 3001);
        }
        $input = $request->all();
        $input['job_id'] = $job_id;
        self::checkRules(
            $input,
            __FUNCTION__,
            3000,
        );
        $link = PdfMakerService::pdfLink($job_id, $user_id);

        if (!isset($link)) {
            return $this->respondError(trans('messages.custom.notSuccess'), 422, 2003);
        } elseif ($link == 'expired') {
            return $this->respondError(trans('messages.custom.link_expired'), 410, 2004);
        } else {
            return $this->respondItemResult($link);
        }
    }
    public function gavahiPdfWithInfo(Request $request)
    {


        $user_id = $request->header('x-user-id');

        if (!isset($user_id)) {
            throw new UnauthorizedUserException(trans('messages.custom.unauthorized_user'), 5001);
        }
        $input = $request->all();

        $data = self::checkRules(
            $input,
            __FUNCTION__,
            5002,
        );

        if (!isset($data['geo'])) {
            $data['geo'] = 0;
        }

        $result = PdfMakerService::gavahiPdfWithInfo( $user_id, $data);
        if ($result) {
            return $this->respondArrayResult($result);
        }else{
            return $this->respondNoFound(trans('messages.custom.404'), 1002);
        }
    }


}


