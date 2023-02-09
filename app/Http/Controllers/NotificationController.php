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
use App\Http\Controllers\Backend\DashboardController;
use App\Models\User;

class NotificationController extends Controller
{
    public $user;
    public $isManager;
    public $superAdmin;

    public function __construct()
    {
        $this->user         = Auth::guard('admin')->user();
        $userId             = $this->user->id;
        //$this->isManager    = DashboardController::isManager($userId,$this->user);
        $this->superAdmin   = DashboardController::SuperAdmin($this->user);

    }
    
    public function index()
    {

    }

    public function create($data)
    {
        Notification::create($data);
        return true;
    }

    public function getNotifications(Request $request){
        /*$user =  $this->user;
        if($this->superAdmin){
            $notification   =   Notification::where('is_read',0)->where('to',0);
        }else{
           $customers       =    User::leftjoin('user_sales_persons','users.id','=','user_sales_persons.user_id')
                                ->leftjoin('sales_persons','user_sales_persons.sales_person_id','=','sales_persons.id')
                                ->leftjoin('admins','sales_persons.email','=','admins.email')
                                ->leftjoin('notifications','users.id','=','notifications.from_user');

                                //->where('users.id',$user->id)->orWhere('');
            
        }*/

        return Response::json($data);
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
