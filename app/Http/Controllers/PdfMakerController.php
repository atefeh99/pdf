<?php

namespace App\Http\Controllers;

use App\Helpers\OdataQueryParser;
use App\Http\Services\PdfMakerService;
use Armancodes\DownloadLink\Models\DownloadLink;
use BaconQrCode\Encoder\QrCode;
use Illuminate\Http\Request;
use App\Http\Controllers\RulesTrait;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use function App\Helpers\asset;
use function App\Helpers\public_path;
use Ramsey\Uuid\Uuid;


class PdfMakerController extends ApiController
{
    use RulesTrait;


    public function getPdf(Request $request, $identifier)
    {
        $data = self::checkRules(
            $request->all(),
            __FUNCTION__,
            $identifier,
            1000
        );
        if(!isset($data['geo'])){
            $data['geo'] = 0;
        }
        $user_id = $request->header('x-user-id');
        $uuid = Uuid::uuid4();
//        $link = URL::asset(env('API_PREFIX') . '/' . $uuid . '.pdf');
        $link = env('API_PREFIX') . '/' . $uuid . '.pdf';

        $result = PdfMakerService::getPdf($identifier, $link, $uuid, $user_id, $data);
//        dd($result);
//        return view('gavahi_1', $result['gavahi_1']);
        if ($result)
            return $this->respondItemResult($link);
        else
            return $this->respondNoFound(trans('messages.custom.404'), 1000);

    }

}


