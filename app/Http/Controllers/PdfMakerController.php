<?php

namespace App\Http\Controllers;

use App\Http\Services\PdfMakerService;
use Illuminate\Http\Request;
use App\Http\Controllers\RulesTrait;

class PdfMakerController extends ApiController
{
    use RulesTrait;

    public function daftarche(Request $request, $identifier)
    {
        $data = self::checkRules(
            array_merge($request->all(), array('$identifier' => $identifier)),
            __FUNCTION__,
            1000
        );
        $result = PdfMakerService::daftarche($identifier, $data);
        if ($result) return
            $this->respondSuccessCreate($identifier);

    }

    public function gavahi()
    {
        $result = PdfMakerService::gavahi();
        if ($result) return $this->respondSuccessCreate();

    }

}
