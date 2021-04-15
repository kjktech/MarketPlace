<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\NewBrandListing;
use App\Models\Directory;
use App\Models\DirectoryCategory;
use App\Models\DirectoryPayment;
use App\Models\DayOpenTimeRange;
use App\Models\Team;
use App\Models\DirectorySocialPage;

use App\Entities\Time;

use Grimzy\LaravelMysqlSpatial\Types\Point;
use Storage;
use Image;
//use Location;
use GeoIP;
use Validator;
use App\Mail\TopBrandMessage;
use Paystack;

class CreateController extends Controller
{

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function index()
  {
    if(auth()->check() /*&& setting('single_directory_per_user')*/ && !auth()->user()->can('edit listing')) {
        //let's see if we have a listing

        $user = auth()->user();
        if($user->directories->count()) {
            $directory = $user->directories->first();
            return redirect($directory->edit_url);
        }
    }

    // This is to check if a user has bought slots for creating a business and will redirect to payment page if not
    $check_payment = DirectoryPayment::where('user_id', auth()->user()->id)->where('status', 'paid')->whereNull('directory_id')->get();
    if(count($check_payment) == 0){
      if(!auth()->user()->can('edit listing')){
        //return redirect(route('page.pricing'));
      }
    }

    $data = [];
    $data['listings_form'] = [];
    $directory = new Directory();
    $data['directory'] = $directory;
    $categories = DirectoryCategory::nested()->get();
    $categories = flatten($categories, 0);
    $list = [];
    $list_payment_type = [];
    if(auth()->user()->can('edit listing')){
      $list_payment_type[0] = "Admin - Individual Business";
      $list_payment_type[-1] = "Admin - Enterprise Business";
      $list_payment_type[-2] = "Admin - Limited Liability";
    }
    if(count($check_payment) > 0){
      foreach($check_payment as $payment) {
        //$list_payment_type[''] = '';
        $pay_type = "";
        switch ($payment['payment_type']) {
          case 1:
            // code...
            $pay_type = "Individual Business";
            break;
          case 2:
              // code...
            $pay_type = "Enterprise Business";
            break;
          case 3:
                // code...
            $pay_type = "Limited Liability";
             break;
          default:
            // code...
            break;
        }
        $list_payment_type[$payment['id']] = $pay_type;
    }
  }else{
    // Show free individual business
    $list_payment_type[0] = "Free - Individual Business";
  }
    foreach($categories as $category) {
        $list[''] = '';
        $list[$category['id']] = str_repeat("&mdash;", $category['depth']+1) . " " .$category['name'];
    }
    $data['categories'] = $list;
    $data['payment_types'] = $list_payment_type;

    $selected_category = null;
    $selected_payment = null;
    if(request('category')) {
        $selected_category = DirectoryCategory::find(request('category'));
        $selected_payment = request('payment_type');
    } else {
        if( count($data['categories']) == 1 ) {
            $category = DirectoryCategory::first();
            return redirect(route('create.index', ['category' => $category->id]));
        }
    }

    $data['selected_category'] = $selected_category;
    $data['selected_payment'] = $selected_payment;
    $data['form'] = 'create';

    $view = 'directory.create.create_initial';

    return view($view, $data);

  }

  #public function store(StoreListing $request)
  public function store(Request $request)
  {

      $params = $request->all();
      #return response('OK', 200)->header('X-IC-Redirect', '/create/r4W0J7ObQJ/edit#images_section');
      $validator = Validator::make($request->all(), [
          'name' => 'required|min:5|max:255',
          'description_new' => 'required|min:5',
      ]);

      if ($validator->fails()) {
          return redirect(route('create.index', ['category' => $request->get('category') ]))
                      ->withErrors($validator)
                      ->withInput();
      }

      $params['directory_category_id'] = $request->get('category');
      $params['user_id'] = auth()->user()->id;
      $params['name'] = $request->get('name');
      $params['description'] = $request->get('description_new');
      $params['setup_id'] = auth()->user()->id;

      //set a default city - let user fine tune later

      //$city = GeoIP::getCity();
      //$params['lat'] = (float) GeoIP::getLatitude();
      //$params['lng'] = (float) GeoIP::getLongitude();
      //$params['location'] = new Point($params['lat'], $params['lng']);
      //$params['city'] = $city;
      //$params['country'] = GeoIP::getCountryCode();
      $params['slug'] =  str_slug($request->get('name'));

      $listing = Directory::create($params);

      if($request->has('payment_type')){
        if((int)$request->get('payment_type') > 0){
          $pay = DirectoryPayment::where('id', $request->get('payment_type'))->first();
          //$pay = DirectoryPayment::where('user_id', auth()->user()->id)->where('payment_type', $request->get('payment_type'))->whereNull('directory_id')->first();
          $pay->directory_id = $listing->id;
          $pay->save();
        }else{
          $payment_type = (int)$request->get('payment_type');
          switch ($payment_type) {
            case 0:
              // code...
              $dir_pay = new DirectoryPayment();
              $dir_pay->user_id = auth()->user()->id;
              $dir_pay->paystack_reference = Paystack::genTranxRef();
              $dir_pay->amount = 1000;
              $dir_pay->payment_type = 1;
              $dir_pay->directory_id = $listing->id;
              $dir_pay->save();
              break;
            case -1:
                // code...
                $dir_pay = new DirectoryPayment();
                $dir_pay->user_id = auth()->user()->id;
                $dir_pay->paystack_reference = Paystack::genTranxRef();
                $dir_pay->amount = 2500;
                $dir_pay->payment_type = 2;
                $dir_pay->directory_id = $listing->id;
                $dir_pay->save();
              break;
            case -2:
                  // code...
              $dir_pay = new DirectoryPayment();
              $dir_pay->user_id = auth()->user()->id;
              $dir_pay->paystack_reference = Paystack::genTranxRef();
              $dir_pay->amount = 5000;
              $dir_pay->payment_type = 3;
              $dir_pay->directory_id = $listing->id;
              $dir_pay->save();
              break;
            default:
              // code...
              break;
          }
        }
    }

    //redirect to success page
    return response('OK', 200)->header('X-IC-Redirect', $listing->edit_url.'#images_section');

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

      //$city = GeoIP::getCity();
      //$this->authorize('update', $listing);

      // get social $pages
      $linkedin_link = null;
      $facebook_link = null;
      $twitter_link = null;
      $instagram_link = null;
      if($listing->is_topbrand){
      $social_pages = DirectorySocialPage::where('directory_id', $listing->id)->first();
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
      $data['city_array'] = $cities_array;
      $data['listing'] = $listing;
      if($listing->city){
        $data['city'] = $listing->city;
      }else{
        $data['city'] = "Anambra";
      }

      // Directory categories
      $categories = DirectoryCategory::nested()->get();
      $categories = flatten($categories, 0);
      $list = [];
      foreach($categories as $category) {
          $list[''] = '';
          $list[$category['id']] = str_repeat("&mdash;", $category['depth']+1) . " " .$category['name'];
      }
      $data['categories'] = $list;

      $listings_form = [];
      $data['listings_form'] = $listings_form;

      // retrieve opening date_times
      $openings = [];
      $dateTimeRangeMon = DayOpenTimeRange::where('day', 'monday')->where('openable_id', $listing->id)->first();
      if($dateTimeRangeMon){
        if($dateTimeRangeMon->is_open == 1){
           $openings[0] = Array("isActive" => true,"timeFrom" => substr($dateTimeRangeMon->start,0,5),"timeTill" => substr($dateTimeRangeMon->end,0,5));
        }else{
          $openings[0] = Array("isActive" => false,"timeFrom" => null,"timeTill" => null);
        }
      }else{
        $openings[0] = Array("isActive" => false,"timeFrom" => null,"timeTill" => null);
      }
      $dateTimeRangeTue = DayOpenTimeRange::where('day', 'tuesday')->where('openable_id', $listing->id)->first();
      if($dateTimeRangeTue){
        //$openings[1] = Array("isActive" => true,"timeFrom" => substr($dateTimeRangeTue->start,0,5),"timeTill" => substr($dateTimeRangeTue->end,0,5));
        if($dateTimeRangeTue->is_open == 1){
           $openings[1] = Array("isActive" => true,"timeFrom" => substr($dateTimeRangeTue->start,0,5),"timeTill" => substr($dateTimeRangeTue->end,0,5));
        }else{
          $openings[1] = Array("isActive" => false,"timeFrom" => null,"timeTill" => null);
        }
      }else{
        $openings[1] = Array("isActive" => false,"timeFrom" => null,"timeTill" => null);
      }
      $dateTimeRangeWed = DayOpenTimeRange::where('day', 'wednesday')->where('openable_id', $listing->id)->first();
      if($dateTimeRangeWed){
        //$openings[2] = Array("isActive" => true,"timeFrom" => substr($dateTimeRangeWed->start,0,5),"timeTill" => substr($dateTimeRangeWed->end,0,5));
        if($dateTimeRangeWed->is_open == 1){
           $openings[2] = Array("isActive" => true,"timeFrom" => substr($dateTimeRangeWed->start,0,5),"timeTill" => substr($dateTimeRangeWed->end,0,5));
        }else{
          $openings[2] = Array("isActive" => false,"timeFrom" => null,"timeTill" => null);
        }
      }else{
        $openings[2] = Array("isActive" => false,"timeFrom" => null,"timeTill" => null);
      }
      $dateTimeRangeThu = DayOpenTimeRange::where('day', 'thursday')->where('openable_id', $listing->id)->first();
      if($dateTimeRangeThu){
        //$openings[3] = Array("isActive" => true,"timeFrom" => substr($dateTimeRangeThu->start,0,5),"timeTill" => substr($dateTimeRangeThu->end,0,5));
        if($dateTimeRangeThu->is_open == 1){
           $openings[3] = Array("isActive" => true,"timeFrom" => substr($dateTimeRangeThu->start,0,5),"timeTill" => substr($dateTimeRangeThu->end,0,5));
        }else{
          $openings[3] = Array("isActive" => false,"timeFrom" => null,"timeTill" => null);
        }
      }else{
        $openings[3] = Array("isActive" => false,"timeFrom" => null,"timeTill" => null);
      }
      $dateTimeRangeFri = DayOpenTimeRange::where('day', 'friday')->where('openable_id', $listing->id)->first();
      if($dateTimeRangeFri){
        //$openings[4] = Array("isActive" => true,"timeFrom" => substr($dateTimeRangeFri->start,0,5),"timeTill" => substr($dateTimeRangeFri->end,0,5));
        if($dateTimeRangeFri->is_open == 1){
           $openings[4] = Array("isActive" => true,"timeFrom" => substr($dateTimeRangeFri->start,0,5),"timeTill" => substr($dateTimeRangeFri->end,0,5));
        }else{
          $openings[4] = Array("isActive" => false,"timeFrom" => null,"timeTill" => null);
        }
      }else{
        $openings[4] = Array("isActive" => false,"timeFrom" => null,"timeTill" => null);
      }
      $dateTimeRangeSat = DayOpenTimeRange::where('day', 'saturday')->where('openable_id', $listing->id)->first();
      if($dateTimeRangeSat){
        //$openings[5] = Array("isActive" => true,"timeFrom" => substr($dateTimeRangeSat->start,0,5),"timeTill" => substr($dateTimeRangeSat->end,0,5));
        if($dateTimeRangeSat->is_open == 1){
           $openings[5] = Array("isActive" => true,"timeFrom" => substr($dateTimeRangeSat->start,0,5),"timeTill" => substr($dateTimeRangeSat->end,0,5));
        }else{
          $openings[5] = Array("isActive" => false,"timeFrom" => null,"timeTill" => null);
        }
      }else{
        $openings[5] = Array("isActive" => false,"timeFrom" => null,"timeTill" => null);
      }
      $dateTimeRangeSun = DayOpenTimeRange::where('day', 'sunday')->where('openable_id', $listing->id)->first();
      if($dateTimeRangeSun){
        //$openings[6] = Array("isActive" => true,"timeFrom" => substr($dateTimeRangeSun->start,0,5),"timeTill" => substr($dateTimeRangeSun->end,0,5));
        if($dateTimeRangeSun->is_open == 1){
           $openings[6] = Array("isActive" => true,"timeFrom" => substr($dateTimeRangeSun->start,0,5),"timeTill" => substr($dateTimeRangeSun->end,0,5));
        }else{
          $openings[6] = Array("isActive" => false,"timeFrom" => null,"timeTill" => null);
        }
      }else{
        $openings[6] = Array("isActive" => false,"timeFrom" => null,"timeTill" => null);
      }
      $openings_json =  json_encode($openings);
      $data['openings'] = $openings_json;
      return view('create.edit', $data);
  }

  public function deleteUpload($listing, $uuid, Request $request) {

      $photos = (array) $listing->photos;
      unset($photos[$uuid]);
      $listing->photos = $photos;
      $listing->save();
      return ['success' => true];
  }

  public function deleteGalleryUpload($listing, $uuid, Request $request) {

      $galleries = (array) $listing->galleries;
      unset($galleries[$uuid]);
      $listing->galleries = $galleries;
      $listing->save();
      return ['success' => true];
  }

  public function deleteBannerUpload($directory, $uuid, Request $request) {

      $photos = (array) $directory->coverphotos;
      unset($photos[$uuid]);
      $directory->coverphotos = $photos;
      $directory->save();
      return ['success' => true];
  }

  public function deleteVideoUpload($directory, $uuid, Request $request) {
      if($directory->video){
        Storage::cloud()->delete($directory->video);
      }
      $directory->video = null;
      $directory->save();
      return ['success' => true];
  }

  public function deleteLogoUpload($directory, $uuid, Request $request) {
    if($directory->logo){
      Storage::cloud()->delete($directory->logo);
    }
    $directory->logo = null;
    $directory->save();
    return ['success' => true];
  }

  public function session($listing, Request $request) {

      $files = [];
      //dd($listing->photos);
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

  public function sessionbrand($listing, Request $request) {

      $files = [];
      if($listing->galleries) {
          foreach($listing->galleries as $i => $photo) {
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

  public function sessionbanner($directory, Request $request) {

      $files = [];
      if($directory->coverphotos) {
          foreach($directory->coverphotos as $i => $photo) {
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

  public function sessionvideo($listing, Request $request) {

      $files = [];
      $thumb = asset('themes/' . current_theme(). '/images/video_thumb.jpg');
      if($listing->video) {
        $tmp = [
            "name" => 'photo_1.jpg',
            "uuid" => 1,
            "thumbnailUrl" => $thumb,
        ];
          $files[] = $tmp;
      }
      return $files;
  }

  public function sessionlogo($listing, Request $request) {

      $files = [];
      if($listing->logo) {
        $tmp = [
            "name" => 'photo_1.jpg',
            "uuid" => 1,
            "thumbnailUrl" => $listing->logo,
        ];
          $files[] = $tmp;
      }
      return $files;
  }

  public function upload(Request $request) {

      $path = 'images/'.date('Y/m/d') .'/'. md5_file($request->qqfile->getRealPath()).'.jpg';
      if($request->has('directory_id')){
        $path = 'images/'.date('Y/m/d') .'/'. $request->get('directory_id') . '_' .md5_file($request->qqfile->getRealPath()).'.jpg';
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

  public function uploadgallery(Request $request) {
      $path = 'galleries/'.date('Y/m/d') .'/'. md5_file($request->qqfile->getRealPath()).'.jpg';
      if($request->has('directory_id')){
         $path = 'galleries/'.date('Y/m/d') .'/'. $request->get('directory_id') . '_' .md5_file($request->qqfile->getRealPath()).'.jpg';
      }
      $img = Image::make($request->qqfile);

      $img->fit(500, 500, function ($constraint) {
          $constraint->upsize();
      });
      $img->resizeCanvas(500, 500, 'center', false, '#ffffff');
      $img = (string) $img->encode('jpg', 90);

      $thumb = Storage::cloud()->put($path, $img, 'public');
      return ['success' => true, 'path' => Storage::cloud()->url($path)];
  }

  public function uploadbanner(Request $request) {
      $directory = Directory::findOrFail($request->get('directory_id'));
      $img = Image::make($request->qqfile)
              ->fit(1600, 1024, function ($constraint) {
                  $constraint->aspectRatio();
                  $constraint->upsize();
              })
              ->resizeCanvas(1600, 1024);
      Storage::cloud()->put('coverphotos/'.$directory->path, (string) $img->encode());
      return ['success' => true, 'path' => Storage::cloud()->url("coverphotos/".$directory->path)];
  }

  public function uploadvideo(Request $request) {
      $directory = Directory::findOrFail($request->get('directory_id'));
      $file = $request->qqfile;
      $thumb = asset('themes/' . current_theme(). '/images/video_thumb.jpg');
      //Storage::cloud()->put('brandvideo/'.$directory->path_vid, $file->getClientOriginalName());
      //Storage::move($file->getClientOriginalName(), 'brandvideo/'.$directory->path_vid);
      $mp4 = $directory->id.".mp4";
      $file->move('storage/brandvideo/'.$directory->path_vid, $mp4);
      $path = Storage::cloud()->url("brandvideo/".$directory->path_vid.$mp4);
      $directory->video = $path;
      $directory->save();
      return ['success' => true, 'path' => $thumb];
  }

  public function uploadlogo(Request $request) {
      $directory = Directory::findOrFail($request->get('directory_id'));
      $image = Image::make($request->qqfile)
              ->fit(500, 500, function ($constraint) {
                  $constraint->aspectRatio();
                  $constraint->upsize();
              })
              ->resizeCanvas(500, 500);
      Storage::cloud()->put('brandlogos/'.$directory->path, (string) $image->encode());
      $directory->logo = Storage::cloud()->url("brandlogos/".$directory->path);
      $directory->save();
      return ['success' => true, 'path' => $directory->logo];
  }

  public function update($listing, Request $request)
  {
      //$this->authorize('update', $listing);

      $params = $request->all();

      //$filters = Filter::orderBy('position', 'ASC')->where('is_hidden', 0)->where('is_default', 0)->get();
      if($request->input('tags_string')) {
          $listing->tags = explode(",", $request->input('tags_string'));
          $listing->tags_string = $request->input('tags_string');
      }

      $listing->fill($request->only(['name', 'description', 'about', 'services', 'lat', 'lng', 'city', 'country', 'email', 'phone', 'address', 'website', 'directory_category_id']));
      if($request->input('photos') && is_array($request->input('photos'))) {
          $listing->photos = $request->input('photos');
      }
      if($request->input('photos-brand') && is_array($request->input('photos-brand'))) {
          $listing->galleries = $request->input('photos-brand');
      }

      if($request->input('photos-banner') && is_array($request->input('photos-banner'))) {
          $listing->coverphotos = $request->input('photos-banner');
      }

      if($request->get('lat') && $request->get('lng')) {
          $point= new Point($request->get('lat'), $request->get('lng'));
          $listing->location = \DB::raw("GeomFromText('POINT(".$point->getLng()." ".$point->getLat().")')");
      }


      $listing->save();
      //$listing->dayOpenTimeRanges()->create(['day' => 'monday', 'start' => '08:00', 'end' => '12:00']);

      if($request->get('opening_hidden')) {
         $openings_arr = json_decode($request->get('opening_hidden'), true);
         foreach($openings_arr as $i => $opening) {
             switch($i){
               case 0:
                 if($opening["isActive"] == true){
                     $dateTimeRange = DayOpenTimeRange::where('day', 'monday')->where('openable_id', $listing->id)->first();
                     if($dateTimeRange){
                         $dateTimeRange->start =  Time::fromString($opening["timeFrom"]);
                         $dateTimeRange->end = Time::fromString($opening["timeTill"]);
                         $dateTimeRange->is_open = 1;
                         $dateTimeRange->save();
                     }else{
                       $dateTimeRange = new DayOpenTimeRange();
                       $dateTimeRange->openable_id =  $listing->id;
                       $dateTimeRange->openable_type =  'App\Models\Directory';
                       $dateTimeRange->day =  'monday';
                       $dateTimeRange->start =  Time::fromString($opening["timeFrom"]);
                       $dateTimeRange->end = Time::fromString($opening["timeTill"]);
                       $dateTimeRange->save();
                     }
                 }else{
                     $dateTimeRange = DayOpenTimeRange::where('day', 'monday')->where('openable_id', $listing->id)->first();
                     if($dateTimeRange){
                         $dateTimeRange->is_open = 0;
                         $dateTimeRange->save();
                     }
                 }
               break;
               case 1:
               if($opening["isActive"] == true){
                   $dateTimeRange = DayOpenTimeRange::where('day', 'tuesday')->where('openable_id', $listing->id)->first();
                   if($dateTimeRange){
                       $dateTimeRange->start =  Time::fromString($opening["timeFrom"]);
                       $dateTimeRange->end = Time::fromString($opening["timeTill"]);
                       $dateTimeRange->is_open = 1;
                       $dateTimeRange->save();
                   }else{
                     $dateTimeRange = new DayOpenTimeRange();
                     $dateTimeRange->openable_id =  $listing->id;
                     $dateTimeRange->openable_type =  'App\Models\Directory';
                     $dateTimeRange->day =  'tuesday';
                     $dateTimeRange->start =  Time::fromString($opening["timeFrom"]);
                     $dateTimeRange->end = Time::fromString($opening["timeTill"]);
                     $dateTimeRange->save();
                   }
               }else{
                 $dateTimeRange = DayOpenTimeRange::where('day', 'tuesday')->where('openable_id', $listing->id)->first();
                 if($dateTimeRange){
                     $dateTimeRange->is_open = 0;
                     $dateTimeRange->save();
                 }
               }
               break;
               case 2:
               if($opening["isActive"] == true){
                   $dateTimeRange = DayOpenTimeRange::where('day', 'wednesday')->where('openable_id', $listing->id)->first();
                   if($dateTimeRange){
                       $dateTimeRange->start =  Time::fromString($opening["timeFrom"]);
                       $dateTimeRange->end = Time::fromString($opening["timeTill"]);
                       $dateTimeRange->is_open = 1;
                       $dateTimeRange->save();
                   }else{
                     $dateTimeRange = new DayOpenTimeRange();
                     $dateTimeRange->openable_id =  $listing->id;
                     $dateTimeRange->openable_type =  'App\Models\Directory';
                     $dateTimeRange->day =  'wednesday';
                     $dateTimeRange->start =  Time::fromString($opening["timeFrom"]);
                     $dateTimeRange->end = Time::fromString($opening["timeTill"]);
                     $dateTimeRange->save();
                   }
               }else{
                 $dateTimeRange = DayOpenTimeRange::where('day', 'wednesday')->where('openable_id', $listing->id)->first();
                 if($dateTimeRange){
                     $dateTimeRange->is_open = 0;
                     $dateTimeRange->save();
                 }
               }
               break;
               case 3:
               if($opening["isActive"] == true){
                   $dateTimeRange = DayOpenTimeRange::where('day', 'thursday')->where('openable_id', $listing->id)->first();
                   if($dateTimeRange){
                       $dateTimeRange->start =  Time::fromString($opening["timeFrom"]);
                       $dateTimeRange->end = Time::fromString($opening["timeTill"]);
                       $dateTimeRange->is_open = 1;
                       $dateTimeRange->save();
                   }else{
                     $dateTimeRange = new DayOpenTimeRange();
                     $dateTimeRange->openable_id =  $listing->id;
                     $dateTimeRange->openable_type =  'App\Models\Directory';
                     $dateTimeRange->day =  'thursday';
                     $dateTimeRange->start =  Time::fromString($opening["timeFrom"]);
                     $dateTimeRange->end = Time::fromString($opening["timeTill"]);
                     $dateTimeRange->save();
                   }
               }else{
                 $dateTimeRange = DayOpenTimeRange::where('day', 'thursday')->where('openable_id', $listing->id)->first();
                 if($dateTimeRange){
                     $dateTimeRange->is_open = 0;
                     $dateTimeRange->save();
                 }
               }
               break;
               case 4:
               if($opening["isActive"] == true){
                   $dateTimeRange = DayOpenTimeRange::where('day', 'friday')->where('openable_id', $listing->id)->first();
                   if($dateTimeRange){
                       $dateTimeRange->start =  Time::fromString($opening["timeFrom"]);
                       $dateTimeRange->end = Time::fromString($opening["timeTill"]);
                       $dateTimeRange->is_open = 1;
                       $dateTimeRange->save();
                   }else{
                     $dateTimeRange = new DayOpenTimeRange();
                     $dateTimeRange->openable_id =  $listing->id;
                     $dateTimeRange->openable_type =  'App\Models\Directory';
                     $dateTimeRange->day =  'friday';
                     $dateTimeRange->start =  Time::fromString($opening["timeFrom"]);
                     $dateTimeRange->end = Time::fromString($opening["timeTill"]);
                     $dateTimeRange->save();
                   }
               }else{
                 $dateTimeRange = DayOpenTimeRange::where('day', 'friday')->where('openable_id', $listing->id)->first();
                 if($dateTimeRange){
                     $dateTimeRange->is_open = 0;
                     $dateTimeRange->save();
                 }
               }
               break;
               case 5:
               if($opening["isActive"] == true){
                   $dateTimeRange = DayOpenTimeRange::where('day', 'saturday')->where('openable_id', $listing->id)->first();
                   if($dateTimeRange){
                       $dateTimeRange->start =  Time::fromString($opening["timeFrom"]);
                       $dateTimeRange->end = Time::fromString($opening["timeTill"]);
                       $dateTimeRange->is_open = 1;
                       $dateTimeRange->save();
                   }else{
                     $dateTimeRange = new DayOpenTimeRange();
                     $dateTimeRange->openable_id =  $listing->id;
                     $dateTimeRange->openable_type =  'App\Models\Directory';
                     $dateTimeRange->day =  'saturday';
                     $dateTimeRange->start =  Time::fromString($opening["timeFrom"]);
                     $dateTimeRange->end = Time::fromString($opening["timeTill"]);
                     $dateTimeRange->save();
                   }
               }else{
                 $dateTimeRange = DayOpenTimeRange::where('day', 'saturday')->where('openable_id', $listing->id)->first();
                 if($dateTimeRange){
                     $dateTimeRange->is_open = 0;
                     $dateTimeRange->save();
                 }
               }
               break;
               case 6:
               if($opening["isActive"] == true){
                   $dateTimeRange = DayOpenTimeRange::where('day', 'sunday')->where('openable_id', $listing->id)->first();
                   if($dateTimeRange){
                       $dateTimeRange->start =  Time::fromString($opening["timeFrom"]);
                       $dateTimeRange->end = Time::fromString($opening["timeTill"]);
                       $dateTimeRange->is_open = 1;
                       $dateTimeRange->save();
                   }else{
                     $dateTimeRange = new DayOpenTimeRange();
                     $dateTimeRange->openable_id =  $listing->id;
                     $dateTimeRange->openable_type =  'App\Models\Directory';
                     $dateTimeRange->day =  'sunday';
                     $dateTimeRange->start =  Time::fromString($opening["timeFrom"]);
                     $dateTimeRange->end = Time::fromString($opening["timeTill"]);
                     $dateTimeRange->save();
                   }
               }else{
                 $dateTimeRange = DayOpenTimeRange::where('day', 'sunday')->where('openable_id', $listing->id)->first();
                 if($dateTimeRange){
                     $dateTimeRange->is_open = 0;
                     $dateTimeRange->save();
                 }
               }
               break;
             }
         }

      }
      $directory = Directory::findOrFail($listing->id);
      if($request->file('logo')) {
          $image = Image::make($request->file('logo'))
                  ->fit(200, 200, function ($constraint) {
                      $constraint->aspectRatio();
                      $constraint->upsize();
                  })
                  ->resizeCanvas(200, 200);
          Storage::cloud()->put('brandlogos/'.$directory->path, (string) $image->encode());
          $directory->logo = Storage::cloud()->url("brandlogos/".$directory->path);
          $directory->save();
      }

      if($request->has('draft')) {
          $directory->is_draft = true;
      }
      if($request->has('undraft')) {
          $directory->is_draft = false;
      }

      $directory->save();

      // save social
      if($directory->is_topbrand){
        if($request->has('pages')){
          $social_page = DirectorySocialPage::where('directory_id', $directory->id)->first();
          if($social_page){
            $social_page->pages = $request->input('pages');
            $social_page->save();
          }else{
            $social_page = new DirectorySocialPage();
            $social_page->directory_id = $directory->id;
            $social_page->pages = $request->input('pages');
            $social_page->save();
          }
        }
      }

      if($request->has('publish')) {
        //check that all params are set

        if($directory->phone && $directory->address && $directory->city && $directory->logo && $directory->photos && $directory->description){

          $directory->is_draft = false;
          if(!$directory->is_published && !$directory->is_admin_verified) {
            //dd(config('mail.from.address'));
            \Mail::to(config('mail.from.address'))->send(new NewBrandListing($directory));
          }
          $directory->is_published = true;
          $directory->save();

          alert()->success( __('Successfully published.') );
          return back();
        }else{
         alert()->warning( __('You cannot publish until you fill required fields. *') );
         return back();
        }
      }

      alert()->success( __('Successfully saved.') );
      return back();
  }

  /**
   * Update the specified resource in storage.
   * @param  Request $request
   * @return Response
   */
  public function teamcreate($listing, Request $request)
  {
    $team = new Team();
    if($request->get('teamid') != 0){
      $team = Team::find($request->get('teamid'));
    }else{
      $team->directory_id = $listing->id;
    }

    $team->name = $request->get('teamname');
    $team->position = $request->get('teamposition');
    $team->save();
      if($request->file('teamimage')) {
          $image = Image::make($request->file('teamimage'))
                  ->fit(300, 300, function ($constraint) {
                      $constraint->aspectRatio();
                      $constraint->upsize();
                  })
                  ->resizeCanvas(300, 300);
          Storage::cloud()->put('brandteams/'.$team->path, (string) $image->encode());
          $team->photo = Storage::cloud()->url("brandteams/".$team->path);
          $team->save();
      }

      return ['success' => true];
  }

  public function postmessage($listing, Request $request)
  {
    //$request_all = $request->all();
    //dd($request_all['brand-mail']);
    //dd($request->get('brand-mail'));
    $topbrand = $listing->name;
    $name = $request->get('brand-name');
    $email = $request->get('brand-mail');
    $subject = $request->get('brand-subject');
    $message = $request->get('message');
    if($listing->email){
     $data = ['name' => $name, 'email' => $email,
     'subject' => $subject, 'message' => $message,
     'topbrand' => $topbrand];
     \Mail::to($listing->email)->send(new TopBrandMessage($data));
    }
    return ['success' => true];
  }
}
