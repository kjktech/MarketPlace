<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Nestable\NestableTrait;

class DirectoryCategory extends Model
{
    //use \Spiritix\LadaCache\Database\LadaCacheTrait;
    use NestableTrait;
    protected $parent = 'parent_id';

    protected $appends = ['custom_banner'];

    protected $fillable = [
        'name', 'hash', 'order', 'parent_id', 'slug'
    ];

	  public function child()
	  {
	    	return $this->hasOne('App\Models\DirectoryCategory', 'id', 'parent_id');
	  }

    public function parent() {
        return $this->belongsTo('App\Models\DirectoryCategory', 'parent_id');
    }

    public function getPathAttribute() {
      return store(getDir($this->attributes['id'].'.jpg', 4), $this->attributes['id'].'.jpg');
    }

    public function getPngAttribute() {
      return store(getDir($this->attributes['id'].'.png', 4), $this->attributes['id'].'.png');
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

    public function getCustomBannerAttribute(){
       $banner = "/images/no_image.png";

       if($this->attributes['pagebanner'] == "" || $this->attributes['pagebanner'] == null){
         if($this->attributes['parent_id'] != 0){
           if($this->parent()->first()->pagebanner != ""){
            $banner = $this->parent()->first()->pagebanner;
           }
         }
       }else{
         $banner = $this->attributes['pagebanner'];
       }
        //dd($banner);
        return $banner;
    }
}
