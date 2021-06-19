<?php

namespace App\Modules;

use Mpdf\Mpdf;

class MakePdf
{
    public static function createPdf($id, $pages)
    {

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'orientation' => 'P',
            'margin_left' => '10',
            'margin_right' => '5',
            'margin_top' => '3',
            'margin_bottom' => '0',
            'margin_header' => '0',
            'margin_footer' => '3'
        ]);
        $mpdf->SetFont('mitra');

        foreach ($pages as $index => $page) {
            $mpdf->WriteHTML($page);
            if ($index != count($pages) - 1) {
                $mpdf->AddPage();
            }
        }
        $mpdf->Output(base_path() . "/public/" . $id . ".pdf", 'F');
    }

}
