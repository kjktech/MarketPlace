<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Nestable\NestableTrait;

class DirectoryPayment extends Model
{
  protected $fillable = [
      'user_id', 'paystack_reference', 'payment_type', 'directory_id', 'amount',
  ];

  /*
  public function pricing_models() {
     return $this->belongsToMany('App\Models\PricingModel');
  }
 */
 public function user() {
     return $this->belongsTo('App\Models\User');
 }

 public function directory() {
     return $this->belongsTo('App\Models\Directory');
 }

}
