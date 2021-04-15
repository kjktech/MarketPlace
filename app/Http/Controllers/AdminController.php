<?php
namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;

use App\Models\DirectoryCategory;
use App\Models\StoreCategory;

class AdminController extends Controller
{
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
      $this->middleware('auth');
  }
  /**
   * Show the application dashboard.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
      return view('admin_index');
  }

  public function userindex()
  {
      return view('user_admin_index');
  }

  public function managedircategory()
  {
     if(request('name')){
       $params['name'] = request('name');
       $params['parent_id'] = request('category');
       $params['slug'] = str_slug(request('name'));
       $category = DirectoryCategory::create($params);
       $category->save();
     }
     $categories = DirectoryCategory::orderBy('order', 'ASC')->nested()->get();
     $data['categories'] = flatten($categories, 0);
     return view('manage_dir_category', $data);
  }

  public function editdircategory()
  {
     $id = request('id');
     if(request('name')){
       $dircategory = DirectoryCategory::find($id);
       $dircategory->name =  request('name');
       $dircategory->parent_id = request('category');
       $dircategory->save();
     }else{
       $dircategory = DirectoryCategory::find($id);
     }
     $data['dircategory'] =  $dircategory;
     $categories = DirectoryCategory::orderBy('order', 'ASC')->nested()->get();
     $data['categories'] = flatten($categories, 0);
     return view('category.edit', $data);
  }

  public function managestorecategory()
  {
     if(request('name')){
       $params['name'] = request('name');
       $params['parent_id'] = request('category');
       $params['slug'] = str_slug(request('name'));
       $category = StoreCategory::create($params);
       $category->save();
     }
     $categories = StoreCategory::orderBy('order', 'ASC')->nested()->get();
     $data['categories'] = flatten($categories, 0);
     return view('manage_store_category', $data);
  }

  public function editstorecategory()
  {
     $id = request('id');
     if(request('name')){
       $dircategory = StoreCategory::find($id);
       $dircategory->name =  request('name');
       $dircategory->parent_id = request('category');
       $dircategory->save();
     }else{
       $dircategory = StoreCategory::find($id);
     }
     $data['dircategory'] =  $dircategory;
     $categories = StoreCategory::orderBy('order', 'ASC')->nested()->get();
     $data['categories'] = flatten($categories, 0);
     return view('category.edit', $data);
  }
}
