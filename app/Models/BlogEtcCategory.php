<?php

namespace App\Models;

use \WebDevEtc\BlogEtc\Models\BlogEtcCategory as BlogCategory;

class BlogEtcCategory extends BlogCategory{


  public function icon()
  {
      return $this->hasOne('App\Models\BlogCategoryIcon', 'blog_etc_category_id');
  }

  public static function recentgroup()
  {
    $categories = \DB::table('blog_etc_categories')->get();
    $blogs = [];
    foreach($categories as $category){
      $blogs_is = \WebDevEtc\BlogEtc\Models\BlogEtcPost::select('blog_etc_posts.*')
      ->join('blog_etc_post_categories', 'blog_etc_posts.id', 'blog_etc_post_categories.blog_etc_post_id')
      ->where('blog_etc_post_categories.blog_etc_category_id', $category->id)
      ->first();
      if($blogs_is){
        $blogs[] = $blogs_is;
      }
    }
    return $blogs;
  }

  public static function relatedgroup($categories, $post_id)
  {

    $categories_id = [];
    foreach($categories as $category){
      $categories_id[] = $category->id;
    }
    $blogs_is = [];
    foreach($categories as $category){
      $blogs_is = \WebDevEtc\BlogEtc\Models\BlogEtcPost::select('blog_etc_posts.*')
      ->join('blog_etc_post_categories', 'blog_etc_posts.id', 'blog_etc_post_categories.blog_etc_post_id')
      ->whereIn('blog_etc_post_categories.blog_etc_category_id', $categories_id)
      //->where('blog_etc_posts.id', '!=', $post_id)
      ->get();
    }
    //dd($blogs_is);
    return $blogs_is;
  }

}
