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
use App\Models\UserSalesPersons;
use DateInterval;
use DateTime;
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
        $is_temp = Session()->get('is_temp');
        if($is_temp){
           // unset($menus['help']);
            unset($menus['logout']);            
          //  unset($menus['account-settings']);
        } 
        if($by_admin){
           /* unset($menus['help']);
            unset($menus['logout']);            
            unset($menus['account-settings']); */
            unset($menus['logout']);
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
        $is_temp        = $request->session()->get('temp_access');
        $customerDetails= null;
        $year           = date('Y');
       // if(empty($is_temp)){
            $user_id        = $customers[0]->user_id;
            $response = self::CustomerPageRestriction($user_id,$data['menus'],$data['current_menu']);
            if(!$response) return redirect()->back();

            $data['customer_menus'] = $response;

            $data['region_manager'] =  SalesPersons::select('sales_persons.*','admins.profile_path as profile','admins.phone_no')->leftjoin('user_sales_persons','sales_persons.id','=','user_sales_persons.sales_person_id')
                                                    ->leftjoin('user_details','user_sales_persons.user_details_id','=','user_details.id')->where('user_details.customerno',$customer_no)
                                                    ->leftjoin('admins','admins.email','=','sales_persons.email')
                                                    ->first();
       
            $customerDetails            = UserDetails::where('customerno',$customer_no)->where('user_id',$user_id)->first();            
            $data['constants']          = config('constants');
            // comment for api issue
       // }

        $saleby_productline1         = ProductLine::getSaleDetails($customerDetails,$year);
        if($saleby_productline1){
            $saleby_productline     = $saleby_productline1['sales_line']; 
            $saleby_productline_desc = $saleby_productline1['sales_desc_details']; 
        } else {
            $saleby_productline = [];
            $saleby_productline_desc = [];
        }
        $sale_map                   = array();
        $sale_map_desc              = array();
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

    public function switchAccount(request $request,$customer_no = ''){
        $customer   = $request->session()->get('customers');
        $current    = $request->session()->get('customer_no');
        if($customer_no != $current){
            foreach($customer as $_customer){
                if($_customer['customerno'] == $customer_no)
                    $request->session()->put('customer_no',$customer_no);
            }
        }
        
        // change the customer details
        $selected_customer = array();
        foreach($customer as $cs) {
            if($cs['customerno'] == $customer_no){
                $selected_customer = $cs;
                
            }
        }
        $user = User::where('id',$selected_customer['user_id'])->first();
        if($user->is_vmi){
            $data = array(            
                "filter" => [
                    [
                        "column"=>"customerno",
                        "type"=>"equals",
                        // "value"=>$customer[0]['customerno'],
                        "value"=>$customer_no,
                        "operator"=>"and"
                    ]
                ],
                "offset" => 1,
                "limit" => 1
            );

            $SDEAPi = new SDEApi();
            $response   = $SDEAPi->Request('post','Customers',$data);
            if(!empty($response)){
                if(!empty($response['customers'])){           
                    $request->session()->put('vmi_nextonsitedate',Carbon::createFromFormat('Y-m-d',$response['customers'][0]['vmi_nextonsitedate'])->format('d-m-Y'));            
                    $request->session()->put('vmi_physicalcountdate',Carbon::createFromFormat('Y-m-d', $response['customers'][0]['vmi_physicalcountdate'])->format('d-m-Y'));            
                }
            } 
        }
        $request->session()->put('selected_customer',$selected_customer);
        // return redirect()->route('auth.customer.dashboard');
        return back();
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
                "index" => "KSDEDESCENDING",           
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
            // dd($response);
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
            ->with("invoices", isset($response['salesorderhistoryheader']) ? $response['salesorderhistoryheader'] : [])
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
        $SDEAPi = new SDEApi();
        if($api_data){
            $time_now = date('Y-m-d h:i:s');
            $update_time = $api_data->updated_at->diffInMinutes($time_now);
            if($update_time <= 30){
                $is_data_fetch = false;
            }
        }

        if($is_data_fetch){
            $data = array(   
                "index" => "KSDEDESCENDING",         
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
            $response = isset($response['salesorders']) ? $response['salesorders'] : null;
        } else {
            $response = json_decode($api_data->data,true);
            $response = isset($response['salesorders']) ? $response['salesorders'] : null;
        }
        $open_orders = [];
        $year = date('Y');
        $order_sum  = array();
        $range      = 6;
        $filter_dates = $SDEAPi->getRangeDates($range,$year);

        $string_months  = isset($filter_dates['string_months']) ? $filter_dates['string_months']: null;
        $range_months   = isset($filter_dates['range_months']) ? $filter_dates['range_months']: null;
        $month_name     = isset($filter_dates['month_name']) ? $filter_dates['month_name']: null;
        $filter_start_date = $filter_dates['start'];
        $filter_end_date = $filter_dates['end'];


        foreach($response as $res){
           $orders_info = isset($res['details']) ? $res['details'] : null;
            if(!empty($orders_info)){
                foreach($orders_info as $order){
                  if($order['quantityordered'] > 0){
                    $promisedate    = date('mY',strtotime($order['promisedate']));
                    if(isset($open_orders[$promisedate])){
                        $open_orders[$promisedate] += $order['extensionamt'];
                    }else{
                        $open_orders[$promisedate] = $order['extensionamt'];
                    }
                  }
                }
            }

            /*$date = explode("-",$res['orderdate']);
            if($date[0] == $year){
                foreach($res['details'] as $detail){
                    if(isset($open_orders['0-'.$date[1]])){
                        $open_orders['0-'.$date[1]] = $open_orders['0-'.$date[1]] + ($detail['quantityordered'] *$detail['unitprice']);
                    } else {
                        $open_orders['0-'.$date[1]] = ($detail['quantityordered'] * $detail['unitprice']);
                    }
                }   
            }*/
        }
        
       
    
        $total = 0;
        $new_open_orders = [];
        foreach($month_name as $key => $_name){           
            $open_orders[$_name]    = isset($open_orders[$_name]) ? $open_orders[$_name] : 0;
            $total                  = $total + $open_orders[$_name];
            $new_open_orders[$key]  =  $open_orders[$_name];
        }
        echo json_encode(['success' => true, 'data' => ['data' => $new_open_orders,'count' => $total,'month' => $string_months],'error' => []]);
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
                "index" => "KSDEDESCENDING",
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
            $res['error'] = config('constants.invoice_order_detail.not_found');
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
        $view_type = isset($data['view_type']) ? intval($data['view_type']) : 1;
        $chart_type = isset($data['chart_type']) ? intval($data['chart_type']) : 0;
        $table_code =  $pagination_code = $new_data = $month_year = $sale_map = $sale_map_desc = $is_another_get = '';
        // $sed_pro_line = $data['prod_line'] ?? '';
        $item_code_search = $data['item_code_search'] ?? ''; 
        $search_by_itemcode = $data['is_search_by_itemcode'] ?? 0;
        $show_chart   = 0; 
        $response_table_data = [];
        $product_line_types = [];
        if($page == 0){
            $offset = 1;
        } else {
            $offset = $page * $limit + 1;
        }
        $customer_no    = $request->session()->get('customer_no');
        $user_id        = Auth::user()->id;
        $user_details   = UserDetails::where('user_id',$user_id)->where('customerno',$customer_no)->first();
        $response_table = [];
        $SDEAPi = new SDEApi();

        $filter_dates = $SDEAPi->getRangeDates($range,$year);
        $string_months  = isset($filter_dates['string_months']) ? $filter_dates['string_months']: null;
        $range_months   = isset($filter_dates['range_months']) ? $filter_dates['range_months']: null;
        $month_name     = isset($filter_dates['month_name']) ? $filter_dates['month_name']: null;
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
        $start_dates_array = explode('-',$filter_start_date);
        $end_dates_array = explode('-',$filter_end_date);
        $is_another_get = false;
        if($start_dates_array[0] != $end_dates_array[0]) {
            $is_another_get = true;
        } 
        $year_1 = "";
        if($is_another_get) {
            $year_1 = $start_dates_array[0];
        }
        
        if($view_type == 1 && $chart_type == 0){
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

            //print_r($response_table); die; 
        }

        // table display by product line

        if($view_type == 1 && $chart_type == 1){
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
                            "column" => "FiscalCalYear",
                            "type" => "equals",
                            "value" => $year,
                            "operator" => "and"
                        ],
                    ],
                    "method" => "GET",
                );
                if($item_code_search != '') {
                    // $column_name = intval($search_by_itemcode) == 0 ? 'itemcode' : 'aliasitemno';
                    $column_name = 'itemcode';
                    $product_line_filter = [
                        [
                        "column" => $column_name,
                        "type" => "equals",
                        "value" => $item_code_search,
                        "operator" => "and"
                        ]
                    ];
                    $data['filter'] = array_merge($product_line_filter,$data['filter']);
                }
                
                // dd($data);
                $response_table = $SDEAPi->Request('post','CustItemByPeriod',$data);
                if(!empty($response_table)){
                    $response_table_data = $response_table['custitembyperiod'];
                }
            }
            $filtered_new_response_data = [];
            if($item_code_search == '') {
                $new_response_table_data = [];
                foreach($response_table_data as $response_data) {
                    if(isset($new_response_table_data[$response_data['itemcode']])) {
                        $new_response_table_data[$response_data['itemcode']]['quantitysold'] += $response_data['quantitysold'];
                        $new_response_table_data[$response_data['itemcode']]['dollarssold'] += $response_data['dollarssold'];
                    } else {
                        $new_response_table_data[$response_data['itemcode']] = $response_data;
                    }
                }
                $new_reponse_table_data_1 = array_values($new_response_table_data);
                $for_end = $limit + ($offset - 1) < count($new_reponse_table_data_1) ? $limit + ($offset - 1) : count($new_reponse_table_data_1);
                for($i = $offset - 1; $i < $for_end; $i++) {
                    array_push($filtered_new_response_data,$new_reponse_table_data_1[$i]);
                }
                $filtered_new_response_data['meta']['records'] = count($new_reponse_table_data_1);
            } else {
                $filtered_new_response_data = $response_table_data;
                $filtered_new_response_data['meta']['records'] = count($response_table_data);
            }
            $is_item_search = $item_code_search != '' ? true : false; 
            $table_code = View::make("components.datatabels.analysis-page-product-line-component")
            ->with("analysisdata", $filtered_new_response_data)
            ->with("isItemSearch", $is_item_search)
            ->render();
            $path = '/get-analysis-page-data';
            $custom_pagination = self::CreatePaginationData($filtered_new_response_data,$limit,$page,$offset,$path);
            $pagination_code = "";
            if($custom_pagination['last_page'] >= 1){
                $pagination_code = View::make("components.ajax-pagination-component")
                ->with("pagination", $custom_pagination)
                ->render();
            } 
        }

        if($range == 4){
            $year = explode('-',$filter_start_date)[0];
        }

        $dataSalesHistory = array(
                                    array(  "column" => "ARDivisionNo",
                                            "type" => "equals",
                                            "value" => $user_details->ardivisionno,
                                            "operator" => "and"),
                                    array(  "column" =>  "CustomerNo",
                                            "type" =>  "equals",
                                            "value" =>  $user_details->customerno,
                                            "operator" =>  "and"));
        if($year_1 != $year){   
            
            $yearinfo =  array(  "column" =>  "FiscalYear",
                                "type" =>  ">=",
                                "value" =>  $year_1,
                                "operator" =>  "and");

            array_push($dataSalesHistory,$yearinfo);

            $year_new   =  array(  "column" =>  "FiscalYear",
                                    "type" =>  "<=",
                                    "value" =>  $year,
                                    "operator" =>  "and");                    

            array_push($dataSalesHistory,$year_new);

        }else{
            $yearinfo =  array(  "column" =>  "FiscalYear",
                                "type" =>  "equals",
                                "value" =>  $year,
                                "operator" =>  "and");

            array_push($dataSalesHistory,$yearinfo);                    
        }
        $data = array("filter" => $dataSalesHistory);
       
        //print_r($dataSalesHistory);

        $response_data1 = [];
        

        if($view_type == 2 && $chart_type == 0){
            $response_data   = $SDEAPi->Request('post','CustomerSalesHistory',$data);           
            $new_data = array();
            $month_year = array();
            $is_change = false;
            if(isset($response_data)){
                foreach($response_data['customersaleshistory'] as $resp2){
                    $_fiscalyear = $resp2['fiscalperiod'].$resp2['fiscalyear'];    
                    $_index  = '';                    
                    if(!empty($month_name) && in_array($_fiscalyear,$month_name) !== false){ 
                        $_index = array_search($_fiscalyear,$month_name);                            
                    }else if(!empty($range_months) && empty($month_name)){
                        $_index = array_search($resp2['fiscalperiod'],$range_months);
                    }
                    if($_index != ''){
                        $new_data[$_index] = $resp2;
                        $month_year[$_index] = self::convertMonthName($resp2['fiscalperiod']).'-'.$resp2['fiscalyear'];
                    }
                }
            }
        }
       
        if($chart_type == 1  && $view_type == 2){ // added $view_type            
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
                            if(is_array($v)) {
                                $total_val = $total_val + $v['value'];
                            } else {
                                $total_val = $total_val + $v;
                            }
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
                            if(is_array($v)){
                                $total_val = $total_val + $v['value'];
                            } else {
                                $total_val = $total_val + $v;
                            }
                        }
                    }                  
                    $sale_map_desc[] = array('label' => $key,'value' => $total_val);
                }
            }
        }

        if(!empty($month_year)){
            $dateval = '';
            foreach($range_months as $index => $_val){
                if(!isset($month_year[$index])){
                    $int_month = (int) $_val;
                    if($dateval == ''){
                        $dateval = date('Y',strtotime('01-'.$month_year[$index + 1]));
                    }
                    if(isset($string_months[$index]))
                        $month_year[$index] = $string_months[$index];
                    else
                        $month_year[$index] = date('M-Y',strtotime('01-'.$_val.'-'.$dateval));

                    $new_data[$index]   = array('fiscalyear' => 2023,'fiscalperiod' => $_val,'dollarssold' => 0);
                }
                $dateval = date('Y',strtotime('01-'.$_val));
            }
            ksort($month_year);
            ksort($new_data);
        }

        //print_r($new_data);
       // print_r($month_year);

        $res['is_export'] =  count($response_table_data) > 0 ? true : false;
        $res['table_code'] = $table_code;
        $res['pagination_code'] = $pagination_code;
        $res['analysis_data'] = $new_data;
        $res['range_months'] =  $filter_dates['range_months'];
        $res['range_months_year'] =  $month_year;
        $res['product_data'] = $sale_map;
        $res['product_data_desc'] = $sale_map_desc;
        $res['is_year_change'] = $is_another_get;
        $res['prod_line_types'] = $product_line_types;
        echo json_encode($res);
        die();
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
        $SDEAPi = new SDEApi();        
        
        $range = $data['range'];
        $year = $data['year'];
        $type = $data['type'];
        
        $customer_no    = $request->session()->get('customer_no');
        $user_detail = UserDetails::where('customerno',$customer_no)->first();
        $filter_dates = $SDEAPi->getRangeDates($range,$year);
        $start_date = $filter_dates['start'];
        $end_date = $filter_dates['end'];
        
        $year1 = '';
        if($range == 0){
            $year1 = Carbon::parse($start_date)->addYear()->format('Y');
        } else {
            $year1 = Carbon::parse($start_date)->format('Y'); 
        }
        
        if($range == 0) {
            $start_date = Carbon::parse($start_date)->format('Ymd');
            $end_date = Carbon::parse($end_date)->format('Ymd'); 
        } else if($range == 4) {
            $start_date = Carbon::parse($start_date)->addDay()->format('Ymd');
            $end_date = Carbon::parse($end_date)->subDay()->format('Ymd'); 
        } else {
            $start_date = Carbon::parse($start_date)->format('Ymd');
            $end_date = Carbon::parse($end_date)->subDay()->format('Ymd'); 
        }
        
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

        $is_local = config('app.env') == 'local' ? true : false;
        $email = $user_detail->email;
        if($is_local){
            $email = config('app.support_email');
        }
        // api request 
        $data = array(            
            "JobName" => "INVOICEHISTORY",
            "detail" => [
                [
                    "lineKey" => "000001",
                    "ReportSetting" =>  "SDE_VMI",
                    "EmailAddress" =>  $email,
                    "filter" =>  [
                        [
                            "column" => "CustomerNo",
                            "type" => "equals",
                            "value" => $user_detail->customerno,
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
                    ]
                ]
            ],
        );
        $response   = $SDEAPi->Request('post','ScheduledTask',$data);
        if(!empty($response)){
            if(isset($response['action']) && $response['action'] == 'executed') {
                return ['success' => true,'icon' => 'success','title' => 'Request Sent','message' => 'The sales report for invoiced orders has been sent. You will receive a notification once it has been generated.'];
            }
        }

        return ['success' => false, 'icon' => 'error', 'title' => 'Something Went Wrong','message' => 'Please try again in a few minutes.'];
        // if($analysisRequest){
        //     return json_encode(['success' => true,'message' => config('constants.analysis_message.message')]);
        // }
        // return json_encode(['success' => false]);
    }

    // Send Help
    public function sendHelp(Request $request){
        $data = $request->all();
        $customer_no    = $request->session()->get('customer_no');
        $user_detail = UserDetails::where('customerno',$customer_no)->first();
        // dd($user_detail);
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

        $regional_manager_email = "";
        if($user_detail->salesPerson){
            if($user_detail->salesPerson->salesPerson) {
                if($user_detail->salesPerson->salesPerson->email){
                    $regional_manager_email = $user_detail->salesPerson->salesPerson->email;
                }
            }
        }   
        if($is_local){
            $regional_manager_email = config('app.manager_emails');
            UsersController::commonEmailSend($admin_emails,$details);
            UsersController::commonEmailSend($regional_manager_email,$details);
        } else {
            // $admin_emails = Admin::all()->pluck('email')->toArray();
            $admin_emails = SDEApi::getHasPermissionEmailAddress('help.request');
            UsersController::commonEmailSend($admin_emails,$details);
            if($regional_manager_email){
                UsersController::commonEmailSend($regional_manager_email,$details);
            }
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

    public static function convertMonthName($monthNumber){
        $dateTime = DateTime::createFromFormat('d-m', '01-'.$monthNumber);
        $monthName = $dateTime->format('M');
        return $monthName;
    }
}
