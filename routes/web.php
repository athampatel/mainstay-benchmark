<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Backend\UsersController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\SDEDataController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/', function () {
    return redirect('/sign-in');
});

Route::get('/delete',[AuthController::class,'delete']);

Route::get('/dashboard', function () {
    // return view('dashboard');
    // return view('layouts.dashboard');
    return view('pages.dashboard');
})->middleware(['auth'])->name('dashboard');

// menu routes
Route::middleware('auth')->group(function () {  
    Route::get('/invoice',[MenuController::class,'invoicePage']);
    Route::get('/open-orders',[MenuController::class,'openOrdersPage']);
    Route::get('/change-order',[MenuController::class,'changeOrderPage']);
    Route::get('/vmi-user',[MenuController::class,'vmiUserPage']);
    Route::get('/analysis',[MenuController::class,'analysisPage']);
    Route::get('/account-settings',[MenuController::class,'accountSettingsPage']);
    Route::get('/help',[MenuController::class,'helpPage']);
    // test routes
    Route::get('/customersales',[SDEDataController::class,'getCustomerSalesHistory']);
    // test api checks
    Route::get('/alias-item',[SDEDataController::class,'getAliasItems']);
    Route::get('/customers',[SDEDataController::class,'getCustomers']);
    Route::get('/invoice-history-detail',[SDEDataController::class,'getInvoiceHistoryDetail']);
    Route::get('/invoice-history-header',[SDEDataController::class,'getInvoiceHistoryHeader']);
    Route::get('/item-warehouses',[SDEDataController::class,'getItemWarehouses']);
    Route::get('/products',[SDEDataController::class,'getProducts']);
    Route::get('/sales-order-history-detail',[SDEDataController::class,'getSalesOrderHistoryDetail']);
    Route::get('/sales-order-history-header',[SDEDataController::class,'getSalesOrderHistoryHeader']);
    Route::get('/sales-persons',[SDEDataController::class,'getSalespersons']);
    Route::get('/vendors',[SDEDataController::class,'getVendors']);

    //change user status
    // Route::get('/user/{id}/change-status',[SDEDataController::class,'changeUserStatus']);
    Route::get('/user/{id}/active',[SDEDataController::class,'changeUserActive']);
    Route::get('/user/{id}/cancel',[SDEDataController::class,'changeUserCancel']);
});

Route::get('/autheticate',[AuthController::class,'autheticate']);


//Route::get('/email-view', function () {
  //  return view('emails/email-body');
//});



/**
 * Admin routes
 */

Route::group(['prefix' => 'admin'], function () {
    Route::get('/', '\App\Http\Controllers\Backend\DashboardController@index')->name('admin.dashboard');
    Route::resource('roles', '\App\Http\Controllers\Backend\RolesController', ['names' => 'admin.roles']);
    Route::resource('users', '\App\Http\Controllers\Backend\UsersController', ['names' => 'admin.users']);
    Route::resource('customers', '\App\Http\Controllers\Backend\CustomerController', ['names' => 'admin.customer']);
    Route::resource('admins', '\App\Http\Controllers\Backend\AdminsController', ['names' => 'admin.admins']);


    // Login Routes
    Route::get('/login', '\App\Http\Controllers\Backend\Auth\LoginController@showLoginForm')->name('admin.login');
    Route::post('/login/submit', '\App\Http\Controllers\Backend\Auth\LoginController@login')->name('admin.login.submit');

    // Logout Routes
    Route::post('/logout/submit', '\App\Http\Controllers\Backend\Auth\LoginController@logout')->name('admin.logout.submit');

    // Forget Password Routes
    Route::get('/password/reset', '\App\Http\Controllers\Backend\Auth\ForgetPasswordController@showLinkRequestForm')->name('admin.password.request');
    Route::post('/password/reset/submit', '\App\Http\Controllers\Backend\Auth\ForgetPasswordController@reset')->name('admin.password.update');

    // get customer information
    Route::post('/get_customer_info',[UsersController::class,'getCustomerInfo']);
    // link 
    Route::get('/user/{user_id}/change-status/{admin_token}',[UsersController::class,'getUserRequest']);
    // user activate
    Route::post('/user/activate',[UsersController::class,'getUserActive']);
    // user decline
    Route::post('/user/cancel',[UsersController::class,'getUserCancel']);
}); 

Route::get('send-mail', function () {
   
    $details = [
        'title' => 'Mail from BenchMark Products',
        'body' => 'This is for testing email using smtp',
		'link' => '####'
    ];
   
    \Mail::to('atham@tendersoftware.in')->send(new \App\Mail\SendMail($details));
   
    dd("Email is Sent.");
});

require __DIR__.'/auth.php';
