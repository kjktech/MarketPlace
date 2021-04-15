<?php

namespace App\Http\Controllers;

use Grimzy\LaravelMysqlSpatial\Types\LineString;
use Grimzy\LaravelMysqlSpatial\Types\Polygon;
use Illuminate\Http\Request;
use App\Models\Directory;
use App\Models\DirectoryCategory;
use App\Models\User;
use App\Models\Filter;
use App\Models\BrandComment;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Setting;
use MetaTag;
use GeoIP;

class BrowseController extends Controller{
  public function listings(Request $request) {

    // Bug to prevent null bug by running query first
    $categories_nested = DirectoryCategory::nested()->get();
    $categories_flattened = flatten($categories_nested, 0);
    $list = [];
    foreach($categories_flattened as $category) {
        $list[$category['id']] = str_repeat("&mdash;", $category['depth']+1) . " " .$category['name'];
    }
    //dd($list);


    $cities_array = array("all" => "All Cities", "Abia"=>"Abia","Adamawa"=>"Adamawa",
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
      #save address in session
      foreach($request->only(['lat', 'lng', 'bounds', 'location']) as $k => $v) {
          session([$k => $v]);
      }
      $listing_data_obj = $this->getListingData($request);
      $data = $listing_data_obj["data"];
      if($request->get('ajax')) {
          return response()->json($data);
      }
      MetaTag::set('title', __("Browse listings"));
      $reflFunc = new \ReflectionFunction('active');
      $finder = $reflFunc->getFileName() . ':' .$reflFunc->getStartLine();
      $data['finder'] = $finder;
      $data['city_array'] = $cities_array;
      $data['all_categories'] = $list;
      $data['reviews'] = $listing_data_obj['review'];
      return view('browse.index_latest_b', $data);
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
      $listings = new Directory();
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
            $listings = $listings->groupBy('meta->'.$filter->field)
                            ->whereNotNull('meta->'.$filter->field.'')
                            ->select('meta->'.$filter->field.' as name', 'meta->'.$filter->field.' as value', \DB::raw('count(*) as total'))
                            ->orderBy('meta->'.$filter->field, 'ASC')
                            ->get();
            $facet_group['options'] = $listings->toArray();
        }

    } else if(in_array($filter->search_ui, ['rangeSlider', 'priceRange'])) {
        $min = $listings->min('meta->'.$filter->field);
        $max = $listings->max('meta->'.$filter->field);
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
      //dd($data['facets']);
      $listings = new Directory();
      //$listings = $listings->whereNull('topbrand');
      $listings = $listings->active();

      //search by title, description, tags
      if($request->get('q')) {
          $listings = $listings->search($request->get('q'));
          #dd(debug_backtrace ());
      }

      if($request->has('top_brand')) {
        if($request->get('top_brand') && (int)$request->get('top_brand') == 1) {
            $listings = $listings->whereNotNull('topbrand');
        }
      }

      $is_filtered = false;
      if($request->has('category')) {
          $is_filtered = true;
      }

      $category_id = $request->get('category', 0) ? :0; //get the category

      //get listings with category and child categories
      $full_categories = DirectoryCategory::all();
      $categories = $this->getSearchableCategories($full_categories, $category_id); //get all child categories

      $listings = $listings->whereIn('directory_category_id', $categories);
      $listings = $listings->whereNotNull('lat');
      $listings = $listings->whereNotNull('lng');
      $listings = $listings->whereNotNull('location');
      if($request->has('city')) {
          if($request->get('city') != "all" && $request->get('city') != ""){
            $listings->where('city', $request->get('city'));
          }
      }

      $this->categories = $categories;
      $category = DirectoryCategory::find($category_id); //current category

      $level_categories = DirectoryCategory::where('parent_id', $category_id)->get(); //categories on this level
      $parent_categories = $this->getParentCategories($category_id);

      if(count($level_categories) == 0 && $category) {
          $level_categories = DirectoryCategory::where('parent_id', $category->parent_id)->get();
          $parent_categories = DirectoryCategory::whereIn('id', $parent_categories)->get();
      } else {
          $parent_categories = DirectoryCategory::whereIn('id', array_merge([$category_id], $parent_categories))->get();

      }
      $data['parent_categories'] = $parent_categories;
      $data['level_categories'] = $level_categories;
      $data['category'] = $category;
      $data['category_id'] = $category_id;



      //distance calculations
      $lat = 6.57476889;//$request->get('lat') ? : GeoIP::getLatitude();
      $lng = 3.25692093;//$request->get('lng') ? : GeoIP::getLongitude();
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
                  $expandedBounds = $math->expand($bounds, $request->input('distance').config('afiaanyi.distance_unit'));

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
      $data['lat'] = $lat;
      $data['lng'] = $lng;

      $sort = $request->input('sort')?:'date';
      //$listings = $listings->orderByRaw('IF(priority_until>NOW(), 1, 0) DESC');
      if($sort == 'date') {
          $listings = $listings->orderBy('created_at', 'DESC');
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
      $plucked_id = $listings->take('1000')->pluck('id')->toArray();
      $review_obj = BrandComment::whereIn('directory_id', $plucked_id)->distinct('directory_id')->take(4)->get();
      $data['listings'] = $listings->paginate(12);
      $data['is_filtered'] = $is_filtered;

      $data['load_time'] = round(microtime(true) - LARAVEL_START);
      $return_obj = array("data" => $data, "review" => $review_obj);
      return $return_obj;
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
