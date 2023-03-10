<?php 

namespace App\Helpers;

use App\Models\ApiLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

// sde : Simple data exchange

class SDEApi
{
  //  public $end_point = 'https://sde.BenchmarkProducts.com:2960/sde';
   public  $end_point ;
   protected $username = '';
   protected $password = '';
   protected $is_ssl_verify = false;
  //  protected $is_ssl_verify = true;

   public function __construct(){
       // $this->end_point = env('API_URL');\
        $this->end_point =  env('API_URL');
        $this->username = 'MainStay';
        $this->password = 'M@1nSt@y';
       // $this->is_ssl_verify = env('SSL_VERIFY');
		    //$this->end_point = env('API_URL');
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

  // public function getInvoiceHistoryHeader( $data = null ) {

  //   return true;
  // }
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

  
  public function Request($method = 'post',$resource = 'Customers', $data = null){
        $default_data = array(
            "user" 		    => $this->username,
            "password" 	  => $this->password,
			      "resource"	  => $resource,
        );
        $post_data = array_merge($default_data,$data);
        $request = Http::withOptions([
            'verify' => $this->is_ssl_verify,
            'timeout' => env('API_MAX_TIME')
        ]);
        if($method === 'get') {
          $response = $request->get($this->end_point,$post_data);
        } else {
          $response = $request->post($this->end_point,$post_data);
        }
        // if(!$response->successful()){
        self::responseErrorCheck($response,$data,$resource);
        // }

        return $response->json();
      }

    public static function responseErrorCheck($response,$data,$resource){
      $response_code = $response->getStatusCode();
      $error_codes = explode(',', env('API_ERROR_CODES'));
      if(in_array($response_code,$error_codes)){
        $error_message = json_decode($response->body(), true)['message'];
        
        // insert api log
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
        // dd($body);
        // $body   .= '<p><span style="max-width:590px;font-weight:bold;font-size:14px;">Filter Data: </span>&nbsp;</p>';
        // $body .= "<p style='max-width:590px;'><span style='max-width:590px;'>".json_encode($data)."</span></p>";
        $details['body'] = $body;
        $url = '';
        $details['link']            =  $url;      
        $details['mail_view']       =  'emails.email-body';
        $admin_emails = env('ADMIN_EMAILS');
        Mail::bcc(explode(',',$admin_emails))->send(new \App\Mail\SendMail($details));
        return;
      }
    }
}