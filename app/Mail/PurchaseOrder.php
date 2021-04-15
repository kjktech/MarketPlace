<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PurchaseOrder extends Mailable{

   protected $order;

   /**
    * Create a new message instance.
    *
    * @return void
    */
   public function __construct($order)
   {
       //
       $this->order = $order;
   }

   /**
    * Build the message.
    *
    * @return $this
    */
   public function build()
   {
       $locale = 'en';
       return $this->markdown("emails.$locale.purchase_order")
           ->with([
               'order' => $this->order,
               /*'url' => route('branding', [$this->directory, $this->directory->slug]),*/
           ]);
   }
}
