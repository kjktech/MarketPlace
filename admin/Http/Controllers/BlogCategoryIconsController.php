<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Models\BlogCategoryIcon;
use WebDevEtc\BlogEtc\Models\BlogEtcCategory;

class BlogCategoryIconsController extends controller{

  public function index(Request $request){
    $categories = BlogEtcCategory::all();
    $categories_icon = BlogCategoryIcon::all();
    $categories_arr = [];
    foreach($categories as $category){
        $category_icon = BlogCategoryIcon::where('blog_etc_category_id', $category->id)->first();
        if($category_icon){
          $categories_arr[] = array("name"=> $category->category_name, "blog_etc_category_id"=> $category->id, "icon"=>$category_icon->icon);
        }else{
          $categories_arr[] = array("name"=> $category->category_name, "blog_etc_category_id"=> $category->id, "icon"=>"");
        }
    }
    //dd($categories_arr);
    $data = [];
    $data['categories'] = $categories_arr;
    return view('panel::blogcategoryicons.index', $data);
  }
  public function update(Request $request){
    $input_arr = explode(",", $request->get('input-val'));
    foreach($input_arr as $input){

      $arr_val = explode("_", $input);
      $category_icon = BlogCategoryIcon::where('blog_etc_category_id', $arr_val[0])->first();
      if($category_icon){
        $category_icon->icon = $arr_val[1];
        $category_icon->save();
      }else{
        $category_icon = new BlogCategoryIcon();
        $category_icon->blog_etc_category_id = $arr_val[0];
        $category_icon->icon = $arr_val[1];
        $category_icon->save();
      }
    }
    return redirect(route('panel.blog.icon'));
  }
}
