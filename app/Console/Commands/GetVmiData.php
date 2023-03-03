<?php

namespace App\Console\Commands;

use App\Models\UserDetails;
use Illuminate\Console\Command;
use App\Helpers\SDEApi;
use App\Models\ApiData;
use App\Models\ApiType;
use Carbon\Carbon;

class GetVmiData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:vmi-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Vmi data';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $customers = UserDetails::all();
        $sdeApi = new SDEApi();
        foreach($customers as $customer){
            if($customer){
                $data = array(            
                    "filter" => [
                        [
                            "column"=> "companyCode",
                            "type"=> "equals",
                            "value"=> $customer->vmi_companycode,
                            "operator"=> "and"
                        ],
                    ],
                );
                $response = $sdeApi->Request('post','Products',$data);
                $response = $response['Products'];
            }
            // $response = $response['salesorders'];
            // if(!empty($response)){
            //     foreach ($response as $key => $res) {
            //         $total_amount = 0;
            //         $total_quantity = 0;
            //         foreach($res['details'] as $detail){
            //             $total_amount = $total_amount + ($detail['quantityordered'] * $detail['unitprice']);
            //             $total_quantity = $total_quantity + $detail['quantityordered'];
            //         }
            //         $response[$key]['total_amount']= $total_amount;
            //         $response[$key]['total_quantity']= $total_quantity;
            //     }
            // }
            // $type = ApiType::where('name','salesorders')->first();
            // $api_data = ApiData::where('customer_no',$customer_no)->where('type',$type->id)->first();
            // if($api_data){
            //     $api_data->data = json_encode($response);
            //     $api_data->updated_at = Carbon::now()->format('Y-m-d h:i:s');
            //     $api_data->save();
            // } else {
            //     $api_data = ApiData::create([
            //         'customer_no' => $customer_no,
            //         'type' => $type->id,
            //         'data' => json_encode($response)
            //     ]);
            // }
        }
        echo '---------------------------- Completed ----------------------------';

    }
}
