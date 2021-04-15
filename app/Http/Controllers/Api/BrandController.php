<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\NewBrandListing;
use App\Models\Directory;
use App\Models\DirectoryPayment;
use App\Models\DirectoryCategory;
use App\Models\DayOpenTimeRange;
use App\Models\BrandComment;
use App\Mail\ContactMessage;

use App\Entities\Time;

use Grimzy\LaravelMysqlSpatial\Types\Point;
use Storage;
use Image;
//use Location;
use GeoIP;
use Validator;
use GuzzleHttp\Client;
use Paystack;

class BrandController extends Controller
{
  /**
   * Create a new AuthController instance.
   *
   * @return void
   */
  public function __construct()
  {
      $this->middleware('auth:api', ['except' => ['login', 'homebrands', 'verifypayment', 'getreviews', 'contactmessage']]);
  }
  public function mebrands(){

    $user = auth('api')->user();
    if($user != null){
      $directories = Directory::where("user_id", $user->id)->get();
      return response()->json([
          "msg" => "Success",
          "brands" => $directories,
          "status" => true
      ]);
    }else{
      return response()->json([
          "msg" => "You are not authenticated",
          "status" => false
      ], 401);
    }
  }

  public function homebrands(){
    $categories_nested = DirectoryCategory::orderBy('order', 'ASC')->nested()->get();
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
    $listings_array = array();
    //Listings for each section
    $itiration_b = 0;
    foreach($result_cat_ids as $arr_id){
      //dd($arr_id[0]);
      $listings_res = Directory::whereIn('directory_category_id', $arr_id)->orderBy('created_at', 'ASC')->take(5)->get();
      $listings_array[] = $listings_res;
      $result[$itiration_b]['product'] = $listings_res;
      $itiration_b++;
    }
    $data=[];
    //$data['results_test_products'] = $listings_array;
    $data['results_test'] = $result;
    return response()->json([
        'data' => $data
    ], 200);
  }

  public function createbusiness(Request $request){
     $user = auth('api')->user();
     $params = $request->all();
     $pay = null;
     $category = null;
     if($request->has('category')){
       $category_obj = DirectoryCategory::find($request->get('category'));
       if(!$category_obj){
         return response()->json([
             "msg" => "Invalid category",
             "status"=> false
         ], 400);
       }else{
         $category = $category_obj->id;
       }
     }else{
       return response()->json([
           "msg" => "Missing category",
           "status"=> false
       ], 400);
     }

     #return response()->json(array("msg" =>"test flight", "status"=> false), 200);

     $validator = Validator::make($request->all(), [
         'name' => 'required|min:5|max:255',
         'description' => 'required|min:5',
     ]);

     if($validator->fails()){
      $error = $validator->messages();
      //$error =$error false;
      return response()->json(array("msg" =>$error, "status"=> false), 400);
     }

     if($user != null){
       $params['directory_category_id'] = $category;
       $params['user_id'] = auth()->user()->id;
       $params['name'] = $request->get('name');
       $params['description'] = $request->get('description');
       $params['slug'] =  str_slug($request->get('name'));

       if($request->has('state')){
          $params['city'] =  $request->get('state');
       }

       //check valid Slot
       if($request->has('payment_type')){
         if((int)$request->get('payment_type') > 0){
           $pay = DirectoryPayment::where('id', $request->get('payment_type'))->first();
           //$pay = DirectoryPayment::where('user_id', auth()->user()->id)->where('payment_type', $request->get('payment_type'))->whereNull('directory_id')->first();
         }
       }
       if(!$pay){
         return response()->json([
             "msg" => "Payment type not valid",
             "status"=> false
         ], 403);
       }
       $listing = Directory::create($params);
       if($request->get('lat') && $request->get('lng')) {
           $point= new Point($request->get('lat'), $request->get('lng'));
           $listing->location = \DB::raw("GeomFromText('POINT(".$point->getLng()." ".$point->getLat().")')");
           $listing->save();
       }

       $directory = Directory::findOrFail($listing->id);
       /*
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
       */

       if($request->has('logo_base64')){
         $image = Image::make($request->get('logo_base64'))
                 ->fit(200, 200, function ($constraint) {
                     $constraint->aspectRatio();
                     $constraint->upsize();
                 })
                 ->resizeCanvas(200, 200);
         Storage::cloud()->put('brandlogos/'.$directory->path, (string) $image->encode());
         $directory->logo = Storage::cloud()->url("brandlogos/".$directory->path);
         $directory->save();
       }

       if($request->has('banner_base64')){
         $path = 'images/'.date('Y/m/d') .'/'. $directory->id . '_' .time().'.jpg';
         $img = Image::make($request->get('banner_base64'));

         $img->fit(680, 460, function ($constraint) {
             $constraint->upsize();
         });
         $img->resizeCanvas(680, 460, 'center', false, '#ffffff');
         $img = (string) $img->encode('jpg', 90);
         $thumb = Storage::cloud()->put($path, $img, 'public');
         $path_url = Storage::cloud()->url($path);
         $directory->photos = [$path_url];
         $directory->save();
         /*
         if($business->photos) {
             if(count($business->photos) > 5){
               return response()->json([
                   'msg' => 'Maximum file size exceeded',
                   'status' => false
               ], 403);
             }
             $new_arry = $business->photos;
             array_push($new_arry, $path_url);
             $business->photos = $new_arry;
             $business->save();
         }else{

           $business->photos = [$path_url];
           $business->save();
         }
         */
       }

       if($pay){
           $pay->directory_id = $listing->id;
           $pay->save();
       }
     }else{
       return response()->json([
           'msg' => "You are not authenticated",
           "status"=> false
       ], 401);
     }
     //$data = [];
     //$data['id'] = $listing->id;
     $this->saveOpenings($request, $listing);
     return response()->json([
         "id" => $listing->id,
         "msg" => "Business created",
         "status"=> true
     ], 200);
  }

  function updatebusiness($directory_id, Request $request){
    //Retrieve business

    $business = Directory::find($directory_id);
    if($business == null){
      return response()->json([
        "msg" => "No resource found",
        "status"=> false
      ], 404);
    }
    $user = auth('api')->user();
    if($user == null){
      return response()->json([
        "msg" => "You are not authenticated",
        "status"=> false
      ], 401);
    }
    if($business->user_id != $user->id){
      return response()->json([
          "msg" => "You are not authorized",
          "status" => false
      ], 403);
    }

    $params = $request->all();
    if($request->input('tags_string')) {
        $business->tags = explode(",", $request->input('tags_string'));
        $business->tags_string = $request->input('tags_string');
    }

    $business->fill($request->only(['name', 'description', 'about', 'services', 'lat', 'lng', 'city', 'country', 'email', 'phone', 'address', 'website']));
    if($request->get('state')) {
        $business->city = $request->get('state');
    }
    if($request->input('photos') && is_array($request->input('photos'))) {
        $business->photos = $request->input('photos');
    }
    if($request->input('photos-brand') && is_array($request->input('photos-brand'))) {
        $business->galleries = $request->input('photos-brand');
    }

    if($request->input('photos-banner') && is_array($request->input('photos-banner'))) {
        $business->coverphotos = $request->input('photos-banner');
    }

    if($request->get('lat') && $request->get('lng')) {
        $point= new Point($request->get('lat'), $request->get('lng'));
        $business->location = \DB::raw("GeomFromText('POINT(".$point->getLng()." ".$point->getLat().")')");
    }


    $business->save();
    //$listing->dayOpenTimeRanges()->create(['day' => 'monday', 'start' => '08:00', 'end' => '12:00']);

    /*
    if($request->get('opening_hidden')) {
       $openings_arr = json_decode($request->get('opening_hidden'), true);
       // validate sahpe or param
       foreach($openings_arr as $i => $opening) {
           switch($i){
             case 0:
               if($opening["isActive"] == true){
                   $dateTimeRange = DayOpenTimeRange::where('day', 'monday')->where('openable_id', $business->id)->first();
                   if($dateTimeRange){
                       $dateTimeRange->start =  Time::fromString($opening["timeFrom"]);
                       $dateTimeRange->end = Time::fromString($opening["timeTill"]);
                       $dateTimeRange->is_open = 1;
                       $dateTimeRange->save();
                   }else{
                     $dateTimeRange = new DayOpenTimeRange();
                     $dateTimeRange->openable_id =  $business->id;
                     $dateTimeRange->openable_type =  'App\Models\Directory';
                     $dateTimeRange->day =  'monday';
                     $dateTimeRange->start =  Time::fromString($opening["timeFrom"]);
                     $dateTimeRange->end = Time::fromString($opening["timeTill"]);
                     $dateTimeRange->save();
                   }
               }else{
                   $dateTimeRange = DayOpenTimeRange::where('day', 'monday')->where('openable_id', $business->id)->first();
                   if($dateTimeRange){
                       $dateTimeRange->is_open = 0;
                       $dateTimeRange->save();
                   }
               }
             break;
             case 1:
             if($opening["isActive"] == true){
                 $dateTimeRange = DayOpenTimeRange::where('day', 'tuesday')->where('openable_id', $business->id)->first();
                 if($dateTimeRange){
                     $dateTimeRange->start =  Time::fromString($opening["timeFrom"]);
                     $dateTimeRange->end = Time::fromString($opening["timeTill"]);
                     $dateTimeRange->is_open = 1;
                     $dateTimeRange->save();
                 }else{
                   $dateTimeRange = new DayOpenTimeRange();
                   $dateTimeRange->openable_id =  $business->id;
                   $dateTimeRange->openable_type =  'App\Models\Directory';
                   $dateTimeRange->day =  'tuesday';
                   $dateTimeRange->start =  Time::fromString($opening["timeFrom"]);
                   $dateTimeRange->end = Time::fromString($opening["timeTill"]);
                   $dateTimeRange->save();
                 }
             }else{
               $dateTimeRange = DayOpenTimeRange::where('day', 'tuesday')->where('openable_id', $business->id)->first();
               if($dateTimeRange){
                   $dateTimeRange->is_open = 0;
                   $dateTimeRange->save();
               }
             }
             break;
             case 2:
             if($opening["isActive"] == true){
                 $dateTimeRange = DayOpenTimeRange::where('day', 'wednesday')->where('openable_id', $business->id)->first();
                 if($dateTimeRange){
                     $dateTimeRange->start =  Time::fromString($opening["timeFrom"]);
                     $dateTimeRange->end = Time::fromString($opening["timeTill"]);
                     $dateTimeRange->is_open = 1;
                     $dateTimeRange->save();
                 }else{
                   $dateTimeRange = new DayOpenTimeRange();
                   $dateTimeRange->openable_id =  $business->id;
                   $dateTimeRange->openable_type =  'App\Models\Directory';
                   $dateTimeRange->day =  'wednesday';
                   $dateTimeRange->start =  Time::fromString($opening["timeFrom"]);
                   $dateTimeRange->end = Time::fromString($opening["timeTill"]);
                   $dateTimeRange->save();
                 }
             }else{
               $dateTimeRange = DayOpenTimeRange::where('day', 'wednesday')->where('openable_id', $business->id)->first();
               if($dateTimeRange){
                   $dateTimeRange->is_open = 0;
                   $dateTimeRange->save();
               }
             }
             break;
             case 3:
             if($opening["isActive"] == true){
                 $dateTimeRange = DayOpenTimeRange::where('day', 'thursday')->where('openable_id', $business->id)->first();
                 if($dateTimeRange){
                     $dateTimeRange->start =  Time::fromString($opening["timeFrom"]);
                     $dateTimeRange->end = Time::fromString($opening["timeTill"]);
                     $dateTimeRange->is_open = 1;
                     $dateTimeRange->save();
                 }else{
                   $dateTimeRange = new DayOpenTimeRange();
                   $dateTimeRange->openable_id =  $business->id;
                   $dateTimeRange->openable_type =  'App\Models\Directory';
                   $dateTimeRange->day =  'thursday';
                   $dateTimeRange->start =  Time::fromString($opening["timeFrom"]);
                   $dateTimeRange->end = Time::fromString($opening["timeTill"]);
                   $dateTimeRange->save();
                 }
             }else{
               $dateTimeRange = DayOpenTimeRange::where('day', 'thursday')->where('openable_id', $business->id)->first();
               if($dateTimeRange){
                   $dateTimeRange->is_open = 0;
                   $dateTimeRange->save();
               }
             }
             break;
             case 4:
             if($opening["isActive"] == true){
                 $dateTimeRange = DayOpenTimeRange::where('day', 'friday')->where('openable_id', $business->id)->first();
                 if($dateTimeRange){
                     $dateTimeRange->start =  Time::fromString($opening["timeFrom"]);
                     $dateTimeRange->end = Time::fromString($opening["timeTill"]);
                     $dateTimeRange->is_open = 1;
                     $dateTimeRange->save();
                 }else{
                   $dateTimeRange = new DayOpenTimeRange();
                   $dateTimeRange->openable_id =  $business->id;
                   $dateTimeRange->openable_type =  'App\Models\Directory';
                   $dateTimeRange->day =  'friday';
                   $dateTimeRange->start =  Time::fromString($opening["timeFrom"]);
                   $dateTimeRange->end = Time::fromString($opening["timeTill"]);
                   $dateTimeRange->save();
                 }
             }else{
               $dateTimeRange = DayOpenTimeRange::where('day', 'friday')->where('openable_id', $business->id)->first();
               if($dateTimeRange){
                   $dateTimeRange->is_open = 0;
                   $dateTimeRange->save();
               }
             }
             break;
             case 5:
             if($opening["isActive"] == true){
                 $dateTimeRange = DayOpenTimeRange::where('day', 'saturday')->where('openable_id', $business->id)->first();
                 if($dateTimeRange){
                     $dateTimeRange->start =  Time::fromString($opening["timeFrom"]);
                     $dateTimeRange->end = Time::fromString($opening["timeTill"]);
                     $dateTimeRange->is_open = 1;
                     $dateTimeRange->save();
                 }else{
                   $dateTimeRange = new DayOpenTimeRange();
                   $dateTimeRange->openable_id =  $business->id;
                   $dateTimeRange->openable_type =  'App\Models\Directory';
                   $dateTimeRange->day =  'saturday';
                   $dateTimeRange->start =  Time::fromString($opening["timeFrom"]);
                   $dateTimeRange->end = Time::fromString($opening["timeTill"]);
                   $dateTimeRange->save();
                 }
             }else{
               $dateTimeRange = DayOpenTimeRange::where('day', 'saturday')->where('openable_id', $business->id)->first();
               if($dateTimeRange){
                   $dateTimeRange->is_open = 0;
                   $dateTimeRange->save();
               }
             }
             break;
             case 6:
             if($opening["isActive"] == true){
                 $dateTimeRange = DayOpenTimeRange::where('day', 'sunday')->where('openable_id', $business->id)->first();
                 if($dateTimeRange){
                     $dateTimeRange->start =  Time::fromString($opening["timeFrom"]);
                     $dateTimeRange->end = Time::fromString($opening["timeTill"]);
                     $dateTimeRange->is_open = 1;
                     $dateTimeRange->save();
                 }else{
                   $dateTimeRange = new DayOpenTimeRange();
                   $dateTimeRange->openable_id =  $business->id;
                   $dateTimeRange->openable_type =  'App\Models\Directory';
                   $dateTimeRange->day =  'sunday';
                   $dateTimeRange->start =  Time::fromString($opening["timeFrom"]);
                   $dateTimeRange->end = Time::fromString($opening["timeTill"]);
                   $dateTimeRange->save();
                 }
             }else{
               $dateTimeRange = DayOpenTimeRange::where('day', 'sunday')->where('openable_id', $business->id)->first();
               if($dateTimeRange){
                   $dateTimeRange->is_open = 0;
                   $dateTimeRange->save();
               }
             }
             break;
           }
       }

    }

    */
    $this->saveOpenings($request, $business);
    /*
    if($request->file('logo')) {
        $image = Image::make($request->file('logo'))
                ->fit(200, 200, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->resizeCanvas(200, 200);
        Storage::cloud()->put('brandlogos/'.$business->path, (string) $image->encode());
        $business->logo = Storage::cloud()->url("brandlogos/".$business->path);
        $business->save();
    }
    */
    if($request->has('logo_base64')){
      $image = Image::make($request->get('logo_base64'))
              ->fit(200, 200, function ($constraint) {
                  $constraint->aspectRatio();
                  $constraint->upsize();
              })
              ->resizeCanvas(200, 200);
      Storage::cloud()->put('brandlogos/'.$business->path, (string) $image->encode());
      $business->logo = Storage::cloud()->url("brandlogos/".$business->path);
      $business->save();
    }

    if($request->has('banner_base64')){
      $path = 'images/'.date('Y/m/d') .'/'. $business->id . '_' .time().'.jpg';
      $img = Image::make($request->get('banner_base64'));

      $img->fit(680, 460, function ($constraint) {
          $constraint->upsize();
      });
      $img->resizeCanvas(680, 460, 'center', false, '#ffffff');
      $img = (string) $img->encode('jpg', 90);
      $thumb = Storage::cloud()->put($path, $img, 'public');
      $path_url = Storage::cloud()->url($path);

      if($business->photos) {
          if(count($business->photos) > 5){
            $limit_exceed = true;
          }else{
            $new_arry = $business->photos;
            array_push($new_arry, $path_url);
            $business->photos = $new_arry;
            $business->save();
          }
      }else{
        $business->photos = [$path_url];
        $business->save();
      }
    }
    //$data = [];
    //$data['id'] = $business->id;
    return response()->json([
      "id" => $business->id,
      "msg" => "Business updated",
      "status"=> true
    ], 200);

  }

  function getpaymentslots(Request $request){
    $user = auth('api')->user();
    if($user == null){
      return response()->json([
        'msg' => "You are not authenticated",
        "status"=> false
      ], 401);
    }
    $check_payment = DirectoryPayment::where('user_id', auth()->user()->id)->where('status', 'paid')->whereNull('directory_id')->get();
    $list_payment_type = [];
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

    if(count($check_payment) == 0){
      return response()->json([
        "msg" => "No resource found",
        "status"=> false
      ], 200);
    }else{
      $data['payment_types'] = $list_payment_type;
      return response()->json([
          "msg" => "Payment slots available",
          "data" => $data,
          "status"=> true
      ], 200);
    }
  }

  public function upload($directory_id, Request $request) {

      $user = auth('api')->user();
      $business = Directory::find($directory_id);
      if($user == null){
        return response()->json([
          'msg' => "You are not authenticated",
          "status"=> false
        ], 401);
      }
      if($business == null){
        return response()->json([
          'msg' => "No resource found",
          "status"=> false
        ], 404);
      }
      if($business->user_id != $user->id){
        return response()->json([
            'msg' => 'You are not authorized',
            'status' => false
        ], 403);
      }



      $path = 'images/'.date('Y/m/d') .'/'. $business->id . '_' .md5_file($request->image->getRealPath()).'.jpg';
      $img = Image::make($request->image);

      $img->fit(680, 460, function ($constraint) {
          $constraint->upsize();
      });
      $img->resizeCanvas(680, 460, 'center', false, '#ffffff');
      $img = (string) $img->encode('jpg', 90);
      $thumb = Storage::cloud()->put($path, $img, 'public');
      $path_url = Storage::cloud()->url($path);

      if($business->photos) {
          if(count($business->photos) > 5){
            return response()->json([
                'msg' => 'Maximum file size exceeded',
                'status' => false
            ], 403);
          }
          $new_arry = $business->photos;
          array_push($new_arry, $path_url);
          $business->photos = $new_arry;
          $business->save();
      }else{

        $business->photos = [$path_url];
        $business->save();
      }
      return response()->json([
          'path' => $path_url,
          'status' => true
      ], 200);
  }

  public function deleteUpload($directory_id, $uuid, Request $request) {

      $business = Directory::find($directory_id);
      $photos = (array) $business->photos;
      $arr_obj = [];
      // make it independent  sort off unset($photos[$uuid]);
      foreach($photos as $i => $photo){
        array_push($arr_obj, $photo);
      }
      unset($arr_obj[$uuid]);
      $arr_obj_b = [];
      foreach($arr_obj as $i => $photo){
        array_push($arr_obj_b, $photo);
      }
      //return ['success' => $arr_obj_b];

      $business->photos = $arr_obj_b;
      $business->save();
      return ['success' => true];
  }

  public function initiatepayments(Request $request){


    /*
    return response()->json([
      'proceed' => 'proceed',
      'access_code' => "bbsavjbvfdqhogbiaebpjipja",
    ], 200);

    $res_result = $this->get_access_code('ref', 50000, 'email');
    $body_test = json_decode($res_result['body'], true);
    return response()->json([
        'msg' => $body_test['url'],
        'status' => true
    ], 200);
    */
    if(!$request->has('payment_type')){
      return response()->json([
          'msg' => 'No valid business type',
          'status' => false
      ], 400);
    }

    //checkpayment
    $amount = 1000;
    $user = auth('api')->user();
    $user_id = null;
    if($user != null){
      $user_id = $user->id;
    }else{
      return response()->json([
          'msg' => "You are not authenticated",
          'status' => false
      ], 401);
    }
    $payment_type = $request->get('payment_type');
    $check = DirectoryPayment::where('user_id', $user_id)->where('payment_type', $payment_type)->whereNull('directory_id')->whereNotNull('paystack_access_code')->orderBy('created_at', 'DESC')->first();
    switch((int)$payment_type){
      case 1:
        $amount = 1000;
        break;
      case 2:
        $amount = 2500;
        break;

      case 3:
         $amount = 5000;
         break;

      default:
          $amount = 1000;
    }
    if($check){
      if($check->status == 'paid' && $check->verified == true){
        //need to skup
        return response()->json([
            'proceed' => 'paid',
            'status' => true
        ], 200);
      }else if($check->status == 'paid'){
        //need to verify
        return response()->json([
            'proceed' => 'verify',
            'status' => true
        ], 200);
      }else{
        //need to verify

        // Try to verify real status
        //$request->trxref = $check->paystack_reference;
        //$request->reference = $check->paystack_reference;
        $request->merge(["trxref"=> $check->paystack_reference, "reference" => $check->paystack_reference]);
        try {
          $paymentDetails = Paystack::getPaymentData();
          if(array_key_exists("metadata", $paymentDetails['data'])){
            if(array_key_exists('payment_type', $paymentDetails['data']['metadata'])){
              if($paymentDetails['data']['metadata']["payment_type"] != 3 && $paymentDetails['data']['metadata']["payment_type"] != 2){
                if($paymentDetails['data']['status'] == "success"){
                  //$data['status'] = "success";
                  $check->status = 'paid';
                  $check->verified = true;
                  $check->save();
                  return response()->json([
                      'proceed' => 'paid',
                      'status' => true
                  ], 200);
                  // redirect no need to pay
                }else{
                  //Get access code
                  $check->status = 'fail';
                  $check->save();
                  $reference = Paystack::genTranxRef();
                  $get_response = $this->get_access_code($reference, $amount, auth('api')->user()->email);
                  if($get_response['status'] == 'proceed'){
                    $body_test = json_decode($get_response['body'], true);
                    if($body_test['status']){

                      $access_code = $body_test['data']['access_code'];
                      $dir_pay = new DirectoryPayment();
                      $dir_pay->user_id = $user_id;
                      $dir_pay->paystack_reference = $reference;
                      $dir_pay->amount = $amount;
                      $dir_pay->payment_type = $payment_type;
                      $dir_pay->paystack_access_code = $access_code;
                      $dir_pay->save();

                      return response()->json([
                        'proceed' => 'proceed',
                        'access_code' => $access_code,
                        'status' => true,
                        'email' => auth('api')->user()->email
                      ], 200);
                   }else{
                     return response()->json([
                       'proceed' => 'retry',
                       'status' => false
                     ], 200);
                   }
                 }else{
                   return response()->json([
                     'proceed' => 'retry',
                     'status' => false
                   ], 200);
                 }
                }
              }
            }else{
              if($paymentDetails['data']['status'] == "success"){
                 //$data['status'] = "success";
                 $check->status = 'paid';
                 $check->verified = true;
                 $check->save();
                 return response()->json([
                     'proceed' => 'paid',
                     'status' => true
                 ], 200);
                 // redirect no need to pay
              }else{
                 //get access code
                 $check->status = 'fail';
                 $check->save();
                 $reference = Paystack::genTranxRef();
                 $get_response = $this->get_access_code($reference, $amount, auth('api')->user()->email);
                 if($get_response['status'] == 'proceed'){
                   $body_test = json_decode($get_response['body'], true);
                   if($body_test['status']){

                     $access_code = $body_test['data']['access_code'];
                     $dir_pay = new DirectoryPayment();
                     $dir_pay->user_id = $user_id;
                     $dir_pay->paystack_reference = $reference;
                     $dir_pay->amount = $amount;
                     $dir_pay->payment_type = $payment_type;
                     $dir_pay->paystack_access_code = $access_code;
                     $dir_pay->save();

                     return response()->json([
                       'proceed' => 'proceed',
                       'access_code' => $access_code,
                       'status' => true,
                       'email' => auth('api')->user()->email
                     ], 200);
                  }else{
                    return response()->json([
                      'proceed' => 'retry',
                      'status' => false
                    ], 200);
                  }
                 }else{
                   return response()->json([
                     'proceed' => 'retry',
                     'status' => false
                   ], 200);
                 }
              }
            }
          }else{
            if($paymentDetails['data']['status'] == "success"){
              //$data['status'] = "success";
              $check->status = 'paid';
              $check->verified = true;
              $check->save();
              return response()->json([
                  'proceed' => 'paid',
                  'status' => true
              ], 200);
            }else{
              //get accesscode
              $check->status = 'fail';
              $check->save();
              $reference = Paystack::genTranxRef();
              $get_response = $this->get_access_code($reference, $amount, auth('api')->user()->email);
              if($get_response['status'] == 'proceed'){
                $body_test = json_decode($get_response['body'], true);
                if($body_test['status']){

                  $access_code = $body_test['data']['access_code'];
                  $dir_pay = new DirectoryPayment();
                  $dir_pay->user_id = $user_id;
                  $dir_pay->paystack_reference = $reference;
                  $dir_pay->amount = $amount;
                  $dir_pay->payment_type = $payment_type;
                  $dir_pay->paystack_access_code = $access_code;
                  $dir_pay->save();

                  return response()->json([
                    'proceed' => 'proceed',
                    'access_code' => $access_code,
                    'status' => true,
                    'email' => auth('api')->user()->email
                  ], 200);
               }else{
                 return response()->json([
                   'proceed' => 'retry',
                   'status' => false
                 ], 200);
               }
              }else{
                return response()->json([
                  'proceed' => 'retry',
                  'status' => false
                ], 200);
              }
            }
          }
        }catch(\GuzzleHttp\Exception\ConnectException $e) {
          //$data['status'] = "fail"; Generate another
          //$data['check'] = false;
          return response()->json([
              'proceed' => 'unresolved',
              'status' => false
          ], 200);
        }catch(\GuzzleHttp\Exception\ClientException  $e){
          // Get access code
          $check->status = 'fail';
          $check->save();
          $reference = Paystack::genTranxRef();
          $get_response = $this->get_access_code($reference, $amount, auth('api')->user()->email);
          if($get_response['status'] == 'proceed'){
            $body_test = json_decode($get_response['body'], true);
            if($body_test['status']){

              $access_code = $body_test['data']['access_code'];
              $dir_pay = new DirectoryPayment();
              $dir_pay->user_id = $user_id;
              $dir_pay->paystack_reference = $reference;
              $dir_pay->amount = $amount;
              $dir_pay->payment_type = $payment_type;
              $dir_pay->paystack_access_code = $access_code;
              $dir_pay->save();

              return response()->json([
                'proceed' => 'proceed',
                'access_code' => $access_code,
                'status' => true,
                'email' => auth('api')->user()->email
              ], 200);
           }else{
             return response()->json([
               'proceed' => 'retry',
               'status' => false
             ], 200);
           }
          }else{
            return response()->json([
              'proceed' => 'retry',
              'status' => false
            ], 200);
          }

        }catch(\Exception $e){
         //get accesscode
         $check->status = 'fail';
         $check->save();
         $reference = Paystack::genTranxRef();
         $get_response = $this->get_access_code($reference, $amount, auth('api')->user()->email);
         if($get_response['status'] == 'proceed'){
           $body_test = json_decode($get_response['body'], true);
           if($body_test['status']){

             $access_code = $body_test['data']['access_code'];
             $dir_pay = new DirectoryPayment();
             $dir_pay->user_id = $user_id;
             $dir_pay->paystack_reference = $reference;
             $dir_pay->amount = $amount;
             $dir_pay->payment_type = $payment_type;
             $dir_pay->paystack_access_code = $access_code;
             $dir_pay->save();

             return response()->json([
               'proceed' => 'proceed',
               'access_code' => $access_code,
               'status' => true,
               'email' => auth('api')->user()->email
             ], 200);
          }else{
            return response()->json([
              'proceed' => 'retry',
              'status' => false
            ], 200);
          }
         }else{
           return response()->json([
             'proceed' => 'retry',
             'status' => false
           ], 200);
         }
        }

       }

    }else{
      $reference = Paystack::genTranxRef();
      $get_response = $this->get_access_code($reference, $amount, auth('api')->user()->email);
      if($get_response['status'] == 'proceed'){
        $body_test = json_decode($get_response['body'], true);
        if($body_test['status']){

          $access_code = $body_test['data']['access_code'];
          $dir_pay = new DirectoryPayment();
          $dir_pay->user_id = $user_id;
          $dir_pay->paystack_reference = $reference;
          $dir_pay->amount = $amount;
          $dir_pay->payment_type = $payment_type;
          $dir_pay->paystack_access_code = $access_code;
          $dir_pay->save();

          return response()->json([
            'proceed' => 'proceed',
            'access_code' => $access_code,
            'status' => true,
            'email' => auth('api')->user()->email
          ], 200);
       }else{
         return response()->json([
           'proceed' => 'retry',
           'status' => false
         ], 200);
       }
      }else{
        return response()->json([
          'proceed' => 'retry',
          'status' => false
        ], 200);
      }

    }

  }

  public function verifypayment(Request $request){
    if($request->has('reference')){
      $directory_payment = DirectoryPayment::where('paystack_reference', $request->get('reference'))->first();
      if($directory_payment){
        $verify_status = $this->verify_payment($directory_payment->paystack_reference);
        if($verify_status['status'] == 'success'){
          $body_test = json_decode($verify_status['body'], true);
          if($body_test['status']){
            $access_status = $body_test ['data']['status'];
            if($access_status == "success"){
              $directory_payment->status = 'paid';
              $directory_payment->verified = true;
              $directory_payment->save();
            }
            return response()->json([
              'status' => true,
              'transaction_status' => $access_status,
              'msg' => $access_status
            ], 200);
          }else{
            return response()->json([
              'status' => false,
              'transaction_status' => 'Unknown',
              'msg' => 'Invalid reference'
            ], 200);
          }
        }else{
          return response()->json([
            'status' => false,
            'transaction_status' => 'Unknown',
            'msg' => $verify_status['status']
          ], 200);
        }
      }else{
        return response()->json([
          'status' => false,
          'msg' => 'Invalid reference'
        ], 400);
      }
    }else{
      return response()->json([
        'status' => false,
        'msg' => 'Invalid reference'
      ], 400);
    }
  }

  private function get_access_code($reference, $amount, $email){
    $pay_amount = $amount * 100;
    $status = [];
    $status['body'] = null;
    $client = new Client(); //GuzzleHttp\Client
    $headers = [
    "Authorization" => "Bearer " . getenv('PAYSTACK_SECRET_KEY'/*'PAYSTACK_SECRET_KEY'*/),
      "Content-Type" => "application/json",
      "Accept" => "application/json"
    ];
    $result = null;
    try {
    $result = $client->post('https://api.paystack.co/transaction/initialize', [
      'headers' => $headers,
      \GuzzleHttp\RequestOptions::JSON => ['reference' => $reference, 'amount' => $pay_amount, 'email' => $email,
      'metadata' => array("payment_type" => 1)]
     ]);
     $status['status'] = 'proceed';
     $status['body'] =  $result->getBody();
   }catch(\GuzzleHttp\Exception\ConnectException  $e){
     // Connection error
     $status['status'] = 'connectionerror';
     $result = null;

   }catch(\GuzzleHttp\Exception\ClientException  $e){
     // Bad code
     $status['status'] = 'clienterror';
     $result = null;

   }catch(\Exception $e){
     // Likely Conection error
     $status['status'] = 'unknown';
     $result = null;
   }
   $status['result'] = $result;
     return $status;
  }

  private function verify_payment($access_code){

    $pay_url = "https://api.paystack.co/transaction/verify/" . $access_code;

    $status = [];
    $status['body'] = null;
    $client = new Client(); //GuzzleHttp\Client
    $headers = [
    "Authorization" => "Bearer " . getenv('PAYSTACK_SECRET_KEY'/*'PAYSTACK_SECRET_KEY'*/),
      "Content-Type" => "application/json",
      "Accept" => "application/json"
    ];
    $result = null;
    try {
    $result = $client->get($pay_url, [
      'headers' => $headers
     ]);
     $status['status'] = 'success';
     $status['body'] =  $result->getBody();

   }catch(\GuzzleHttp\Exception\ConnectException  $e){
     // Connection error
     $status['status'] = 'connectionerror';
     $result = null;

   }catch(\GuzzleHttp\Exception\ClientException  $e){
     // Bad code
     $status['status'] = 'clienterror';
     $result = null;
     //dd($e);
   }catch(\Exception $e){
     // Likely Conection error
     $status['status'] = 'unknown';
     $result = null;

   }
   $status['result'] = $result;
     return $status;
  }

  /**
   * Store a newly created resource in storage.
   * @param  Request $request
   * @return Response
   */
  public function review(Request $request, $directory_id)
  {
     $user = auth('api')->user();
      if($user == null){
       return response()->json([
        'msg' => "You are not authenticated",
        "status"=> false
       ], 401);
      }
      $directory = Directory::find($directory_id);
      if(!$directory){
         return response()->json(array("msg" => "Invalid direcory", "status"=> false), 400);
      }
      $validator = Validator::make($request->all(), [
          'comment' => 'required|min:5',
          'score' => 'required',
      ]);

      if($validator->fails()){
       $error = $validator->messages();
       //$error =$error false;
       return response()->json(array("msg" =>$error, "status"=> false), 400);
      }
      $user->brandcomment($directory, $request->get('comment'), $request->get('score'));

      return response()->json([
        'status' => true,
        'msg' => 'Review saved'
      ], 200);
  }

  public function getreviews(Request $request, $business_id){
    $comments = BrandComment::where("directory_id", $business_id)->whereNotNull("rate")->paginate(100);
    return response()->json([
      'reviews' => $comments,
      'status' => true
    ], 200);
  }

  public function userreview(Request $request, $business_id){
    $user = auth('api')->user();
    if($user == null){
      return response()->json([
        "msg" => "You are not authenticated",
        "status"=> false
      ], 401);
    }
    $comments = BrandComment::where("directory_id", $business_id)->where("commenter_id", $user->id)->orderBy("updated_at", "DESC")->get();
    $reviewobj = null;
    if(count($comments) > 0){
      $reviewobj = $comments[0];
    }
    return response()->json([
      'review' => $reviewobj,
      'status' => true
    ], 200);
  }

  private function saveOpenings(Request $request, Directory $business){
    if($request->get('opening_hidden')) {
       $openings_arr = json_decode($request->get('opening_hidden'), true);
       // validate sahpe or param
       foreach($openings_arr as $i => $opening) {
           if(!array_key_exists("from", $opening)){
             $opening["from"] = "";
             $opening["to"] = "";
           }else{
             $opening["timeFrom"] = $opening["from"];
             $opening["timeTill"] = $opening["to"];
           }
           switch($opening["day"]){
             case "Mon":
               if($opening["from"] != ""){
                   $dateTimeRange = DayOpenTimeRange::where('day', 'monday')->where('openable_id', $business->id)->first();
                   if($dateTimeRange){
                       $dateTimeRange->start =  Time::fromString($opening["timeFrom"]);
                       $dateTimeRange->end = Time::fromString($opening["timeTill"]);
                       $dateTimeRange->is_open = 1;
                       $dateTimeRange->save();
                   }else{
                     $dateTimeRange = new DayOpenTimeRange();
                     $dateTimeRange->openable_id =  $business->id;
                     $dateTimeRange->openable_type =  'App\Models\Directory';
                     $dateTimeRange->day =  'monday';
                     $dateTimeRange->start =  Time::fromString($opening["timeFrom"]);
                     $dateTimeRange->end = Time::fromString($opening["timeTill"]);
                     $dateTimeRange->save();
                   }
               }else{
                   $dateTimeRange = DayOpenTimeRange::where('day', 'monday')->where('openable_id', $business->id)->first();
                   if($dateTimeRange){
                       $dateTimeRange->is_open = 0;
                       $dateTimeRange->save();
                   }
               }
             break;
             case "Tue":
             if($opening["from"] != ""){
                 $dateTimeRange = DayOpenTimeRange::where('day', 'tuesday')->where('openable_id', $business->id)->first();
                 if($dateTimeRange){
                     $dateTimeRange->start =  Time::fromString($opening["timeFrom"]);
                     $dateTimeRange->end = Time::fromString($opening["timeTill"]);
                     $dateTimeRange->is_open = 1;
                     $dateTimeRange->save();
                 }else{
                   $dateTimeRange = new DayOpenTimeRange();
                   $dateTimeRange->openable_id =  $business->id;
                   $dateTimeRange->openable_type =  'App\Models\Directory';
                   $dateTimeRange->day =  'tuesday';
                   $dateTimeRange->start =  Time::fromString($opening["timeFrom"]);
                   $dateTimeRange->end = Time::fromString($opening["timeTill"]);
                   $dateTimeRange->save();
                 }
             }else{
               $dateTimeRange = DayOpenTimeRange::where('day', 'tuesday')->where('openable_id', $business->id)->first();
               if($dateTimeRange){
                   $dateTimeRange->is_open = 0;
                   $dateTimeRange->save();
               }
             }
             break;
             case "Wed":
             if($opening["from"] != ""){
                 $dateTimeRange = DayOpenTimeRange::where('day', 'wednesday')->where('openable_id', $business->id)->first();
                 if($dateTimeRange){
                     $dateTimeRange->start =  Time::fromString($opening["timeFrom"]);
                     $dateTimeRange->end = Time::fromString($opening["timeTill"]);
                     $dateTimeRange->is_open = 1;
                     $dateTimeRange->save();
                 }else{
                   $dateTimeRange = new DayOpenTimeRange();
                   $dateTimeRange->openable_id =  $business->id;
                   $dateTimeRange->openable_type =  'App\Models\Directory';
                   $dateTimeRange->day =  'wednesday';
                   $dateTimeRange->start =  Time::fromString($opening["timeFrom"]);
                   $dateTimeRange->end = Time::fromString($opening["timeTill"]);
                   $dateTimeRange->save();
                 }
             }else{
               $dateTimeRange = DayOpenTimeRange::where('day', 'wednesday')->where('openable_id', $business->id)->first();
               if($dateTimeRange){
                   $dateTimeRange->is_open = 0;
                   $dateTimeRange->save();
               }
             }
             break;
             case "Thu":
             if($opening["from"] != ""){
                 $dateTimeRange = DayOpenTimeRange::where('day', 'thursday')->where('openable_id', $business->id)->first();
                 if($dateTimeRange){
                     $dateTimeRange->start =  Time::fromString($opening["timeFrom"]);
                     $dateTimeRange->end = Time::fromString($opening["timeTill"]);
                     $dateTimeRange->is_open = 1;
                     $dateTimeRange->save();
                 }else{
                   $dateTimeRange = new DayOpenTimeRange();
                   $dateTimeRange->openable_id =  $business->id;
                   $dateTimeRange->openable_type =  'App\Models\Directory';
                   $dateTimeRange->day =  'thursday';
                   $dateTimeRange->start =  Time::fromString($opening["timeFrom"]);
                   $dateTimeRange->end = Time::fromString($opening["timeTill"]);
                   $dateTimeRange->save();
                 }
             }else{
               $dateTimeRange = DayOpenTimeRange::where('day', 'thursday')->where('openable_id', $business->id)->first();
               if($dateTimeRange){
                   $dateTimeRange->is_open = 0;
                   $dateTimeRange->save();
               }
             }
             break;
             case "Fri":
             if($opening["from"] != ""){
                 $dateTimeRange = DayOpenTimeRange::where('day', 'friday')->where('openable_id', $business->id)->first();
                 if($dateTimeRange){
                     $dateTimeRange->start =  Time::fromString($opening["timeFrom"]);
                     $dateTimeRange->end = Time::fromString($opening["timeTill"]);
                     $dateTimeRange->is_open = 1;
                     $dateTimeRange->save();
                 }else{
                   $dateTimeRange = new DayOpenTimeRange();
                   $dateTimeRange->openable_id =  $business->id;
                   $dateTimeRange->openable_type =  'App\Models\Directory';
                   $dateTimeRange->day =  'friday';
                   $dateTimeRange->start =  Time::fromString($opening["timeFrom"]);
                   $dateTimeRange->end = Time::fromString($opening["timeTill"]);
                   $dateTimeRange->save();
                 }
             }else{
               $dateTimeRange = DayOpenTimeRange::where('day', 'friday')->where('openable_id', $business->id)->first();
               if($dateTimeRange){
                   $dateTimeRange->is_open = 0;
                   $dateTimeRange->save();
               }
             }
             break;
             case "Sat":
             if($opening["from"] != ""){
                 $dateTimeRange = DayOpenTimeRange::where('day', 'saturday')->where('openable_id', $business->id)->first();
                 if($dateTimeRange){
                     $dateTimeRange->start =  Time::fromString($opening["timeFrom"]);
                     $dateTimeRange->end = Time::fromString($opening["timeTill"]);
                     $dateTimeRange->is_open = 1;
                     $dateTimeRange->save();
                 }else{
                   $dateTimeRange = new DayOpenTimeRange();
                   $dateTimeRange->openable_id =  $business->id;
                   $dateTimeRange->openable_type =  'App\Models\Directory';
                   $dateTimeRange->day =  'saturday';
                   $dateTimeRange->start =  Time::fromString($opening["timeFrom"]);
                   $dateTimeRange->end = Time::fromString($opening["timeTill"]);
                   $dateTimeRange->save();
                 }
             }else{
               $dateTimeRange = DayOpenTimeRange::where('day', 'saturday')->where('openable_id', $business->id)->first();
               if($dateTimeRange){
                   $dateTimeRange->is_open = 0;
                   $dateTimeRange->save();
               }
             }
             break;
             case "Sun":
             if($opening["from"] != ""){
                 $dateTimeRange = DayOpenTimeRange::where('day', 'sunday')->where('openable_id', $business->id)->first();
                 if($dateTimeRange){
                     $dateTimeRange->start =  Time::fromString($opening["timeFrom"]);
                     $dateTimeRange->end = Time::fromString($opening["timeTill"]);
                     $dateTimeRange->is_open = 1;
                     $dateTimeRange->save();
                 }else{
                   $dateTimeRange = new DayOpenTimeRange();
                   $dateTimeRange->openable_id =  $business->id;
                   $dateTimeRange->openable_type =  'App\Models\Directory';
                   $dateTimeRange->day =  'sunday';
                   $dateTimeRange->start =  Time::fromString($opening["timeFrom"]);
                   $dateTimeRange->end = Time::fromString($opening["timeTill"]);
                   $dateTimeRange->save();
                 }
             }else{
               $dateTimeRange = DayOpenTimeRange::where('day', 'sunday')->where('openable_id', $business->id)->first();
               if($dateTimeRange){
                   $dateTimeRange->is_open = 0;
                   $dateTimeRange->save();
               }
             }
             break;
           }
       }

    }
  }

  public function contactmessage(Request $request, $business_id)
  {

    $businessObj = Directory::where('id', $business_id)->first();
    if($businessObj == null){
      return response()->json([
        "msg" => "Business does not exist",
        "status"=> false
      ], 404);
    }else{
      if($businessObj->topbrand == null || $businessObj->is_disabled != null ){
        return response()->json([
          "msg" => "Business contact form is not active",
          "status"=> false
        ], 404);
      }
    }
    $validator = Validator::make($request->all(), [
        'name' => 'required|min:5|max:255',
        'email' => 'required|string|email|max:255',
        'message' => 'required|min:5',
    ]);

    if($validator->fails()){
     $error = $validator->messages();
     //$error =$error false;
     return response()->json(array("msg" =>$error, "status"=> false), 400);
    }
    $name = $request->get('name');
    $email = $request->get('email');
    $message = $request->get('message');

     $data = ['name' => $name, 'email' => $email,
              'message' => $message];
     //\Mail::to('')->send(new ContactMessage($data));
     //\Mail::to($businessObj->email)->send(new ContactMessage($data));
     return response()->json([
         'status' => true,
         'msg' => "Mail Sent"
     ], 200);
  }

}
