<?php

  namespace App\Models;

  use Illuminate\Database\Eloquent\Model;

 class VariantCategory extends Model
 {
   //protected $table="category_variant_category";
   /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'variant_category_id', 'category_id'
    ];
 }
