<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Nestable\NestableTrait;

class StorePayment extends Model
{
  protected $fillable = [
      'user_id', 'paystack_reference', 'directory_payment_type', 'directory_id', 'store_id', 'amount',
  ];

  /*
  public function pricing_models() {
     return $this->belongsToMany('App\Models\PricingModel');
  }
 */
 public function user() {
     return $this->belongsTo('App\Models\User');
 }
}
