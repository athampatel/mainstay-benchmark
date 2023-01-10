<?php

use App\Http\Controllers\AuthController;
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


//Route::get('/email-view', function () {
  //  return view('emails/email-body');
//});



/**
 * Admin routes
 */
/*
Route::group(['prefix' => 'admin'], function () {
    Route::get('/', '\App\Http\Controllers\Backend\DashboardController@index')->name('admin.dashboard');
    Route::resource('roles', '\App\Http\Controllers\Backend\RolesController', ['names' => 'admin.roles']);
    Route::resource('users', '\App\Http\Controllers\Backend\UsersController', ['names' => 'admin.users']);
    Route::resource('admins', '\App\Http\Controllers\Backend\AdminsController', ['names' => 'admin.admins']);


    // Login Routes
    Route::get('/login', '\App\Http\Controllers\Backend\Auth\LoginController@showLoginForm')->name('admin.login');
    Route::post('/login/submit', '\App\Http\Controllers\Backend\Auth\LoginController@login')->name('admin.login.submit');

    // Logout Routes
    Route::post('/logout/submit', '\App\Http\Controllers\Backend\Auth\LoginController@logout')->name('admin.logout.submit');

    // Forget Password Routes
    Route::get('/password/reset', '\App\Http\Controllers\Backend\Auth\ForgetPasswordController@showLinkRequestForm')->name('admin.password.request');
    Route::post('/password/reset/submit', '\App\Http\Controllers\Backend\Auth\ForgetPasswordController@reset')->name('admin.password.update');
}); */

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
