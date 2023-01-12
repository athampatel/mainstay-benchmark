<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\SDEApi;

class SDEDataController extends Controller
{

    public function __construct(SDEApi $SDEApi)
    {
        $this->SDEApi = $SDEApi;
    }

    public function getCustomerSalesHistory(){

        $data = array(            
            "filter" => [
                [
                    "column" => "ARDivisionNo",
                    "type" => "equals",
                    "value" => "00",
                    "operator" => "and"
                ],
                [
                    "column" =>  "CustomerNo",
                    "type" =>  "equals",
                    "value" =>  "GEMWI00",
                    "operator" =>  "and"
                ],
                [
                    "column" =>  "FiscalYear",
                    "type" =>  "equals",
                    "value" =>  "2021",
                    "operator" =>  "and"
                ]
            ]
        );

        $response   = $this->SDEApi->Request('post','CustomerSalesHistory',$data);
        echo \json_encode($response);
    }


    public function getCustomerItemHistory(){
        $data = array(            
            "filter" => [
                [
                    "column"=> "ARDivisionNo",
                    "type"=> "equals",
                    "value"=> "00",
                    "operator"=> "and"
                ],
                [
                    "column"=> "CustomerNo",
                    "type"=> "equals",
                    "value"=> "GEMWI00",
                    "operator"=> "and"
                ],
                [
                    "column"=> "FiscalCalYear",
                    "type"=> "equals",
                    "value"=> "2021",
                    "operator"=> "and"
                ]
            ]
        );

        $response   = $this->SDEApi->Request('post','CustomerItemHistory',$data);
        echo \json_encode($response);
    }


    public function gteInvoiceHistoryHeader(){
        $data = array(            
            "filter" => [
                [
                    "column" => "ARDivisionNo",
                    "type" => "equals",
                    "value" => "00",
                    "operator" => "and"
                ],
                [
                    "column" => "CustomerNo",
                    "type" => "equals",
                    "value" => "GEMWI00",
                    "operator" => "and"
                ],
            ]
        );

        $response   = $this->SDEApi->Request('post','InvoiceHistoryHeader',$data);
        echo \json_encode($response);
    }
}
