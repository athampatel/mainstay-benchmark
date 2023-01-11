<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function invoicePage(){
        return view('pages.invoice');  
    }
    
    public function openOrdersPage(){
        return view('pages.open-orders');
    }
    
    public function changeOrderPage(){
        return view('pages.change-order');
    }
    
    public function vmiUserPage(){
        return view('pages.vmi-user');
    }
    
    public function analysisPage(){
        return view('pages.analysis');
    }
    
    public function accountSettingsPage(){
        return view('pages.account-settings');
    }
    
    public function helpPage(){
        return view('pages.help');
    }
}
