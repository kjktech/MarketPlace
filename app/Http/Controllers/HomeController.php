<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Directory;
use App\Models\DirectoryCategory;
use App\Models\BlogEtcCategory;
// use WebDevEtc\BlogEtc\Models\BlogEtcPost;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      
      //$testdb = new BlogEtcCategory();
      //dd($testdb->recentgroup());
      $cities_array = array("Abia"=>"Abia","Adamawa"=>"Adamawa",
      "Akwa Ibom"=>"Akwa Ibom","Anambra"=>"Anambra","Bauchi"=>"Bauchi",
      "Bayelsa"=>"Bayelsa","Benue"=>"Benue","Borno"=>"Borno","Cross River"=>"Cross River",
      "Delta"=>"Delta","Ebonyi"=>"Ebonyi",
      "Edo"=>"Edo","Ekiti"=>"Ekiti","Enugu"=>"Enugu",
      "Gombe"=>"Gombe","Imo"=>"Imo","Jigawa"=>"Jigawa",
      "Kaduna"=>"Kaduna","Kano"=>"Kano","Katsina"=>"Katsina",
      "Kebbi"=>"Kebbi","Kogi"=>"Kogi","Kwara"=>"Kwara",
      "Lagos"=>"Lagos","Nasarawa"=>"Nasarawa","Niger"=>"Niger",
      "Ogun"=>"Ogun","Ondo"=>"Ondo","Osun"=>"Osun","Oyo"=>"Oyo",
      "Plateau"=>"Plateau","Rivers"=>"Rivers","Sokoto"=>"Sokoto","Taraba"=>"Taraba",
      "Yobe"=>"Yobe","Zamfara"=>"Zamfara","FCT"=>"FCT");
        $listcategory = [];
        // $posts = BlogEtcPost::limit(8)->get();
        $directories = new Directory();
        $directories = $directories->active();
        $directories = $directories->orderBy('created_at', 'DESC')->limit(8)->get();
        $categories = DirectoryCategory::where('parent_id', 0)->orderBy('name', 'ASC')->get();
        $categories_arr = DirectoryCategory::where('parent_id', 0)->whereNotNull('order')->orderBy('order', 'ASC')->get();
        $child_categories = DirectoryCategory::where('parent_id', '!=', 0)->orderBy('name', 'ASC')->get();
        if(count($child_categories) == 0){
          $child_categories = [];
        }
        $listcategory[0] = "Category";
        foreach($categories as $category) {
          $listcategory[$category['id']] = $category['name'];
        }
        // $data['posts'] = $posts;
        $data['categories'] = $listcategory;
        $data['categories_arr'] = $categories_arr;
        $data['directories'] = $directories;
        $data['child_categories'] = $child_categories;
        //dd($child_categories);
        $data['city_array'] = $cities_array;
        return view('page.index_latest', $data);
    }
}
