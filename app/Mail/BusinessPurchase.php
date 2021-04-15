<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use LaravelLocalization;

class BusinessPurchase extends Mailable
{
    use Queueable, SerializesModels;

    protected $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        //
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $locale = LaravelLocalization::getCurrentLocale();
        return $this->markdown("emails.$locale.business_purchase")
            ->with([
                'name' => $this->data['name'],
                'type' => $this->data['type'],
                'url' => 'https://afiaanyi.com',
            ]);
    }
}
