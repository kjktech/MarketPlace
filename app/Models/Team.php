<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

 class Team extends Model{

   protected $fillable = [
       'name', 'position', 'photo', 'directory_id'
   ];

   public function getPathAttribute() {
     return store(getDir($this->attributes['id'].'.jpg', 4), $this->attributes['id'].'.jpg');
   }

 }
