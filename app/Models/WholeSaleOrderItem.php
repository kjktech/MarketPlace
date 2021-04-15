<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WholeSaleOrderItem extends Model {

  /**
    * The attributes that are mass assignable.
    *
    * @var array
  */
  protected $fillable = ['whole_sale_order_id', 'seller_id', 'listing_id', 'price', 'quantity', 'choices'];

  protected $casts = [
      'choices' => 'array',
  ];
}
