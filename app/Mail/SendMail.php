<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
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
		$subject = 'New Customer Request for Member Portal'; //$this->details['subject'];
        return $this->subject($subject) //'New Customer Request for Member Portal'
                    ->view('emails.email-body');
    }
}
