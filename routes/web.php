<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MenuController;
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
});

//Route::get('/email-view', function () {
  //  return view('emails/email-body');
//});

Route::get('send-mail', function () {
   
    $details = [
        'title' => 'Mail from ItSolutionStuff.com',
        'body' => 'This is for testing email using smtp',
		'link' => '####'
    ];
   
    \Mail::to('atham@tendersoftware.in')->send(new \App\Mail\SendMail($details));
   
    dd("Email is Sent.");
});

require __DIR__.'/auth.php';
