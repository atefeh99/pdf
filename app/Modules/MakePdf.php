<?php

namespace App\Modules;

use Illuminate\Support\Facades\Log;
use Mpdf\Mpdf;
use Mpdf\MpdfException;
use mysql_xdevapi\Exception;

class MakePdf
{
    public static function createPdf($id, $pages, $params, $uuid, $data)
    {
        $arguments = [
//            'mode' => 'utf-8',
//            'defaultPageNumStyle' => 'arabic-indic',
            'orientation' => 'P',
            'margin_left' => '10',
            'margin_right' => '10',
            'margin_top' => '3',
            'margin_bottom' => '0',
            'margin_header' => '0',
            'margin_footer' => '3',

        ];
        if ($id == 'gavahi' || $id == 'gavahi_with_info') {

            $arguments['margin_left'] = '16';
            $arguments['margin_right'] = '16';
            $arguments['margin_top'] = '10';

            if($data['geo'] == "1"){
                $arguments['margin_top'] = '21';
            } else {
                $arguments['margin_top'] = '12';
            }
            $arguments['margin_bottom'] = '40';

            $arguments['default_font_size'] = '10';
        }


        if ($id == 'direct_mail') {
            $arguments ['format'] = [70, 60];
            $arguments ['default_font_size'] = 10;
            $arguments['margin_top'] = '0';

            $arguments['margin_left'] = '4';
            $arguments['margin_right'] = '4';
            $arguments['margin_footer'] = '0';

        }
        $mpdf = new Mpdf($arguments);
        $mpdf->useSubstitutions = false;

        // ini_set("pcre.backtrack_limit", "10000000");
        if ($id == 'direct_mail') {
            $mpdf->showImageErrors = true;
            $mpdf->imageVars['logo'] = file_get_contents(base_path() . '/public/images/mini-logo.png');

        }
        if ($id == 'gavahi') {
            $mpdf->showImageErrors = true;
            $mpdf->imageVars['logo'] = file_get_contents(base_path() . '/public/images/logo.png');
//            $mpdf->imageVars['barcode'] = file_get_contents('images/barcode.png');
            foreach ($params['gavahi_1']['data'] as $key => $value) {
                if (isset($value['image_exists']) && $value['image_exists'] == true) {
                    $mpdf->imageVars[$key] = file_get_contents('images/' . $key . '.png');
                }
            }
//            $mpdf->imageVars['3711655194'] = file_get_contents('images/3711655194.png');
        }
        $time = round(microtime(true) * 1000);
        Log::info("#start creating pdf " . (round(microtime(true) * 1000) - $time) . " milisec long");

        foreach ($pages as $index => $page) {
            try {
                $mpdf->WriteHTML($page);

            } catch (\Mpdf\MpdfException $e) {
                dd($e->getMessage());
//                log::error($e->getMessage());
            }

            if ($index != count($pages) - 1) {
                $mpdf->AddPage();
            }
        }
        if ($id == 'gavahi_with_info') {
            $id = 'gavahi';
        }
        Log::info("#end creating pdf " . (round(microtime(true) * 1000) - $time) . " milisec long");

        $mpdf->Output(base_path() . "/public/files/$id/$uuid.pdf", 'F');
        Log::info("#pdf output finished" . (round(microtime(true) * 1000) - $time) . " milisec long");


//        }
//        elseif ($id == 'notebook') {
//            $mpdf->Output(base_path() . "/public/files/notebook/" . $uuid . ".pdf", 'F');
//        }elseif ($id == 'direct_mail') {
//            $mpdf->Output(base_path() . "/public/files/direct_mail/" . $uuid . ".pdf", 'F');
//
//        }
    }
}
