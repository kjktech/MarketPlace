<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Models\Listing;
use App\Models\Variant;
use App\Models\ListingVariant;
use App\Models\BrandCategory;
use App\Models\Filter;
use App\Models\Category;
use App\Models\Store;
use App\Models\OrderItem;
use Setting;
use Validator;

use function BenTools\CartesianProduct\cartesian_product;

class ListingsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $listings = new Listing();
        if($request->get('q')) {
            $listings = $listings->search($request->get('q'));
        }
        if($request->get('store_id')) {
            $listings = $listings->where('store_id', $request->get('store_id'));
        }
        $listings = $listings->orderBy('created_at', 'desc');
        $data['listings'] = $listings->paginate(50);
        /*
        $listing_loop =  OrderItem::all();
        foreach ($listing_loop as $key => $value) {
          $value->store_id = $value->listing->store->id;
          $value->save();
        }
        */
        return view('panel::listings.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(Request $request)
    {
      $data = [];
      $categories = Category::nested()->get();
      $categories = flatten($categories, 0);
      $list = [];
      foreach($categories as $category) {
          $list[''] = '';
          $list[$category['id']] = str_repeat("&mdash;", $category['depth']+1) . " " .$category['name'];
      }
      if($request->has('store_id')){
        $data['store_id'] = $request->get('store_id');
        $store = Store::find($request->get('store_id'));
        if(!$store){
          return redirect(route('panel.'));
        }
      }else{
        return redirect(route('panel.'));
      }
      $data['categories'] = $list;
      return view('panel::listings.create', $data);
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
            'description' => 'required|min:5',
        ]);

        if ($validator->fails()) {
            return redirect(route('panel.listings.create'))
                        ->withErrors($validator)
                        ->withInput();
        }

        $params['category_id'] = $request->get('category');
        $params['store_id'] = $request->get('store_id');
        $params['pricing_model_id'] = 2;
        $params['user_id'] = auth()->user()->id;
        $params['title'] = $request->get('title');
        $params['description'] = $request->get('description');

        $params['country'] = "Nigeria";

        $params['currency'] = Setting::get('currency', config('afiaanyi.currency'));
        $params['is_published'] = false;

        $listing = Listing::create($params);

        if($listing->pricing_model->widget == 'book_time') {
            $slots = [];
            foreach(range(1,5) as $day)
                for($hour = 9; $hour <= 17; $hour++)
                    $slots[] = ['day' => $day, 'start_time' => $hour.':00', 'end_time' => ($hour+1).':00'];
            $listing->timeslots = $slots;
            $listing->save();
        }

        return redirect(route('panel.listings.edit', $listing));
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('panel::show');
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
        //$variants = Variant::orderBy('attribute')->get();
        // Get Brand Category
        $cat_id = $listing->category_id;
        $parent_category = $this->getparentcategory($cat_id);
        $brands = BrandCategory::where('category_id', $cat_id)->get();
        $data = [];
        if($listing->city){
          $data['city'] = $listing->city;
        }else{
          $data['city'] = "Anambra";
        }

        if(count($parent_category->variants) > 0){
         //dd($parent_category->variants);
         $variants = $parent_category->variants;
        }else{
          $variants = [];//Variant::orderBy('attribute')->get();
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
        $data['city_array'] = $cities_array;
        $data['listings_form'] = $listings_form;
        return view('panel::listings.edit', $data);
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

      // BRAND
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

      if($request->has('publish')) {
          // Check if price exists and if its a valid string numerical
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

      $listing->is_whole_sale = $request->has('is_whole_sale');
      if($request->has('whole_sale_price')){
        $listing->whole_sale_price = $request->get('whole_sale_price');
      }
      if($request->has('whole_sale_min_quantity')){
        $listing->whole_sale_min_quantity = $request->get('whole_sale_min_quantity');
      }
      $listing->save();

      alert()->success( __('Successfully saved.') );
      return back();
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($listing)
    {
        $listing->delete();

        alert()->success('Successfully deleted');
        return redirect()->route('panel.listings.index');
    }

    private function getparentcategory($category_id){
      $cat_obj = Category::find($category_id);
      while($cat_obj->parent_id != 0) {
        $cat_obj = Category::find($cat_obj->parent_id);
      }
      return $cat_obj;
    }
}
