<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variant extends Model{


  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
   protected $fillable = [
       'attribute', 'values', 'values_string'
   ];

   protected $casts = [
         'values' => 'array',
   ];
}
