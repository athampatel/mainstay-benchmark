<?php 

namespace App\Helpers;

class EmailHelper
{
   protected $from = '';
   protected $to = '';
   protected $content = '';
   protected $subject = '';

   public function __construct() {
        $this->from = config('mail.from.address');
   }

    public function Request($to,$subject, $content = null) {
        
    }
}