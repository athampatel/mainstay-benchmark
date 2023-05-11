<?php 

namespace App\Helpers;

use App\Models\Admin;
use App\Models\ApiConnectionError;
use App\Models\ApiLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Client\RequestException;
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
          Mail::bcc(explode(',',$admin_emails))->send(new \App\Mail\SendMail($details));
        } else {
          $admin_emails = Admin::all()->pluck('email')->toArray();
          Mail::bcc($admin_emails)->send(new \App\Mail\SendMail($details));
        }
        return 1;
      }
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
        $body      = "<p style='max-width:590px;font-weight:bold;font-size:14px;'>The Api with resource <span style='color:#000'>{$resource} </span>has get an {$response_code} error.</p>";
        $details['body'] = $body;
        $url = '';
        $details['link']            =  $url;      
        $details['mail_view']       =  'emails.email-body';
        $details['is_button']       =  false;
        $admin_emails = config('app.admin_emails');
        $is_local = config('app.env') == 'local' ? true : false;
        // return;
        if($is_local){
          Mail::bcc(explode(',',$admin_emails))->send(new \App\Mail\SendMail($details));
        } else {
          $admin_emails = Admin::all()->pluck('email')->toArray();
          Mail::bcc($admin_emails)->send(new \App\Mail\SendMail($details));
        }
        return;
      }
    }
}