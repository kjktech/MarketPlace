<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Nestable\NestableTrait;

class Brand extends Model
{
  protected $fillable = [
      'name', 'slug', 'logo'
  ];

  public function getPathAttribute() {
    return store(getDir($this->attributes['id'].'.jpg', 4), $this->attributes['id'].'.jpg');
  }

  public function getBrandLogoAttribute(){
     $logo = "/images/no_image.png";

     if(!$this->attributes['logo']== ""){
        $logo = $this->attributes['logo'];
     }
      return $logo;
  }

}
