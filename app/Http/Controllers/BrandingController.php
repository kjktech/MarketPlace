<?php

namespace App\Http\Controllers;

use App\Mail\BusinessVerified;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use App\Models\Directory;
use App\Models\DirectoryCategory;
use App\Models\DirectorySocialPage;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use View;
use Location;
use Mapper;
use Setting;
use MetaTag;
use Mail;
use Carbon\Carbon;

class BrandingController extends Controller
{

    protected $category_id;

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index($branding, $slug, Request $request)
    {
        views($branding)->record();
        $data = [];
        $visible_branding = $branding->is_published && $branding->is_admin_verified && !$branding->is_disabled;
        $can_edit = auth()->check() && (auth()->user()->id == $branding->user_id || auth()->user()->can('edit listing'));

        if(!$visible_branding && !$can_edit) {
            //return abort(404);
        }
      $open_hours = $this->arrangeOpeningHours($branding->opening_hours->asStructuredData());
      $data['opening_hours'] = $open_hours;
		  $breadcrumbs = [];
		  $category = $branding->directory_category;
      $par_cat = $this->getParentCategories($category->id);
      $cat_id = $category->id;
       array_push($par_cat, $cat_id);

      $rel_branding = Directory::whereIn('directory_category_id', $par_cat)->active()->where('id', '!=', $branding->id)->take(6)->get();

		  while($category = $category->child) {
			  $breadcrumbs[] = $category;
		  }
      $data['breadcrumbs'] = array_reverse($breadcrumbs);

      $data['has_map'] = false;
      if($branding->location /*&& setting('googlmapper.key')*/) {
            Mapper::map($branding->location->getLat(), $branding->location->getLng(), ['zoom' => 14, 'zoomControl' => false, 'streetViewControl' => false, 'mapTypeControl' => false, 'center' => true, 'marker' => true]);
            $data['has_map'] = true;
       }

       $data['branding'] = $branding;
        #$data['comments'] = $listing->comments()->orderBy('created_at', 'DESC')->limit(5)->get();
        #$data['comment_count'] = $listing->totalCommentCount();

        MetaTag::set('title', $branding->name);
        MetaTag::set('description', $branding->description);
        MetaTag::set('image', url($branding->thumbnail));
        if($request->has('iframe')) {
            return view('branding.iframe', $data);
        }
        $data['rel_branding'] = $rel_branding;

        return view('branding.regularbrand', $data);
    }

    public function star($branding) {
        $branding->toggleFavorite();
        return view('branding.partials.favorite', compact('branding'));
    }
    public function follow($branding) {
        auth()->user()->toggleFollow($branding);
        return view('branding.partials.follow', compact('branding'));
    }
    public function spotlight($branding) {
        if(auth()->user()->can('disable listing')) {
            $branding->toggleSpotlight();
        }
        return redirect(route('branding', [$branding, $branding->slug]));
    }
    public function verify($branding) {
        //sleep(2);
        if(auth()->user()->can('disable listing')) {
            if($branding->is_admin_verified && !$branding->is_disabled) {
                $branding->is_disabled = Carbon::now();
            } elseif($branding->is_admin_verified && $branding->is_disabled) {
                $branding->is_disabled = null;
            }

            if(!$branding->is_admin_verified) {
                $branding->is_admin_verified = Carbon::now();
                $branding->is_disabled = null;
                //dd($branding->name);
                \Mail::to($branding->user->email)->send(new BusinessVerified($branding));
            }

            $branding->save();
        }
        return redirect(route('branding', [$branding, $branding->slug]));
    }

    public function topbrand($branding) {
        //sleep(2);
        if(auth()->user()->can('disable listing')) {
            $branding->toggleTopbrand();
        }
        return redirect(route('branding', [$branding, $branding->slug]));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($branding)
    {
        $data = [];
        $data['brandings_form'] = Setting::get('brandings_form', []);
        $data['branding'] = $branding;
        $categories = DirectoryCategory::nested()->get();
        $categories = flatten($categories, 0);
        $list = [];
        foreach($categories as $category) {
            $list[''] = '';
            $list[$category['id']] = str_repeat("&mdash;", $category['depth']+1) . " " .$category['name'];
        }
        $data['categories'] = $list;

        $selected_category = null;
        $selected_category = DirectoryCategory::find(request('category', $branding->category_id));
        $selected_pricing_model = null;

        $data['selected_category'] = $selected_category;
        //$data['selected_pricing_model'] = $selected_pricing_model;
        $data['form'] = 'edit';

        return view('create.details', $data);
    }

    function showtopbrand($branding, $slug, Request $request){
      views($branding)->record();
      // get social $pages
      $linkedin_link = null;
      $facebook_link = null;
      $twitter_link = null;
      $instagram_link = null;
      if($branding->is_topbrand){
      $social_pages = DirectorySocialPage::where('directory_id', $branding->id)->first();
      if($social_pages){

        foreach($social_pages->pages as $pages){
          if($pages['key'] == 'linkedin'){
             $linkedin_link = $pages['link'];
          }elseif ($pages['key'] == 'facebook') {
            $facebook_link = $pages['link'];
          }elseif ($pages['key'] == 'twitter') {
            $twitter_link = $pages['link'];
          }elseif ($pages['key'] == 'instagram') {
            $instagram_link = $pages['link'];
          }
        }
      }
      }
      $data = [];
      $data['linkedin'] = $linkedin_link;
      $data['facebook'] = $facebook_link;
      $data['twitter'] = $twitter_link;
      $data['instagram'] = $instagram_link;
      //dd($branding->opening_hours);
      $open_hours = $this->arrangeOpeningHours($branding->opening_hours->asStructuredData());
      /*if(count($open_hours) > 0){
        dd($open_hours["Monday"]);
      */


      $data['branding'] = $branding;
      $data['has_map'] = false;
      if($branding->location /*&& setting('googlmapper.key')*/) {
            //Mapper::map($branding->location->getLat(), $branding->location->getLng(), ['zoom' => 14, 'zoomControl' => false, 'streetViewControl' => false, 'mapTypeControl' => false, 'center' => true, 'marker' => true]);
          $data['has_map'] = true;
       }
       $data["opening_hours"] = $open_hours;
       return view('branding.topbrand_latest', $data);
    }

    private function getParentCategories($category_id, $parents = []) {
        $category = DirectoryCategory::find($category_id);
        if($category) {
            $parents[] = $category->parent_id;
            if($category->parent_id) {
                $parents = $this->getParentCategories($category->parent_id, $parents);
            }
        }
        return $parents;
    }

    private function arrangeOpeningHours($openingHoursArr){

        $flat_arr = [];
        foreach ($openingHoursArr as $key => $value) {
          // code...
          //dd($value);
          $flat_arr = array_merge($flat_arr, array( $value["dayOfWeek"] => array("opens" => $value["opens"] , "closes" => $value["closes"]  ) )) ;

        }
        return $flat_arr;
    }

}
