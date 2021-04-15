<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DirectorySocialPage extends Model
{
  protected $casts = [
       'pages' => 'array'
   ];

   protected $fillable = ['id', 'directory_id', 'pages'];

   public function setPagesAttribute($value)
   {
     $pages = [];

     foreach ($value as $array_item) {
        if (!is_null($array_item['link'])) {
            $pages[] = $array_item;
        }
      }

    $this->attributes['pages'] = json_encode($pages);
  }
}
