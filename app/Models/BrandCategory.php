<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Nestable\NestableTrait;

class BrandCategory extends Model
{
 protected $table = 'brand_category';

 public function brand()
 {
     return $this->belongsTo('App\Models\Brand');
 }
}
