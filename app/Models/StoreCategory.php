<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Nestable\NestableTrait;

class StoreCategory extends Model
{
    //
    use NestableTrait;
    protected $parent = 'parent_id';

    protected $fillable = [
        'name', 'hash', 'order', 'parent_id', 'slug'
    ];

    public function child()
	  {
	    	return $this->hasOne('App\Models\StoreCategory', 'id', 'parent_id');
	  }

    public function categories() {
        return $this->belongsTo('App\Models\StoreCategory', 'parent_id');
    }

     /*
     public function pricing_models() {
        return $this->belongsToMany('App\Models\PricingModel');
     }
    */
    public function parent() {
        return $this->belongsTo('App\StoreCategory', 'parent_id');
    }
}
