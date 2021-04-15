<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DirectoryCategory;

class DirectoryCategoryController extends Controller
{
   public function index(){
     $categories = DirectoryCategory::orderBy('order', 'ASC')->nested()->get();
     $data = flatten($categories, 0);
     $result = array();
     $result_test = array();
     $result_other = array();
     $result_cat_ids = array();
     $result_cat_ids_ind = array();
     $count = count($data);
     $itiration = 0;
     foreach($data as $cat){
         $itiration++;
         $flag_header = false;
         if($cat['depth'] == 0){
           if(count($result_test) > 0){
             $result_test["other"] = $result_other;
             $result[] = $result_test;
             $result_cat_ids[] = $result_cat_ids_ind;
           }
           $result_test = array("header" => $cat);
           $flag_header = true;
           $result_other = array();
           $result_cat_ids_ind = array();
           $result_cat_ids_ind[] = $cat["id"];
         }else{
           if($itiration == $count){
             $result_test["other"] = $result_other;
             $result[] = $result_test;
             $result_cat_ids[] = $result_cat_ids_ind;
           }else{
             $result_other[] = $cat;
             $result_cat_ids_ind[] = $cat["id"];
           }

         }
     }
     return response()->json([
         'data' => $result
     ], 200);
   }

}
