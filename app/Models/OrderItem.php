<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model{

  use SoftDeletes;

  protected $fillable = [
      'order_id', 'seller_id', 'listing_id', 'price', 'quantity', 'choices'
  ];

  protected $casts = [
      'listing_options' => 'array',
      'choices' => 'array',
      'customer_details' => 'array',
  ];
  protected $dates = [
      'created_at',
      'updated_at',
      'deleted_at'
  ];

  protected $searchable = [
      'columns' => [
          'listings.title' => 10,
      ],
      'joins' => [
          'listings' => ['order_items.listing_id','listings.id'],
          'sellers' => ['listings.user_id','listings.id'],
      ],
  ];

  public function listing() {
    return $this->belongsTo('App\Models\Listing');
  }

  public function order() {
    return $this->belongsTo('App\Models\Order');
  }
}
