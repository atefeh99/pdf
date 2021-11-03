<?php

namespace App\Modules;

use Illuminate\Support\Facades\Log;
use Mpdf\Mpdf;
use Mpdf\MpdfException;
use mysql_xdevapi\Exception;

class MakePdf
{
    public static function createPdf($id, $pages, $params, $uuid)
    {
        $arguments = [
//            'mode' => 'utf-8',
//            'defaultPageNumStyle' => 'arabic-indic',
            'orientation' => 'P',
            'margin_left' => '10',
            'margin_right' => '5',
            'margin_top' => '3',
            'margin_bottom' => '0',
            'margin_header' => '0',
            'margin_footer' => '3',

        ];

        $mpdf = new Mpdf($arguments);
        $mpdf->useSubstitutions = false;

        // ini_set("pcre.backtrack_limit", "10000000");

        if ($id == 'gavahi') {
            $mpdf->showImageErrors = true;
            $mpdf->imageVars['logo'] = file_get_contents(base_path() . '/public/images/logo.png');
//            $mpdf->imageVars['barcode'] = file_get_contents('images/barcode.png');
            foreach ($params['gavahi_1']['data'] as $value) {
                if (isset($value['image_exists']) && $value['image_exists'] == true) {
                    $mpdf->imageVars[$value['postalcode']] = file_get_contents('images/' . $value['postalcode'] . '.png');

                }
            }
//            $mpdf->imageVars['3711655194'] = file_get_contents('images/3711655194.png');
        }


        foreach ($pages as $index => $page) {
            try {

                // $chunks = explode("class=table6 tab",$page);
                // dd($chunks[1]);
                // foreach($chunks as $chunk){
                $mpdf->WriteHTML($page);


                // }

            } catch (\Mpdf\MpdfException $e) {
                log::error($e->getMessage());
//                dd($e->getMessage());
            }

            if ($index != count($pages) - 1) {
                $mpdf->AddPage();
            }
        }
        if ($id == 'gavahi' || $id == 'gavahi_with_info') {
            $mpdf->Output(base_path() . "/public/files/gavahi/" . $uuid . ".pdf", 'F');

        } elseif ($id == 'notebook') {
            $mpdf->Output(base_path() . "/public/files/notebook/" . $uuid . ".pdf", 'F');
        }
    }
}
