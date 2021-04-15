<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use LaravelLocalization;

class TopBrandMessage extends Mailable
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
      return $this->markdown("emails.$locale.topbrand_message")
          ->with([
              'topbrand' => $this->data['topbrand'],
              'name' => $this->data['name'],
              'subject' => $this->data['subject'],
              'email' => $this->data['email'],
              'message' => $this->data['message']
          ]);
    }
}
