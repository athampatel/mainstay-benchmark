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
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;
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

Route::redirect('/', '/sign-in');
Route::get('/delete',[AuthController::class,'delete']);

Route::middleware(['auth','checkAdminPrefix'])->group(function () {  
    Route::get('/dashboard',[MenuController::class,'dashboard'])->name('auth.customer.dashboard');
    Route::get('/invoice',[MenuController::class,'invoicePage'])->name('auth.customer.invoice');
    Route::get('/open-orders',[MenuController::class,'openOrdersPage'])->name('auth.customer.open-orders');
    Route::get('/vmi-user',[MenuController::class,'vmiUserPage'])->name('auth.customer.vmi');
    Route::get('/analysis',[MenuController::class,'analysisPage'])->name('auth.customer.analysis');
    Route::get('/analysis/{year}',[MenuController::class,'analysisPage'])->name('auth.customer.analysis');
    Route::get('/account-settings',[MenuController::class,'accountSettingsPage'])->name('auth.customer.account-settings');
    Route::get('/help',[MenuController::class,'helpPage'])->name('auth.customer.help');
    Route::get('/customersales',[SDEDataController::class,'getCustomerSalesHistory']);
    Route::get('/customer-invoice-orders',[SDEDataController::class,'getCustomerInvoiceOrders']);
    Route::post('/order-detail',[SDEDataController::class,'getSalesOrderDetail']);
    Route::get('/customer-sales',[SaleOrdersController::class,'getSaleByYear']);
    Route::get('/customer-open-orders-details',[SDEDataController::class,'getCustomerOpenOrdersDetails']);
    Route::get('/change-order/{orderid}',[MenuController::class,'changeOrderPage'])->name('auth.customer.change-order');
    Route::post('/change_order_items_save',[SDEDataController::class,'changeOrderPageSave']);
    Route::post('/account_edit_upload',[SDEDataController::class,'accountEditUpload']);
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
    Route::get('/user/{id}/active',[SDEDataController::class,'changeUserActive']);
    Route::get('/user/{id}/cancel',[SDEDataController::class,'changeUserCancel']);
    Route::get('/getOpenOrders',[MenuController::class,'getOpenOrders']);
    Route::get('/getInvoiceOrders',[MenuController::class,'getInvoiceOrders']);
    Route::get('/get-analysis-page-data',[MenuController::class,'getAnalysisPageData']);
    Route::get('/switch-account/{account}',[MenuController::class,'switchAccount']);
    Route::get('/getCustomerOpenOrders',[MenuController::class,'getCustomerOpenOrdersData']);
    Route::get('/getVmiData',[MenuController::class,'getVmiData']);
    Route::get('/invoice-detail/{orderid}',[MenuController::class,'showInvoiceDetail']);
    Route::post('/invoice-order-detail',[MenuController::class,'getInvoiceDetail']);
    Route::get('/requests/change_orders',[MenuController::class,'getChangeOrderRequests']);
    Route::get('/getChangeOrderRequest',[MenuController::class,'getAllChangeRequests']);
    Route::get('/change-order/info/{order_id}',[MenuController::class,'getChangeOrderInfo']);
    Route::post('/cancelChangeRequest',[MenuController::class,'cancelChangeOrder']);
    Route::post('/exportAnalysis',[MenuController::class,'analysisExport']);
    Route::post('/sendHelp',[MenuController::class,'sendHelp']);
    Route::get('/invoice-export/csv',[CustomerExportController::class,'exportInvoiceCsv']);
    Route::post('/invoice-export/pdf',[CustomerExportController::class,'exportInvoicePdf']);
    Route::get('/open-export/csv',[CustomerExportController::class,'exportOpenCsv']);
    Route::get('/open-export/pdf',[CustomerExportController::class,'exportOpenPdf']);
    Route::get('/vmi-page-export/csv',[CustomerExportController::class,'exportVmiCsv']);
    Route::get('/vmi-page-export/pdf',[CustomerExportController::class,'exportVmiPdf']);
    Route::post('/exportInvoiceOrders',[CustomerExportController::class,'invoiceRequest']);
    Route::post('/exportOpenOrders',[CustomerExportController::class,'openOrdersRequest']);
    Route::post('/exportVmiUser',[CustomerExportController::class,'vmiUserRequest']);
    Route::get('/change_order_status/{id}',[MenuController::class,'changeMessageDisplay']);
});

Route::get('/get-notifications',[NotificationController::class,'getNotifications']);
Route::post('/get-notifications',[NotificationController::class,'getNotifications']);
Route::post('/get-bottom-notifications',[NotificationController::class,'getBottomNotifications']);
Route::post('/get-new-bottom-notifications',[NotificationController::class,'getNewBottomNotifications']);
Route::post('/notification-seen',[NotificationController::class,'changeNotificationStatus']);
Route::post('/logout', '\App\Http\Controllers\AuthController@logout')->name('admin.logout.submit');
Route::get('/autheticate',[AuthController::class,'autheticate']);
Route::post('/check-auth',[AuthController::class,'checkAuth']);
/**LOG USED FOR TESTTNNG PURPOSE */
// Route::get('/products/fetch-sde-manual',[ProductsController::class,'getProducts'])->name('fectch-products');
// Route::get('/products/import-sde-manual',[ProductsController::class,'CheckUpdateProducts'])->name('import-products');
// Route::get('/invoiced-orders/fetch-sde-manual',[SchedulerLogController::class,'runScheduler'])->name('fectch-invoice');
// Route::get('/invoiced-orders/invoice-sde-manual',[InvoicedOrdersController::class,'getInvoiceOrders'])->name('import-invoice');
//$invoice = new InvoicedOrdersController();
//$invoice->getInvoiceOrders();
/**
 * Admin routes
 */
Route::redirect('/login', '/sign-in');
Route::group(['prefix' => 'admin'], function () {
    Route::get('/login', '\App\Http\Controllers\Backend\Auth\LoginController@showLoginForm')->name('admin.login');
    Route::post('/login/submit', '\App\Http\Controllers\Backend\Auth\LoginController@login')->name('admin.login.submit');
    Route::get('/password/reset', '\App\Http\Controllers\Backend\Auth\ForgetPasswordController@showLinkRequestForm')->name('admin.password.request');
    Route::post('/password/reset/submit', '\App\Http\Controllers\Backend\Auth\ForgetPasswordController@reset')->name('admin.password.update');
    Route::get('/customers/{id}/login/{user_detail_id}',[UsersController::class,'customerLogin'])->name('admin.users.login');
    Route::get('/back_to_admin',[UsersController::class,'adminLogin']);
});

Route::group(['prefix' => 'admin','middleware' => ['auth:admin','checkAdminPrefix']], function () {    
    Route::get('/', '\App\Http\Controllers\Backend\DashboardController@index')->name('admin.dashboard'); //
    Route::get('/admins/manager', '\App\Http\Controllers\Backend\UsersController@UserManagers')->name('admin.admins.manager');
    Route::resource('roles', '\App\Http\Controllers\Backend\RolesController', ['names' => 'admin.roles']);
    Route::get('/customers/inventory/{userId}', '\App\Http\Controllers\Backend\UsersController@CustomerInventory')->name('admin.users.inventory');
    Route::get('/customers/change-orders', '\App\Http\Controllers\Backend\UsersController@CustomerChangeOrders')->name('admin.users.change-order');
    Route::get('/customers/exports',[UsersController::class,'customerExports'])->name('admin.users.exports');
    Route::get('/customer/request/{id}',[UsersController::class,'customerExportInfo']);
    Route::get('/customers/change-orders/{order_id}', '\App\Http\Controllers\Backend\UsersController@CustomerChangeOrderDetails')->name('admin.users.change-order-view');
    Route::resource('customers', '\App\Http\Controllers\Backend\UsersController', ['names' => 'admin.users']);
    Route::get('/getAllCustomers',[UsersController::class,'getAllCustomers']);
    Route::resource('admins', '\App\Http\Controllers\Backend\AdminsController', ['names' => 'admin.admins']);
    Route::post('/logout/submit', '\App\Http\Controllers\Backend\Auth\LoginController@logout')->name('admin.logout.submit');
    Route::post('/get_customer_info',[UsersController::class,'getCustomerInfo']);
    Route::get('/user/{user_id}/change-status/{admin_token}',[UsersController::class,'getUserRequest']);
    Route::get('/user/{user_id}/change-status/',[UsersController::class,'getUserRequest']);
    Route::post('/user/activate',[UsersController::class,'getUserActive']);
    Route::post('/user/cancel',[UsersController::class,'getUserCancel']);
    Route::post('/order/request/{change_id}/change',[AdminOrderController::class,'changeOrderRequestStatus']);
    Route::post('/order/request/{change_id}/sync',[AdminOrderController::class,'changeOrderRequestSync']);
    Route::get('/exportAllCustomers',[UsersController::class,'ExportAllCustomers']);
    Route::get('/exportAllExports',[UsersController::class,'ExportAllExports']);
    Route::get('/signup-request', '\App\Http\Controllers\Backend\UsersController@UserAccessRequest')->name('admin.users.requests');
    Route::get('/profile',[AdminsController::class,'adminProfile']);
    Route::post('/profile-save',[AdminsController::class,'adminProfileSave']);
    Route::get('/exportAllCustomerInPdf',[UsersController::class,'ExportAllCustomerInPdf']);
    Route::get('/exportAllUserRolesInpdf',[RolesController::class,'ExportAllRolesInPdf']);
    Route::get('/exportAllAdminsInPdf',[AdminsController::class,'ExportAllAdminsToPdf']);
    Route::get('/exportAllManagersInPdf',[UsersController::class,'ExportAllManagersToPdf']);
    Route::get('/exportAllChangeOrdersInPdf',[UsersController::class,'ExportAllOrdersToPdf']);
    Route::get('/exportAllSignupInPdf',[UsersController::class,'ExportAllSignupToPdf']);
    Route::get('/exportAllExportsInPdf',[UsersController::class,'ExportAllExportsPdf']);
    Route::get('/exportAllChangeOrdersInExcel',[UsersController::class,'ExportAllOrdersToExcel']);
    Route::get('/exportAllManagersInExcel',[UsersController::class,'ExportAllManagersToExcel']);
    Route::get('/exportAllAdminsInExcel',[AdminsController::class,'ExportAllAdminsToExcel']);
    Route::get('/exportAllSignupsInExcel',[UsersController::class,'ExportAllSignupToExcel']);
    Route::get('/exportAllUserRolesInExcel',[RolesController::class,'ExportAllRolesInExcel']);
    Route::post('/getAdminVmiItem',[UsersController::class,'GetInventoryItem']);
    Route::post('/update_inventory_item',[UsersController::class,'UpdateInventoryItem']);
    Route::get('/getAdminVmiData',[UsersController::class,'GetUserVmiData']);
    Route::post('/saveAdminVmiData',[UsersController::class,'SaveUserVmiData']);
    Route::get('/ExportVmiInventory',[UsersController::class,'ExportVmiInventory']);
    Route::get('/welcomemessage',[UsersController::class,'removeWelcome']);

    Route::get('/manager/{id}/customers/{is_exits}',[UsersController::class,'ManagerCustomersView'])->name('admin.manager.customers');
    Route::get('/manager/customers',[UsersController::class,'ManagerCustomers'])->name('admin.manager.customers_list');
    Route::get('/manager/create',[UsersController::class,'ManagerCreate'])->name('admin.manager.create');
    Route::post('/get_manager_info',[UsersController::class,'ManagerInfo'])->name('admin.manager.info');
}); 

Route::get('/clearNotifications',[NotificationController::class,'ClearAdminNotifications']);

Route::resource('/salesOrderExportResponse',ExportController::class);


Route::get('/insertCustomerno',[UsersController::class,'insertCustomerNumbers']);

require __DIR__.'/auth.php';
