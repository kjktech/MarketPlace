<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Listing;
use App\Models\Category;
use App\Models\PricingModel;
use App\Models\ListingVariant;
use App\Models\Filter;
use App\Models\Store;
use App\Models\Brand;
use App\Models\BrandCategory;
use App\Models\Variant;

use Carbon\Carbon;

use Grimzy\LaravelMysqlSpatial\Types\Point;
use Setting;
use Storage;
use Image;
//use Location;
use GeoIP;
use Validator;

use function BenTools\CartesianProduct\cartesian_product;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
       if(!auth()->user()->is_verified()){
        return redirect( route('page.notverified'));
       }
       if(auth()->user()->stores->count() == 0) {
           //let's see if we have a listing
           //alert()->warning( __('You need to create a store to create products.') );
           //ToDo show message on redirect
           return redirect( route('createstore.index'));
       }
       if(auth()->check() && setting('single_listing_per_user')) {
            //let's see if we have a listing
            $user = auth()->user();
            if($user->listings->count()) {
                $listing = $user->listings->first();
                return redirect( $listing->edit_url );
            }
        }

        $data = [];
        $data['listings_form'] = Setting::get('listings_form', []);

        $listing = new Listing();
        $data['listing'] = $listing;
        $stores = Store::where('user_id', auth()->user()->id)->get();
        $categories = Category::nested()->get();
        $categories = flatten($categories, 0);
        $list = [];
        foreach($categories as $category) {
            $list[''] = '';
            $list[$category['id']] = str_repeat("&mdash;", $category['depth']+1) . " " .$category['name'];
        }
        $liststore = [];
        foreach($stores as $store) {
            $liststore[$store['id']] = $store['name'];
        }
        $data['stores'] = $liststore;
        $data['categories'] = $list;
        $data['pricing_models'] = PricingModel::where('is_active', true)->pluck('seller_label', 'id');

        $selected_category = null;
        $selected_store = null;
        if(request('category')) {
            $selected_store = Store::find(request('store'));
            $selected_category = Category::find(request('category'));
            $pricing_models = $selected_category->pricing_models->where('is_active', true)->pluck('seller_label', 'id');
            if(count($pricing_models)) {
                $data['pricing_models'] = $pricing_models;
            }
        } else {
            if( count($data['categories']) == 1 ) {
                $category = Category::first();
                return redirect(route('post.index', ['category' => $category->id, 'store' => request('store')]));
            }
        }
        $selected_pricing_model = null;
        if(request('pricing_model')) {
            #dd($selected_category->pricing_models);
            $selected_pricing_model = $data['pricing_models'][request('pricing_model')];
        } else {
            if(request('category')) {
                if( $data['pricing_models']->count() == 1 ) {
                    $first_key = $data['pricing_models']->keys()->first();
                    return redirect(route('post.index', ['category' => request('category'), 'store' => request('store'), 'pricing_model' => $first_key]));
                }
            }
        }

        $data['selected_category'] = $selected_category;
        $data['selected_store'] = $selected_store;
        $data['selected_pricing_model'] = $selected_pricing_model;
        $data['form'] = 'create';

        $view = 'listing.create.pricing_model';

        return view($view, $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    #public function store(StoreListing $request)
    public function store(Request $request)
    {

        $params = $request->all();
        #return response('OK', 200)->header('X-IC-Redirect', '/create/r4W0J7ObQJ/edit#images_section');
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:5|max:255',
            'description_new' => 'required|min:5',
        ]);

        if ($validator->fails()) {
            return redirect(route('post.index', ['category' => $request->get('category'), 'store' => $request->get('store'), 'pricing_model' => $request->get('pricing_model') ]))
                        ->withErrors($validator)
                        ->withInput();
        }

        $params['category_id'] = $request->get('category');
        $params['store_id'] = $request->get('store');
        $params['pricing_model_id'] = $request->get('pricing_model');
        $params['user_id'] = auth()->user()->id;
        $params['title'] = $request->get('title');
        $params['description'] = $request->get('description_new');

        //set a default city - let user fine tune later

        //$city = GeoIP::getCity();
        //$params['lat'] = (float) GeoIP::getLatitude();
        //$params['lng'] = (float) GeoIP::getLongitude();
        //$params['location'] = new Point($params['lat'], $params['lng']);
        //$params['city'] = $city;
        $params['country'] = "Nigeria";

        $params['currency'] = Setting::get('currency', config('afiaanyi.currency'));
        $params['is_published'] = false;

        $listing = Listing::create($params);
        #dd($listing);
        #$listing->save();

        #if it's a service - set to 9-5
        if($listing->pricing_model->widget == 'book_time') {
            $slots = [];
            foreach(range(1,5) as $day)
                for($hour = 9; $hour <= 17; $hour++)
                    $slots[] = ['day' => $day, 'start_time' => $hour.':00', 'end_time' => ($hour+1).':00'];
            $listing->timeslots = $slots;
            $listing->save();
        }

        //redirect to success page
        return response('OK', 200)->header('X-IC-Redirect', $listing->edit_url.'#images_section');
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('create::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($listing)
    {
      //Cities array
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
        //$this->authorize('update', $listing);
        // Get Brand Category

        $cat_id = $listing->category_id;
        $parent_category = $this->getparentcategory($cat_id);
        $brands = BrandCategory::where('category_id', $cat_id)->get();
        $data = [];
        $data['city_array'] = $cities_array;
        if(count($parent_category->variants) > 0){
         //dd($parent_category->variants);
         $variants = $parent_category->variants;
        }else{
          $variants = [];//Variant::orderBy('attribute')->get();Variant::orderBy('attribute')->get();
        }

        $listvariant = [];
        $listvariant['Select variant'] = "Select variant";
        $index = 0;
        $all_var = [];
        foreach($variants as $variant) {

            $listvariant[$variant['attribute']] = $variant['attribute'];
            $values = explode(",", $variant->values_string);

            $variant_options = [];
            foreach($values as $valuev){

              $arr = array("text" => $valuev, "value" => $valuev);

              array_push($variant_options, $arr);
              //dd($variant_options);
            }
            $all_var[$index] = $variant_options;
            $index = $index + 1;
        }

        // Product category
        $categories = Category::nested()->get();
        $categories = flatten($categories, 0);
        $list = [];
        foreach($categories as $category) {
            $list[''] = '';
            $list[$category['id']] = str_repeat("&mdash;", $category['depth']+1) . " " .$category['name'];
        }

        $data['variants_options'] = $all_var;
        $data['variants'] =  $listvariant;
        $data['categories'] = $list;
        $listbrands = [];
        $listbrands[0] = '--SELECT BRAND--';
        foreach($brands as $brand) {
            $listbrands[$brand->brand->id] = $brand->brand->name;
        }
        $data['brands'] = $listbrands;
        $data['listing'] = $listing;
        $filters = Filter::get();
        $listings_form = [];

        foreach($filters as $element) {
            if($element->form_input_meta) {
                $form_input_meta = $element->form_input_meta;
                $form_input_meta['name'] = 'filters['.$element->form_input_meta['name'].']';
                $form_input_meta['value'] = (@$listing->meta[$element->form_input_meta['name']]);

                if(isset($form_input_meta['values']) && is_array($form_input_meta['value'])) {
                    foreach ($form_input_meta['values'] as $k => $v) {
                        $form_input_meta['values'][$k]['selected'] = in_array($v['value'], $form_input_meta['value']);
                    }
                }

                $listings_form[] = $form_input_meta;
            }
        }

        $data['listings_form'] = $listings_form;
        return view('post.edit', $data);
    }

    public function images($listing)
    {
        return view('create.images', compact('listing'));
    }

    public function additional($listing)
    {
        $data = [];
        $data['listing'] = $listing;
        $listings_form = json_decode(Setting::get('listings_form', []));
        foreach($listings_form as $k => $element) {
            $listings_form[$k]->value = (@$listing->meta[$element->name]);
        }
        $data['listings_form'] = $listings_form;
        return view('create.additional', $data);
    }

    public function pricing($listing)
    {
        return view('create.pricing', compact('listing'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update($listing, Request $request)
    {
        //$this->authorize('update', $listing);

        $params = $request->all();

        $filters = Filter::orderBy('position', 'ASC')->where('is_hidden', 0)->where('is_default', 0)->get();
        if($request->input('tags_string')) {
            $listing->tags = explode(",", $request->input('tags_string'));
            $listing->tags_string = $request->input('tags_string');
        }
        if($request->input('filters')) {
            $meta = [];
            foreach($filters as $filter) {
                if(isset($params['filters'][$filter->field])) {
                    $value = $params['filters'][$filter->field];
                    if($filter->search_ui == 'rangeSlider' || $filter->search_ui == 'priceRange') {
                        $value = (float) $value;
                    }
                    $meta[$filter->field] = $value;
                }
            }

            $listing->meta = $meta;
            $listing->save();
        }

        if($request->input('variations')) {
            $variant_options = [];
            foreach($params['variations'] as $variant) {
                if($variant['options'] && $variant['name'] != 'Select variant')
                    $variant_options[$variant['name']] = explode(",", $variant['options']);
            }
            $listing->variant_options = $variant_options;
            $listing->save();


            //now create the variants
            $matrix = collect();
            if($listing->variant_options) {
                $matrix = collect(cartesian_product($listing->variant_options)->asArray());
            }

		        //dd($listing->variants);

            #delete the ones we no longer need
            foreach($listing->variants as $variant) {
                $delete = true;
                foreach($matrix as $item) {
                    if(($variant->meta == $item)) {
                        $delete = false;
                    }
                }
                if($delete) {
                    $variant->delete();
                }
            }

            #insert it in db, if not exists
            //dd($matrix);
            foreach($matrix as $item) {
                $listing_variant = new ListingVariant();

                foreach($item as $field => $value) {
                    $listing_variant = $listing_variant->where('meta->' . $field, $value)->where('listing_id', $listing->id);
                }

                $listing_variant = $listing_variant->first();
                if(!$listing_variant) {
                    $listing_variant = new ListingVariant();
                    $listing_variant->listing_id = $listing->id;
                    $listing_variant->stock = 100;
                    $listing_variant->meta = $item;
                    $listing_variant->save();
                }
            }

        }

        if($request->input('variants')) {
            foreach($params['variants'] as $variant_id => $variant) {
                $listing_variant = ListingVariant::find($variant_id);
                if($listing_variant) {
                    $listing_variant->price = (float)$variant['price'];
                    $listing_variant->stock = (int)$variant['stock'];
                    $listing_variant->save();
                }

            }
        }

        //set dates we don't want them to book
        if($request->input('blocked_dates')) {
            $table = (new ListingBookedDate())->getTable();
            DB::table($table)->where('listing_id', $listing->id)->update(['is_available' => true]);
            $blocked_dates = explode(",", $params['blocked_dates']);
            foreach($blocked_dates as $blocked_date) {
                $blocked_date = Carbon::parse($blocked_date);
                $listing_booked_date = ListingBookedDate::updateOrCreate([
                    'booked_date'   => $blocked_date,
                    'listing_id'    => $listing->id
                ], ['is_available'  => false]);

            }
        }

        //shipping stuff
        if($request->input('shipping')) {
            $count = 0;
            foreach($params['shipping'] as $position => $shipping) {
                if(!$shipping['name'])
                    continue;
                $listing_shipping_option = ListingShippingOption::updateOrCreate([
                    'position'   => $position,
                    'listing_id'    => $listing->id
                ], ['name'  => $shipping['name'], 'price'  => $shipping['price']]);
                $count++;
            }

            //delete the ones that were removed
            foreach($listing->shipping_options as $position => $shopping_option) {
                if($position >= $count) {
                    $shopping_option->delete();
                }
            }
        }

        if($request->input('additional')) {
            $count = 0;
            foreach($params['additional'] as $position => $additional_option) {
                if(!$additional_option['name'])
                    continue;
                $listing_shipping_option = ListingAdditionalOption::updateOrCreate([
                    'position'   => $position,
                    'listing_id'    => $listing->id
                ], ['name'  => $additional_option['name'], 'price'  => $additional_option['price']]);
                $count++;
            }

            //delete the ones that were removed
            if($listing->additional_options) {
                foreach ($listing->additional_options as $position => $additional_option) {
                    if ($position >= $count) {
                        $additional_option->delete();
                    }
                }
            }
        }
        // BRAND
        if($request->has('brand_id') && $request->get('brand_id') != 0){
          $listing->brand_id = $request->get('brand_id');
        }


        if($request->has('discount') && (int)$request->get('discount') >= 0 && (int)$request->get('discount') <= 100){
          $listing->discount = $request->get('discount');
        }

        if($request->has('price'))
            $listing->price = (float) $request->get('price');

        if($request->has('weight'))
            $listing->weight = $request->get('weight');

        if($request->has('length'))
            $listing->length = $request->get('length');

        if($request->has('width'))
            $listing->width = $request->get('width');

        if($request->has('height'))
            $listing->height = $request->get('height');

        if($request->input('services')) {
            $count = 0;
            foreach($params['services'] as $position => $service) {
                if(!$service['name'])
                    continue;
                $listing_service = ListingService::updateOrCreate([
                    'position'   => $position,
                    'listing_id'    => $listing->id
                ], ['name'  => $service['name'], 'duration'  => $service['duration'], 'price'  => $service['price']]);
                $count++;
            }

            //delete the ones that were removed
            if($listing->services) {
                foreach ($listing->services as $position => $service) {
                    if ($position >= $count) {
                        $service->delete();
                    }
                }
            }
            $listing->price = (float) collect($params['services'])->sortBy('price')->first()['price'];
        }

        $listing->fill($request->only(['title', 'description', 'stock', 'lat', 'lng', 'city', 'country', 'session_duration', 'min_duration', 'max_duration', 'category_id']));
        if($request->input('photos') && is_array($request->input('photos'))) {
            $listing->photos = $request->input('photos');
        }

        if($request->get('lat') && $request->get('lng')) {
            $point= new Point($request->get('lat'), $request->get('lng'));
            $listing->location = \DB::raw("GeomFromText('POINT(".$point->getLng()." ".$point->getLat().")')");
        }
        if($request->has('price_per_unit_display')) {
            $listing->price_per_unit_display = $request->input('price_per_unit_display');
            if($listing->pricing_model->widget == 'request') {
                $listing->price_per_unit_display = $listing->price_per_unit_display;
            }
        }
        if($request->has('draft')) {
            $listing->is_draft = true;
            $listing->is_published = false;
        }
        if($request->has('undraft')) {
            $listing->is_draft = false;
        }

        $listing->save();

        if($request->has('renew')) {
            return $this->publish_listing($listing);
        }

        /*
        if($request->has('publish')) {

            $listing->is_draft = false;
            if(!$listing->is_admin_verified && !setting('listings_require_verification')) {
                $listing->is_admin_verified = Carbon::now();
            }
            $listing->save();

            if(module_enabled("memberships") || module_enabled("listingfee")) {
                return $this->publish_listing($listing);
            } else {
                if(!$listing->is_published && !$listing->is_admin_verified) {
                    Mail::to(config('mail.from.address'))->send(new NewListing($listing));
                }
                $listing->is_published = true;
                $listing->save();
            }

        }
        */
        if($request->has('publish')) {
            $price_valid = false;
            if($listing->price){
              try{
                $price_conver = (int) $listing->price;
                if($price_conver > 0){
                $price_valid = true;
                }
              }catch(\Exception $e){
              // log error
              $price_valid = false;
              }
            }
            if($listing->description && $listing->photos && $listing->stock && $price_valid && $listing->city && $listing->weight){
            $listing->is_draft = false;
            if(!$listing->is_admin_verified && !setting('listings_require_verification')) {
                $listing->is_admin_verified = Carbon::now();
            }
            $listing->save();

            if(module_enabled("memberships") || module_enabled("listingfee")) {
                return $this->publish_listing($listing);
            } else {
                if(!$listing->is_published && !$listing->is_admin_verified) {
                    Mail::to(config('mail.from.address'))->send(new NewListing($listing));
                }
                $listing->is_published = true;
                $listing->save();
            }
          }else{
            alert()->warning( __('You cannot publish until you fill required fields. *') );
            return back();
          }

        }

        alert()->success( __('Successfully saved.') );
        return back();
    }

    private function publish_listing($listing) {

        $ordered_routes = ['listingfee', 'memberships', 'credits'];
        $user = auth()->user();

        #does the user have an active membership?
        if(module_enabled("memberships")) {
            if($user->subscription('main') && $user->subscription('main')->plan->price > 0) {
                return redirect()->route('addons.memberships.payment', [$listing]);
            }
        }

        #does the user have any credits?
        if(module_enabled("credits")) {
            if($user->balance > 0) {
                return redirect()->route('addons.credits.payment', [$listing]);
            }
        }

        #otherwise redirect to a payment method
        foreach($ordered_routes as $ordered_route) {
            if(module_enabled($ordered_route)) {
                return redirect()->route('addons.'.$ordered_route.'.payment', [$listing]);
            }
        }

        #if listing fee
        /*if(module_enabled("listingfee")) {
            return redirect()->route('addons.listingfee.payment', [$listing]);
        }

        #if memberships
        if(module_enabled("memberships")) {
            return redirect()->route('addons.memberships.payment', [$listing]);
        }*/


    }

    protected function asWKT(GeometryInterface $geometry)
    {
        return $this->getQuery()->raw("ST_GeomFromText('".$geometry->toWKT()."')");
    }

    public function deleteUpload($listing, $uuid, Request $request) {
        $photos = (array) $listing->photos;
        unset($photos[$uuid]);
        $listing->photos = $photos;
        $listing->save();
        return ['success' => true];
    }

    public function session($listing, Request $request) {
        $files = [];
        if($listing->photos) {
            foreach($listing->photos as $i => $photo) {
                $tmp = [
                    "name" => 'photo_'.($i+1).'.jpg',
                    "uuid" => $i,
                    "thumbnailUrl" => $photo,
                ];
                $files[] = $tmp;
            }
        }
        return $files;
    }

    public function upload(Request $request) {
        $path = 'listings/'.date('Y/m/d') .'/'. md5_file($request->qqfile->getRealPath()).'.jpg';
        if($request->has('listing_id')){
          $path = 'listings/'.date('Y/m/d') .'/'. $request->get('listing_id') . '_' .md5_file($request->qqfile->getRealPath()).'.jpg';
        }
        $img = Image::make($request->qqfile);

        $img->fit(680, 460, function ($constraint) {
            $constraint->upsize();
        });
        $img->resizeCanvas(680, 460, 'center', false, '#ffffff');
        $img = (string) $img->encode('jpg', 90);

        $thumb = Storage::cloud()->put($path, $img, 'public');
        return ['success' => true, 'path' => Storage::cloud()->url($path)];
    }


    public function getTimes($listing) {
        $this->authorize('update', $listing);

        $data = [];
        $data['listing'] = $listing;

        $slots = [];
        if($listing->timeslots) {
            foreach($listing->timeslots as $timeslot) {
                $slots[$timeslot['day']][(int) $timeslot['start_time']] = 1;
            }
        }

        /**
         * Timeslots
         */
        $days = range(1, 7);
        $hours = range(0, 23);
        $matrix = [];
        foreach($days as $day) {
            foreach($hours as $hour) {
                $matrix[$day][$hour] = array_get($slots, $day.'.'.$hour, 0);
            }
        }

        $data['matrix'] = $matrix;
        $data['slots'] = $slots;

        return view('create.times', $data);

    }

    public function postTimes($listing, Request $request) {
        $this->authorize('update', $listing);

        $times = $request->get('selection');

        $slots = [];
        foreach($times as $day => $times) {
            foreach($times as $hour => $value) {
                $slots[] = ['day' => $day, 'start_time' => $hour.':00', 'end_time' => ($hour+1).':00'];
            }
        }
        $listing->timeslots = $slots;
        $listing->save();
        return redirect(url()->current());
        return $listing;

    }

    public function SearchEngine(Request $request){
        if($request->model === 'Category'){
            $query =  Listing::searchcategory($request->column1, $request->column2, $request->id);
        }else {
            $query = Listing::search($request->model, $request->column1, $request->column2, $request->id);
        }
        return $query;
    }

    private function getparentcategory($category_id){
      $cat_obj = Category::find($category_id);
      while($cat_obj->parent_id != 0) {
        $cat_obj = Category::find($cat_obj->parent_id);
      }
      return $cat_obj;
    }
}
