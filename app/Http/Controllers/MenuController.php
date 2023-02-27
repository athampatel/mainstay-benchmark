<?php

namespace App\Http\Controllers;

use App\Models\UserDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\SDEApi;
use App\Models\ApiData;
use App\Models\ApiType;
use App\Models\CustomerMenu;
use App\Models\CustomerMenuAccess;
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
                        'vmi-user'          =>          array(  'name' => 'vmi user', 
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

    public function dashboard(Request $request){
        $data['title']  = '';
        $data['current_menu']   = 'dashboard';
        $data['menus']          = $this->NavMenu('dashboard');

        $customer_no    = $request->session()->get('customer_no');
        $customers    = $request->session()->get('customers');
        $user_id = $customers[0]->user_id;
        $customer_menu_access = CustomerMenuAccess::where('user_id',$user_id)->first();
        if($customer_menu_access){
            $menu_access = explode(',',$customer_menu_access->access);
            $data['customer_menus'] = CustomerMenu::whereIn('id',$menu_access)->pluck('code')->toArray();
            if(!in_array($data['menus']['dashboard']['code'],$data['customer_menus'])){
                return redirect()->back();
            }
        } else {
            $data['customer_menus'] = [];
        }

        $data['region_manager'] =  SalesPersons::select('sales_persons.*')->leftjoin('user_sales_persons','sales_persons.id','=','user_sales_persons.sales_person_id')
                                                ->leftjoin('user_details','user_sales_persons.user_details_id','=','user_details.id')->where('user_details.customerno',$customer_no)->first();
        // dd($data['region_manager']);
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
        $customer_menu_access = CustomerMenuAccess::where('user_id',$user_id)->first();
        if($customer_menu_access){
            $menu_access = explode(',',$customer_menu_access->access);
            $data['customer_menus'] = CustomerMenu::whereIn('id',$menu_access)->pluck('code')->toArray();
            if(!in_array($data['menus']['invoice']['code'],$data['customer_menus'])){
                return redirect()->back();
            }
        } else {
            $data['customer_menus'] = [];
        }
        return view('Pages.invoice',$data);  
    }
    
    public function openOrdersPage(Request $request){
        $final_data['title']  = '';
        $final_data['current_menu']   = 'open-orders';
        $final_data['menus']          = $this->NavMenu('open-orders');
        $customers    = $request->session()->get('customers');
        $user_id = $customers[0]->user_id;
        $customer_menu_access = CustomerMenuAccess::where('user_id',$user_id)->first();
        if($customer_menu_access){
            $menu_access = explode(',',$customer_menu_access->access);
            $final_data['customer_menus'] = CustomerMenu::whereIn('id',$menu_access)->pluck('code')->toArray();
            if(!in_array($final_data['menus']['open-orders']['code'],$final_data['customer_menus'])){
                return redirect()->back();
            }
        } else {
            $final_data['customer_menus'] = [];
        }
        return view('Pages.open-orders',$final_data);
    }
    
    public function changeOrderPage(Request $request,$orderid){           
        // if()
        if(Auth::user()->is_vmi == 1){
            $final_data['title']  = '';
            $final_data['current_menu']   = 'change-order';
            $final_data['menus']          = $this->NavMenu('change-order');
            $user = User::find(Auth::user()->id);

            $final_data['order_id'] = $orderid;
            $final_data['user'] = $user;
            $customer_no   = $request->session()->get('customers');
            $customers    = $request->session()->get('customers');
            $user_id = $customers[0]->user_id;
            $customer_menu_access = CustomerMenuAccess::where('user_id',$user_id)->first();
            if($customer_menu_access){
                $menu_access = explode(',',$customer_menu_access->access);
                $final_data['customer_menus'] = CustomerMenu::whereIn('id',$menu_access)->pluck('code')->toArray();
                if(!in_array($final_data['menus']['change-order']['code'],$final_data['customer_menus'])){
                    return redirect()->back();
                }
            } else {
                $final_data['customer_menus'] = [];
            }
            $final_data['user_detail'] = UserDetails::where('user_id',$user->id)->where('customerno',$customer_no)->first();
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
        $customer_menu_access = CustomerMenuAccess::where('user_id',$user_id)->first();
        if($customer_menu_access){
            $menu_access = explode(',',$customer_menu_access->access);
            $data['customer_menus'] = CustomerMenu::whereIn('id',$menu_access)->pluck('code')->toArray();
            if(!in_array($data['menus']['vmi-user']['code'],$data['customer_menus'])){
                return redirect()->back();
            }
        } else {
            $data['customer_menus'] = [];
        }
        return view('Pages.vmi-user',$data);
    }

    // analysis page
    public function analysisPage(Request $request){
        $data['title']  = '';
        $data['current_menu']   = 'analysis';
        $data['menus']          = $this->NavMenu('analysis');
        $customers    = $request->session()->get('customers');
        $user_id = $customers[0]->user_id;
        $customer_menu_access = CustomerMenuAccess::where('user_id',$user_id)->first();
        if($customer_menu_access){
            $menu_access = explode(',',$customer_menu_access->access);
            $data['customer_menus'] = CustomerMenu::whereIn('id',$menu_access)->pluck('code')->toArray();
            if(!in_array($data['menus']['analysis']['code'],$data['customer_menus'])){
                return redirect()->back();
            }
        } else {
            $data['customer_menus'] = [];
        }
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
        $customer_menu_access = CustomerMenuAccess::where('user_id',$user_id)->first();
        if($customer_menu_access){
            $menu_access = explode(',',$customer_menu_access->access);
            $data['customer_menus'] = CustomerMenu::whereIn('id',$menu_access)->pluck('code')->toArray();
            if(!in_array($data['menus']['account-settings']['code'],$data['customer_menus'])){
                return redirect()->back();
            }
        } else {
            $data['customer_menus'] = [];
        }
        $data['user_detail'] = UserDetails::where('user_id',$user_id)->where('customerno',$customer_no)->first();
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
        $customer_menu_access = CustomerMenuAccess::where('user_id',$user_id)->first();
        if($customer_menu_access){
            $menu_access = explode(',',$customer_menu_access->access);
            $data['customer_menus'] = CustomerMenu::whereIn('id',$menu_access)->pluck('code')->toArray();
            if(!in_array($data['menus']['help']['code'],$data['customer_menus'])){
                return redirect()->back();
            }
        } else {
            $data['customer_menus'] = [];
        }
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
            
            if($custom_pagination['last_page'] > 1){
                $pagination_code = View::make("components.ajax-pagination-component")
                ->with("pagination", $custom_pagination)
                ->render();
            } else {
                $pagination_code = '';
            }
            $change_order_menu_id = CustomerMenu::where('code','auth.customer.change-order')->pluck('id')->toArray();
            $change_order_menu_id = !empty($change_order_menu_id) ? $change_order_menu_id[0] : 0;
            $customers    = $request->session()->get('customers');
            $user_id = $customers[0]->user_id;
            $customer_menu_access = CustomerMenuAccess::where('user_id',$user_id)->first();
            $is_change_order = false;
            if($customer_menu_access){
                $menu_access = explode(',',$customer_menu_access->access);
                if(in_array($change_order_menu_id,$menu_access)){
                    $is_change_order = true;
                } 
            }
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
            if($custom_pagination['last_page'] > 1){
                $pagination_code = View::make("components.ajax-pagination-component")
                ->with("pagination", $custom_pagination)
                ->render();
            }
    
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

    public function getCustomerOpenOrdersData(Request $request){
        $customer = $request->session()->get('customers');
        $customer_no = $request->session()->get('customer_no');
        $type = ApiType::where('name','salesorders')->first();
        $api_data = ApiData::where('customer_no',$customer_no)->where('type',$type->id)->first();
        if($api_data){
            $response = json_decode($api_data->data);
            $count_total = 0;
            $test_counts = [];
            foreach($response as $res){
                $date = explode("-",$res->orderdate);
                if($date[0] == '2022'){
                    if(isset($test_counts['0-'.$date[1]])){
                        $test_counts['0-'.$date[1]] = $test_counts['0-'.$date[1]] + $res->total_amount;
                    } else {
                        $test_counts['0-'.$date[1]] = $res->total_amount;
                    }
                }
                $count_total = $count_total + $res->total_amount; 
            }
            $new_open_orders = [];
            for($i = 1; $i <= 12 ; $i++){
                $num = $i < 10 ? '0'.$i : $i;
                $test_counts['0-'.$num] = isset($test_counts['0-'.$num]) ?  $test_counts['0-'.$num] : 0;
                $new_open_orders[] = $test_counts['0-'.$num];
            }
            echo json_encode(['success' => true, 'data' => ['data' => $new_open_orders,'count' => $count_total],'error' => []]);
            die(); 
        } else {
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
            $response = $response['salesorders'];
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
    }

}
