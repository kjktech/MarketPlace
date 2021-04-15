<?php

namespace App\Http\Controllers;

use Grimzy\LaravelMysqlSpatial\Types\LineString;
use Grimzy\LaravelMysqlSpatial\Types\Polygon;
use Illuminate\Http\Request;
use App\Models\Filter;
use App\Models\Listing;
use App\Models\Category;
use App\Models\Directory;
use App\Models\Store;
use App\Models\User;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Setting;
use MetaTag;
use GeoIP;

class ScanController extends Controller
{

    public function listings(Request $request) {
        #save address in session
        return redirect(route('home'));
        foreach($request->only(['lat', 'lng', 'bounds', 'location']) as $k => $v) {
            session([$k => $v]);
        }

        $data = $this->getListingData($request);
        if($request->get('ajax')) {
            return response()->json($data);
        }
        MetaTag::set('title', __("Browse listings"));
        $reflFunc = new \ReflectionFunction('active');
        $finder = $reflFunc->getFileName() . ':' .$reflFunc->getStartLine();
        //$data['finder'] = $finder;
        return view('scan.index', $data);
    }

    public function marketplace(Request $request) {
        //return redirect(route('home'));
        // Top pick appliance
        // Top pick fashion
        $featured_brands = Directory::take(20)->get();
        $listing_appliance = Listing::where('category_id', 1)->whereNull('staff_pick')->orderBy('updated_at', 'DESC')->take(16)->get();
        $listing_fashion = Listing::where('category_id', 1)->whereNull('staff_pick')->orderBy('updated_at', 'DESC')->take(16)->get();

        // Best phones
        //$this->comments()->where('approved', true)->avg('rate')
        $best_phones = Listing::where('category_id', 1)->select('listings.*',
                        \DB::raw('AVG(comments.rate) as average')
                    )
                    ->join('comments', 'listings.id', 'comments.listing_id')
                    ->groupBy('listing_id')
                    ->orderBy('average', 'DESC')
                    ->take(20)
                    ->get();

        // Best selling items
        $best_sellers = Listing::select('listings.*',
                        \DB::raw('SUM(order_items.quantity) as quantity')
                    )
                    ->join('order_items', 'listings.id', 'order_items.listing_id')
                    ->groupBy('listing_id')
                    ->orderBy('quantity', 'DESC')
                    ->take(20)
                    ->get();

        $listing_discount = Listing::where('discount', '>', 0)->orderBy('updated_at', 'DESC')->take(16)->get();
        $listing_random = Listing::orderBy(\DB::raw('RAND()'))->take(16)->get();
        $category = Category::where('parent_id', 0)->whereNotNull('order')->orderBy('order', 'ASC')->take(11)->get();

        foreach($request->only(['lat', 'lng', 'bounds', 'location']) as $k => $v) {
            session([$k => $v]);
        }

        $data = $this->getListingData($request);
        if($request->get('ajax')) {
            return response()->json($data);
        }
        MetaTag::set('title', __("Browse listings"));
        $reflFunc = new \ReflectionFunction('active');
        $finder = $reflFunc->getFileName() . ':' .$reflFunc->getStartLine();
        $data['categories'] = $category;
        $data['discount'] = $listing_discount;
        $data['best_sellers'] = $best_sellers;
        $data['recommended'] = $listing_random;
        $data['best_phone'] = $best_phones;
        $data['listing_appliance'] = $listing_appliance;
        $data['listing_fashion'] = $listing_fashion;
        $data['featured_brands'] = $featured_brands;

        $categories_nested = Category::orderBy('order', 'ASC')->nested()->get();
        $flatten_home = flattenhome($categories_nested, 0, 4);

        //sort into depths and headers
        $result = array();
        $result_test = array();
        $result_other = array();
        $result_cat_ids = array();
        $result_cat_ids_ind = array();
        $count = count($flatten_home);
        $itiration = 0;
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
                $result_test["other"] = $result_other;
                $result[] = $result_test;
                $result_cat_ids[] = $result_cat_ids_ind;
              }else{
                $result_other[] = $cat;
                $result_cat_ids_ind[] = $cat["id"];
              }

            }
        }
        //dd($result_cat_ids);
        $listings_array = array();
        //Listings for each section
        foreach($result_cat_ids as $arr_id){
          //dd($arr_id[0]);
          $listings_array[] = Listing::whereIn('category_id', $arr_id)->take(20)->get();
        }
        $data['results_test_products'] = $listings_array;
        $data['results_test'] = $result;
        //$data['finder'] = $finder;
        return view('scan.marketplace', $data);
    }

    public function getFacets() {
        /*
            listing fields editor must have category selector

            inputFilter     ->  anything
            refinementList  ->  get list of values
            menuSelect      ->  get list of values
            rangeSlider     ->  min, max
            priceRange      ->  min, max

            go through listing fields
                get searchUI
                check categories
        */
        $filters = Filter::where('is_hidden', 0)->where('is_searchable', 1)->orderBy('position', 'ASC')->get();
        $facet_groups = [];
        foreach($filters as $filter) {
            $facet_group = [];
            $facet_group['field'] = $filter->field;
            $facet_group['name'] = $filter->name;
            $facet_group['search_ui'] = $filter->search_ui;
            $listings = new Listing();
            if(in_array($filter->search_ui, ['refinementList', 'menuSelect'])) {
                $facet_group['options'] = [];
                if($filter->form_input_meta && isset($filter->form_input_meta['values'])) {
                    foreach($filter->form_input_meta['values'] as $k => $v) {
                        $tmp = [];
                        $tmp['name'] = $v['label'];
                        $tmp['value'] = $v['value'] ;
                        $facet_group['options'][] = $tmp;
                    }

                } else {
                    $listings = $listings->groupBy($filter->field)
                                    ->whereNotNull($filter->field.'')
                                    ->select($filter->field.' as name', ''.$filter->field.' as value', \DB::raw('count(*) as total'))
                                    ->orderBy($filter->field, 'ASC')
                                    ->get();

                    $facet_group['options'] = $listings->toArray();
                }

            } else if(in_array($filter->search_ui, ['rangeSlider', 'priceRange'])) {
                $min = $listings->min($filter->field);
                $max = $listings->max($filter->field);
                $facet_group['options'] = [$min, $max];
            } else {
                $facet_group['options'] = null;
            }
            $facet_groups[$filter->field] = json_decode(json_encode($facet_group));
        }

        return $facet_groups;
    }

    public function getListingData(Request $request) {

        $data = [];
        $data['facets'] = $this->getFacets();

        $listings = new Listing();
        $listings = $listings->active();

        //search by title, description, tags
        if($request->get('q')) {
            $listings = $listings->search($request->get('q'));
            #dd(debug_backtrace ());
        }
        if($request->get('price_min')) {
            $listings = $listings->where('price', '>=', (int) $request->get('price_min'));
        }
        if($request->get('price_max')) {
            $listings = $listings->where('price', '<=', (int) $request->get('price_max'));
        }

        $listings = $listings->with('pricing_model')->with('user');

        $filters = Filter::orderBy('position', 'ASC')->where('is_hidden', 0)->where('is_default', 0)->get();
        $is_filtered = false;
        if($request->has('category')) {
            $is_filtered = true;
        }

        foreach($filters as $filter) {
            if($filter->default){
                continue;
            }
            if(in_array($filter->search_ui, ['menuSelect']) && $request->input($filter->field)) {
                $listings = $listings->where('meta->' . $filter->field, $request->input($filter->field));
                $is_filtered = true;
            } elseif(in_array($filter->search_ui, ['refinementList']) && $request->input($filter->field)) {

                $filter_values = collect($request->input($filter->field))->filter(function ($value, $key) {
                    return $value == 1;
                })->keys();

                $listings->where(function ($query) use($filter_values, $filter) {
                    foreach($filter_values as $filter_value) {
                        $filter_value = urldecode($filter_value);
                        $filter_value = trim($filter_value,'"');
                        $query->orWhereRaw("JSON_CONTAINS(meta, '".addslashes(json_encode([$filter->field => $filter_value]))."')");
                    }
                });
                $is_filtered = true;
            } elseif(in_array($filter->search_ui, ['rangeSlider', 'priceRange'])) {
                if($request->input($filter->field.'_min')) {
                    $listings = $listings->where('meta->'.$filter->field, '>=', (int) $request->input($filter->field.'_min'));
                    $is_filtered = true;
                }
                if($request->input($filter->field.'_max')) {
                    $listings = $listings->where('meta->'.$filter->field, '<=', (int) $request->input($filter->field.'_max'));
                    $is_filtered = true;
                }


            } else {
                if($request->input($filter->field)) {
                    $listings = $listings->where('meta->' . $filter->field, $request->input($filter->field));
                    $is_filtered = true;
                }
            }
        }

        $category_id = $request->get('category', 0) ? :0; //get the category

		//get listings with category and child categories
        $full_categories = Category::all();
        $categories = $this->getSearchableCategories($full_categories, $category_id); //get all child categories

        $listings = $listings->whereIn('category_id', $categories);
        //$listings = $listings->whereNotNull('lat');
        //$listings = $listings->whereNotNull('lng');
        //$listings = $listings->whereNotNull('location');

        $this->categories = $categories;
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



        //distance calculations
        $lat = $request->get('lat') ? : GeoIP::getLatitude();
        $lng = $request->get('lng') ? : GeoIP::getLongitude();
        if($request->get('bounds') || ( $request->get('lat') && $request->get('lng') )) {
            $bounds = $request->get('bounds');
            $bounds = explode(",", $bounds);

            if(count($bounds) == 4) {
                $swLat = $bounds[0];
                $swLon = $bounds[1];
                $neLat = $bounds[2];
                $neLon = $bounds[3];

                $southWest = new \Geokit\LatLng($swLat, $swLon);
                $northEast = new \Geokit\LatLng($neLat, $neLon);
                $bounds = new \Geokit\Bounds($southWest, $northEast);

                if($request->input('distance') && (int) $request->input('distance') >= 0) {

                    $math = new \Geokit\Math();
                    $expandedBounds = $math->expand($bounds, $request->input('distance').config('marketplace.distance_unit'));

                    $swLat = $expandedBounds->getSouthWest()->getLatitude();
                    $swLon = $expandedBounds->getSouthWest()->getLongitude();
                    $neLat = $expandedBounds->getNorthEast()->getLatitude();
                    $neLon = $expandedBounds->getNorthEast()->getLongitude();
                }

                $polygon = new Polygon([new LineString([
                    new Point($swLat, $swLon),
                    new Point($neLat, $swLon),
                    new Point($neLat, $neLon),
                    new Point($swLat, $neLon),
                    new Point($swLat, $swLon),
                ])]);
                if($request->input('distance') >= 0) {
                    $listings = $listings->within('location', $polygon);
                }

            } else {
                if($request->get('distance')) {
                    $listings = $listings->distanceSphere($request->get('distance', 1000)*1000, new Point($lat, $lng), 'location');
                }
            }

            $listings = $listings->distanceSphereValue('location', new Point($lat, $lng));
        }

        $data['view'] = $request->get('view', setting('default_view', 'map'));
        $data['filters'] = $filters;
        $data['lat'] = $lat;
        $data['lng'] = $lng;

        $sort = $request->input('sort')?:'date';
        //$listings = $listings->orderByRaw('IF(priority_until>NOW(), 1, 0) DESC');
        if($sort == 'date') {
            $listings = $listings->orderBy('created_at', 'DESC');
        }
        if($sort == 'price_lowest_first') {
            $listings = $listings->orderBy('price', 'ASC');
        }
        if($sort == 'price_highest_first') {
            $listings = $listings->orderBy('price', 'DESC');
        }
        if($sort == 'distance') {
            $listings = $listings->orderBy('distance', 'ASC');
        }

        if($request->get('ajax')) {
            $data['map_listings'] =  $listings->whereNotNull('lat')->whereNotNull('lng')->limit(1000)->get();
        }

        $data['params'] = $request->all();
        $data['sort'] = $sort;

        #dd($listings->get());
        $data['listings'] = $listings->paginate(24);
        $data['is_filtered'] = $is_filtered;

        $data['load_time'] = round(microtime(true) - LARAVEL_START);
        return $data;
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

    public function marketplacenew(Request $request) {
        //return redirect(route('home'));
        // Top pick appliance
        // Top pick fashion
        $featured_brands = Directory::take(20)->get();
        $featured_stores = Store::take(20)->get();
        $listing_appliance = Listing::where('category_id', 1)->whereNull('staff_pick')->orderBy('updated_at', 'DESC')->take(16)->get();
        $listing_fashion = Listing::where('category_id', 1)->whereNull('staff_pick')->orderBy('updated_at', 'DESC')->take(16)->get();

        // Best phones
        //$this->comments()->where('approved', true)->avg('rate')
        $best_phones = Listing::where('category_id', 1)->select('listings.*',
                        \DB::raw('AVG(comments.rate) as average')
                    )
                    ->join('comments', 'listings.id', 'comments.listing_id')
                    ->groupBy('listing_id')
                    ->orderBy('average', 'DESC')
                    ->take(20)
                    ->get();

        // Best selling items
        $best_sellers = Listing::select('listings.*',
                        \DB::raw('SUM(order_items.quantity) as quantity')
                    )
                    ->join('order_items', 'listings.id', 'order_items.listing_id')
                    ->groupBy('listing_id')
                    ->orderBy('quantity', 'DESC')
                    ->take(20)
                    ->get();

        $listing_discount = Listing::where('discount', '>', 0)->orderBy('updated_at', 'DESC')->take(16)->get();
        $listing_random = Listing::orderBy(\DB::raw('RAND()'))->take(16)->get();
        //dd($listing_discount);
        if(count($listing_discount) == 0){
           // No deals so show regular listings filtered by updates
           $listing_discount = Listing::where('discount', '=', 0)->orderBy('updated_at', 'DESC')->take(16)->get();
        }
        //dd($listing_discount);
        #save address in session
        $category = Category::where('parent_id', 0)->whereNotNull('order')->orderBy('order', 'ASC')->take(11)->get();
        //dd($category);
        foreach($request->only(['lat', 'lng', 'bounds', 'location']) as $k => $v) {
            session([$k => $v]);
        }
        $discount_html = $this->buildItemHtml($listing_discount);
        $discount_sole_html = $this->buildSoleDiscountItem($listing_discount);
        $best_seller_html = $this->buildItemHtml($best_sellers);
        $bestphone_html = $this->buildItemHtml($best_phones);
        $appliance_html = $this->buildItemHtml($listing_appliance);
        $fashion_html = $this->buildItemHtml($listing_fashion);

        //dd($appliance_html);


        $data = $this->getListingData($request);
        if($request->get('ajax')) {
            return response()->json($data);
        }
        MetaTag::set('title', __("Browse listings"));
        $reflFunc = new \ReflectionFunction('active');
        $finder = $reflFunc->getFileName() . ':' .$reflFunc->getStartLine();
        $data['categories'] = $category;
        $data['discount'] = $discount_html;
        $data['top_deals'] = $listing_discount;
        $data['recommended'] = $listing_random;
        $data['discount_sole'] = $discount_sole_html;
        $data['best_sellers'] = $best_seller_html;
        $data['best_phone'] = $bestphone_html;
        $data['listing_appliance'] = $appliance_html;
        $data['listing_fashion'] = $fashion_html;
        $data['featured_brands'] = $featured_brands;
        $data['featured_stores'] = $featured_stores;

        $categories_nested = Category::orderBy('order', 'ASC')->nested()->get();
        $flatten_home = flattenhome($categories_nested, 0, 4);

        //var_export($flatten_home);
        //sort into depths and headers
        $result = array();
        $result_test = array();
        $result_other = array();
        $result_cat_ids = array();
        $result_cat_ids_ind = array();
        $count = count($flatten_home);
        $itiration = 0;
        foreach($flatten_home as $cat){
            $itiration++;
            $flag_header = false;
            if($cat['depth'] == 0){
              if(count($result_test) > 0){
                $result_test["other"] = $result_other;
                $count_b = Listing::whereIn('category_id', $result_cat_ids_ind)->distinct('id')->count('id');
                $result_test["count"] = $count_b;
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
                // Total Count of products
                $count = Listing::whereIn('category_id', $result_cat_ids_ind)->distinct('id')->count('id');
                $result_test["count"] = $count;
                $result[] = $result_test;
                //dd($result);
                $result_cat_ids[] = $result_cat_ids_ind;
              }else{
                $result_other[] = $cat;
                $result_cat_ids_ind[] = $cat["id"];
              }

            }
        }
        //var_export($result);
        $result_nav = $this->genNavbar($result);
        //var_export($result);
        $result_nav_res = $this->genNavbarRes($result);
        //dd($result_cat_ids);
        $listings_array = array();
        //Listings for each section
        foreach($result_cat_ids as $arr_id){
          //dd($arr_id[0]);
          $listings_array[] = Listing::whereIn('category_id', $arr_id)->take(20)->get();
        }
        $data['results_test_products'] = $listings_array;
        $data['results_test'] = $result;
        $data['categories_nested'] = $result_nav;
        $data['categories_nested_res'] = $result_nav_res;
        //$data['finder'] = $finder;
        //return view('scan.marketplace_new', $data);
        return view('scan.marketplace_responsive', $data);
    }

    public function searchproducts(Request $request){
      $listings = new Listing;
      if($request->get('q')) {
        $listings = Listing::where('title', 'like', "%{$request->get('q')}%");
          //$users = $users->role('member');
          $data['$listings'] = $listings->orderBy('title', 'ASC')->take(10)->get();
          return response()->json(
              $data['$listings']
          );
      }
    }

    private function buildItemHtml($listing_arr){
      $discount_html = "";
      foreach($listing_arr as $item){
        $brand = 'Generic';
        if($item->brand){
          $brand = $item->brand->name;
        }
        $discount_html .= "<div class='product__card product__crf'>"
            ."<div class='product-modal'>"
                ."<div class='product-modal__content'>"
                    ."<a href='$item->url' class='quick-view'>View</a>"
                ."</div>"
            ."</div>"
            ."<div class='paper'>"
                ."<div class='product-image'>"
                    ."<div class='badge__new-container'>"
                        ."<div class='badge__new'>NEW</div>"
                    ."</div>"
                    ."<div class='img__overlay'>"
                        ."<img src='$item->cover_image' class='product__img' alt='product'>"
                    ."</div>"
                    ."<span class='product__badge'></span>"
                ."</div>"

                ."<div>"
                    ."<span class='brand__name'>$brand</span>"
                    ."<hr>"
                    ."<span class='full__brand-name'>"
                        ."$item->title"
                    ."</span>"

                    ."<div class='price-details'>"
                        ."<p class='price'>"
                            ."&#x20A6;$item->price"
                        ."</p>"
                        ."<p class='percentage__off'>"
                            ."22%"
                        ."</p>"
                    ."</div>"
                    ."<div class='ratings-numbers'>"
                        ."<div class='ratings'>"
                        ."</div>"
                        ."<div class='numbers'>"
                            .""
                        ."</div>"
                    ."</div>"
                ."</div>"
            ."</div>"
        ."</div>";

      }
      return $discount_html;
    }

    private function buildSoleDiscountItem($listing_arr){
      $discount_html = "";
      foreach($listing_arr as $item){
        $brand = 'Generic';
        if($item->brand){
          $brand = $item->brand->name;
        }
        $discount_html .= "<div class='product__card product_c_f_R'>"
            ."<div class='product-modal'>"
                ."<div class='product-modal__content'>"
                    ."<a href='$item->url' class='quick-view'>View</a>"
                ."</div>"
            ."</div>"
            ."<div class='paper'>"
                ."<div class='product-image'>"
                    ."<div class='badge__new-container'>"
                        ."<div class='badge__new'>NEW</div>"
                    ."</div>"
                    ."<div class='img__overlay'>"
                        ."<img src='$item->cover_image' class='product__img' alt='product'>"
                    ."</div>"
                    ."<span class='product__badge'></span>"
                ."</div>"

                ."<div>"
                    ."<span class='brand__name'>$brand</span>"
                    ."<hr>"
                    ."<span class='full__brand-name'>"
                        ."$item->title"
                    ."</span>"

                    ."<div class='price-details'>"
                        ."<p class='price'>"
                            ."&#x20A6;$item->price"
                        ."</p>"
                        ."<p class='percentage__off'>"
                            ."22%"
                        ."</p>"
                    ."</div>"
                    ."<div class='ratings-numbers'>"
                        ."<div class='ratings'>"
                        ."</div>"
                        ."<div class='numbers'>"
                            .""
                        ."</div>"
                    ."</div>"
                ."</div>"
            ."</div>"
        ."</div>";

      }
      return $discount_html;
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
               $route = categoryUrl($other['id'], $other['slug']);
               $html_append = "<h4 class='drop-drop__list-title'><a href='$route'>$other[name]</a></h4><ul>";
               // Added for final loop
               if($itiration == count($cat_other)){
                  $html_append .= "</ul>";
                  $result_array[] = $html_append;
               }
             }else{
               $route = categoryUrl($other['id'], $other['slug']);
               $html_append .= "<li><a href='$route'>$other[name]</a></li>";
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

}
