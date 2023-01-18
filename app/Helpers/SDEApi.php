<?php 

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

// sde : Simple data exchange

class SDEApi
{
   public $end_point = 'https://sde.BenchmarkProducts.com:2959/sde';
   protected $username = '';
   protected $password = '';
   protected $is_ssl_verify = false;

   public function __construct(){
       // $this->end_point = env('API_URL');
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

  public function getInvoiceHistoryHeader( $customer_id = '') {
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
        ]);

        if($method === 'get') {
           $response = $request->get($this->end_point,$post_data);
        } else {
            $response = $request->post($this->end_point,$post_data);
        }
        return $response->json();
    }
}