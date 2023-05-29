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
use App\Http\Controllers\SaleOrdersController;
use App\Models\Admin;
use App\Models\AnalaysisExportRequest;
use App\Models\ChangeOrderItem;
use App\Models\ChangeOrderRequest;
use App\Models\HelpRequest;
use App\Models\Notification;
use App\Models\SearchWord;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Mail;

class MenuController extends Controller
{

    public function NavMenu($current = ''){

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
        // $by_admin = $request->session()->get('by_admin');
        $by_admin = Session()->get('by_admin');
        if($by_admin){
            $menus['by_admin'] =  array( 'name' => 'Back To Admin', 
                                        'icon_name' => file_get_contents(public_path('/assets/images/svg/back_to_admin.svg')),
                                        'active' => 0,
                                        'link'=> '/admin/back_to_admin','code' => 'auth.admin.back');
        }

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
        $by_admin = Session()->get('by_admin');
        if($by_admin){
            $customer_menus[] = 'auth.admin.back';
        }
        return $customer_menus;
    }

    public function dashboard(Request $request){
        $data['title']  = '';
        $data['current_menu']   = 'dashboard';
        $data['menus']          = $this->NavMenu('dashboard');
        $customer_no    = $request->session()->get('customer_no');
        $customers      = $request->session()->get('customers');
        $user_id        = $customers[0]->user_id;
        $response = self::CustomerPageRestriction($user_id,$data['menus'],$data['current_menu']);
        if(!$response) return redirect()->back();
        $data['customer_menus'] = $response;

        $data['region_manager'] =  SalesPersons::select('sales_persons.*','admins.profile_path as profile','.admins.phone_no')->leftjoin('user_sales_persons','sales_persons.id','=','user_sales_persons.sales_person_id')
                                                ->leftjoin('user_details','user_sales_persons.user_details_id','=','user_details.id')->where('user_details.customerno',$customer_no)
                                                ->leftjoin('admins','admins.email','=','sales_persons.email')
                                                ->first();
        $data['constants']          = config('constants');
        $customerDetails            = UserDetails::where('customerno',$customer_no)->where('user_id',$user_id)->first();
        $year                       = 2022;
        // comment for api issue
        $saleby_productline1         = ProductLine::getSaleDetails($customerDetails,$year);
        // dd($saleby_productline1);
        if($saleby_productline1){
            $saleby_productline = $saleby_productline1['sales_details']; 
            $saleby_productline_desc = $saleby_productline1['sales_desc_details']; 
        } else {
            $saleby_productline = [];
            $saleby_productline_desc = [];
        }
        $sale_map                   = array();
        $sale_map_desc                   = array();
        if(!empty($saleby_productline)){
            foreach($saleby_productline as $key => $value){         
                $sale_map[] = array('label' => $key,'value' => array_sum($value[$year]));
            }
        }            
        
        if(!empty($saleby_productline_desc)){
            foreach($saleby_productline_desc as $key => $value){         
                $sale_map_desc[] = array('label' => $key,'value' => array_sum($value[$year]));
            }
        }

        $data['saleby_productline'] = $sale_map; 
        $data['data_productline']   = $sale_map;
        $data['data_productline_desc']   = $sale_map_desc;

        // search words
        $searchWords = SearchWord::where('type',2)->get()->toArray();
        $data['user'] = Auth::user();
        $data['searchWords']   = $searchWords;
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

        $response = self::CustomerPageRestriction($user_id,$data['menus'],$data['current_menu']);
        if(!$response) return redirect()->back();
        $data['customer_menus'] = $response;
        $data['constants'] = config('constants');        
        $data['recent_orders'] = [];
        $searchWords = SearchWord::where('type',2)->get()->toArray();
        $data['searchWords']   = $searchWords;
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
        $searchWords = SearchWord::where('type',2)->get()->toArray();
        $final_data['searchWords']   = $searchWords;
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
            $searchWords = SearchWord::where('type',2)->get()->toArray();
            $final_data['searchWords']   = $searchWords;
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
        $searchWords = SearchWord::where('type',2)->get()->toArray();
        $data['searchWords']   = $searchWords;
        return view('Pages.vmi-user',$data);
    }

    // analysis page
    public function analysisPage(Request $request,$year = ''){
        $data['title']  = '';
        $data['current_menu']   = 'analysis';
        $data['menus']          = $this->NavMenu('analysis');
        $customers    = $request->session()->get('customers');
        $user_id = $customers[0]->user_id;
        $response = self::CustomerPageRestriction($user_id,$data['menus'],$data['current_menu']);
        if(!$response) return redirect()->back();
        $data['customer_menus'] = $response;
        $data['constants'] = config('constants');
        $searchWords = SearchWord::where('type',2)->get()->toArray();
        $data['searchWords']   = $searchWords;
        $data['urlyear']   = $year;
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
        $data['sales_person'] = UserDetails::leftjoin('user_sales_persons','user_sales_persons.user_details_id','=','user_details.id')
                                ->leftjoin('sales_persons','sales_persons.id','=','user_sales_persons.sales_person_id')
                                ->leftjoin('admins','admins.email','=','sales_persons.email')
                                ->select('sales_persons.person_number','sales_persons.name','sales_persons.email','admins.profile_path')
                                ->where('user_id',$user_id)->where('customerno',$customer_no)->first()->toArray();
        $data['constants'] = config('constants');
        $searchWords = SearchWord::where('type',2)->get()->toArray();
        $data['searchWords']   = $searchWords; 
        return view('Pages.account-settings',$data);
    }

    public function helpPage(Request $request){
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
        $searchWords = SearchWord::where('type',2)->get()->toArray();
        $data['searchWords']   = $searchWords;
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

            $SDEAPi = new SDEApi();
            $response   = $SDEAPi->Request('post','SalesOrders',$data);
            $path = '/getOpenOrders';
            $custom_pagination = self::CreatePaginationData($response,$limit,$page,$offset,$path);        
            if($custom_pagination['last_page'] >= 1){
                $pagination_code = View::make("components.ajax-pagination-component")
                ->with("pagination", $custom_pagination)
                ->render();
            } else {
                $pagination_code = '';
            }
            $customers    = $request->session()->get('customers');
            $user_id = $customers[0]->user_id;
            $is_change_order = self::CheckChangeOrderAccess($user_id);
            $table_code = View::make("components.datatabels.open-orders-page-component")
            ->with("saleorders", $response['salesorders'])
            ->with("is_change_order", $is_change_order)
            ->render();
            $response['pagination_code'] = $pagination_code;
            $response['table_code'] = $table_code;
            $response['total_records'] = $custom_pagination['total'];

            echo json_encode($response);
            die();
        }  
    }

    public function getInvoiceOrders(Request $request){
        $data = $request->all();
        $page = $data['page'];
        $limit = $data['count'];
        $start_date = $data['start_date'];
        $end_date = $data['end_date'];
        if($page == 0){
            $offset = 1;
        } else {
            $offset = $page * $limit + 1;
        }

        $user_id        = Auth::user()->id;
        $customer_no    = $request->session()->get('customer_no');
        $user_details   = UserDetails::where('user_id',$user_id)->where('customerno',$customer_no)->first();
        $companycode = $user_details->vmi_companycode;
        if($user_details){
            $add_data = array(
                "companyCode" => $companycode,
            );

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
                        "column" => "invoiceDate",
                        "type" => ">=",
                        "value" => $start_date,
                        "operator" => "and"
                    ],
                    [
                        "column" => "invoiceDate",
                        "type" => "<=",
                        "value" => $end_date,
                        "operator" => "and"
                    ]
                ],
                "offset" => $offset,
                "limit" => $limit,
                "index" => "KSDEDESCENDING",
            );
            $SDEAPi = new SDEApi();
            $response   = $SDEAPi->Request('post','SalesOrderHistoryHeader',$data);
            $path = '/getInvoiceOrders';
            $custom_pagination = self::CreatePaginationData($response,$limit,$page,$offset,$path);
            $pagination_code = '';
            if($custom_pagination['last_page'] >= 1){
                $pagination_code = View::make("components.ajax-pagination-component")
                ->with("pagination", $custom_pagination)
                ->render();
            }

            $table_code = View::make("components.datatabels.invoice-orders-page-component")
            ->with("invoices", $response['salesorderhistoryheader'])
            ->render();
            
            $response['pagination_code'] = $pagination_code;
            $response['table_code'] = $table_code;
            $response['total_records'] = $custom_pagination['total'];
            
            echo json_encode($response);
            die();
        }  
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
            $SDEAPi = new SDEApi();
            $response   = $SDEAPi->Request('post','SalesOrders',$data);
            $is_api_data = ApiData::where('customer_no',$customer_no)->where('type',$type->id)->first();
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
            }
            $response = $response['salesorders'];
        } else {
            $response = json_decode($api_data->data,true);
            $response = $response['salesorders'];
        }
        $open_orders = [];
        $year = date('Y');
        foreach($response as $res){
            $date = explode("-",$res['orderdate']);
            if($date[0] == $year){
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
            $SDEAPi = new SDEApi();
            $response   = $SDEAPi->Request('post','Products',$data);
            $path = '/getVmiData';
            if(empty($response)) {
                $response = [];
            }
            $custom_pagination = self::CreatePaginationData($response,$limit,$page,$offset,$path);
            $pagination_code = "";
            if(!empty($response)){
                $pagination_code = View::make("components.ajax-pagination-component")
                ->with("pagination", $custom_pagination)
                ->render();
            }
            if(empty($response)) $response['products'] = [];
            $table_code = View::make("components.datatabels.vmi-component")
                ->with("vmiProducts", $response['products'])
                ->render();
                $res = ['success' => true, 'data' => $response, 'table_code' => $table_code,'pagination_code' => $pagination_code,'total_records' => $custom_pagination['total']];
        } else {
            $res = ['success' => false];
        }
        echo json_encode($res);
        die(); 
    }

    public static function CreatePaginationData($response,$limit,$page,$offset,$path){
        if(empty($response)){
            $response['meta']['records'] = 0;
        }
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
            $final_data['is_change_order'] = false;
            $response = self::CustomerPageRestriction($user_id,$final_data['menus'],$final_data['current_menu']);
            if(!$response) return redirect()->back();
            $final_data['customer_menus'] = $response;
            $sales_orders   = new SaleOrdersController();
            $final_data['details']      = $sales_orders->getOrderDetails($customer_no,$orderid);
            $response                   = self::CustomerPageRestriction($user_id,$final_data['menus'],$final_data['current_menu']); 
            $final_data['user_detail']  = UserDetails::where('user_id',$user->id)->where('customerno',$customer_no)->first();
            $final_data['constants']    = config('constants');
            $searchWords = SearchWord::where('type',2)->get()->toArray();
            $final_data['searchWords']   = $searchWords;
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
            $SDEAPi = new SDEApi();
            $response   = $SDEAPi->Request('post','SalesOrderHistoryHeader',$data);
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
        $response_table = [];
        $filter_dates = $this->getRangeDates($range,$year);
        $filter_start_date = $filter_dates['start'];
        $filter_end_date = $filter_dates['end'];
        $date_filter = [
            [
                "column" => "invoiceDate",
                "type" => ">=",
                "value" => $filter_start_date,
                "operator" => "and"
            ],
            [
                "column" => "invoiceDate",
                "type" => "<=",
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
                "index" => "KSDEDESCENDING",
                "offset" => $offset,
                "limit" => $limit,
            );
            $data['filter'] = array_merge($date_filter,$data['filter']);
          
            $SDEAPi = new SDEApi();
            $response_table = $SDEAPi->Request('post','SalesOrderHistoryHeader',$data);
            if(!empty($response_table)){
                $response_table_data = $response_table['salesorderhistoryheader'];
            }
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
        $SDEAPi = new SDEApi();
        $response_data   = $SDEAPi->Request('post','CustomerSalesHistory',$data);
        $saleby_productline1         = ProductLine::getSaleDetails($user_details,$year);
        $saleby_productline = $saleby_productline1['sales_details']; 
        $saleby_productline_desc = $saleby_productline1['sales_desc_details'];
        $sale_map                   = array();
        $sale_map_desc                   = array();

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
        
        if(!empty($saleby_productline_desc)){
            foreach($saleby_productline_desc as $key => $value){   
                $total_val = 0;
                foreach($value[$year] as $k => $v){
                    $k = $k > 9 ? strval($k) : "0$k";
                    if(in_array($k,$filter_dates['range_months'])){
                        $total_val = $total_val + $v;
                    }
                }                  
                $sale_map_desc[] = array('label' => $key,'value' => $total_val);
            }
        }

        $res['table_code'] = $table_code;
        $res['pagination_code'] = $pagination_code;
        $res['analysis_data'] = $response_data['customersaleshistory'];
        $res['range_months'] =  $filter_dates['range_months'];
        $res['product_data'] = $sale_map;
        $res['product_data_desc'] = $sale_map_desc;
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
                $start_date =  Carbon::parse($year . '-' . $current_month . '-01')->subMonths(1)->endOfMonth()->format('Y-m-d');            
                $end_date = date('Y-m-d');
                $last_month = Carbon::now()->subMonth()->month;
                $last_month = $last_month > 9 ? $last_month : "0$last_month"; 
                

                $_month = Carbon::parse($start_date)->startOfMonth()->format('m');                    
                $e_month = Carbon::parse($end_date)->startOfMonth()->format('m');                    
                if($_month != $e_month){
                    $range_months = [$_month,$last_month];
                }else{
                    $range_months = [$_month];
                }
            }

            if($range == 2 || $range == 3 || $range == 5){
                $num = 2;
                if($range == 3)
                    $num = 5;
                elseif($range == 5)    
                    $num = 11; 
                $data = array();
                for ($i = $num; $i >= 0; $i--) {
                    //$month = Carbon::today()->subMonth($i);    
                    $current_month = date('m-Y'); //Carbon::now()->format('m');                
                    $month = Carbon::parse('01-'.$current_month)->subMonth($i)->startOfMonth()->format('m');                    
                    $year = Carbon::parse('01-'.$current_month)->subMonth($i)->format('Y');
                    array_push($data, array(
                        'month' => $month,
                        'year' => $year,
                        'index' => $i,
                    ));
                    array_push($range_months,$month);
                }
                $_st = $data[0];
                $w_st = $data[$num];
                $start_date = $_st['year']."-".$_st['month']."-01";
                $end_date   = date('Y-m-d');
            }
           
            /*if($range == 2){
                $start_date = ($year - 1).'-01-01';
                $end_date = $year. '-04-01';
                $range_months = ['01','02','03'];
            }
            if($range == 3){
                $start_date = ($year - 1).'-01-01';
                $end_date = $year."-"."07-01";
                $range_months = ['01','02','03','04','05','06'];
            }
            if($range == 5){
                $start_date = ($year - 1).'-01-01';
                $end_date = $year."-"."07-01";
                $range_months = ['01','02','03','04','05','06'];
            }?*/
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
            $start_date = ($year) . "-01-01";
            $end_date = ($year)."-12-31";
        }

        $return = array('start' => $start_date, 'end' => $end_date ,'range_months' => $range_months);
        return $return;
    }

    public function getChangeOrderRequests(Request $request){
        $final_data['title']  = '';
        $final_data['current_menu']   = 'open-orders';
        $final_data['menus']          = $this->NavMenu('open-orders');
        $customers    = $request->session()->get('customers');
        $user_id = $customers[0]->user_id;
        $response = self::CustomerPageRestriction($user_id,$final_data['menus'],$final_data['current_menu']);
        if(!$response) return redirect()->back();
        $final_data['customer_menus'] = $response;
        $final_data['constants'] = config('constants');
        $searchWords = SearchWord::where('type',2)->get()->toArray();
        $final_data['searchWords']   = $searchWords;
        return view('Pages.change-order-request',$final_data);
    }

    public function getAllChangeRequests (Request $request){
        $data = $request->all();
        $page = $data['page'];
        $limit = $data['count'];
        $offset     = isset($_GET['page']) ? $_GET['page'] : 0;
        
        if($offset > 1){
            $offset = $offset * $limit;
        } 
        
        if(isset($_GET['page']) && $_GET['page'] == 1){
            $offset = $offset * $limit;
        }
        $customers    = $request->session()->get('customers');
        $user_id = $customers[0]->user_id;
        $change_order_requests = ChangeOrderRequest::where('user_id',$user_id)
                                ->leftjoin('change_order_items','change_order_items.order_table_id','=','change_order_requests.id')
                                ->select('change_order_requests.order_no','change_order_requests.ordered_date','change_order_requests.created_at','change_order_items.item_code','change_order_items.existing_quantity','change_order_items.modified_quantity','change_order_requests.request_status','change_order_items.order_item_price')
                                ->offset($offset)->limit($limit)
                                ->get()->toArray();
        $table_code = View::make("components.change-order-request-component")
            ->with("change_orders", $change_order_requests)
            ->render(); 

        $page_data = ChangeOrderRequest::where('user_id',$user_id)
                                ->leftjoin('change_order_items','change_order_items.order_table_id','=','change_order_requests.id')
                                ->select('change_order_requests.order_no','change_order_requests.ordered_date','change_order_requests.created_at','change_order_items.item_code','change_order_items.existing_quantity','change_order_items.modified_quantity','change_order_requests.request_status','change_order_items.order_item_price')
                                ->paginate(intval($limit));
        
        $paginate = $page_data->toArray();

        $response  = [];
        $response['meta']['records'] = $paginate['total'];
        $path = '/getChangeOrderRequest';
        $offset = $offset + 1;
        $custom_pagination = self::CreatePaginationData($response,$limit,$page,$offset,$path);
        $pagination_code = "";
        if(!empty($response)){
            if($custom_pagination['last_page'] > 0){
                $pagination_code = View::make("components.ajax-pagination-component")
                ->with("pagination", $custom_pagination)
                ->render();
            }
        }
        echo json_encode(['success' => true, 'table_code' => $table_code,'pagination_code' => $pagination_code]);

    }

    public static function getPageNumberInUrl($url,$sym = '?'){
        $string = $url;
        $char = $sym;
        $pos = strpos($string, $char);
        if ($pos !== false) {
            $remaining_string = substr($string, $pos+1);
            if(preg_match('/^[0-9]+$/', $remaining_string)){
                return intval($remaining_string);
            } else {
                return self::getPageNumberInUrl($remaining_string,'=');
            }
        }
    }

    // analysis export
    public function analysisExport(Request $request){
        $data = $request->all();
        $range = $data['range'];
        $year = $data['year'];
        $type = $data['type'];
        $customer_no    = $request->session()->get('customer_no');
        $user_detail = UserDetails::where('customerno',$customer_no)->first();        
        $filter_dates = $this->getRangeDates($range,$year);
        $start_date = $filter_dates['start'];
        $end_date = $filter_dates['end'];
        $year1 = '';
        if($range == 0){
            $year1 = Carbon::parse($start_date)->addYear()->format('Y');
        } else {
            $year1 = Carbon::parse($start_date)->format('Y'); 
        }
        
        $start_date = Carbon::parse($start_date)->addDay()->format('Y-m-d');
        $end_date = Carbon::parse($end_date)->subDay()->format('Y-m-d');        
        $time_stamp = Carbon::now()->format('Ymd_his');
        $time_stamp = $time_stamp.'_'.$user_detail->id;
        $request_data = array('customer_no' =>  $customer_no,
                                'user_detail_id' => $user_detail->id,
                                'ardivisiono' => $user_detail->ardivisionno,
                                'start_date' => $start_date,
                                'end_date' => $end_date,
                                'year' => $year1,
                                'unique_id' => $time_stamp,
                                'type' => intval($type),
                                'status' => 0,
                                'request_body' => '',
                                'resource' => 'SalesOrderHistoryHeader',
                                'is_analysis' => 1);
                             
        $analysisRequest = AnalaysisExportRequest::create($request_data);

        if($analysisRequest){
            return json_encode(['success' => true,'message' => config('constants.analysis_message.message')]);
        }
        return json_encode(['success' => false]);
    }

    // Send Help
    public function sendHelp(Request $request){
        $data = $request->all();
        $customer_no    = $request->session()->get('customer_no');
        $user_detail = UserDetails::where('customerno',$customer_no)->first();

        $helpRequest = HelpRequest::create([
            'user_detail_id' => $user_detail->id,
            'name' => $data['name'],
            'email' => $data['email'],
            'phone_no' => $data['phone_no'],
            'message' => $data['message'],
        ]);
        $details['title']   = config('constants.email.help.title');   
        $details['subject'] = config('constants.email.help.subject');
        $details['status']    = 'success';
        $details['name'] = $data['name'];
        $details['email'] = $data['email'];
        $details['body']   = $data['message'];
        $details['link']            =  '';      
        $details['mail_view']       =  'emails.email-body';
        $admin_emails = config('app.admin_emails');
        $is_local = config('app.env') == 'local' ? true : false;
        if($is_local){
            UsersController::commonEmailSend($admin_emails,$details);
            Mail::bcc(explode(',',$admin_emails))->send(new \App\Mail\SendMail($details));
        } else {
            $admin_emails = Admin::all()->pluck('email')->toArray();
            UsersController::commonEmailSend($admin_emails,$details);
            // Mail::bcc($admin_emails)->send(new \App\Mail\SendMail($details));
        }
        if($helpRequest){
            echo json_encode(['success' => true, 'message' => config('constants.help_message.message')]);
            die();
        }
    }

    public function getChangeOrderInfo(Request $request,$order_id){
        $final_data['title']  = '';
        $final_data['current_menu']   = 'open-orders';
        $final_data['menus']          = $this->NavMenu('open-orders');
        $customers    = $request->session()->get('customers');
        $user_id = $customers[0]->user_id;
        $request_url = Request()->url();
        self::CustomerNotificationRemove($request_url,$user_id);
        $response = self::CustomerPageRestriction($user_id,$final_data['menus'],$final_data['current_menu']);
        if(!$response) return redirect()->back();
        $final_data['customer_menus'] = $response;
        $final_data['constants'] = config('constants');
        $searchWords = SearchWord::where('type',2)->get()->toArray();
        $final_data['searchWords']   = $searchWords;
        $order_request = ChangeOrderRequest::where('order_no',$order_id)->get()->first();
        $order_information = ChangeOrderItem::where('order_table_id',$order_request->id)->get();
        $final_data['order_request']   = $order_request;
        $final_data['order_information']   = $order_information;
        return view('Pages.change_order_info',$final_data);
    }

    // cancel change order
    public function cancelChangeOrder(Request $request){
        $data = $request->all();
        $order_no = $data['order_no'];
        $request_id = $data['request_id'];
        $change_order_request = ChangeOrderRequest::where('order_no',$order_no)->where('id',$request_id)->get()->first();
        if($change_order_request){
            $change_order_request->request_status = 2;
            $change_order_request->save();
            $response = [ 'success' => true, 'message' => config('constants.change_order_cancel.success')];
        } else {
            $response = [ 'success' => false, 'message' => config('constants.change_order_cancel.not_found')];
        }
        echo json_encode($response);
        die();
    }

    public static function CustomerNotificationRemove($url,$user_id){    
        $is_notification = Notification::where('to_user',$user_id)->where('action',$url)->where('status',1)->where('is_read',0)->first();
        if($is_notification){
            $is_notification->status = 0;
            $is_notification->is_read = 1;
            $is_notification->save();
        }

        return true;
    }
}
