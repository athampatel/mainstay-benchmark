<?php

namespace App\Http\Controllers;

use App\Models\UserDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\SDEApi;
use App\Models\Post;
use App\Models\SalesPersons;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;

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

    public function dashboard(Request $request){
        $data['title']  = '';
        $data['current_menu']   = 'dashboard';
        $data['menus']          = $this->NavMenu('dashboard');
        $customer_no    = $request->session()->get('customer_no');
        $customers    = $request->session()->get('customers');
        // dd($customers);
        $data['region_manager'] =  SalesPersons::select('sales_persons.*')->leftjoin('user_sales_persons','sales_persons.id','=','user_sales_persons.sales_person_id')
                                                ->leftjoin('user_details','user_sales_persons.user_details_id','=','user_details.id')->where('user_details.customerno',$customer_no)->first();
        return view('Pages.dashboard',$data); 
    }

    public function switchAccount($customer_no = '',request $request){
        $customer   = $request->session()->get('customers');
        $current    = $request->session()->get('customer_no');
        if($customer_no != $current){
            foreach($customer as $_customer){
                if($_customer['customerno'] == $customer_no)
                    $request->session()->put('customer_no',$customer_no);
            }
        }
        return redirect()->route('customer.dashboard');
    }
    public function invoicePage(){
        $data['title']  = '';
        $data['current_menu']   = 'invoice';
        $data['menus']          = $this->NavMenu('invoice');
        return view('Pages.invoice',$data);  
    }
    
    public function openOrdersPage(){
        $final_data['title']  = '';
        $final_data['current_menu']   = 'open-orders';
        $final_data['menus']          = $this->NavMenu('open-orders');
        // return view('pages.open-orders',$final_data);
        // $posts = Post::paginate(10);
        // $final_data['pagination'] = $posts->toArray();
        return view('Pages.open-orders',$final_data);
        // data getting work start
        // $user_id = Auth::user()->id;
        // $user_details = UserDetails::where('user_id',$user_id)->first();
        // if($user_details){
        //     $data = array(            
        //         "filter" => [
        //             [
        //                 "column"=> "ARDivisionNo",
        //                 "type"=> "equals",
        //                 "value"=> "00",
        //                 "operator"=> "and"
        //             ],
        //             [
        //                 "column"=> "CustomerNo",
        //                 "type"=> "equals",
        //                 "value"=> "GEMWI00",
        //                 "operator"=> "and"
        //             ],
        //         ],
        //         "offset" => 1,
        //         "limit" => 10,
        //     );

            
        //     $response   = $this->SDEApi->Request('post','SalesOrders',$data);
        //     foreach($response['salesorders'] as $key => $order){
        //         $total_amount = 0;
        //         $total_quantity = 0;
        //         foreach($order['details'] as $detail){
        //             if($detail['quantityordered'] != 0){
        //                 $total_quantity += $detail['quantityordered'];
        //                 $total_amount += $detail['quantityordered'] * $detail['unitprice'];
        //             }
        //         }
        //         $response['salesorders'][$key]['total_amount'] = $total_amount; 
        //         $response['salesorders'][$key]['total_quantity'] = $total_quantity; 
        //         $response['salesorders'][$key]['format_date'] = Carbon::createFromFormat('Y-m-d', $order['orderdate'])->format('M d,Y'); 
        //     }
        //     $final_data['orders'] = $response['salesorders'];
        // }  
        return view('Pages.open-orders',$final_data);
    }
    
    public function changeOrderPage($orderid){           
        if(Auth::user()->is_vmi == 1){
            $final_data['title']  = '';
            $final_data['current_menu']   = 'change-order';
            $final_data['menus']          = $this->NavMenu('change-order');
            // get order details work start
            // $order_no = $request->order_no;
            // $item_code = $request->item_code;
           /* $data = array(            
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
            $sales_order_header['sales_order_history_detail'] = $sales_order_detail; */
            $user = User::find(Auth::user()->id);

            $final_data['order_id'] = $orderid;
            $final_data['user'] = $user;
            $customer_no   = $request->session()->get('customers');
            $final_data['user_detail'] = UserDetails::where('user_id',$user->id)->where('customerno',$customer_no)->first();
            // dd($final_data);
            return view('Pages.change-order',$final_data);
        } else {
            return redirect()->route('customer.dashboard');
        }
    }
    
    public function vmiUserPage(){
        $data['title']  = '';
        $data['current_menu']   = 'vmi-user';
        $data['menus']          = $this->NavMenu('vmi-user');
        return view('Pages.vmi-user',$data);
    }

    // analysis page
    public function analysisPage(){
        $data['title']  = '';
        $data['current_menu']   = 'analysis';
        $data['menus']          = $this->NavMenu('analysis');
        return view('Pages.analysis',$data);
        // $arr = [
        //     [
        //         'no' => '87145254',
        //         'date' => '2022-04-08',
        //         'custpono' => '1234',
        //         'city' => 'city',
        //         'total_items' => 10,
        //         'total_amount' => 145,
        //     ],
        //     [
        //         'no' => '87145254',
        //         'date' => '2022-04-15',
        //         'custpono' => '1234',
        //         'city' => 'city',
        //         'total_items' => 10,
        //         'total_amount' => 100,
        //     ],
        //     [
        //         'no' => '87145254',
        //         'date' => '2022-03-08',
        //         'custpono' => '1234',
        //         'city' => 'city',
        //         'total_items' => 10,
        //         'total_amount' => 153,
        //     ],
        //     [
        //         'no' => '87145254',
        //         'date' => '2022-02-08',
        //         'custpono' => '1234',
        //         'city' => 'city',
        //         'total_items' => 10,
        //         'total_amount' => 165,
        //     ],
        //     [
        //         'no' => '87145254',
        //         'date' => '2022-01-08',
        //         'custpono' => '1234',
        //         'city' => 'city',
        //         'total_items' => 10,
        //         'total_amount' => 98,
        //     ],
        //     [
        //         'no' => '87145254',
        //         'date' => '2022-08-08',
        //         'custpono' => '1234',
        //         'city' => 'city',
        //         'total_items' => 10,
        //         'total_amount' => 200,
        //     ],
        //     [
        //         'no' => '87145254',
        //         'date' => '2022-10-08',
        //         'custpono' => '1234',
        //         'city' => 'city',
        //         'total_items' => 10,
        //         'total_amount' => 198,
        //     ],
        // ];
        // $data['response'] = $arr;
        // return view('pages.analysis',$data);
    }
    
    public function accountSettingsPage(request $request){
        $data['title']  = '';
        $data['current_menu']   = 'account-settings';
        $data['menus']          = $this->NavMenu('account-settings');
        $user_id = Auth::user()->id;
        $customer_no   = $request->session()->get('customer_no');
        $data['user_detail'] = UserDetails::where('user_id',$user_id)->where('customerno',$customer_no)->first();
        return view('Pages.account-settings',$data);
    }
    public function helpPage(){
        $data['title']  = '';
        $data['current_menu']  = 'help';
        $data['menus']         = $this->NavMenu('help');
        $data['posts'] = Post::paginate(10);
        $posts = Post::paginate(10);
        $data['pagination'] = $posts->toArray();
        // $data['posts'] = Post::paginate(10)->withQueryString();
        // $data['posts'] = Post::cursorPaginate(10);
        // $data['posts'] = Post::cursorPaginate(10);
        return view('Pages.help',$data);
    }


    // get open orders
    public function getOpenOrders(Request $request){
        $data = $request->all();
        $page = $data['page'];
        $limit = $data['count'];
        if($page == 0){
            $offset = 1;
        } else {
            $offset = $page * $limit + 1;
        }
        $user_id = Auth::user()->id;
        $customer_no   = $request->session()->get('customer_no');
        $user_details = UserDetails::where('user_id',$user_id)->where('customerno',$customer_no)->first();
        if($user_details){
            $data = array(            
                "filter" => [
                    [
                        "column"=> "ARDivisionNo",
                        "type"=> "equals",
                        "value"=> $user_details->ardivisionno,
                        "operator"=> "and"
                    ],
                    [
                        "column"=> "CustomerNo",
                        "type"=> "equals",
                        "value"=> $user_details->customerno,
                        "operator"=> "and"
                    ],
                ],
                "offset" => $offset,
                "limit" => $limit,
            );
            
            $response   = $this->SDEApi->Request('post','SalesOrders',$data);

            $custom_pagination = self::AjaxgetPagination($offset,$limit,$response['meta']['records'],$page,'/getOpenOrders');
            
            $pagination_code = View::make("components.ajax-pagination-component")
            ->with("pagination", $custom_pagination)
            ->render();
    
            $table_code = View::make("components.datatabels.open-orders-page-component")
            ->with("saleorders", $response['salesorders'])
            ->render();
            
            $response['pagination_code'] = $pagination_code;
            $response['table_code'] = $table_code;

            echo json_encode($response);
            die();
        }  
    }

    // custom ajax pagination
    public static function AjaxgetPagination($offset,$limit,$total,$page,$path){
        $custom_pagination['from'] = $offset;
        $custom_pagination['to'] = intval($offset + $limit - 1) > intval($total) ? intval($total) :intval($offset + $limit - 1);
        $custom_pagination['total'] = $total;
        $custom_pagination['first_page'] = 1;
        $custom_pagination['last_page'] = ceil($total / $limit);
        $custom_pagination['prev_page'] = $page - 1 == -1 ? 0 : $page - 1;
        $custom_pagination['curr_page'] = intval($page);
        $custom_pagination['next_page'] = $page + 1;
        $custom_pagination['path'] = $path;
        $last = ceil($total / $limit); 
        if(intval($total) > $limit ){
            if($page !=0 && ($page + 1) != $last){
                $active_number[] = $page;
                $active_number[] = $page + 1;
                $active_number[] = $page + 2;
            } else {
                if($page == 0 || $page == 1){
                    $active_number = [1,2,3];
                }
                if(($page + 1) == $last){
                    for($i = 2;$i >= 0;$i--){
                        $active_number []= $last - $i;
                    }       
                }
            }
        } else {
            $active_number[] = 1;
        }
        foreach($active_number as $number){               
            $custom_link = [
                'label' => $number,
                'active' => $page + 1 == $number,
            ];
            $custom_pagination['links'][] = $custom_link;
        }
        return $custom_pagination;
    }

    public function getInvoiceOrders(Request $request){
        $data = $request->all();
        $page = $data['page'];
        $limit = $data['count'];
        if($page == 0){
            $offset = 1;
        } else {
            $offset = $page * $limit + 1;
        }
        $user_id        = Auth::user()->id;
        $customer_no    = $request->session()->get('customer_no');
        $user_details   = UserDetails::where('user_id',$user_id)->where('customerno',$customer_no)->first();
        if($user_details){
            // $data = array(            
            //     "filter" => [
            //         [
            //             "column" =>  "CustomerNo",
            //             "type" =>  "equals",
            //             "value" =>  $user_details->customerno,
            //             "operator" =>  "and"
            //         ],
            //         [
            //             "column" => "ARDivisionNo",
            //             "type" => "equals",
            //             "value" => $user_details->ardivisionno,
            //             "operator" => "and"
            //         ],
            //     ],
            //     "offset" => 1,
            //     "limit" => 2,
            // );
            // $response_data   = $this->SDEApi->Request('post','SalesOrderHistoryHeader',$data);
            // foreach($response_data['salesorderhistoryheader'] as $key => $res){
            //     $data1 = array(            
            //         "filter" => [
            //             [
            //                 "column" => "SalesOrderNo",
            //                 "type" => "equals",
            //                 "value" => $res['salesorderno'],
            //                 "operator" => "and"
            //             ],
            //         ]
            //     );  
            //     $response_data1   = $this->SDEApi->Request('post','SalesOrderHistoryDetail',$data1);
            //     $response_data['salesorderhistoryheader'][$key]['salesorderhistorydetail'] = $response_data1['salesorderhistorydetail'];
            // };
            $data = array(            
                "filter" => [
                    [
                        "column"=> "ARDivisionNo",
                        "type"=> "equals",
                        "value"=> $user_details->ardivisionno,
                        "operator"=> "and"
                    ],
                    [
                        "column"=> "CustomerNo",
                        "type"=> "equals",
                        "value"=> $user_details->customerno,
                        "operator"=> "and"
                    ],
                ],
                "offset" => $offset,
                "limit" => $limit,
            );
            
            $response   = $this->SDEApi->Request('post','SalesOrders',$data);
            // dd($response);
            $custom_pagination = self::AjaxgetPagination($offset,$limit,$response['meta']['records'],$page,'/getInvoiceOrders');
            $pagination_code = View::make("components.ajax-pagination-component")
            ->with("pagination", $custom_pagination)
            ->render();
    
            $table_code = View::make("components.datatabels.invoice-orders-page-component")
            ->with("invoices", $response['salesorders'])
            ->render();
            
            $response['pagination_code'] = $pagination_code;
            $response['table_code'] = $table_code;
            /* custom pagination work end */
            
            echo json_encode($response);
            die();
        }  
    }

    public function getAnalysisPageData(Request $request){
        $arr = [
            [
                'no' => '89742',
                'date' => '2022-04-08',
                'custpono' => '123456',
                'city' => 'london',
                'total_items' => 10,
                'total_amount' => 145,
            ],
            [
                'no' => '89742',
                'date' => '2022-04-15',
                'custpono' => '123456',
                'city' => 'london',
                'total_items' => 10,
                'total_amount' => 100,
            ],
            [
                'no' => '89742',
                'date' => '2022-03-08',
                'custpono' => '123456',
                'city' => 'london',
                'total_items' => 10,
                'total_amount' => 153,
            ],
            [
                'no' => '89742',
                'date' => '2022-02-08',
                'custpono' => '123456',
                'city' => 'london',
                'total_items' => 10,
                'total_amount' => 165,
            ],
            [
                'no' => '89742',
                'date' => '2022-01-08',
                'custpono' => '123456',
                'city' => 'london',
                'total_items' => 10,
                'total_amount' => 98,
            ],
            [
                'no' => '89742',
                'date' => '2022-08-08',
                'custpono' => '123456',
                'city' => 'london',
                'total_items' => 10,
                'total_amount' => 200,
            ],
            [
                'no' => '89742',
                'date' => '2022-10-08',
                'custpono' => '123456',
                'city' => 'london',
                'total_items' => 10,
                'total_amount' => 198,
            ],
        ];
        $table_code = View::make("components.datatabels.analysis-page-component")
            ->with("analysisdata", $arr)
            ->render();

        $data['response'] = $arr;
        $data['table_code'] = $table_code;
        echo json_encode($data);
        die();

    }

}
