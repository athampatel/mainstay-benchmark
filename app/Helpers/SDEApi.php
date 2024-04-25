<?php 

namespace App\Helpers;

use App\Http\Controllers\Backend\UsersController;
use App\Models\Admin;
use App\Models\ApiConnectionError;
use App\Models\ApiLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Client\RequestException;
use Carbon\Carbon;
use Spatie\Permission\Models\Permission;

// sde : Simple data exchange

class SDEApi
{
   public  $end_point ;
   protected $username = '';
   protected $password = '';
   protected $is_ssl_verify = false;
   public function __construct(){
        $this->end_point = config('app.api_url');
        $this->username = 'MainStay';
        $this->password = 'M@1nSt@y';
   }

   public function getAliasItems( $data = null ) {

        return true;
   }

   public function getCustomers( $data = null ) {


    return true;
  }

  public function getCustomer( $customer_email = '', $customer_no = '' ) {


    return true;
  }

  public function getInvoiceHistoryDetail( $data = null ) {


    return true;
  }

  public function getInvoiceHistoryHeader( $data = null ) {
    return true;
  }
  public function getProducts( $data = null ) {
    return true;
  }
  public function getSalesOrderHistoryDetail( $data = null ) {
    return true;
  }

  public function getSalesOrderHistoryHeader( $data = null ) {
    return true;
  } 
  public function getSalespersons( $data = null ) {
    return true;
  }

  public function getVendors( $data = null ) {
    return true;
  }
  public function getCustomerSalesHistory( $customer_id = '',$year = '') {

    if(empty($customer_id) || !$customer_id == '')
      return false;
    
    $FiscalYear = ($year != '') ? $year : date('Y') ;
    $method     = 'POST';
    $resource   = 'CustomerSalesHistory';
    $args       = array('filter' => array(
                                      array('column'  => 'CustomerNo',
                                            'type'    => 'equals',
                                            'value'   => $customer_id,
                                            'operator'=> 'and',
                                      ),
                                      array('column'  => 'FiscalYear',
                                            'type'    => 'equals',
                                            'value'   => $FiscalYear,
                                            'operator'=> 'and',
                                      ),
                                  )
                    );
    $response = $this->Request($method,$resource,$args);
    return $response;
  }

  public function getCustomerItemHistory( $customer_id = '' , $item_id = '') {
    return true;
  }


  public function getSalesOrders( $customer_id = '') {
    return true;
  }


  public function CheckConnection(){
    try {
        $data = [
          "user" 		    => $this->username,
          "password" 	  => $this->password,
          "resource"	  => 'Customers',
          "limit"       => 1,
          "offset"      => 1
        ];
      
        $response = Http::withOptions([
            'verify' => $this->is_ssl_verify,
            'timeout' => config('app.app_max_time'),
        ])
        ->post($this->end_point, $data);
        return 0;
      } catch (\Exception $e) {
        $message = $e->getMessage();
        // store the error message
        $apiConnectionError = ApiConnectionError::create([
          'message' => $message
        ]);
        $message = 'We lost the connection in the SDE API, so the portal is not functioning.';
        // send the error message to email
        $details['title']   = config('constants.api_connection_error_email.title');   
        $details['subject'] = config('constants.api_connection_error_email.subject');
        $details['status']    = 'success';
        $details['message']   = config('constants.api_connection_error_email.message');
        $body      = "<p style='max-width:590px;font-weight:bold;font-size:14px;'>{$message}</p>";
        $details['body'] = $body;
        $url = '';
        $details['link']            =  $url;      
        $details['mail_view']       =  'emails.email-body';
        $details['is_button'] = false;
        $details['api_connection_error'] = 'We Get Connection Error on SDE API';
        $admin_emails = config('app.admin_emails');
        $is_local = config('app.env') == 'local' ? true : false;
        if($is_local){
          UsersController::commonEmailSend($admin_emails,$details);
        } else {
          // $admin_emails = Admin::all()->pluck('email')->toArray();
          // $admin_emails = self::getSuperAdminEmails();
          $admin_emails = self::getHasPermissionEmailAddress('api.error');
          UsersController::commonEmailSend($admin_emails,$details);
        }
        return 1;
      }
    }

    // Retrieve the email addresses of all admin users in the 'superadmin' role
    public static function getSuperAdminEmails() {
      return Admin::whereHas('roles', function ($query) {
            $query->where('name', 'superadmin');
        })->pluck('email')->toArray();
    }

    // Retrieve the email address of all admin user has a particular permission
    public static function getHasPermissionEmailAddress($permission_name){
      $permission = Permission::where('name', $permission_name)->first();
      $roles = $permission->roles;
      $admin_emails = [];
      foreach($roles as $role) {
          $new_admin_emails = Admin::whereHas('roles', function ($query) use($role) {
              $query->where('name', $role->name);
          })->pluck('email')->toArray();
          $admin_emails = array_values(array_unique([...$admin_emails,...$new_admin_emails]));
      }
      return $admin_emails;
    }
  
    public function Request($method = 'post',$resource = 'Customers', $data = null){
      $default_data = array(
          "user" 		    => $this->username,
          "password" 	  => $this->password,
          "resource"	  => $resource,
      );
      $post_data = array_merge($default_data,$data);

      
      
      $request = Http::withOptions([
          'verify' => $this->is_ssl_verify,
          'timeout' => config('app.app_max_time')
      ]);

      
      if($method === 'get') {
        $response = $request->get($this->end_point,$post_data);
      } else {
        $response = $request->post($this->end_point,$post_data);
      }
      $response_code = $response->getStatusCode();
      self::responseErrorCheck($response,$data,$resource);
      if($resource == 'Products' && $response_code == 500){
        return [];
      }
      if($resource == 'SalesOrderHistoryHeader' && $response_code == 500){
        return [];
      } 
      return $response->json();
    }

    public static function responseErrorCheck($response,$data,$resource){
      $response_code = $response->getStatusCode();
      $error_codes = explode(',', config('app.api_error_codes'));
      if(in_array($response_code,$error_codes)){
        if($response->serverError()) {
          $error_message = false;
        } else {
          $error_message = json_decode($response->body(), true);
        }

        if(!$error_message) {
          $error_message = 'null';
        } else {
          $error_message = $error_message['message'];
        }

        ApiLog::create([
          'resource' => $resource,
          'data' =>  json_encode($data),
          'error_code' => $response_code,
          'message' => $error_message,
        ]);

        // send a mail 
        $details['title']   = config('constants.api_error_email.title');   
        $details['subject'] = config('constants.api_error_email.subject');
        $details['status']    = 'success';
        $details['message']   = config('constants.api_error_email.message');
        $body      = "<p style='max-width:590px;font-weight:bold;font-size:14px;'>The API with resource <span style='color:#000'>{$resource} </span>has generated a Server response error (error code $response_code).</p>";
        $details['body'] = $body;
        $url = '';
        $details['link']            =  $url;      
        $details['mail_view']       =  'emails.email-body';
        $details['is_button']       =  false;
        $admin_emails = config('app.admin_emails');
        $is_local = config('app.env') == 'local' ? true : false;
        // return;
        if($is_local){
          UsersController::commonEmailSend($admin_emails,$details);
        } else {
          //$admin_emails = Admin::all()->pluck('email')->toArray();
          // $admin_emails = self::getSuperAdminEmails();
          $admin_emails = self::getHasPermissionEmailAddress('api.error');
          UsersController::commonEmailSend($admin_emails,$details);
        }
        return;
      }
    }
	
	public function getRangeDates($range,$year) {
        $start_date = '';
        $end_date = '';
        $range_months   = [];
        $string_months  = [];
        $month_name     = [];
        $current_month  = Carbon::now()->format('m');
        if($range != 0){
            if($range ==  1){
                $start_date =  Carbon::parse($year . '-' . $current_month . '-01')->subMonths(1)->endOfMonth()->format('Y-m-d');            
                $end_date = date('Y-m-d');
                $last_month = Carbon::now()->subMonth()->month;
                $last_month = $last_month > 9 ? $last_month : "0$last_month"; 
                

                $_month = Carbon::parse($start_date)->startOfMonth()->format('m');                    
                $e_month = Carbon::parse($end_date)->startOfMonth()->format('m');                    
                $s_year = Carbon::parse($start_date)->startOfMonth()->format('y');                    
                $e_year = Carbon::parse($end_date)->startOfMonth()->format('y');                    
                //array_push($month_name,$_month.$s_year);
                if($_month != $e_month){
                    $range_months = [$_month,$last_month];                    
                    //array_push($month_name,$e_month.$e_year);                    
                }else{
                    $range_months = [$_month];
                }
            }elseif($range == 6){
                $data = array();
                $num = 11;
                for ($i = 0; $i <= $num; $i++) {
                    //$month = Carbon::today()->subMonth($i);    
                    $current_month = date('m-Y'); //Carbon::now()->format('m');                
                    $month = Carbon::parse('01-'.$current_month)->addMonth($i)->startOfMonth()->format('m');                    
                    $monthName = Carbon::parse('01-'.$current_month)->addMonth($i)->startOfMonth()->format('M');                    
                    $year = Carbon::parse('01-'.$current_month)->addMonth($i)->format('Y');
                    array_push($data, array(
                        'month' => $month,
                        'year' => $year,
                        'index' => $i,
                    ));
                   // echo $year.'##'.$i.'##'.$monthName.'<br/>';

                    $month_strnig = $monthName.'-'.$year;
                    array_push($range_months,$month);
                    array_push($string_months,$month_strnig);
                    array_push($month_name,$month.$year);
                }
                $_st = $data[0];
                $w_st = isset($data[$num]) ? $data[$num] : 0;
                $start_date = $_st['year']."-".$_st['month']."-01";
                $end_date   = date('Y-m-d');

            }elseif($range == 2 || $range == 3 || $range == 5){
                $num = 2;
                if($range == 3)
                    $num = 5;
                elseif($range == 5)    
                    $num = 11; 
                $data = array();
                for ($i = $num; $i >= 0; $i--) {
                    //$month = Carbon::today()->subMonth($i);    
                    $current_month = date('m-Y'); //Carbon::now()->format('m');                
                    $month = Carbon::parse('01-'.$current_month)->subMonth($i)->startOfMonth()->format('m');                    
                    $monthName = Carbon::parse('01-'.$current_month)->subMonth($i)->startOfMonth()->format('M');                    
                    $year = Carbon::parse('01-'.$current_month)->subMonth($i)->format('Y');
                   
                    array_push($data, array(
                        'month' => $month,
                        'year' => $year,
                        'index' => $i,
                    ));
                    $month_strnig = $monthName.'-'.$year;

                    array_push($range_months,$month);
                    array_push($string_months,$month_strnig);
                    array_push($month_name,$month.$year);
                    
                    
                }
                $_st = $data[0];
                $w_st = $data[$num];
                $start_date = $_st['year']."-".$_st['month']."-01";
                $end_date   = date('Y-m-d');
            }elseif($range == 4){
                $dates = explode('&',$year);
                $start_date = isset($dates[0]) ? $dates[0] : date('Y-d-01');
                $end_date   = isset($dates[1]) ? $dates[1] : date('Y-d-30');

                $start = Carbon::parse($start_date);
                $end = Carbon::parse($end_date);

                $interval = \DateInterval::createFromDateString('1 month');
                $period = new \DatePeriod($start, $interval, $end);

                foreach ($period as $dt) {
                    $range_months[] = $dt->format("m");
                }
            }
        } else {
            $range_months = ['01','02','03','04','05','06','07','08','09','10','11','12'];
            $start_date = ($year) . "-01-01";
            $end_date = ($year)."-12-31";
        }

        $return = array('start'         => $start_date, 
                        'end'           => $end_date ,
                        'range_months'  => $range_months,
                        'string_months' => $string_months,
                        'month_name'    => $month_name);
        return $return;
    }
}