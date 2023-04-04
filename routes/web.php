<?php

use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Backend\AdminsController;
use App\Http\Controllers\Backend\RolesController;
use App\Http\Controllers\Backend\UsersController;
use App\Http\Controllers\CustomerExportController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\SDEDataController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\SchedulerLogController;
use App\Http\Controllers\InvoicedOrdersController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\SaleOrdersController;
use Illuminate\Support\Facades\Mail;



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

/*Route::get('/dashboard', function () {    
    return view('pages.dashboard');
})->middleware(['auth'])->name('dashboard'); */

// menu routes
Route::middleware('auth')->group(function () {  
    Route::get('/dashboard',[MenuController::class,'dashboard'])->name('auth.customer.dashboard');
    Route::get('/invoice',[MenuController::class,'invoicePage'])->name('auth.customer.invoice');
    Route::get('/open-orders',[MenuController::class,'openOrdersPage'])->name('auth.customer.open-orders');
    // Route::get('/change-order',[MenuController::class,'changeOrderPage']);
    Route::get('/vmi-user',[MenuController::class,'vmiUserPage'])->name('auth.customer.vmi');
    Route::get('/analysis',[MenuController::class,'analysisPage'])->name('auth.customer.analysis');
    Route::get('/account-settings',[MenuController::class,'accountSettingsPage'])->name('auth.customer.account-settings');
    Route::get('/help',[MenuController::class,'helpPage'])->name('auth.customer.help');

    
    // chart routes
    Route::get('/customersales',[SDEDataController::class,'getCustomerSalesHistory']);
    Route::get('/customer-invoice-orders',[SDEDataController::class,'getCustomerInvoiceOrders']);
    Route::post('/order-detail',[SDEDataController::class,'getSalesOrderDetail']);

    Route::get('/customer-sales',[SaleOrdersController::class,'getSaleByYear']);

    



    // open orders
    Route::get('/customer-open-orders-details',[SDEDataController::class,'getCustomerOpenOrdersDetails']);
    Route::get('/change-order/{orderid}',[MenuController::class,'changeOrderPage'])->name('auth.customer.change-order');
    Route::post('/change_order_items_save',[SDEDataController::class,'changeOrderPageSave']);

    // photo upload
    // Route::post('/account_edit_upload',[SDEDataController::class,'profilePicUpload']);
    Route::post('/account_edit_upload',[SDEDataController::class,'accountEditUpload']);
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
    //Route::get('/user/{id}/change-status',[SDEDataController::class,'changeUserStatus']);
    Route::get('/user/{id}/active',[SDEDataController::class,'changeUserActive']);
    Route::get('/user/{id}/cancel',[SDEDataController::class,'changeUserCancel']);


    Route::get('/getOpenOrders',[MenuController::class,'getOpenOrders']);

    Route::get('/getInvoiceOrders',[MenuController::class,'getInvoiceOrders']);

    // get analysis page data
    Route::get('/get-analysis-page-data',[MenuController::class,'getAnalysisPageData']);

    Route::get('/switch-account/{account}',[MenuController::class,'switchAccount']);

    // get customer open orders data
    Route::get('/getCustomerOpenOrders',[MenuController::class,'getCustomerOpenOrdersData']);
    // get Vmi page data
    Route::get('/getVmiData',[MenuController::class,'getVmiData']);

    Route::get('/invoice-detail/{orderid}',[MenuController::class,'showInvoiceDetail']);
    Route::post('/invoice-order-detail',[MenuController::class,'getInvoiceDetail']);

    // change order request page
    Route::get('/requests/change_orders',[MenuController::class,'getChangeOrderRequests']);
    Route::get('/getChangeOrderRequest',[MenuController::class,'getAllChangeRequests']);

    // change order info 
    Route::get('/change-order/info/{order_id}',[MenuController::class,'getChangeOrderInfo']);
    Route::post('/cancelChangeRequest',[MenuController::class,'cancelChangeOrder']);

    // export data in analysis page
    Route::post('/exportAnalysis',[MenuController::class,'analysisExport']);

    // help message send
    Route::post('/sendHelp',[MenuController::class,'sendHelp']);

    // exports

    // invoice
    Route::get('/invoice-export/csv',[CustomerExportController::class,'exportInvoiceCsv']);
    Route::get('/invoice-export/pdf',[CustomerExportController::class,'exportInvoicePdf']);
    
    // open orders
    Route::get('/open-export/csv',[CustomerExportController::class,'exportOpenCsv']);
    Route::get('/open-export/pdf',[CustomerExportController::class,'exportOpenPdf']);
    
    // vmi page
    Route::get('/vmi-page-export/csv',[CustomerExportController::class,'exportVmiCsv']);
    Route::get('/vmi-page-export/pdf',[CustomerExportController::class,'exportVmiPdf']);

    // request
    Route::post('/exportInvoiceOrders',[CustomerExportController::class,'invoiceRequest']);
    Route::post('/exportOpenOrders',[CustomerExportController::class,'openOrdersRequest']);
    Route::post('/exportVmiUser',[CustomerExportController::class,'vmiUserRequest']);
});

Route::get('/get-notifications',[NotificationController::class,'getNotifications']);
Route::post('/get-notifications',[NotificationController::class,'getNotifications']);

Route::post('/get-bottom-notifications',[NotificationController::class,'getBottomNotifications']);
Route::post('/get-new-bottom-notifications',[NotificationController::class,'getNewBottomNotifications']);
Route::post('/notification-seen',[NotificationController::class,'changeNotificationStatus']);

Route::post('/logout', '\App\Http\Controllers\AuthController@logout')->name('admin.logout.submit');
Route::get('/autheticate',[AuthController::class,'autheticate']);


/**LOG USED FOR TESTTNNG PURPOSE */

// Route::get('/products/fetch-sde-manual',[ProductsController::class,'getProducts'])->name('fectch-products');
// Route::get('/products/import-sde-manual',[ProductsController::class,'CheckUpdateProducts'])->name('import-products');

// Route::get('/invoiced-orders/fetch-sde-manual',[SchedulerLogController::class,'runScheduler'])->name('fectch-invoice');
// Route::get('/invoiced-orders/invoice-sde-manual',[InvoicedOrdersController::class,'getInvoiceOrders'])->name('import-invoice');

    //$invoice = new InvoicedOrdersController();
        //$invoice->getInvoiceOrders();
        //die; 

/**
 * Admin routes
 */
Route::redirect('/login', '/sign-in');
Route::group(['prefix' => 'admin'], function () {
    Route::get('/', '\App\Http\Controllers\Backend\DashboardController@index')->name('admin.dashboard');

    Route::get('/admins/manager', '\App\Http\Controllers\Backend\UsersController@UserManagers')->name('admin.admins.manager');

    Route::resource('roles', '\App\Http\Controllers\Backend\RolesController', ['names' => 'admin.roles']);


    Route::get('/customers/inventory/{userId}', '\App\Http\Controllers\Backend\UsersController@CustomerInventory')->name('admin.users.inventory');


    Route::get('/customers/change-orders', '\App\Http\Controllers\Backend\UsersController@CustomerChangeOrders')->name('admin.users.change-order');

    Route::get('/customers/exports',[UsersController::class,'customerExports'])->name('admin.users.exports');

    // customer export info
    Route::get('/customer/request/{id}',[UsersController::class,'customerExportInfo']);

    Route::get('/customers/change-orders/{order_id}', '\App\Http\Controllers\Backend\UsersController@CustomerChangeOrderDetails')->name('admin.users.change-order-view');

    Route::resource('customers', '\App\Http\Controllers\Backend\UsersController', ['names' => 'admin.users']);
    
    Route::get('/getAllCustomers',[UsersController::class,'getAllCustomers']);
    //Route::resource('customers', '\App\Http\Controllers\Backend\CustomerController', ['names' => 'admin.customer']);

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

    Route::get('/user/{user_id}/change-status/',[UsersController::class,'getUserRequest']);
    
    // user activate
    Route::post('/user/activate',[UsersController::class,'getUserActive']);
    // user decline
    Route::post('/user/cancel',[UsersController::class,'getUserCancel']);
    // change order request
   
    Route::post('/order/request/{change_id}/change',[AdminOrderController::class,'changeOrderRequestStatus']);
    Route::post('/order/request/{change_id}/sync',[AdminOrderController::class,'changeOrderRequestSync']);


    // export all customers
    Route::get('/exportAllCustomers',[UsersController::class,'ExportAllCustomers']);
    Route::get('/exportAllExports',[UsersController::class,'ExportAllExports']);


    Route::get('/signup-request', '\App\Http\Controllers\Backend\UsersController@UserAccessRequest')->name('admin.users.requests');

    // profiles
    Route::get('/profile',[AdminsController::class,'adminProfile']);
    Route::post('/profile-save',[AdminsController::class,'adminProfileSave']);

    // Export pdf file
    // Customers
    Route::get('/exportAllCustomerInPdf',[UsersController::class,'ExportAllCustomerInPdf']);
    // user roles
    Route::get('/exportAllUserRolesInpdf',[RolesController::class,'ExportAllRolesInPdf']);
    // admins
    Route::get('/exportAllAdminsInPdf',[AdminsController::class,'ExportAllAdminsToPdf']);
    // managers
    Route::get('/exportAllManagersInPdf',[UsersController::class,'ExportAllManagersToPdf']);
    // change order
    Route::get('/exportAllChangeOrdersInPdf',[UsersController::class,'ExportAllOrdersToPdf']);
    // User Signup Request
    Route::get('/exportAllSignupInPdf',[UsersController::class,'ExportAllSignupToPdf']);
    //  export requests
    Route::get('/exportAllExportsInPdf',[UsersController::class,'ExportAllExportsPdf']);
    
    // csv
    
    // Change orders
    Route::get('/exportAllChangeOrdersInExcel',[UsersController::class,'ExportAllOrdersToExcel']);
    // Managers
    Route::get('/exportAllManagersInExcel',[UsersController::class,'ExportAllManagersToExcel']);
    // Admins
    Route::get('/exportAllAdminsInExcel',[AdminsController::class,'ExportAllAdminsToExcel']);
    // signup
    Route::get('/exportAllSignupsInExcel',[UsersController::class,'ExportAllSignupToExcel']);
    // Roles
    Route::get('/exportAllUserRolesInExcel',[RolesController::class,'ExportAllRolesInExcel']);

    // inventory item
    Route::post('/getAdminVmiItem',[UsersController::class,'GetInventoryItem']);
    // update inventory item
    Route::post('/update_inventory_item',[UsersController::class,'UpdateInventoryItem']);

    // get Users Vmi Data
    Route::get('/getAdminVmiData',[UsersController::class,'GetUserVmiData']);
    // Save Users Vmi data
    Route::post('/saveAdminVmiData',[UsersController::class,'SaveUserVmiData']);
    // download vmi inventory
    Route::get('/ExportVmiInventory',[UsersController::class,'ExportVmiInventory']);
}); 


// Export route

Route::resource('/salesOrderExportResponse',ExportController::class);

require __DIR__.'/auth.php';
