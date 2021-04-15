<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;

class Order extends Model{

  use SoftDeletes;
  use SearchableTrait;

  protected $fillable = [
      'reference', 'cart_data', 'amount', 'user_id', 'last_name', 'email', 'session_key', 'verified'
  ];

  protected $casts = [
      'customer_details' => 'array',
  ];
  protected $dates = [
      'accepted_at',
      'declined_at',
      'created_at',
      'updated_at',
      'deleted_at'
  ];

  protected $searchableColumns = ['user.email'];

  protected $searchable = [
      'columns' => [
          'users.name' => 10,
          'users.email' => 10,
      ],
      'joins' => [
          'users' => ['orders.user_id','users.id'],
          //'sellers' => ['listings.user_id','listings.id'],
      ],
  ];


  public function user() {
    return $this->belongsTo('App\Models\User');
  }

  public function items() {
    return $this->hasMany('App\Models\OrderItem');
  }

  public function contact() {
    return $this->hasOne('App\Models\OrderContact');
  }

  public function shipping() {
    return $this->hasOne('App\Models\Shipping');
  }

  public  static  function calculateDateOrderDiff($total_order){
      $today=0;
      $yesterday=0;
      $old =0;
      foreach($total_order as $r) {
           $timediff = ((now()->timestamp) - (($r->created_at)->timestamp));
          if($timediff  < 86400){
              $today += 1;
          }else if($timediff < 172800){
              $yesterday += 1;
          }
          else if($timediff >172800){
              $old  += 1;
          }
      }
   return [ 'today' => $today, 'yesterday' => $yesterday, 'old'=> $old];
  }

    public  static  function calculateShippingOrder48($total_order){
        $count=0;
        foreach($total_order as $r) {
            $timediff = ((now()->timestamp) - (($r->created_at)->timestamp));
           if($timediff <= 172800){
               $count += 1;
            }
        }
        return $count;
    }

    public  static  function calculateNewProducts14($products){
        $count=0;
        foreach($products as $r) {
            $timediff = ((now()->timestamp) - (($r->created_at)->timestamp));
            if($timediff <= 1209600){
                $count += 1;
            }
        }
        return $count;
    }

    public function listings(){
        return $this->belongsTo('App\Models\Listing', 'listing_id', 'id');
    }
}
