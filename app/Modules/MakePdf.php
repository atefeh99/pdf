<?php

namespace App\Modules;

use Mpdf\Mpdf;

class MakePdf
{
    public static function createPdf($html1, $html2, $html3)
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

        $mpdf->WriteHTML($html1);
        $mpdf->AddPage();
        $mpdf->WriteHTML($html2);
        $mpdf->AddPage();
        $mpdf->WriteHTML($html3);
        $mpdf->Output("/home/shiri/home/lumen/interpreter/post.pdf", 'F');
    }

}
