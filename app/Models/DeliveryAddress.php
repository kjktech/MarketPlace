<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryAddress extends Model{
  protected $fillable = [
      'address', 'user_id', 'city', 'first_name', 'last_name', 'phone', 'city_id'
  ];

  public function lga(){
    return $this->belongsTo('\App\Models\City', 'city_id');
  }

  public function user(){
    return $this->belongsTo('\App\Models\User');
  }
}
