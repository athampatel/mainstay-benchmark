<?php

namespace App\Http\Controllers;
use Dompdf\Dompdf;

class PdfController extends Controller
{
    public static function generatePdf($data,$name) {

        // $tableData = User::all()->toArray();
        $tableData = $data;
        $array_keys = [];
        if(!empty($tableData)) {
            $array_keys = array_keys($tableData[0]);
        }

        $dompdf = new Dompdf();

        $html = '<table border="2">';

        $thead = '<tr>';
        foreach($array_keys as $array_key){
            $thead .= '<th>'.$array_key.'</th>';
        }
        $thead .= '</tr>';
        $html .= $thead;
        foreach ($tableData as $row) {
            $html .= '<tr>';
            foreach ($row as $cell) {
                $html .= '<td>' . $cell . '</td>';
            }
            $html .= '</tr>';
        }
        $html .= '</table>';

        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'portrait');

        $dompdf->render();

        // return $dompdf->stream('table.pdf');
        return $dompdf->stream($name);
    }
}
