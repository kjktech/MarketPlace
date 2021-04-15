<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Store;
use App\Models\StoreCategory;
use App\Models\Directory;
use App\Models\StorePayment;
use App\Models\StoreSetup;
use App\Models\State;
use App\Models\City;

use Storage;
use Image;
use Validator;
use Paystack;

class CreateStoreController extends Controller
{

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function index(Request $request)
  {
    if(!auth()->user()->is_verified()){
      return redirect( route('page.notverified'));
    }
    if(auth()->check() && setting('single_store_per_user')) {
        //let's see if we have a listing
        $user = auth()->user();
        if($user->stores->count()) {
            $store = $user->stores->first();
            return redirect( $store->edit_url );
        }
    }

    //check directory Valid
    $check = Directory::where('id', $request->get('directory_id'))->first();

    if($check){
      if($check->user_id != auth()->user()->id){
        alert()->warning( __('You cannot create a store without a valid business.') );
        return redirect(route('account.overview'));
      }
      // This is to check if a user has bought slots for creating a store and will redirect to payment page if not
      $check_pay = StorePayment::where('directory_id', $request->get('directory_id'))->where('status', 'paid')->whereNull('store_id')->orderBy('created_at', 'DESC')->first();
      if(!$check_pay){
        if(!auth()->user()->can('edit listing')){
          // Temporary disable creating store
          //return redirect()->route('page.storepricing', ['directory' => $request->get('directory_id')]);
        }
      }
    }else{
      alert()->warning( __('You cannot create a store without a valid business.') );
      return redirect(route('account.overview'));
      //return back();
    }

    $data = [];
    $data['stores_form'] = [];
    $store = new Store();
    $data['store'] = $store;
    $directories = Directory::where('user_id', auth()->user()->id)->where('id', $request->get('directory_id'))->get();
    $categories = StoreCategory::nested()->get();
    $categories = flatten($categories, 0);
    $list = [];
    foreach($categories as $category) {
        $list[''] = '';
        $list[$category['id']] = str_repeat("&mdash;", $category['depth']+1) . " " .$category['name'];
    }
    $listdirectory = [];
    foreach($directories as $directory) {
        $listdirectory[$directory['id']] = $directory['name'];
    }
    $data['directories'] = $listdirectory;
    $data['categories'] = $list;

    $selected_category = null;
    $selected_directory = null;
    if(request('category')) {
        $selected_directory = Directory::find(request('directory_id'));
        $selected_category = StoreCategory::find(request('category'));

    } else {
        if( count($data['categories']) == 1 ) {
            $category = StoreCategory::first();
            return redirect(route('createstore.index', ['category' => $category->id, 'directory_id' => request('directory_id')]));
        }
    }

    $data['selected_category'] = $selected_category;
    $data['selected_directory'] = $selected_directory;
    $data['form'] = 'create';

    $view = 'store.create.create_initial';

    return view($view, $data);

  }

  #public function store(StoreListing $request)
  public function store(Request $request)
  {
     //test purposes
     //return redirect(route('createstore.index', ['category' => $request->get('category'), 'directory_id' => $request->get('directory_id') ]));

      $params = $request->all();
      #return response('OK', 200)->header('X-IC-Redirect', '/create/r4W0J7ObQJ/edit#images_section');
      $validator = Validator::make($request->all(), [
          'name' => 'required|min:5|max:255',
          'description_new' => 'required|min:5',
      ]);

      if ($validator->fails()) {
          return redirect(route('createstore.index', ['category' => $request->get('category'), 'directory_id' => $request->get('directory_id') ]))
                      ->withErrors($validator)
                      ->withInput();
      }

      $directory = Directory::find($request->get('directory_id'));
      $params['store_category_id'] = $request->get('category');
      $params['directory_id'] = $request->get('directory_id');
      $params['user_id'] = $directory->user->id;//auth()->user()->id;
      $params['name'] = $request->get('name');
      $params['description'] = $request->get('description_new');
      $params['country'] = 'Nigeria';

      $params['slug'] =  str_slug($request->get('name'));
      $params['setup_id'] = auth()->user()->id;


      $store = Store::create($params);

      //$check_dir = DirectoryPayment::where('directory_id', $request->get('directory'))->first();
      $amount = 10000.00;
      $check_pay = StorePayment::where('directory_id', $request->get('directory_id'))->where('status', 'paid')->whereNull('store_id')->orderBy('created_at', 'DESC')->first();
      if($check_pay){
        $check_pay->store_id = $store->id;
        $check_pay->save();
      }else{
        $reference = Paystack::genTranxRef();
        $dir_pay = new StorePayment();
        $dir_pay->user_id = auth()->user()->id;
        //$dir_pay->store_id = $store_id;
        $dir_pay->directory_id = $request->get('directory_id');
        $dir_pay->store_id = $store->id;
        $dir_pay->paystack_reference = $reference;
        $dir_pay->amount = $amount - 1000;
        $dir_pay->directory_payment_type = 1;
        $dir_pay->save();
      }

      //redirect to success page
      return response('OK', 200)->header('X-IC-Redirect', $store->edit_url.'#images_section');

  }

  /**
   * Show the form for editing the specified resource.
   * @return Response
   */
  public function edit($store)
  {
      //$this->authorize('update', $listing);
      //Cities array
      //Check if store is properly setup then redirect to setup

      $setup_obj = StoreSetup::where('store_id', $store->id)->first();
      if($setup_obj){
        if($setup_obj->identity == null || $setup_obj->bank_number == null){
          return redirect(route('account.setupstore', ["id" => $store->id]));
        }
      }else{
        return redirect(route('account.setupstore', ["id" => $store->id]));
      }

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
      $data = [];
      $data['store'] = $store;
      $data['city_array'] = $cities_array;

      if($store->city){
        $data['city'] = $store->city;
      }else{
        $data['city'] = "Abia";
      }

      $lga_array = [];
      if($store->city){
        $state_obj = State::where('name', 'LIKE', $store->city)->first();
        $city_obj = City::where('state_id', $state_obj->id)->get();
        foreach ($city_obj as $key => $value) {
          // code...
          $lga_array[$value["id"]] = $value["name"];
        }
        $data['lga_array'] = $lga_array;
      }else{
        $state_obj = State::where('name', 'LIKE', 'Abia')->first();
        $city_obj = City::where('state_id', $state_obj->id)->get();
        foreach ($city_obj as $key => $value) {
          // code...
          $lga_array[$value["id"]] = $value["name"];
        }
        $data['lga_array'] = $lga_array;
       }

      $stores_form = [];
      $data['$stores_form'] = $stores_form;
      return view('createstore.edit', $data);
   }

  public function deleteUpload($store, $uuid, Request $request) {

      $photos = (array) $store->photos;
      unset($photos[$uuid]);
      $store->photos = $photos;
      $store->save();
      return ['success' => true];
  }

  public function session($store, Request $request) {

      $files = [];
      if($store->photos) {
          foreach($store->photos as $i => $photo) {
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
      $path = 'stores/'.date('Y/m/d') .'/'. md5_file($request->qqfile->getRealPath()).'.jpg';
      if($request->has('store_id')){
        $path = 'stores/'.date('Y/m/d') .'/'. $request->get('store_id') . '_' .md5_file($request->qqfile->getRealPath()).'.jpg';
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

  public function update($store, Request $request)
  {
      //$this->authorize('update', $listing);

      $params = $request->all();

      //$filters = Filter::orderBy('position', 'ASC')->where('is_hidden', 0)->where('is_default', 0)->get();
      if($request->input('tags_string')) {
          $store->tags = explode(",", $request->input('tags_string'));
          $store->tags_string = $request->input('tags_string');
      }

      $store->fill($request->only(['name', 'description', 'city', 'city_id', 'country']));
      if($request->input('photos') && is_array($request->input('photos'))) {
          $store->photos = $request->input('photos');
      }


      $store->save();

      alert()->success( __('Successfully saved.') );
      return back();
  }

}
