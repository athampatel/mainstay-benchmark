<?php

namespace App\Console\Commands;

use App\Helpers\SDEApi;
use App\Models\ApiData;
use App\Models\ApiType;
use App\Models\UserDetails;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Http\Request;

class GetOpenOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:open-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get open orders data';

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
        $customer_numbers = UserDetails::all()->pluck('customerno');
        $sdeApi = new SDEApi();
        foreach($customer_numbers as $customer_no){
            $data = array(            
                "filter" => [
                    [
                        "column"=> "CustomerNo",
                        "type"=> "equals",
                        "value"=> $customer_no,
                        "operator"=> "and"
                    ],
                ],
            );
            $response = $sdeApi->Request('post','SalesOrders',$data);
            $type = ApiType::where('name','salesorders')->first();
            $api_data = ApiData::where('customer_no',$customer_no)->where('type',$type->id)->first();
            if($api_data){
                $api_data->data = json_encode($response);
                $api_data->updated_at = Carbon::now()->format('Y-m-d h:i:s');
                $api_data->save();
            } else {
                $api_data = ApiData::create([
                    'customer_no' => $customer_no,
                    'type' => $type->id,
                    'data' => json_encode($response)
                ]);
            }
        }
        echo '---------------------------- Completed ----------------------------';
    }
}
