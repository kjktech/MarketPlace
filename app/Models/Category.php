<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Nestable\NestableTrait;
use App\Traits\HashId;

class Category extends Model
{
    #use \Spiritix\LadaCache\Database\LadaCacheTrait;
    use NestableTrait;
    use HashId;
    protected $parent = 'parent_id';

    protected $append = [
        'category_image',
    ];

    protected $fillable = [
        'name', 'hash', 'order', 'parent_id', 'slug'
    ];

	public function child()
	{
		return $this->hasOne('App\Models\Category', 'id', 'parent_id');
	}

    public function listings() {
        return $this->hasMany('App\Models\Listing');
    }

    public function categories() {
        return $this->belongsTo('App\Models\Category', 'parent_id');
    }

    public function pricing_models() {
        return $this->belongsToMany('App\Models\PricingModel');
    }

    public function variants() {
        return $this->belongsToMany('App\Models\Variant');
    }

    public function product_brands() {
        return $this->belongsToMany('App\Models\Brand');
    }

    public function parent() {
        return $this->belongsTo('App\Models\Category', 'parent_id');
    }


    public function getPathAttribute() {
      return store(getDir($this->attributes['id'].'.jpg', 4), $this->attributes['id'].'.jpg');
    }

    public function getPngAttribute() {
      return store(getDir($this->attributes['id'].'.png', 4), $this->attributes['id'].'.png');
    }

    public function getUrlAttribute() {
        return route('shopping.category', [$this, $this->slug]);
    }

    public function getCategoryImageAttribute(){
       $banner = "/images/no_image.png";

       if($this->attributes['banner']== ""){
         if($this->attributes['parent_id'] != 0){
           if($this->parent()->first()->banner != ""){
            $banner = $this->parent()->first()->banner;
           }
         }
       }else{
         $banner = $this->attributes['banner'];
       }

        return $banner;
    }

}
