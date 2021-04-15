<?php

namespace App\Http\Controllers;

use App\Mail\ListingVerified;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use App\Models\Filter;
use App\Models\Listing;
use App\Models\Category;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use View;
use Location;
use Mapper;
use Setting;
use MetaTag;
use Mail;
use Carbon\Carbon;

class ListingController extends Controller
{

    protected $category_id;

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index($listing, $slug, Request $request)
    {
        //var_dump($listing->quantity);
        //die();
        $cart_quantity = 0;
        if(auth()->check()){
          $userId = auth()->user()->id;
          $cart_content = \Cart::session($userId)->getContent();
          foreach($cart_content as $item){
            if($item->attributes->listing_id == $listing->id){
              $cart_quantity = $cart_quantity + $item->quantity;
            }
          }
        }else{
          $anonym_cart = app('anonymcart');
          $cart_content = $anonym_cart->getContent();
          foreach($cart_content as $item){
            if($item->attributes->listing_id == $listing->id){
              $cart_quantity = $cart_quantity + $item->quantity;
            }
          }
        }
        $selected_quantity = 0;
        if($request->has('quantity-hidden')){
          $selected_quantity = $request->get('quantity-hidden');
        }
        $avail_quantity = $listing->stock - $cart_quantity;
        $data = [];
        $data['avail_quantity'] = $avail_quantity;
        $data['selected_quantity'] = $selected_quantity;
        $visible_listing = $listing->is_published && $listing->is_admin_verified && !$listing->is_disabled;
        $can_edit = auth()->check() && (auth()->user()->id == $listing->user_id || auth()->user()->can('edit listing'));

        if(!$visible_listing && !$can_edit) {
            return abort(404);
        }

		   $breadcrumbs = [];
		   $category = $listing->category;
		   while($category = $category->child) {
			  $breadcrumbs[] = $category;
		   }
       $data['breadcrumbs'] = array_reverse($breadcrumbs);

       $data['has_map'] = false;
        if($listing->location && setting('googlmapper.key')) {
            Mapper::map($listing->location->getLat(), $listing->location->getLng(), ['zoom' => 14, 'zoomControl' => false, 'streetViewControl' => false, 'mapTypeControl' => false, 'center' => true, 'marker' => true]);
            $data['has_map'] = true;
        }

        $data['listing'] = $listing;
        #$data['comments'] = $listing->comments()->orderBy('created_at', 'DESC')->limit(5)->get();
        #$data['comment_count'] = $listing->totalCommentCount();

        MetaTag::set('title', $listing->title);
        MetaTag::set('description', $listing->description);
        MetaTag::set('image', url($listing->thumbnail));
        if($request->has('iframe')) {
            return view('listing.iframe', $data);
        }
        $category = Category::where('parent_id', 0)->whereNotNull('order')->orderBy('order', 'ASC')->take(11)->get();
        $data['quantity'] = 6;
        $data['categories'] = $category;
        // Best selling items
        $best_sellers = Listing::select('listings.*',
                        \DB::raw('SUM(order_items.quantity) as quantity')
                    )
                    ->join('order_items', 'listings.id', 'order_items.listing_id')
                    ->groupBy('listing_id')
                    ->orderBy('quantity', 'DESC')
                    ->take(20)
                    ->get();
        $data['best_sellers'] = $best_sellers;

        $categories_nested = Category::orderBy('order', 'ASC')->nested()->get();
        $flatten_home = flattenhome($categories_nested, 0, 4);

        //dd($flatten_home);
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
        $result_nav = $this->genNavbar($result);
        $result_nav_res = $this->genNavbarRes($result);

        $data['categories_nested'] = $result_nav;
        $data['categories_nested_res'] = $result_nav_res;
        //return view('listing.show', $data);
        return view('listing.show_latest_responsive', $data);
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function wholesale($listing, $slug, Request $request)
    {
        //var_dump($listing->quantity);
        //die();
        $cart_quantity = 0;
        if(auth()->check()){
          $userId = auth()->user()->id;
          $cart_content = \Cart::session($userId)->getContent();
          foreach($cart_content as $item){
            if($item->attributes->listing_id == $listing->id){
              $cart_quantity = $cart_quantity + $item->quantity;
            }
          }
        }else{
          $anonym_cart = app('anonymcart');
          $cart_content = $anonym_cart->getContent();
          foreach($cart_content as $item){
            if($item->attributes->listing_id == $listing->id){
              $cart_quantity = $cart_quantity + $item->quantity;
            }
          }
        }
        $selected_quantity = 0;
        if($request->has('quantity-hidden')){
          $selected_quantity = $request->get('quantity-hidden');
        }
        $avail_quantity = $listing->stock - $cart_quantity;
        $data = [];
        $data['avail_quantity'] = $avail_quantity;
        $data['selected_quantity'] = $selected_quantity;
        $visible_listing = $listing->is_published && $listing->is_admin_verified && !$listing->is_disabled;
        $can_edit = auth()->check() && (auth()->user()->id == $listing->user_id || auth()->user()->can('edit listing'));

        if(!$visible_listing && !$can_edit) {
            return abort(404);
        }

		   $breadcrumbs = [];
		   $category = $listing->category;
		   while($category = $category->child) {
			  $breadcrumbs[] = $category;
		   }
       $data['breadcrumbs'] = array_reverse($breadcrumbs);

       $data['has_map'] = false;
        if($listing->location && setting('googlmapper.key')) {
            Mapper::map($listing->location->getLat(), $listing->location->getLng(), ['zoom' => 14, 'zoomControl' => false, 'streetViewControl' => false, 'mapTypeControl' => false, 'center' => true, 'marker' => true]);
            $data['has_map'] = true;
        }

        $data['listing'] = $listing;
        #$data['comments'] = $listing->comments()->orderBy('created_at', 'DESC')->limit(5)->get();
        #$data['comment_count'] = $listing->totalCommentCount();

        MetaTag::set('title', $listing->title);
        MetaTag::set('description', $listing->description);
        MetaTag::set('image', url($listing->thumbnail));
        if($request->has('iframe')) {
            return view('listing.iframe', $data);
        }
        $category = Category::where('parent_id', 0)->whereNotNull('order')->orderBy('order', 'ASC')->take(11)->get();
        $data['quantity'] = 6;
        $data['categories'] = $category;
        // Best selling items
        $best_sellers = Listing::select('listings.*',
                        \DB::raw('SUM(order_items.quantity) as quantity')
                    )
                    ->join('order_items', 'listings.id', 'order_items.listing_id')
                    ->groupBy('listing_id')
                    ->orderBy('quantity', 'DESC')
                    ->take(20)
                    ->get();
        $data['best_sellers'] = $best_sellers;

        $categories_nested = Category::orderBy('order', 'ASC')->nested()->get();
        $flatten_home = flattenhome($categories_nested, 0, 4);

        //dd($flatten_home);
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
        $result_nav = $this->genNavbar($result);

        $data['categories_nested'] = $result_nav;
        return view('listing.show_latest_wholesale', $data);
    }

    public function star($listing) {
        $listing->toggleFavorite();
        return view('listing.partials.favorite', compact('listing'));
    }
    public function spotlight($listing) {
        if(auth()->user()->can('disable listing')) {
            $listing->toggleSpotlight();
        }
        return redirect(route('listing', [$listing, $listing->slug]));
    }
    public function verify($listing) {
        //sleep(2);
        if(auth()->user()->can('disable listing')) {
            if($listing->is_admin_verified && !$listing->is_disabled) {
                $listing->is_disabled = Carbon::now();
            } elseif($listing->is_admin_verified && $listing->is_disabled) {
                $listing->is_disabled = null;
            }

            if(!$listing->is_admin_verified) {
                $listing->is_admin_verified = Carbon::now();
                $listing->is_disabled = null;
                Mail::to(auth()->user()->email)->send(new ListingVerified($listing));
            }

            $listing->save();
        }
        return redirect(route('listing', [$listing, $listing->slug]));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($listing)
    {
        $data = [];
        $data['listings_form'] = Setting::get('listings_form', []);
        $data['listing'] = $listing;
        $categories = Category::nested()->get();
        $categories = flatten($categories, 0);
        $list = [];
        foreach($categories as $category) {
            $list[''] = '';
            $list[$category['id']] = str_repeat("&mdash;", $category['depth']+1) . " " .$category['name'];
        }
        $data['categories'] = $list;


        $data['pricing_models'] = [];
        foreach(config('pricing-models') as $price_option => $value) {
            $data['pricing_models'][$price_option] = $value['label'];
        }

        $selected_category = null;
        $selected_category = Category::find(request('category', $listing->category_id));
        $selected_pricing_model = null;

        $data['selected_category'] = $selected_category;
        $data['selected_pricing_model'] = $selected_pricing_model;
        $data['form'] = 'edit';

        return view('post.details', $data);
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
               // Added for final loop
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
