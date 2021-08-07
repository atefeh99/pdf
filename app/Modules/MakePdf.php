<?php

namespace App\Modules;

use Mpdf\Mpdf;
use mysql_xdevapi\Exception;

class MakePdf
{
    public static function createPdf($id, $pages, $params, $uuid)
    {

        $mpdf = new Mpdf([
//            'mode' => 'utf-8',
            'orientation' => 'P',
            'margin_left' => '10',
            'margin_right' => '5',
            'margin_top' => '3',
            'margin_bottom' => '0',
            'margin_header' => '0',
            'margin_footer' => '3'
        ]);
        if ($id == 'gavahi') {
            $mpdf->showImageErrors = true;
            $mpdf->imageVars['logo'] = file_get_contents('images/logo.png');
            $mpdf->imageVars['barcode'] = file_get_contents('images/barcode.png');
            foreach ($params['data'] as $value){
                if($value['image_exists']){
                    $mpdf->imageVars[$value['postalcode']] = file_get_contents('images/'.$value['postalcode'].'.png');

                }
            }
//            $mpdf->imageVars['3711655194'] = file_get_contents('images/3711655194.png');
        }


        foreach ($pages as $index => $page) {
            try {

                $mpdf->WriteHTML($page);
            }catch(\Mpdf\MpdfException $e){
                log::error($e->getMessage());
            }

            if ($index != count($pages)-1 ) {
                $mpdf->AddPage();
            }
        }

        $mpdf->Output(base_path() . "/public/" . $uuid . ".pdf", 'F');
    }
}
