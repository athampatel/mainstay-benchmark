<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MenuController extends Controller
{

    public function NavMenu($current = ''){

       $menus = array('dashboard'           =>         array(  'name' => 'products & inventory', 
                                                                'icon_name' => file_get_contents(public_path('/assets/images/svg/products_gray.svg')), 
                                                                'active' => 0,  
                                                                'link'=> 'dashboard'),
                        'invoice'           =>          array(  'name' => 'invoiced orders', 
                                                                'icon_name' => file_get_contents(public_path('/assets/images/svg/invoice_order_gray.svg')),
                                                                'active' => 0,
                                                                'link'=> 'invoice'),
                        'open-orders'       =>          array(  'name' => 'open orders', 
                                                                'icon_name' => file_get_contents(public_path('/assets/images/svg/open_orders_gray.svg')),
                                                                'active' => 0,
                                                                'link'=> 'open-orders'),
                        'change-order'      =>          array(  'name' => 'change order', 
                                                                'icon_name' => file_get_contents(public_path('/assets/images/svg/change_order_gray.svg')),
                                                                'active' => 0,
                                                                'link'=> 'change-order'),
                        'vmi-user'          =>          array(  'name' => 'vmi user', 
                                                                'icon_name' => file_get_contents(public_path('/assets/images/svg/vmi_user_gray.svg')),
                                                                'active' => 0,
                                                                'link'=> 'VMI-User'),
                        'analysis'          =>          array(  'name' => 'analysis', 
                                                                'icon_name' => file_get_contents(public_path('/assets/images/svg/analysis_menu_gray.svg')),
                                                                'active' => 0,
                                                                'link'=> 'analysis'),
                        'account-settings'  =>          array(  'name' => 'account settings', 
                                                                'icon_name' => file_get_contents(public_path('/assets/images/svg/account_settings_gray.svg')),
                                                                'active' => 0,
                                                                'link'=> 'account-settings'),
                        'help'              =>          array(  'name' => 'help', 
                                                                'icon_name' => file_get_contents(public_path('/assets/images/svg/help_menu_gray.svg')),
                                                                'active' => 0,
                                                                'link'=> 'help'),
                        'logout'             =>          array( 'name' => 'logout', 
                                                                'icon_name' => file_get_contents(public_path('/assets/images/svg/logout_menu_gray.svg')),
                                                                'active' => 0,
                                                                'link'=> 'logout')
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
        $data['title']  = '';
        $data['current_menu']   = 'open-orders';
        $data['menus']          = $this->NavMenu('open-orders');
        return view('pages.open-orders',$data);
    }
    
    public function changeOrderPage(){
        $data['title']  = '';
        $data['current_menu']   = 'change-order';
        $data['menus']          = $this->NavMenu('change-order');
        
        return view('pages.change-order',$data);
    }
    
    public function vmiUserPage(){
        $data['title']  = '';
        $data['current_menu']   = 'vmi-user';
        $data['menus']          = $this->NavMenu('vmi-user');
        return view('pages.vmi-user');
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
        return view('pages.account-settings',$data);
    }
    public function helpPage(){
        $data['title']  = '';
        $data['current_menu']  = 'help';
        $data['menus']         = $this->NavMenu('help');
        return view('pages.help',$data);
    }
}
