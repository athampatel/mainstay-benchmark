<?php

namespace App\Http\Controllers;

use App\Models\UserDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\SDEApi;
use App\Http\Controllers\Backend\UsersController;
use App\Models\ApiData;
use App\Models\ApiType;
use App\Models\CustomerMenu;
use App\Models\CustomerMenuAccess;
use App\Models\Post;
use App\Models\SalesPersons;
use App\Http\Controllers\SaleByProductLineController as ProductLine;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\SchedulerLogController;
use App\Http\Controllers\InvoicedOrdersController;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\SaleOrdersController;

class MenuController extends Controller
{

    public function __construct(SDEApi $SDEApi)
    {
        $this->SDEApi = $SDEApi;
    }

    public function NavMenu($current = ''){

    //    $menus = array('dashboard'           =>         array(  'name' => 'products & inventory', 
       $menus = array('dashboard'           =>         array(  'name' => 'Dashboard', 
                                                                'icon_name' => file_get_contents(public_path('/assets/images/svg/products_gray.svg')), 
                                                                'active' => 0,  
                                                                'link'=> '/dashboard','code' => 'auth.customer.dashboard'),
                        'invoice'           =>          array(  'name' => 'invoiced orders', 
                                                                'icon_name' => file_get_contents(public_path('/assets/images/svg/invoice_order_gray.svg')),
                                                                'active' => 0,
                                                                'link'=> '/invoice','code' => 'auth.customer.invoice'),
                        'open-orders'       =>          array(  'name' => 'open orders', 
                                                                'icon_name' => file_get_contents(public_path('/assets/images/svg/open_orders_gray.svg')),
                                                                'active' => 0,
                                                                'link'=> '/open-orders','code' => 'auth.customer.open-orders'),
                        // 'change-order'      =>          array(  'name' => 'change order', 
                        //                                         'icon_name' => file_get_contents(public_path('/assets/images/svg/change_order_gray.svg')),
                        //                                         'active' => 0,
                        //                                         'link'=> 'change-order'),
                        'vmi-user'          =>          array(  'name' => 'VMI user', 
                                                                'icon_name' => file_get_contents(public_path('/assets/images/svg/vmi_user_gray.svg')),
                                                                'active' => 0,
                                                                'link'=> '/vmi-user','code' => 'auth.customer.vmi'),
                        'analysis'          =>          array(  'name' => 'analysis', 
                                                                'icon_name' => file_get_contents(public_path('/assets/images/svg/analysis_menu_gray.svg')),
                                                                'active' => 0,
                                                                'link'=> '/analysis','code' => 'auth.customer.analysis'),
                        'account-settings'  =>          array(  'name' => 'account settings', 
                                                                'icon_name' => file_get_contents(public_path('/assets/images/svg/account_settings_gray.svg')),
                                                                'active' => 0,
                                                                'link'=> '/account-settings','code' => 'auth.customer.account-settings'),
                        'help'              =>          array(  'name' => 'help', 
                                                                'icon_name' => file_get_contents(public_path('/assets/images/svg/help_menu_gray.svg')),
                                                                'active' => 0,
                                                                'link'=> '/help','code' => 'auth.customer.help'),
                        'logout'             =>          array( 'name' => 'logout', 
                                                                'icon_name' => file_get_contents(public_path('/assets/images/svg/logout_menu_gray.svg')),
                                                                'active' => 0,
                                                                'link'=> '/logout','code' => 'auth.customer.logout')
        );

        if(isset($menus[$current])){
            $menus[$current]['active'] = 1;
        }else{
            $menus['dashboard']['active'] = 1;
        }
        return $menus;

    }

    public static function CustomerPageRestriction($user_id,$menus,$current_menu){
        $customer_menus = [];
        $customer_menu_access = CustomerMenuAccess::where('user_id',$user_id)->first();
        if($customer_menu_access){
            $menu_access = explode(',',$customer_menu_access->access);
            $customer_menus = CustomerMenu::whereIn('id',$menu_access)->pluck('code')->toArray();
            if(!in_array($menus[$current_menu]['code'],$customer_menus)){
                return false;
            }
        }
        return $customer_menus;
    }

    public function dashboard(Request $request){
        $data['title']  = '';
        $data['current_menu']   = 'dashboard';
        $data['menus']          = $this->NavMenu('dashboard');
        //$products = new SchedulerLogController();
        //$products->runScheduler();
        //die; 
        //$invoice = new InvoicedOrdersController();
        //$invoice->getInvoiceOrders();
        //die; 
        $customer_no    = $request->session()->get('customer_no');
        $customers      = $request->session()->get('customers');
        $user_id        = $customers[0]->user_id;
        $response = self::CustomerPageRestriction($user_id,$data['menus'],$data['current_menu']);
        if(!$response) return redirect()->back();
        $data['customer_menus'] = $response;

        $data['region_manager'] =  SalesPersons::select('sales_persons.*')->leftjoin('user_sales_persons','sales_persons.id','=','user_sales_persons.sales_person_id')
                                                ->leftjoin('user_details','user_sales_persons.user_details_id','=','user_details.id')->where('user_details.customerno',$customer_no)->first();

        $data['constants']          = config('constants');
        $customerDetails            = UserDetails::where('customerno',$customer_no)->where('user_id',$user_id)->first();
        $year                       = 2022;   

        // $sales_orders               = new SaleOrdersController();
        // $sales_args                 = array('from' => $year.'-01-01','to' => $year.'-12-31');
        // $sales_by_year              = $sales_orders->getSaleByYear($customer_no,$sales_args,$request);
        // $recent_orders              = $sales_orders->getRecentOrders($customer_no,6);
        // $SaleByCategory             = $sales_orders->getSaleByCategory($customer_no,$year);

        $saleby_productline         = ProductLine::getSaleDetails($customerDetails,$year);
        $sale_map                   = array();
        if(!empty($saleby_productline)){
            foreach($saleby_productline as $key => $value){                         
                $sale_map[] = array('label' => $key,'value' => array_sum($value[$year]));
            }
        }            
       
        $data['saleby_productline'] = $sale_map; 
        $data['data_productline']   = $sale_map;

        // $data['sales_orders']       = $sales_by_year;
        // $data['recent_orders']      = $recent_orders;
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
        return redirect()->route('auth.customer.dashboard');
    }
    public function invoicePage(Request $request){
        $data['title']  = '';
        $data['current_menu']   = 'invoice';
        $data['menus']          = $this->NavMenu('invoice');
        $customers    = $request->session()->get('customers');
        $user_id = $customers[0]->user_id;
        $customer_no   = $request->session()->get('customer_no');
        $sales_orders               = new SaleOrdersController();
        $page                       = 0;

        // $recent_orders              = $sales_orders->getRecentOrders($customer_no,15,$page,1);
        $response = self::CustomerPageRestriction($user_id,$data['menus'],$data['current_menu']);
        if(!$response) return redirect()->back();
        $data['customer_menus'] = $response;
        $data['constants'] = config('constants');        
        // $data['recent_orders'] = $recent_orders;
        $data['recent_orders'] = [];
        return view('Pages.invoice',$data);  
    }
    
    public function openOrdersPage(Request $request){
        $final_data['title']  = '';
        $final_data['current_menu']   = 'open-orders';
        $final_data['menus']          = $this->NavMenu('open-orders');
        $customers    = $request->session()->get('customers');
        $user_id = $customers[0]->user_id;
        $response = self::CustomerPageRestriction($user_id,$final_data['menus'],$final_data['current_menu']);
        if(!$response) return redirect()->back();
        $final_data['customer_menus'] = $response;
        $final_data['constants'] = config('constants');
        return view('Pages.open-orders',$final_data);
    }
    
    public function changeOrderPage(Request $request,$orderid){           

        $customer_no   = $request->session()->get('customer_no');
        $customers    = $request->session()->get('customers');
        if(Auth::user()){
            $final_data['title']  = '';
            $final_data['current_menu']   = 'open-orders';
            $final_data['menus']          = $this->NavMenu('open-orders');
            $user = User::find(Auth::user()->id);
            $final_data['order_id'] = $orderid;
            $final_data['user'] = $user;            
            $user_id = $customers[0]->user_id;
            $final_data['is_change_order'] = self::CheckChangeOrderAccess($user_id);
            $response = self::CustomerPageRestriction($user_id,$final_data['menus'],$final_data['current_menu']);
            if(!$response) return redirect()->back();
            $final_data['customer_menus'] = $response;
            $final_data['user_detail'] = UserDetails::where('user_id',$user->id)->where('customerno',$customer_no)->first();
            $final_data['constants'] = config('constants');
            return view('Pages.change-order',$final_data);
        } else {
            return redirect()->route('auth.customer.dashboard');
        }
    }
    
    public function vmiUserPage(Request $request){
        $data['title']  = '';
        $data['current_menu']   = 'vmi-user';
        $data['menus']          = $this->NavMenu('vmi-user');
        $customers    = $request->session()->get('customers');
        $user_id = $customers[0]->user_id;
        $response = self::CustomerPageRestriction($user_id,$data['menus'],$data['current_menu']);
        if(!$response) return redirect()->back();
        $data['customer_menus'] = $response;
        $data['constants'] = config('constants');
        return view('Pages.vmi-user',$data);
    }

    // analysis page
    public function analysisPage(Request $request){
        $data['title']  = '';
        $data['current_menu']   = 'analysis';
        $data['menus']          = $this->NavMenu('analysis');
        $customers    = $request->session()->get('customers');
        $user_id = $customers[0]->user_id;
        $response = self::CustomerPageRestriction($user_id,$data['menus'],$data['current_menu']);
        if(!$response) return redirect()->back();
        $data['customer_menus'] = $response;
        $data['constants'] = config('constants');
        return view('Pages.analysis',$data);
    }
    
    public function accountSettingsPage(request $request){
        $data['title']  = '';
        $data['current_menu']   = 'account-settings';
        $data['menus']          = $this->NavMenu('account-settings');
        $user_id = Auth::user()->id;
        $customer_no   = $request->session()->get('customer_no');
        $customers    = $request->session()->get('customers');
        $user_id = $customers[0]->user_id;
        $response = self::CustomerPageRestriction($user_id,$data['menus'],$data['current_menu']);
        if(!$response) return redirect()->back();
        $data['customer_menus'] = $response;
        $data['user_detail'] = UserDetails::where('user_id',$user_id)->where('customerno',$customer_no)->first();
        $data['constants'] = config('constants');
        return view('Pages.account-settings',$data);
    }
    public function helpPage(Request $request){
        /* test work start */
        // $orders = DB::table('sale_orders')
        //             ->select('sale_orders.orderdate', DB::raw('SUM(order_details.unitprice) AS total'), DB::raw('SUM(order_details.quantityshipped) AS total_qty'), 'sale_orders.salesorderno as order_id', 'sale_orders.shiptostate','sale_orders.shiptocity')
        //             ->leftJoin('order_details', 'sale_orders.id', '=', 'order_details.sale_orders_id')
        //             ->leftJoin('user_details', 'sale_orders.user_details_id', '=', 'user_details.id')
        //             ->leftJoin('users', 'user_details.user_id', '=', 'users.id')
        //             ->where('order_details.quantityshipped', '>', 0)
        //             ->where('order_details.unitprice', '>', 0)
        //             ->where('users.active', 1)
        //             ->where('user_details.customerno', 'GEMWI00')
        //             ->groupBy('sale_orders.id','sale_orders.salesorderno','sale_orders.shiptocity','sale_orders.shiptostate','sale_orders.orderdate')
        //             ->orderBy('sale_orders.orderdate', 'DESC')
        //             ->get()->toArray();
        // echo '<pre>';
        // print_r($orders);            
        // echo '</pre>';
        // die();            
        /* test work end */
        $data['title']  = '';
        $data['current_menu']  = 'help';
        $data['menus']         = $this->NavMenu('help');
        $data['posts'] = Post::paginate(10);
        $posts = Post::paginate(10);
        $data['pagination'] = $posts->toArray();
        $customers    = $request->session()->get('customers');
        $user_id = $customers[0]->user_id;
        $response = self::CustomerPageRestriction($user_id,$data['menus'],$data['current_menu']);
        if(!$response) return redirect()->back();
        $data['customer_menus'] = $response;
        $data['constants'] = config('constants');
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
        // $user_id = Auth::user()->id;
        $customer_no   = $request->session()->get('customer_no');
        $customers    = $request->session()->get('customers');
        $user_id = $customers[0]->user_id;
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
            // dd($response);
            $path = '/getOpenOrders';
            $custom_pagination = self::CreatePaginationData($response,$limit,$page,$offset,$path);        
            // dd($custom_pagination);
            if($custom_pagination['last_page'] >= 1){
                $pagination_code = View::make("components.ajax-pagination-component")
                ->with("pagination", $custom_pagination)
                ->render();
            } else {
                $pagination_code = '';
            }
            // $change_order_menu_id = CustomerMenu::where('code','auth.customer.change-order')->pluck('id')->toArray();
            // $change_order_menu_id = !empty($change_order_menu_id) ? $change_order_menu_id[0] : 0;
            $customers    = $request->session()->get('customers');
            $user_id = $customers[0]->user_id;
            $is_change_order = self::CheckChangeOrderAccess($user_id);
            $table_code = View::make("components.datatabels.open-orders-page-component")
            ->with("saleorders", $response['salesorders'])
            ->with("is_change_order", $is_change_order)
            ->render();
            $response['pagination_code'] = $pagination_code;
            $response['table_code'] = $table_code;

            echo json_encode($response);
            die();
        }  
    }

    // custom ajax pagination
    // public static function AjaxgetPagination($offset,$limit,$total,$page,$path){
    //     $custom_pagination['from'] = $offset;
    //     $custom_pagination['to'] = intval($offset + $limit - 1) > intval($total) ? intval($total) :intval($offset + $limit - 1);
    //     $custom_pagination['total'] = $total;
    //     $custom_pagination['first_page'] = 1;
    //     $custom_pagination['last_page'] = intval(ceil($total / $limit));
    //     $custom_pagination['prev_page'] = $page - 1 == -1 ? 0 : $page - 1;
    //     $custom_pagination['curr_page'] = intval($page);
    //     $custom_pagination['next_page'] = $page + 1;
    //     $custom_pagination['path'] = $path;
    //     $last = ceil($total / $limit); 
    //     if(intval($total) > $limit ){
    //         if($page !=0 && ($page + 1) != $last){
    //             $active_number[] = $page;
    //             $active_number[] = $page + 1;
    //             $active_number[] = $page + 2;
    //         } else {
    //             if($page == 0 || $page == 1){
    //                 $active_number = [1,2,3];
    //             } elseif(($page + 1) == $last){
    //                 for($i = 2;$i >= 0;$i--){
    //                     $active_number []= $last - $i;
    //                 }       
    //             }
    //         }
    //     } else {
    //         $active_number[] = 1;
    //     }
    //     foreach($active_number as $number){               
    //         $custom_link = [
    //             'label' => $number,
    //             'active' => $page + 1 == $number,
    //         ];
    //         $custom_pagination['links'][] = $custom_link;
    //     }
    //     return $custom_pagination;
    // }

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
            $data = array(            
                "filter" => [
                    [
                        "column" =>  "CustomerNo",
                        "type" =>  "equals",
                        "value" =>  $user_details->customerno,
                        "operator" =>  "and"
                    ],
                    [
                        "column" => "ARDivisionNo",
                        "type" => "equals",
                        "value" => $user_details->ardivisionno,
                        "operator" => "and"
                    ],
                ],
                "offset" => $offset,
                "limit" => $limit,
            );
            $response   = $this->SDEApi->Request('post','SalesOrderHistoryHeader',$data);
            $path = '/getInvoiceOrders';
            $custom_pagination = self::CreatePaginationData($response,$limit,$page,$offset,$path);
            if($custom_pagination['last_page'] >= 1){
                $pagination_code = View::make("components.ajax-pagination-component")
                ->with("pagination", $custom_pagination)
                ->render();
            }
            // dd();
            $table_code = View::make("components.datatabels.invoice-orders-page-component")
            ->with("invoices", $response['salesorderhistoryheader'])
            ->render();
            
            $response['pagination_code'] = $pagination_code;
            $response['table_code'] = $table_code;
            /* custom pagination work end */
            
            echo json_encode($response);
            die();
        }  
    }

    public function getAnalysisPageData1(Request $request){
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

    public function getCustomerOpenOrdersData(Request $request){
        $customer = $request->session()->get('customers');
        $customer_no = $request->session()->get('customer_no');
        $type = ApiType::where('name','salesorders')->first();
        $api_data = ApiData::where('customer_no',$customer_no)->where('type',$type->id)->first();
        $is_data_fetch = true;
        if($api_data){
            $time_now = date('Y-m-d h:i:s');
            $update_time = $api_data->updated_at->diffInMinutes($time_now);
            if($update_time <= 30){
                $is_data_fetch = false;
            }
        }
        if($is_data_fetch){
            $data = array(            
                "filter" => [
                    [
                        "column"=> "ARDivisionNo",
                        "type"=> "equals",
                        "value"=> $customer[0]->ardivisionno,
                        "operator"=> "and"
                    ],
                    [
                        "column"=> "CustomerNo",
                        "type"=> "equals",
                        "value"=> $customer_no,
                        "operator"=> "and"
                    ],
                ],
            );
            
            $response   = $this->SDEApi->Request('post','SalesOrders',$data);
            $is_api_data = ApiData::where('customer_no',$customer_no)->where('type',$type->id)->first();
            // dd($is_api_data);
            if($is_api_data){
                $is_api_data->data = json_encode($response);
                $is_api_data->updated_at = date('Y-m-d h:i:s'); 
                $is_api_data->save(); 
            } else {
               $api_data = ApiData::create([
                    'customer_no' => $customer_no,
                    'type' => $type->id,
                    'data' => json_encode($response),
                ]);
                // dd($api_data);
            }
            $response = $response['salesorders'];
        } else {
            $response = json_decode($api_data->data,true);
            $response = $response['salesorders'];
        }
        // dd($response);
        $open_orders = [];
        foreach($response as $res){
            $date = explode("-",$res['orderdate']);
            if($date[0] == '2022'){
                foreach($res['details'] as $detail){
                    if(isset($open_orders['0-'.$date[1]])){
                        $open_orders['0-'.$date[1]] = $open_orders['0-'.$date[1]] + ($detail['quantityordered'] *$detail['unitprice']);
                    } else {
                        $open_orders['0-'.$date[1]] = ($detail['quantityordered'] * $detail['unitprice']);
                    }
                }   
            }
        }
        $total = 0;
        $new_open_orders = [];
        for($i = 1; $i <= 12 ; $i++){
            $num = $i < 10 ? '0'.$i : $i;
            $open_orders['0-'.$num] = isset($open_orders['0-'.$num]) ?  $open_orders['0-'.$num] : 0;
            $total = isset($open_orders['0-'.$num]) ? ($total + $open_orders['0-'.$num]) : $total;
            $new_open_orders[] = $open_orders['0-'.$num];
        }
        echo json_encode(['success' => true, 'data' => ['data' => $new_open_orders,'count' => $total],'error' => []]);
        die();
    }

    public function getVmiData(Request $request){
        $data = $request->all();
        $page = $data['page'];
        $limit = $data['count'];
        if($page == 0){
            $offset = 1;
        } else {
            $offset = $page * $limit + 1;
        }
        $customer = $request->session()->get('customers');
        $customer_no = $request->session()->get('customer_no');
        $user_id = Auth::user()->id;
        $customer_details = UserDetails::where('customerno',$customer_no)->where('user_id',$user_id)->first()->toArray();
        $companycode = $customer_details['vmi_companycode'];
        $response['products'] = array();   
        if($companycode){
                $data = array(                             
                    "companyCode"   => $companycode,
                    "offset"        => $offset,
                    "limit"         => $limit,
                    "filter" => [
                        [
                            "column"=> "ItemType",
                            "type"=> "equals",
                            "value"=> '1',
                            "operator"=> "and"
                        ],
                    ], 
                );
            $response   = $this->SDEApi->Request('post','Products',$data);
            $path = '/getVmiData';
            $custom_pagination = self::CreatePaginationData($response,$limit,$page,$offset,$path);
            // dd($custom_pagination);
            $pagination_code = "";
            // if($custom_pagination['last_page'] > 1){
            $pagination_code = View::make("components.ajax-pagination-component")
            ->with("pagination", $custom_pagination)
            ->render();
            // }
            $table_code = View::make("components.datatabels.vmi-component")
                ->with("vmiProducts", $response['products'])
                ->render();
                $res = ['success' => true, 'data' => $response, 'table_code' => $table_code,'pagination_code' => $pagination_code];
        } else {
            $res = ['success' => false];
        }
        echo json_encode($res);
        die(); 
    }

    public static function CreatePaginationData($response,$limit,$page,$offset,$path){
        $custom_pagination = array();
        $last_page = intval(ceil($response['meta']['records'] / $limit));
        $custom_pagination['links'] = UsersController::customPagination(1,$last_page,$response['meta']['records'],intval($limit),intval($page + 1),'');
        $total = $response['meta']['records'];
        $custom_pagination['from'] = $offset;
        $custom_pagination['to'] = intval($offset + $limit - 1) > intval($total) ? intval($total) :intval($offset + $limit - 1);
        $custom_pagination['total'] = $total;
        $custom_pagination['first_page'] = 0;
        $custom_pagination['last_page'] = intval(ceil($total / $limit)) - 1;
        $custom_pagination['prev_page'] = $page - 1 == -1 ? 0 : $page - 1;
        $custom_pagination['curr_page'] = intval($page);
        $custom_pagination['next_page'] = intval(ceil($total / $limit)) - 1 == $page ? intval(ceil($total / $limit)) - 1 : $page + 1;
        $custom_pagination['path'] = $path;

        return $custom_pagination;
    }

    public static function CheckChangeOrderAccess($user_id){
        $is_change_order = false;
        $customer_menu_access = CustomerMenuAccess::where('user_id',$user_id)->first();
        $menu_access = explode(',',$customer_menu_access->access);
        $customer_menus = CustomerMenu::whereIn('id',$menu_access)->pluck('code')->toArray();
        $change_order_menu_code = CustomerMenu::where('name','Customer Change Order Request')->pluck('code')->first();
        if(in_array($change_order_menu_code,$customer_menus)){
            $is_change_order = true;
        }
        return $is_change_order;
    }

    // show invoice detail
    public function showInvoiceDetail(Request $request,$orderid){
        $customer_no   = $request->session()->get('customer_no');
        $customers    = $request->session()->get('customers');
        if(Auth::user()){
            $final_data['title']  = '';
            $final_data['current_menu']   = 'invoice';
            $final_data['menus']          = $this->NavMenu('invoice');
            $user = User::find(Auth::user()->id);
            $final_data['order_id'] = $orderid;
            $final_data['user'] = $user;            
            $user_id = $customers[0]->user_id;
            // $final_data['is_change_order'] = self::CheckChangeOrderAccess($user_id);
            $final_data['is_change_order'] = false;
            $response = self::CustomerPageRestriction($user_id,$final_data['menus'],$final_data['current_menu']);
            if(!$response) return redirect()->back();
            $final_data['customer_menus'] = $response;
            $sales_orders   = new SaleOrdersController();
            // $final_data['details']      = $sales_orders->getOrderDetails($customer_no,);            
            $final_data['details']      = $sales_orders->getOrderDetails($customer_no,$orderid);
            // dd($final_data['details']);            
            $response                   = self::CustomerPageRestriction($user_id,$final_data['menus'],$final_data['current_menu']); 
            $final_data['user_detail']  = UserDetails::where('user_id',$user->id)->where('customerno',$customer_no)->first();
            $final_data['constants']    = config('constants');
            return view('Pages.invoice-detail',$final_data);
        } else {
            return redirect()->route('auth.customer.dashboard');
        }
    }

    // get invoice detail
    public function getInvoiceDetail(Request $request){
        $customers    = $request->session()->get('customers');
        $customer_no    = $request->session()->get('customer_no');
        $user_id = $customers[0]->user_id;
        $user_details   = UserDetails::where('user_id',$user_id)->where('customerno',$customer_no)->first();
        $order_no = $request->order_no;
        if($user_details){
            $data = array(            
                "filter" => [
                    [
                        "column" =>  "CustomerNo",
                        "type" =>  "equals",
                        "value" =>  $user_details->customerno,
                        "operator" =>  "and"
                    ],
                    [
                        "column" => "ARDivisionNo",
                        "type" => "equals",
                        "value" => $user_details->ardivisionno,
                        "operator" => "and"
                    ],
                    [
                        "column" => "salesorderno",
                        "type" => "equals",
                        "value" => $order_no,
                        "operator" => "and"
                    ],
                ],
                "offset" => 1,
                "limit" => 2,
            );
            $response   = $this->SDEApi->Request('post','SalesOrderHistoryHeader',$data);
            $res['success'] = false;
            $res['error'] = 'Order Details not found';
            if(!empty($response['salesorderhistoryheader'])){
               $res['order_detail'] =  $response['salesorderhistoryheader'][0];
               $res['user_detail'] = $user_details;
               $res['success'] = true;
            }

            echo json_encode($res);
            die();
        }
    }

    public function getAnalysisPageData(Request $request){
        $data = $request->all();
        $page = isset($data['page']) ? $data['page'] : 0;
        $limit = isset($data['count']) ? intval($data['count']) : 12;
        $year = isset($data['year']) ? $data['year'] : intval(date('Y'));
        $range = isset($data['range']) ? intval($data['range']) : 0;
        $response_table_data = [];
        if($page == 0){
            $offset = 1;
        } else {
            $offset = $page * $limit + 1;
        }
        $customer_no    = $request->session()->get('customer_no');
        $user_id        = Auth::user()->id;
        $user_details   = UserDetails::where('user_id',$user_id)->where('customerno',$customer_no)->first();
        // table data
        $response_table = [];
        $filter_dates = $this->getRangeDates($range,$year);
        $filter_start_date = $filter_dates['start'];
        $filter_end_date = $filter_dates['end'];
        $date_filter = [
            [
                "column" => "invoicedate",
                "type" => "greaterthan",
                "value" => $filter_start_date,
                "operator" => "and"
            ],
            [
                "column" => "invoicedate",
                "type" => "lessthan",
                "value" => $filter_end_date,
                "operator" => "and"
            ]
        ];

        if($user_details){
            $data = array(            
                "filter" => [
                    [
                        "column" =>  "CustomerNo",
                        "type" =>  "equals",
                        "value" =>  $user_details->customerno,
                        "operator" =>  "and"
                    ],
                    [
                        "column" => "ARDivisionNo",
                        "type" => "equals",
                        "value" => $user_details->ardivisionno,
                        "operator" => "and"
                    ],
                ],
                "offset" => $offset,
                "limit" => $limit,
            );
            // merge the filter data into the data filter
            $data['filter'] = array_merge($date_filter,$data['filter']);
            $response_table = $this->SDEApi->Request('post','SalesOrderHistoryHeader',$data);
            $response_table_data = $response_table['salesorderhistoryheader'];
        }
        $table_code = View::make("components.datatabels.analysis-page-component")
        ->with("analysisdata", $response_table_data)
        ->render();

        $path = '/get-analysis-page-data';
        $custom_pagination = self::CreatePaginationData($response_table,$limit,$page,$offset,$path);
        $pagination_code = "";
        if($custom_pagination['last_page'] >= 1){
            $pagination_code = View::make("components.ajax-pagination-component")
            ->with("pagination", $custom_pagination)
            ->render();
        }

        // chart data
        if($range == 4){
            $year = explode('-',$filter_start_date)[0];
        }
        $data = array(            
            "filter" => [
                [
                    "column" => "ARDivisionNo",
                    "type" => "equals",
                    "value" => $user_details->ardivisionno,
                    "operator" => "and"
                ],
                [
                    "column" =>  "CustomerNo",
                    "type" =>  "equals",
                    "value" =>  $user_details->customerno,
                    "operator" =>  "and"
                ],
                [
                    "column" =>  "FiscalYear",
                    "type" =>  "equals",
                    "value" =>  $year,
                    "operator" =>  "and"
                ]
            ]
        );

        $response_data   = $this->SDEApi->Request('post','CustomerSalesHistory',$data);

        // product by line chart
        $saleby_productline         = ProductLine::getSaleDetails($user_details,$year);
        $sale_map                   = array();
        if(!empty($saleby_productline)){
            foreach($saleby_productline as $key => $value){   
                $total_val = 0;
                foreach($value[$year] as $k => $v){
                    $k = $k > 9 ? strval($k) : "0$k";
                    if(in_array($k,$filter_dates['range_months'])){
                        $total_val = $total_val + $v;
                    }
                }                  
                $sale_map[] = array('label' => $key,'value' => $total_val);
            }
        }

        $res['table_code'] = $table_code;
        $res['pagination_code'] = $pagination_code;
        $res['analysis_data'] = $response_data['customersaleshistory'];
        $res['range_months'] =  $filter_dates['range_months'];
        $res['product_data'] = $sale_map;
        echo json_encode($res);
        die();
    }

    public function getRangeDates($range,$year) {
        $start_date = '';
        $end_date = '';
        $range_months = [];
        $current_month = Carbon::now()->format('m');
        if($range != 0){
            if($range ==  1){
                $start_date =  Carbon::parse($year . '-' . $current_month . '-01')->subMonths(2)->endOfMonth()->format('Y-m-d');            
                $end_date = $year."-"."$current_month"."-01";
                $last_month = Carbon::now()->subMonth()->month;
                $last_month = $last_month > 9 ? $last_month : "0$last_month"; 
                $range_months = [$last_month];
            }
            if($range == 2){
                // $start_date = Carbon::parse($year . '-' . $current_month . '-01')->subMonths(4)->endOfMonth()->format('Y-m-d');
                $start_date = ($year - 1).'-12-31';
                $end_date = $year. '-04-01';
                $range_months = ['01','02','03'];
            }
            if($range == 3){
                // $start_date =  Carbon::parse($year . '-' . $current_month . '-01')->subMonths(7)->endOfMonth()->format('Y-m-d');
                $start_date = ($year - 1).'-12-31';
                $end_date = $year."-"."07-01";
                $range_months = ['01','02','03','04','05','06'];
            }
            if($range == 4){
                $dates = explode('&',$year);
                $start_date = $dates[0];
                $end_date = $dates[1];

                $start = Carbon::parse($start_date);
                $end = Carbon::parse($end_date);

                $interval = \DateInterval::createFromDateString('1 month');
                $period = new \DatePeriod($start, $interval, $end);

                foreach ($period as $dt) {
                    $range_months[] = $dt->format("m");
                }
            }
        } else {
            $range_months = ['01','02','03','04','05','06','07','08','09','10','11','12'];
            $start_date = ($year - 1) . "-12-31";
            $end_date = ($year + 1)."-01-01";
        }

        return ['start' => $start_date, 'end' => $end_date ,'range_months' => $range_months];
    }
}
