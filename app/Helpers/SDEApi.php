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

   public function __construct()
   {
       // $this->end_point = env('API_URL');
        $this->username = 'MainStay';
        $this->password = 'M@1nSt@y';
       // $this->is_ssl_verify = env('SSL_VERIFY');
		//$this->end_point = env('API_URL');
   }

    public function Request($method = 'post',$resource = 'Customers', $data = null)
    {
        $default_data = array(
            "user" 		=> $this->username,
            "password" 	=> $this->password,
			"resource"	=> $resource,
			
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