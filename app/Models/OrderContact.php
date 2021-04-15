<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderContact extends Model{

  public function lga(){
    return $this->belongsTo('\App\Models\City', 'city_id');
  }
}
