<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\Admin;
use App\Http\Controllers\Controller;
use App\Models\SalesPersons;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class NotificationController extends Controller
{
    public $user;

    public function __construct()
    {
        $this->user = Auth::guard('admin')->user();
    }
    
    public function index()
    {

    }

    public function create($data)
    {
        Notification::create($data);
        return true;
    }

    public function getNotifications(){
        $notification = Notification::where('is_read',0)
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


    }

    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {


    }
     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        $user = User::find($id);
        if (!is_null($user)) {            
            $user->delete();
        }
        session()->flash('success', 'User has been deleted !!');
        return back();
    }
}
