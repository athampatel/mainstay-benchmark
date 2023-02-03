<?php

namespace App\Http\Controllers;

use App\Models\UserDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\SDEApi;
use App\Models\User;
use Carbon\Carbon;

class MenuController extends Controller
{

    public function __construct(SDEApi $SDEApi)
    {
        $this->SDEApi = $SDEApi;
    }

    public function NavMenu($current = ''){

       $menus = array('dashboard'           =>         array(  'name' => 'products & inventory', 
                                                                'icon_name' => file_get_contents(public_path('/assets/images/svg/products_gray.svg')), 
                                                                'active' => 0,  
                                                                'link'=> '/dashboard'),
                        'invoice'           =>          array(  'name' => 'invoiced orders', 
                                                                'icon_name' => file_get_contents(public_path('/assets/images/svg/invoice_order_gray.svg')),
                                                                'active' => 0,
                                                                'link'=> '/invoice'),
                        'open-orders'       =>          array(  'name' => 'open orders', 
                                                                'icon_name' => file_get_contents(public_path('/assets/images/svg/open_orders_gray.svg')),
                                                                'active' => 0,
                                                                'link'=> '/open-orders'),
                        // 'change-order'      =>          array(  'name' => 'change order', 
                        //                                         'icon_name' => file_get_contents(public_path('/assets/images/svg/change_order_gray.svg')),
                        //                                         'active' => 0,
                        //                                         'link'=> 'change-order'),
                        'vmi-user'          =>          array(  'name' => 'vmi user', 
                                                                'icon_name' => file_get_contents(public_path('/assets/images/svg/vmi_user_gray.svg')),
                                                                'active' => 0,
                                                                'link'=> '/vmi-user'),
                        'analysis'          =>          array(  'name' => 'analysis', 
                                                                'icon_name' => file_get_contents(public_path('/assets/images/svg/analysis_menu_gray.svg')),
                                                                'active' => 0,
                                                                'link'=> '/analysis'),
                        'account-settings'  =>          array(  'name' => 'account settings', 
                                                                'icon_name' => file_get_contents(public_path('/assets/images/svg/account_settings_gray.svg')),
                                                                'active' => 0,
                                                                'link'=> '/account-settings'),
                        'help'              =>          array(  'name' => 'help', 
                                                                'icon_name' => file_get_contents(public_path('/assets/images/svg/help_menu_gray.svg')),
                                                                'active' => 0,
                                                                'link'=> '/help'),
                        'logout'             =>          array( 'name' => 'logout', 
                                                                'icon_name' => file_get_contents(public_path('/assets/images/svg/logout_menu_gray.svg')),
                                                                'active' => 0,
                                                                'link'=> '/logout')
        );

        if(isset($menus[$current])){
            $menus[$current]['active'] = 1;
        }else{
            $menus['dashboard']['active'] = 1;
        }
     //   dd($menus);
        return $menus;

    }

    public function dashboard(){
        $data['title']  = '';
        $data['current_menu']   = 'dashboard';
        $data['menus']          = $this->NavMenu('dashboard');
        return view('pages.dashboard',$data); 
    }
    public function invoicePage(){
        $data['title']  = '';
        $data['current_menu']   = 'invoice';
        $data['menus']          = $this->NavMenu('invoice');
        return view('pages.invoice',$data);  
    }
    
    public function openOrdersPage(){
        $final_data['title']  = '';
        $final_data['current_menu']   = 'open-orders';
        $final_data['menus']          = $this->NavMenu('open-orders');
        // return view('pages.open-orders',$final_data);
        // data getting work start
        $user_id = Auth::user()->id;
        $user_details = UserDetails::where('user_id',$user_id)->first();
        if($user_details){
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
                ],
                "offset" => 1,
                "limit" => 5,
            );

            $response   = $this->SDEApi->Request('post','SalesOrders',$data);
            foreach($response['salesorders'] as $key => $order){
                $total_amount = 0;
                $total_quantity = 0;
                foreach($order['details'] as $detail){
                    $total_quantity += $detail['quantityordered'];
                    $total_amount += $detail['quantityordered'] * $detail['unitprice'];
                }
                // $data1 = array(            
                //     "filter" => [
                //             [
                //                 "column"=> "salesorderno",
                //                 "type"=> "equals",
                //                 "value"=> $order['salesorderno'],
                //                 "operator"=> "and"
                //             ],
                //         ]
                // );
                // $response1   = $this->SDEApi->Request('post','SalesOrderHistoryHeader',$data1);
                // if(!empty($response1['salesorderhistoryheader'])){
                //     $response['salesorders'][$key]['location'] =$response1['salesorderhistoryheader'][0]['shiptocity'];
                // }

                $response['salesorders'][$key]['total_amount'] = $total_amount; 
                $response['salesorders'][$key]['total_quantity'] = $total_quantity; 
                $response['salesorders'][$key]['format_date'] = Carbon::createFromFormat('Y-m-d', $order['orderdate'])->format('M d,Y'); 
            }
            $final_data['orders'] = $response['salesorders'];
        }  
        // data getting work end 
        // dd($final_data);
        return view('pages.open-orders',$final_data);
    }
    
    public function changeOrderPage($orderid){
        if(Auth::user()->is_vmi == 1){
            $final_data['title']  = '';
            $final_data['current_menu']   = 'change-order';
            $final_data['menus']          = $this->NavMenu('change-order');
            // get order details work start
            // $order_no = $request->order_no;
            // $item_code = $request->item_code;
            $data = array(            
                "filter" => [
                    [
                        "column" =>  "SalesOrderNo",
                        "type" =>  "equals",
                        "value" => $orderid,
                        "operator" =>  "and"
                    ],
                ],
            );
            $sales_order_history_header = $this->SDEApi->Request('post','SalesOrderHistoryHeader',$data);
            if(empty($sales_order_history_header['salesorderhistoryheader'])){
                    $response = ['success' => false, 'data' => [],'error' => ['No records found']];
                    echo json_encode($response);
                    die();
            }
            $sales_order_header = $sales_order_history_header['salesorderhistoryheader'][0];

            $filter = [
                [
                    "column" =>  "SalesOrderNo",
                    "type" =>  "equals",
                    "value" => $orderid,
                    "operator" =>  "and"
                ],
            ];

            // if($item_code != ""){
            //     $new_filter = [ 
            //         "column" =>  "ItemCode",
            //         "type" =>  "equals",
            //         "value" => $item_code,
            //         "operator" =>  "and"
            //     ];
            //     array_push($filter,$new_filter);
            // }

            $data1 = array(            
                "filter" => $filter
            );
            $sales_order_history_detail = $this->SDEApi->Request('post','SalesOrderHistoryDetail',$data1);
            $sales_order_detail = $sales_order_history_detail['salesorderhistorydetail'];
            foreach ($sales_order_detail as $key => $sales_order) {
                $data2 = array(            
                    "filter" => [
                        [
                            "column" =>  "ItemCode",
                            "type" =>  "equals",
                            "value" => $sales_order['itemcode'],
                            "operator" =>  "and"
                        ],
                    ],
                );
                $product_detail = $this->SDEApi->Request('post','Products',$data2);
                $sales_order_detail[$key]['product_details'] = $product_detail['products'];
            }
            $sales_order_header['sales_order_history_detail'] = $sales_order_detail;
            $user = User::find(Auth::user()->id);
            $final_data['order_detail'] = $sales_order_header;
            $final_data['user'] = $user;
            $final_data['user_detail'] = UserDetails::where('user_id',$user->id)->first();
            // dd($final_data);
            return view('pages.change-order',$final_data);
        } else {
            return redirect()->route('customer.dashboard');
        }
    }
    
    public function vmiUserPage(){
        $data['title']  = '';
        $data['current_menu']   = 'vmi-user';
        $data['menus']          = $this->NavMenu('vmi-user');
        return view('pages.vmi-user',$data);
    }
    
    public function analysisPage(){
        $data['title']  = '';
        $data['current_menu']   = 'analysis';
        $data['menus']          = $this->NavMenu('analysis');
        return view('pages.analysis',$data);
    }
    
    public function accountSettingsPage(){
        $data['title']  = '';
        $data['current_menu']   = 'account-settings';
        $data['menus']          = $this->NavMenu('account-settings');
        $user_id = Auth::user()->id;
        $data['user_detail'] = UserDetails::where('user_id',$user_id)->first();
        return view('pages.account-settings',$data);
    }
    public function helpPage(){
        $data['title']  = '';
        $data['current_menu']  = 'help';
        $data['menus']         = $this->NavMenu('help');
        return view('pages.help',$data);
    }
}
