<?php 

namespace App\Helpers;

class EmailHelper
{
   protected $from = '';
   protected $to = '';
   protected $content = '';
   protected $subject = '';

   public function __construct() {
        $this->from = env('MAIL_FROM_ADDRESS');
   }

    public function Request($to,$subject, $content = null) {
        dd($to);
    }
}