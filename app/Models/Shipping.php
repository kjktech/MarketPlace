<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipping extends Model{

  protected $fillable = ['order_id', 'partner_id', 'partner_class_id', 'session_id', 'cost'];
}
