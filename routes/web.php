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
