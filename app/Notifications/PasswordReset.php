<?php

namespace App\Notifications;

use App\Mail\SendMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class PasswordReset extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    protected $token = '';

    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $email = request()->email;
        // $url = 'https://example.com/reset-password?token='.$token;
        return (new MailMessage)
                    // ->line('We got a forgot password request')
                    ->line(config('constants.email.reset_password.line_1'))
                    // ->line('Please click the below button to change the password')
                    ->line(config('constants.email.reset_password.line_2'))
                    // ->action('Reset Password', url('/reset-password/'.$this->token.'?email='.$email))
                    ->action(config('constants.email.reset_password.button'), url('/reset-password/'.$this->token.'?email='.$email))
                    // ->action('Reset-password', url($url))
                    // ->line('Thank you for using our '.env('APP_NAME'));
                    // ->line('Thank you for using our '.config('app.name'));
                    ->line(config('constants.email.reset_password.thank_message').config('app.name'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
