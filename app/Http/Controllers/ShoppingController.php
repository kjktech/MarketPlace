<?php

namespace App\Http\Controllers;

//use App\Mail\ListingVerified;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use App\Models\Store;
use App\Models\Category;
use App\Models\Listing;
use App\Models\Variant;
use App\Models\Brand;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use View;
use Location;
use Mapper;
use Setting;
use MetaTag;
use Mail;
use Carbon\Carbon;

class ShoppingController extends Controller
{

    protected $category_id;

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index($shopping, $slug, Request $request)
    {
        $data = [];
        $visible_shopping = $shopping->is_published && $shopping->is_admin_verified && !$shopping->is_disabled;
        $can_edit = auth()->check() && (auth()->user()->id == $shopping->user_id || auth()->user()->can('edit listing'));

        if(!$visible_shopping && !$can_edit) {
            //return abort(404);
        }

		$breadcrumbs = [];
		$category = $shopping->store_category;

		while($category = $category->child) {
			$breadcrumbs[] = $category;
		}
        $data['breadcrumbs'] = array_reverse($breadcrumbs);

        $data['has_map'] = false;


        $data['shopping'] = $shopping;
        #$data['comments'] = $listing->comments()->orderBy('created_at', 'DESC')->limit(5)->get();
        #$data['comment_count'] = $listing->totalCommentCount();

        MetaTag::set('title', $shopping->name);
        MetaTag::set('description', $shopping->description);
        MetaTag::set('image', url($shopping->thumbnail));
        if($request->has('iframe')) {
            return view('shopping.iframe', $data);
        }

        return view('shopping.show', $data);
    }

    public function star($shopping) {
        $shopping->toggleFavorite();
        return view('shopping.partials.favorite', compact('shopping'));
    }
    public function spotlight($shopping) {
        if(auth()->user()->can('disable shopping')) {
            $shopping->toggleSpotlight();
        }
        return redirect(route('shopping', [$shopping, $shopping->slug]));
    }
    public function verify($shopping) {
        //sleep(2);
        if(auth()->user()->can('disable shopping')) {
            if($shopping->is_admin_verified && !$shopping->is_disabled) {
                $shopping->is_disabled = Carbon::now();
            } elseif($shopping->is_admin_verified && $shopping->is_disabled) {
                $shopping->is_disabled = null;
            }

            if(!$shopping->is_admin_verified) {
                $shopping->is_admin_verified = Carbon::now();
                $shopping->is_disabled = null;
                Mail::to(auth()->user()->email)->send(new ListingVerified($branding));
            }

            $shopping->save();
        }
        return redirect(route('shopping', [$shopping, $shopping->slug]));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($shopping)
    {
        $data = [];
        $data['shoppings_form'] = Setting::get('shoppings_form', []);
        $data['shopping'] = $shopping;
        $categories = StoreCategory::nested()->get();
        $categories = flatten($categories, 0);
        $list = [];
        foreach($categories as $category) {
            $list[''] = '';
            $list[$category['id']] = str_repeat("&mdash;", $category['depth']+1) . " " .$category['name'];
        }
        $data['categories'] = $list;

        $selected_category = null;
        $selected_category = StoreCategory::find(request('category', $shopping->category_id));
        $selected_pricing_model = null;

        $data['selected_category'] = $selected_category;
        $data['form'] = 'edit';

        return view('createstore.details', $data);
    }

    /**
     *  View to display vendor / store landing page
     *  @return Response
     */
    public function vendor($shopping, Request $request){
      $brands = Brand::orderBy('name', 'ASC')->get();
      $variants = Variant::orderBy('attribute')->get();
      $data = [];
      $data['variants'] = $variants;
      $data['brands'] = $brands;
      $data['filter'] = false;
      $cat_present = Listing::
                 select('category_id', \DB::raw('count(*) as total'))
                 ->join('categories', 'listings.category_id', 'categories.id')
                 ->groupBy('category_id')
                 ->where('store_id', $shopping->id)
                 ->get();


      /* Code for categories */
      $category_id = $request->get('category', 0) ? :0; //get the category
      if($category_id == 0){
        $top_levels = [];
         foreach($cat_present as $item){
          $top_levels[] = $this->getParentCategories($item->category->id);
          $top_levels[] = [$item->category->id];
         }
         if(count($cat_present) > 0){
           $it = call_user_func_array('array_merge', $top_levels);

           $level_categories = Category::where('parent_id', 0)->whereIn('id', $it)->get();
         }else{
           $level_categories = [];
         }
         //$it = call_user_func_array('array_merge', $top_levels);

         //$level_categories = Category::where('parent_id', 0)->whereIn('id', $it)->get();

         $parent_categories = [];
         $data['parent_categories'] = $parent_categories;
         $data['level_categories'] = $level_categories;
         //dd($level_categories);
        }else{
         $category = Category::find($category_id); //current category
         $top_levels = [];
          foreach($cat_present as $item){
           $top_levels[] = $this->getParentCategories($item->category->id);
           $top_levels[] = [$item->category->id];
          }
          if(count($cat_present) > 0){
            $it = call_user_func_array('array_merge', $top_levels);

            $data['filter_value'] = $it;
          }else{
            $data['filter_value'] = [];
          }



          $data['filter'] = true;
          $level_categories = Category::where('parent_id', $category_id)->get(); //categories on this level
          $parent_categories = $this->getParentCategories($category_id);
          //dd($level_categories);
         if(count($level_categories) == 0 && $category) {
            $level_categories = Category::where('parent_id', $category->parent_id)->get();
            $parent_categories = Category::whereIn('id', $parent_categories)->get();
            //dd($parent_categories);
         } else {
            $parent_categories = Category::whereIn('id', array_merge([$category_id], $parent_categories))->get();
         }
         $data['parent_categories'] = $parent_categories;
         $data['level_categories'] = $level_categories;
      }
      /* end Code for categories */

      $categories_nested = Category::orderBy('order', 'ASC')->nested()->get();
      $flatten_home = flattenhome($categories_nested, 0, 4);
      //dd(flattenhome($categories, 0, 4));
      //sort into depths and headers
      $result = array();
      $result_test = array();
      $result_other = array();
      $result_cat_ids = array();
      $result_cat_ids_ind = array();
      $count = count($flatten_home);
      $itiration = 0;

      $listing = Listing::where('store_id', $shopping->id)->where('is_published', 1)->orderBy('created_at', 'DESC');
      if($category_id != 0){
        $listing = $listing->where('category_id', $category_id);
      }
      // filter via variant
      // variant = [Color_Blue_White, Size_XL, XM] etc
      if($request->has('variant')){
        $input = $request->input();
        $variant_arr = $request->input('variant'); //$request->input('variant.*');
        //dd($variant_arr);
        foreach($variant_arr as $value){
          if($value != "" || $value != null){
            $split_value = explode("_", $value);
            $field = $split_value[0];
            unset($split_value[0]);
            foreach($split_value as $attribute_content){
               //$listing = $listing->where('variant_options->' . $field, $attribute_content);

               $listing = $listing->select('listings.*')
                           ->join('listing_variants as var_one', 'listings.id', 'var_one.listing_id')
                           ->where('var_one.meta->' . $field, $attribute_content)->distinct()->take(24)->pluck('id')->toArray();
               //dd($listing);
               $listing =  Listing::whereIn('listings.id', $listing);
            }

          }
        }
      }

      if($request->has('brand_input') && ($request->get('brand_input') != "" && $request->get('brand_input') != null )){
         $brand_arr = explode("_", $request->get('brand_input'));
         foreach($brand_arr as $brand_id){
           $listing =  $listing->where('listings.brand_id', $brand_id);
         }
      }

      if($request->has('discount_hidden') && ($request->get('discount_hidden') != "" && $request->get('discount_hidden') != null )){
         switch((int)$request->get('discount_hidden')){
           case 1:
             $listing =  $listing->where('discount', '>=', 80);
             break;
           case 2:
             $listing =  $listing->where('discount', '>=', 60)->where('discount', '<=', 79);
             break;
           case 3:
             $listing =  $listing->where('discount', '>=', 20)->where('discount', '<=', 59);
             break;
           case 4:
             $listing =  $listing->where('discount', '>=', 1)->where('discount', '<=', 19);
             break;
           default:
              break;
         }
      }

      if($request->has('rating') && ($request->get('rating') != "" && $request->get('rating') != null )){
        $listing = $listing->select('listings.*',
                        \DB::raw('AVG(comments.rate) as average')
                    )
                    ->join('comments', 'listings.id', 'comments.listing_id')
                    ->groupBy('listing_id')
                    ->havingRaw('AVG(comments.rate) >= ?', [(int)$request->get('rating')])
                    ->orderBy('average', 'DESC');

                    //->having('average', '>=', (int)$request->get('rating'));
                    //->where('listings.average', '>=', $request->get('rating'));
      }
      if($request->has('price_from')) {
       if($request->get('price_from') != 0 || $request->get('price_to') != 200000){
        if($request->get('price_to') != 200000){
          $listing = $listing->where('price', '>=', $request->get('price_from'))->where('price', '<=', $request->get('price_to'));
        }else{
          $listing = $listing->where('price', '>=', $request->get('price_from'));
        }
                    //->having('average', '>=', (int)$request->get('rating'));
                    //->where('listings.average', '>=', $request->get('rating'));
       }
      }
      $listing = $listing->paginate(24);

      foreach($flatten_home as $cat){
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
              $result_other[] = $cat;
              $result_test["other"] = $result_other;
              $result[] = $result_test;
              $result_cat_ids[] = $result_cat_ids_ind;
            }else{
              $result_other[] = $cat;
              $result_cat_ids_ind[] = $cat["id"];
            }

          }
      }
      //dd($result);
      $result = $this->genNavbar($result);
      $result_nav_res = $this->genNavbarRes($result);

      $categories = Category::orderBy('order', 'ASC')->take(11)->get();
      //dd($categories);

      $data['listing'] = $listing;
      $data['category_present'] = $cat_present;
      $data['categories'] = $categories;
      $data['categories_nested'] = $result;
      $data['categories_nested_res'] = $result_nav_res;
      $data['store'] = $shopping;
      $data['category_id'] = $category_id;
      //return view('shopping.vendor', $data);
      return view('shopping.vendor_responsive', $data);
    }

    /**
     *  View to display vendor / store landing page
     *  @return Response
     */
    public function category($category, Request $request){

      $brands = Brand::orderBy('name', 'ASC')->get();
      $variants = Variant::orderBy('attribute')->get();
      $data = [];
      $data['variants'] = $variants;
      $data['brands'] = $brands;
      $data['filter'] = false;

      /* Code for categories */
      $category_id = $category->id;//$request->get('category', 0) ? :0; //get the category
      if($request->has('category')){
        if((int)$request->get('category') != 0){
         $category_id = $request->get('category');
        }
      }
      $category = Category::find($category_id); //current category

      $level_categories = Category::where('parent_id', $category_id)->get(); //categories on this level
      $parent_categories = $this->getParentCategories($category_id);

      if(count($level_categories) == 0 && $category) {
          $level_categories = Category::where('parent_id', $category->parent_id)->get();
          $parent_categories = Category::whereIn('id', $parent_categories)->get();
      } else {
          $parent_categories = Category::whereIn('id', array_merge([$category_id], $parent_categories))->get();

      }
      $data['parent_categories'] = $parent_categories;
      $data['level_categories'] = $level_categories;
      $data['category'] = $category;
      $data['category_id'] = $category_id;
      /* end Code for categories */

      $categories_nested = Category::orderBy('order', 'ASC')->nested()->get();
      $flatten_home = flattenhome($categories_nested, 0, 4);
      //dd(flattenhome($categories, 0, 4));
      //sort into depths and headers
      $result = array();
      $result_test = array();
      $result_other = array();
      $result_cat_ids = array();
      $result_cat_ids_ind = array();
      $count = count($flatten_home);
      $itiration = 0;

      $full_categories = Category::all();
      $categories = $this->getSearchableCategories($full_categories, $category_id);
      $listing = Listing::whereIn('category_id', $categories)->where('is_published', 1)->orderBy('created_at', 'DESC');

      // filter via variant
      // variant = [Color_Blue_White, Size_XL, XM] etc
      if($request->has('variant')){
        $input = $request->input();
        $variant_arr = $request->input('variant'); //$request->input('variant.*');
        //dd($variant_arr);
        foreach($variant_arr as $value){
          if($value != "" || $value != null){
            $split_value = explode("_", $value);
            $field = $split_value[0];
            unset($split_value[0]);
            foreach($split_value as $attribute_content){
               //$listing = $listing->where('variant_options->' . $field, $attribute_content);

               $listing = $listing->select('listings.*')
                           ->join('listing_variants as var_one', 'listings.id', 'var_one.listing_id')
                           ->where('var_one.meta->' . $field, $attribute_content)->distinct()->take(24)->pluck('id')->toArray();
               //dd($listing);
               $listing =  Listing::whereIn('listings.id', $listing);
            }

          }
        }
      }

      if($request->has('brand_input') && ($request->get('brand_input') != "" && $request->get('brand_input') != null )){
         $brand_arr = explode("_", $request->get('brand_input'));
         foreach($brand_arr as $brand_id){
           $listing =  $listing->where('listings.brand_id', $brand_id);
         }
      }

      if($request->has('discount_hidden') && ($request->get('discount_hidden') != "" && $request->get('discount_hidden') != null )){
         switch((int)$request->get('discount_hidden')){
           case 1:
             $listing =  $listing->where('discount', '>=', 80);
             break;
           case 2:
             $listing =  $listing->where('discount', '>=', 60)->where('discount', '<=', 79);
             break;
           case 3:
             $listing =  $listing->where('discount', '>=', 20)->where('discount', '<=', 59);
             break;
           case 4:
             $listing =  $listing->where('discount', '>=', 1)->where('discount', '<=', 19);
             break;
           default:
              break;
         }
      }

      if($request->has('rating') && ($request->get('rating') != "" && $request->get('rating') != null )){
        $listing = $listing->select('listings.*',
                        \DB::raw('AVG(comments.rate) as average')
                    )
                    ->join('comments', 'listings.id', 'comments.listing_id')
                    ->groupBy('listing_id')
                    ->havingRaw('AVG(comments.rate) >= ?', [(int)$request->get('rating')])
                    ->orderBy('average', 'DESC');

                    //->having('average', '>=', (int)$request->get('rating'));
                    //->where('listings.average', '>=', $request->get('rating'));
      }
      if($request->has('price_from')) {
       if($request->get('price_from') != 0 || $request->get('price_to') != 200000){
         if($request->get('price_to') != 200000){
          $listing = $listing->where('price', '>=', $request->get('price_from'))
                           ->where('price', '<=', $request->get('price_to'));
         }else{
          $listing = $listing->where('price', '>=', $request->get('price_from'));
         }
                    //->having('average', '>=', (int)$request->get('rating'));
                    //->where('listings.average', '>=', $request->get('rating'));
       }
      }
      $listing = $listing->paginate(24);

      foreach($flatten_home as $cat){
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
              $result_other[] = $cat;
              $result_test["other"] = $result_other;
              $result[] = $result_test;
              $result_cat_ids[] = $result_cat_ids_ind;
            }else{
              $result_other[] = $cat;
              $result_cat_ids_ind[] = $cat["id"];
            }

          }
      }
      //dd($result);
      $result = $this->genNavbar($result);
      $result_nav_res = $this->genNavbarRes($result);
      $categories = Category::orderBy('order', 'ASC')->take(11)->get();
      //dd($categories);

      $data['listing'] = $listing;
      $data['categories'] = $categories;
      $data['categories_nested'] = $result;
      $data['categories_nested_res'] = $result_nav_res;
      return view('shopping.category_responsive', $data);
    }

    private function genNavbar($result){
      $new_array =[];
      $iti = 0;
      foreach($result as $cat){
          $itiration = 0;
          $html_append = "";
          $result_array = [];
          $cat_other = $cat["other"];
          foreach($cat_other as $other){
             $itiration++;
             if( $other["depth"] == 1){
               if($html_append != ""){
                 $html_append .= "</ul>";
                 $result_array[] = $html_append;
               }
               $html_append = "<h4 class='drop-drop__list-title'>$other[name]</h4><ul>";
               // Added for final loop
               if($itiration == count($cat_other)){
                  $html_append .= "</ul>";
                  $result_array[] = $html_append;
               }
             }else{
               $html_append .= "<li><a href=''>$other[name]</a></li>";
               if($itiration == count($cat["other"])){
                 $html_append .= "</ul>";
                 $result_array[] = $html_append;
                 $html_append= "";
               }
             }
          }
          $cat["new_array"]= $result_array;
          $new_array[] = $cat;
          //dd($result);
          /*
          $itiration_b = 0;
          $loop_b = 0;
          $wrap = false;
          $count_b = count($result_array);
          $html_new_app = "";
          for($result_array as $html){
            if(){

            }
          }
          */
      }
      return $new_array;
    }

    private function genNavbarRes($result){
      $new_array =[];
      $iti = 0;
      foreach($result as $cat){
          $itiration = 0;
          $html_append = "";
          $result_array = [];
          $cat_other = $cat["other"];
          foreach($cat_other as $other){
             $itiration++;
             if( $other["depth"] == 1){
               if($html_append != ""){
                 $html_append .= "</div>";
                 $result_array[] = $html_append;
               }
               $route = categoryUrl($other['id'], $other['slug']);
               $html_append = "<div class='resp_menu__mega-inner-container'><a href='$route' class='resp-menu__mega-link resp-menu__mega-link-title'>$other[name]</a>";
               if($itiration == count($cat_other)){
                  $html_append .= "</div>";
                  $result_array[] = $html_append;
               }
             }else{
               $route = categoryUrl($other['id'], $other['slug']);
               $html_append .= "<a class='resp-menu__mega-link' href='$route'>$other[name]</a>";
               if($itiration == count($cat["other"])){
                 $html_append .= "</div>";
                 $result_array[] = $html_append;
                 $html_append= "";
               }
             }
          }
          $cat["new_array"]= $result_array;
          $new_array[] = $cat;
          //dd($result);
          /*
          $itiration_b = 0;
          $loop_b = 0;
          $wrap = false;
          $count_b = count($result_array);
          $html_new_app = "";
          for($result_array as $html){
            if(){

            }
          }
          */
      }
      return $new_array;
    }

    private function getCat(Request $request){
      $category_id = $request->get('category', 0) ? :0; //get the category
      $category = Category::find($category_id); //current category

      $level_categories = Category::where('parent_id', $category_id)->get(); //categories on this level
      $parent_categories = $this->getParentCategories($category_id);
    }

    private function getParentCategories($category_id, $parents = []) {
        $category = Category::find($category_id);
        if($category) {
            $parents[] = $category->parent_id;
            if($category->parent_id) {
                $parents = $this->getParentCategories($category->parent_id, $parents);
            }
        }
        return $parents;
    }

    private function getSearchableCategories($full_categories, $category_id, $level = null) {

        $categories = $full_categories->where('parent_id', (int) $category_id)->pluck('id')->all();

        foreach($categories as $category) {
            if(!$level) {
                $children = $this->getSearchableCategories($full_categories, $category);
                $categories = array_merge($categories, $children);
            }
        }
        $categories = array_unique($categories);

        //current category
        $categories[] = $category_id;
        return $categories;
    }

}
