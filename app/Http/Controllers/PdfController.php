<?php

namespace App\Http\Controllers;
use Dompdf\Dompdf;

class PdfController extends Controller
{
    public static function generatePdf($data,$name) {
        $tableData = $data;
        $array_keys = [];
        if(!empty($tableData)) {
            $array_keys = array_keys($tableData[0]);
        }

        $dompdf = new Dompdf();
        $path = url('/assets/images/logo.svg');
        // dd($path);
        // $path = 'http://localhost:8081/assets/images/logo.svg';
        $html = '';

        // $html .='<body style="background-color:#424448";color:#fff;>';
        // $logo = '<div style="display:flex;justify-content:center;align-items:center;margin-top:10px;margin-bottom:10px;"><img src="'.$path.'"></div>';

        // $html .= $logo;
        
        $html .= '<table>';

        $thead = '<tr>';
        foreach($array_keys as $array_key){
            $thead .= '<th style="max-width:85px;word-break:break-all;word-wrap:break-word;padding:4px;">'.$array_key.'</th>';
        }
        $thead .= '</tr>';
        $html .= $thead;
        foreach ($tableData as $row) {
            $html .= '<tr>';
            foreach ($row as $cell) {
                $html .= '<td style="max-width:85px;word-break:break-all;word-wrap:break-word;padding:4px;">' . $cell . '</td>';
            }
            $html .= '</tr>';
        }
        $html .= '</table>';
        
        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'portrait');

        $dompdf->render();

        return $dompdf->stream($name);
    }
}
