<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WholeSaleOrder extends Model {

 /**
  * The attributes that are mass assignable.
  *
  * @var array
 */
 protected $fillable = ['reference', 'amount', 'user_id', 'first_name', 'last_name', 'email'];

 public function items() {
   return $this->hasMany('App\Models\WholeSaleOrderItem');
 }

 public function contact() {
   return $this->hasOne('App\Models\WholeSaleOrderContact');
 }

}
