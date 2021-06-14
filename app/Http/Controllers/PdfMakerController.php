<?php

namespace App\Http\Controllers;

use App\Http\Services\PdfMakerService;
use Illuminate\Http\Request;

class PdfMakerController extends ApiController
{
    public function getPdf(Request $request, $tour_no)
    {
        $result = PdfMakerService::getPdf($tour_no);
//        if ($result) {
//            return view('first',$result['first']);
//        }
        if ($result) return $this->respondSuccessCreate($tour_no);

    }

}
