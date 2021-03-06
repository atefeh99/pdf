<?php

namespace App\Http\Controllers;

use App\Jobs\SendSmsJob;
use Illuminate\Http\Request;
use App\Modules\otp\UsersModule;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\RulesTrait;
use App\Http\Services\SendSmsService;
use Illuminate\Support\Facades\Queue;
use App\Http\Services\PdfMakerService;
use App\Helpers\Odata\OdataQueryParser;
use App\Exceptions\UnauthorizedUserException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PdfMakerController extends ApiController
{
    use RulesTrait;

    public function getPdf(Request $request, $identifier, $plate_id=null)
    {
        $create_pdf = null;
        $user_id = $request->header('x-user-id');
        if (!isset($user_id)) {
            throw new UnauthorizedUserException(trans('messages.custom.unauthorized_user'), 1001);
        }
        $input = $request->all();
        $input['identifier'] = $identifier;
        
        if($identifier =='postalcodes'){
            $input = array_merge($input,['plate_id' => $plate_id]);
            $create_pdf = !empty($input['create_pdf']) ? $input['create_pdf']: false;
        }
        
        $data = self::checkRules(
            $input,
            __FUNCTION__,
            1000,
        );

        if (!isset($data['geo'])) {
            $data['geo'] = 0;
        }
        $result = PdfMakerService::getPdf($identifier, $user_id, $data, $create_pdf);
        
        if ($result) {
            if ($identifier == 'gavahi') {
                $data['tracking_code'] = $data['tracking_code'] ? $data['tracking_code'] : 23;
                $mobile = UsersModule::getMobile($user_id);
                if(!empty($mobile)){
                    Queue::push(new SendSmsJob($identifier, $data, $result['link'], $user_id),null,$identifier.'_sms');
                }else{
                    Log::info('sms not sent : mobile is empty');
                }
            } elseif(!$create_pdf){
                return $result[0];
            }
            return $this->respondMyItemResult($result);
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

        if ($identifier != 'notebook' && $identifier != 'gavahi' && $identifier != 'direct_mail') {
            throw new NotFoundHttpException(trans('messages.custom.error.route_not_found'));
        }
        $input = $request->all();
        $input['identifier'] = $identifier;

        $data = self::checkRules(
            $input,
            __FUNCTION__,
            4000,
        );
        if(array_key_exists('tour_id',$data) && !env('GASHT_NOTEBOOK_ENABLE')){
            throw new NotFoundHttpException();
        }
        if (str_contains($identifier, 'gavahi') && (!isset($data['geo']))) {
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

        if ($link == 'failed') {
            Log::info('failed in c');
            return $this->respondError(trans('messages.custom.failed'), 424, 2008);
        } elseif ($link == 'pending') {
            Log::info('pending in c');
            return $this->respondError(trans('messages.custom.pending'), 422, 2009);
        } elseif ($link == 'expired') {
            Log::info('expired in c');
            return $this->respondError(trans('messages.custom.link_expired'), 410, 2010);
        } else {
            Log::info('bingo');
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

        $result = PdfMakerService::gavahiPdfWithInfo($user_id, $data);
        if ($result) {
            return $this->respondArrayResult($result);
        } else {
            return $this->respondNoFound(trans('messages.custom.404'), 1002);
        }
    }

    public function getItem(Request $request)
    {
        $user_id = $request->header('x-user-id');

        if ($user_id == null || $user_id == '') {
            throw new UnauthorizedUserException(trans('messages.custom.unauthorized_user'), 3006);
        }

        $odata = OdataQueryParser::parse($request->fullUrl());
        if (OdataQueryParser::isFailed()) {
            return $this->respondInvalidParams(
                1001,
                new MessageBag(OdataQueryParser::getErrors()),
                trans('messages.custom.400')
            );
        }

        $data = PdfMakerService::getItem($odata);
        if ($data['link'] == 'expired') {
            return $this->respondError(trans('messages.custom.error.link_expired'), '410', '10000');
        }
        return $this->respondItemResult($data);
    }
}


