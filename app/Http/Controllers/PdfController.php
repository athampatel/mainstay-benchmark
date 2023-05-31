<?php

namespace App\Http\Controllers;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\View;

class PdfController extends Controller
{
    public static function generatePdf($data,$name,$header_array,$keys_array,$type = 0,$html_content = '') {
        $tableData = $data;

        $dompdf = new Dompdf();

        $html = '';
        if($type == 0) {
            $html .= '<table style="border-spacing: 0px;">';
            $thead = '<tr style="border:1px solid #e2e2e2">';

            foreach($header_array as $theader){
                $thead .= '<th style="padding:10px 5px;border:1px solid #e2e2e2;word-break:break-all;word-wrap:break-word;text-transform: capitalize;font-size:12px;">'.$theader.'</th>';
            }

            $thead .= '</tr>';
            
            $html .= $thead;

            foreach ($tableData as $row) {
                $html .= '<tr style="border:1px solid #e2e2e2">';
                foreach($keys_array as $key){
                    $html .= '<td style="padding:10px 5px;border:1px solid #e2e2e2;word-break:break-all;text-transform: capitalize;font-size:12px;">' . $row[$key] . '</td>';
                }
                $html .= '</tr>';
            }
            $html .= '</table>';
        } else {
            $html = $html_content;
        }
    
        $html1 = View::make("layouts.pdf")
        ->with("content", $html)
        ->render();

        $dompdf->loadHtml($html1);

        
        $dompdf->set_option('isRemoteEnabled', true);
        
        $dompdf->set_option('isPhpEnabled', true);

        $dompdf->set_option('chroot',public_path());
        
        // $dompdf->setPaper('A4', 'portrait');
        $dompdf->setPaper('A4', 'landscape');

        $dompdf->render();

        $canvas = $dompdf->getCanvas();
        
        $canvas->page_script(function($pageNumber, $pageCount, $canvas, $fontMetrics){
            $pageWidth = $canvas->get_width();
            $pageHeight = $canvas->get_height();
            $image_width = 200;
            $image_height = 35;
            $px = ($pageWidth - $image_width) / 2;
            $py = ($pageHeight - $image_height) / 2;
            $canvas->set_opacity(.175);
            $canvas->image("assets/images/black-logo.png", $px, $py, $image_width, $image_height);
        });

        return $dompdf->stream($name);
    }
}
