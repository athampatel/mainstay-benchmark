<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;
	public $details;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details = null)
    {
		  $this->details = $details;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // dd($this->view($this->details['mail_view'])->with('mail_data', $this->details));
      return $this
        ->subject($this->details['subject'])
        // ->markdown($this->details['mail_view'])->with('mail_data', $this->details);
        ->view($this->details['mail_view'])->with('mail_data', $this->details);
    }
}
