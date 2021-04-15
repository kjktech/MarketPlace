<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use LaravelLocalization;

class ContactMessage extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
      $locale = LaravelLocalization::getCurrentLocale();
      return $this->markdown("emails.$locale.contact_message")
          ->with([
              'name' => $this->data['name'],
              'email' => $this->data['email'],
              'message' => $this->data['message']
          ]);
    }
}
