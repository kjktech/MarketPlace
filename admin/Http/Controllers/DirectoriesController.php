<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Mail\NewBrandListing;
use App\Mail\NewAdminBrandListing;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Models\Directory;
use App\Models\DirectoryCategory;
use App\Models\DayOpenTimeRange;
use App\Models\DirectoryPayment;
use App\Models\DirectoryLedger;
use App\Models\Store;
use App\Models\StoreLedger;
use App\Models\User;
use App\Entities\Time;
use App\Models\DirectorySocialPage;

use Grimzy\LaravelMysqlSpatial\Types\Point;
use Storage;
use Image;
//use Location;
use GeoIP;
use Validator;
use App\Mail\TopBrandMessage;
use Paystack;

use Kris\LaravelFormBuilder\FormBuilder;

class DirectoriesController extends Controller
{
    /**
     * Display a directory of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $directories = new Directory();
        if($request->get('q')) {
            $directories = $directories->search($request->get('q'));
        }
        // If role less than super admin only show businesses created by this role
        if(auth()->user()->can('basic approval')){
          $directories = $directories->whereNull('topbrand')->orderBy('created_at', 'desc');
        }else{
          $directories = $directories->where('setup_id', auth()->user()->id)->whereNull('topbrand')->orderBy('created_at', 'desc');
        }
        $data['directories'] = $directories->paginate(50);

        return view('panel::directories.index', $data);
    }

    public function dashboardindex(Request $request){
      return view('panel::directorydashboard.index');
    }

    public function indextop(Request $request)
    {
        $directories = new Directory();
        if($request->get('q')) {
            $directories = $directories->search($request->get('q'));
        }
        if(auth()->user()->can('basic approval')){
          $directories = $directories->whereNotNull('topbrand')->orderBy('created_at', 'desc');
        }else{
          $directories = $directories->where('setup_id', auth()->user()->id)->whereNotNull('topbrand')->orderBy('created_at', 'desc');
        }

        $data['directories'] = $directories->paginate(50);

        return view('panel::directories.index_top', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(Request $request)
    {
      $data = [];
      $categories = DirectoryCategory::nested()->get();
      $categories = flatten($categories, 0);
      $list = [];
      $list_payment_type = [];
      if(auth()->user()->can('edit listing')){
        $list_payment_type[0] = "Admin - Individual Business";
        $list_payment_type[-1] = "Admin - Enterprise Business";
        $list_payment_type[-2] = "Admin - Limited Liability";
      }
      foreach($categories as $category) {
          $list[''] = '';
          $list[$category['id']] = str_repeat("&mdash;", $category['depth']+1) . " " .$category['name'];
      }
      $data['categories'] = $list;
      $data['payment_types'] = $list_payment_type;
      if($request->has('topbrand')){
        $data['topbrand'] = true;
      }else{
        $data['topbrand'] = false;
      }
        return view('panel::directories.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
      $params = $request->all();
      #return response('OK', 200)->header('X-IC-Redirect', '/create/r4W0J7ObQJ/edit#images_section');
      $validator = Validator::make($request->all(), [
          'name' => 'required|min:5|max:255',
          'description' => 'required|min:5',
      ]);

      if ($validator->fails()) {
          return redirect(route('panel.directories.create'))
                      ->withErrors($validator)
                      ->withInput();
      }

      $params['directory_category_id'] = $request->get('category');
      $params['user_id'] = auth()->user()->id;
      $params['name'] = $request->get('name');
      $params['description'] = $request->get('description');
      $params['country'] = 'Nigeria';
      $params['setup_id'] = auth()->user()->id;

      //set a default city - let user fine tune later

      //$city = GeoIP::getCity();
      //$params['lat'] = (float) GeoIP::getLatitude();
      //$params['lng'] = (float) GeoIP::getLongitude();
      //$params['location'] = new Point($params['lat'], $params['lng']);
      //$params['city'] = $city;
      //$params['country'] = GeoIP::getCountryCode();
      $params['slug'] =  str_slug($request->get('name'));

      $directory = Directory::create($params);

      if($request->has('topbrand')){
         $directory->topbrand = Carbon::now();
         $directory->save();
      }

      $payment_type = (int)$request->get('payment_type');
      switch ($payment_type) {
        case 0:
          // code...
          $dir_pay = new DirectoryPayment();
          $dir_pay->user_id = auth()->user()->id;
          $dir_pay->paystack_reference = Paystack::genTranxRef();
          $dir_pay->amount = 1000;
          $dir_pay->payment_type = 1;
          $dir_pay->directory_id = $directory->id;
          $dir_pay->save();
          break;
        case -1:
            // code...
            $dir_pay = new DirectoryPayment();
            $dir_pay->user_id = auth()->user()->id;
            $dir_pay->paystack_reference = Paystack::genTranxRef();
            $dir_pay->amount = 2500;
            $dir_pay->payment_type = 2;
            $dir_pay->directory_id = $directory->id;
            $dir_pay->save();
          break;
        case -2:
              // code...
          $dir_pay = new DirectoryPayment();
          $dir_pay->user_id = auth()->user()->id;
          $dir_pay->paystack_reference = Paystack::genTranxRef();
          $dir_pay->amount = 5000;
          $dir_pay->payment_type = 3;
          $dir_pay->directory_id = $directory->id;
          $dir_pay->save();
          break;
        default:
          // code...
          break;
      }

      //redirect to success page
      return redirect(route('panel.directories.edit', $directory));
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
    public function edit($directory)
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
        //return redirect($directory->url);

        // get social $pages
        $linkedin_link = null;
        $facebook_link = null;
        $twitter_link = null;
        $instagram_link = null;
        if($directory->is_topbrand){
        $social_pages = DirectorySocialPage::where('directory_id', $directory->id)->first();
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
        if($directory->city){
          $data['city'] = $directory->city;
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

        $data['directory'] = $directory;
        $data['city_array'] = $cities_array;

        // retrieve opening date_times
        $openings = [];
        $dateTimeRangeMon = DayOpenTimeRange::where('day', 'monday')->where('openable_id', $directory->id)->first();
        if($dateTimeRangeMon){
          if($dateTimeRangeMon->is_open == 1){
             $openings[0] = Array("isActive" => true,"timeFrom" => substr($dateTimeRangeMon->start,0,5),"timeTill" => substr($dateTimeRangeMon->end,0,5));
          }else{
            $openings[0] = Array("isActive" => false,"timeFrom" => null,"timeTill" => null);
          }
        }else{
          $openings[0] = Array("isActive" => false,"timeFrom" => null,"timeTill" => null);
        }
        $dateTimeRangeTue = DayOpenTimeRange::where('day', 'tuesday')->where('openable_id', $directory->id)->first();
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
        $dateTimeRangeWed = DayOpenTimeRange::where('day', 'wednesday')->where('openable_id', $directory->id)->first();
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
        $dateTimeRangeThu = DayOpenTimeRange::where('day', 'thursday')->where('openable_id', $directory->id)->first();
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
        $dateTimeRangeFri = DayOpenTimeRange::where('day', 'friday')->where('openable_id', $directory->id)->first();
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
        $dateTimeRangeSat = DayOpenTimeRange::where('day', 'saturday')->where('openable_id', $directory->id)->first();
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
        $dateTimeRangeSun = DayOpenTimeRange::where('day', 'sunday')->where('openable_id', $directory->id)->first();
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

        return view('panel::directories.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
     public function update($directory, Request $request)
     {
         //$this->authorize('update', $directory);

         //dd($request->get('description'));

         $params = $request->all();

         //$filters = Filter::orderBy('position', 'ASC')->where('is_hidden', 0)->where('is_default', 0)->get();
         if($request->input('tags_string')) {
             $directory->tags = explode(",", $request->input('tags_string'));
             $directory->tags_string = $request->input('tags_string');
         }

         $directory->fill($request->only(['name', 'description', 'about', 'services', 'lat', 'lng', 'city', 'country', 'email', 'phone', 'address', 'website', 'directory_category_id']));
         if($request->input('photos') && is_array($request->input('photos'))) {
             $directory->photos = $request->input('photos');
         }
         if($request->input('photos-brand') && is_array($request->input('photos-brand'))) {
             $directory->galleries = $request->input('photos-brand');
         }

         if($request->input('photos-banner') && is_array($request->input('photos-banner'))) {
             $directory->coverphotos = $request->input('photos-banner');
         }

         if($request->get('lat') && $request->get('lng')) {
             $point= new Point($request->get('lat'), $request->get('lng'));
             $directory->location = \DB::raw("GeomFromText('POINT(".$point->getLng()." ".$point->getLat().")')");
         }

         $directory->save();
         //$directory->dayOpenTimeRanges()->create(['day' => 'monday', 'start' => '08:00', 'end' => '12:00']);

         if($request->get('opening_hidden')) {
            $openings_arr = json_decode($request->get('opening_hidden'), true);
            foreach($openings_arr as $i => $opening) {
                switch($i){
                  case 0:
                    if($opening["isActive"] == true){
                        $dateTimeRange = DayOpenTimeRange::where('day', 'monday')->where('openable_id', $directory->id)->first();
                        if($dateTimeRange){
                            $dateTimeRange->start =  Time::fromString($opening["timeFrom"]);
                            $dateTimeRange->end = Time::fromString($opening["timeTill"]);
                            $dateTimeRange->is_open = 1;
                            $dateTimeRange->save();
                        }else{
                          $dateTimeRange = new DayOpenTimeRange();
                          $dateTimeRange->openable_id =  $directory->id;
                          $dateTimeRange->openable_type =  'App\Models\Directory';
                          $dateTimeRange->day =  'monday';
                          $dateTimeRange->start =  Time::fromString($opening["timeFrom"]);
                          $dateTimeRange->end = Time::fromString($opening["timeTill"]);
                          $dateTimeRange->save();
                        }
                    }else{
                        $dateTimeRange = DayOpenTimeRange::where('day', 'monday')->where('openable_id', $directory->id)->first();
                        if($dateTimeRange){
                            $dateTimeRange->is_open = 0;
                            $dateTimeRange->save();
                        }
                    }
                  break;
                  case 1:
                  if($opening["isActive"] == true){
                      $dateTimeRange = DayOpenTimeRange::where('day', 'tuesday')->where('openable_id', $directory->id)->first();
                      if($dateTimeRange){
                          $dateTimeRange->start =  Time::fromString($opening["timeFrom"]);
                          $dateTimeRange->end = Time::fromString($opening["timeTill"]);
                          $dateTimeRange->is_open = 1;
                          $dateTimeRange->save();
                      }else{
                        $dateTimeRange = new DayOpenTimeRange();
                        $dateTimeRange->openable_id =  $directory->id;
                        $dateTimeRange->openable_type =  'App\Models\Directory';
                        $dateTimeRange->day =  'tuesday';
                        $dateTimeRange->start =  Time::fromString($opening["timeFrom"]);
                        $dateTimeRange->end = Time::fromString($opening["timeTill"]);
                        $dateTimeRange->save();
                      }
                  }else{
                    $dateTimeRange = DayOpenTimeRange::where('day', 'tuesday')->where('openable_id', $directory->id)->first();
                    if($dateTimeRange){
                        $dateTimeRange->is_open = 0;
                        $dateTimeRange->save();
                    }
                  }
                  break;
                  case 2:
                  if($opening["isActive"] == true){
                      $dateTimeRange = DayOpenTimeRange::where('day', 'wednesday')->where('openable_id', $directory->id)->first();
                      if($dateTimeRange){
                          $dateTimeRange->start =  Time::fromString($opening["timeFrom"]);
                          $dateTimeRange->end = Time::fromString($opening["timeTill"]);
                          $dateTimeRange->is_open = 1;
                          $dateTimeRange->save();
                      }else{
                        $dateTimeRange = new DayOpenTimeRange();
                        $dateTimeRange->openable_id =  $directory->id;
                        $dateTimeRange->openable_type =  'App\Models\Directory';
                        $dateTimeRange->day =  'wednesday';
                        $dateTimeRange->start =  Time::fromString($opening["timeFrom"]);
                        $dateTimeRange->end = Time::fromString($opening["timeTill"]);
                        $dateTimeRange->save();
                      }
                  }else{
                    $dateTimeRange = DayOpenTimeRange::where('day', 'wednesday')->where('openable_id', $directory->id)->first();
                    if($dateTimeRange){
                        $dateTimeRange->is_open = 0;
                        $dateTimeRange->save();
                    }
                  }
                  break;
                  case 3:
                  if($opening["isActive"] == true){
                      $dateTimeRange = DayOpenTimeRange::where('day', 'thursday')->where('openable_id', $directory->id)->first();
                      if($dateTimeRange){
                          $dateTimeRange->start =  Time::fromString($opening["timeFrom"]);
                          $dateTimeRange->end = Time::fromString($opening["timeTill"]);
                          $dateTimeRange->is_open = 1;
                          $dateTimeRange->save();
                      }else{
                        $dateTimeRange = new DayOpenTimeRange();
                        $dateTimeRange->openable_id =  $directory->id;
                        $dateTimeRange->openable_type =  'App\Models\Directory';
                        $dateTimeRange->day =  'thursday';
                        $dateTimeRange->start =  Time::fromString($opening["timeFrom"]);
                        $dateTimeRange->end = Time::fromString($opening["timeTill"]);
                        $dateTimeRange->save();
                      }
                  }else{
                    $dateTimeRange = DayOpenTimeRange::where('day', 'thursday')->where('openable_id', $directory->id)->first();
                    if($dateTimeRange){
                        $dateTimeRange->is_open = 0;
                        $dateTimeRange->save();
                    }
                  }
                  break;
                  case 4:
                  if($opening["isActive"] == true){
                      $dateTimeRange = DayOpenTimeRange::where('day', 'friday')->where('openable_id', $directory->id)->first();
                      if($dateTimeRange){
                          $dateTimeRange->start =  Time::fromString($opening["timeFrom"]);
                          $dateTimeRange->end = Time::fromString($opening["timeTill"]);
                          $dateTimeRange->is_open = 1;
                          $dateTimeRange->save();
                      }else{
                        $dateTimeRange = new DayOpenTimeRange();
                        $dateTimeRange->openable_id =  $directory->id;
                        $dateTimeRange->openable_type =  'App\Models\Directory';
                        $dateTimeRange->day =  'friday';
                        $dateTimeRange->start =  Time::fromString($opening["timeFrom"]);
                        $dateTimeRange->end = Time::fromString($opening["timeTill"]);
                        $dateTimeRange->save();
                      }
                  }else{
                    $dateTimeRange = DayOpenTimeRange::where('day', 'friday')->where('openable_id', $directory->id)->first();
                    if($dateTimeRange){
                        $dateTimeRange->is_open = 0;
                        $dateTimeRange->save();
                    }
                  }
                  break;
                  case 5:
                  if($opening["isActive"] == true){
                      $dateTimeRange = DayOpenTimeRange::where('day', 'saturday')->where('openable_id', $directory->id)->first();
                      if($dateTimeRange){
                          $dateTimeRange->start =  Time::fromString($opening["timeFrom"]);
                          $dateTimeRange->end = Time::fromString($opening["timeTill"]);
                          $dateTimeRange->is_open = 1;
                          $dateTimeRange->save();
                      }else{
                        $dateTimeRange = new DayOpenTimeRange();
                        $dateTimeRange->openable_id =  $directory->id;
                        $dateTimeRange->openable_type =  'App\Models\Directory';
                        $dateTimeRange->day =  'saturday';
                        $dateTimeRange->start =  Time::fromString($opening["timeFrom"]);
                        $dateTimeRange->end = Time::fromString($opening["timeTill"]);
                        $dateTimeRange->save();
                      }
                  }else{
                    $dateTimeRange = DayOpenTimeRange::where('day', 'saturday')->where('openable_id', $directory->id)->first();
                    if($dateTimeRange){
                        $dateTimeRange->is_open = 0;
                        $dateTimeRange->save();
                    }
                  }
                  break;
                  case 6:
                  if($opening["isActive"] == true){
                      $dateTimeRange = DayOpenTimeRange::where('day', 'sunday')->where('openable_id', $directory->id)->first();
                      if($dateTimeRange){
                          $dateTimeRange->start =  Time::fromString($opening["timeFrom"]);
                          $dateTimeRange->end = Time::fromString($opening["timeTill"]);
                          $dateTimeRange->is_open = 1;
                          $dateTimeRange->save();
                      }else{
                        $dateTimeRange = new DayOpenTimeRange();
                        $dateTimeRange->openable_id =  $directory->id;
                        $dateTimeRange->openable_type =  'App\Models\Directory';
                        $dateTimeRange->day =  'sunday';
                        $dateTimeRange->start =  Time::fromString($opening["timeFrom"]);
                        $dateTimeRange->end = Time::fromString($opening["timeTill"]);
                        $dateTimeRange->save();
                      }
                  }else{
                    $dateTimeRange = DayOpenTimeRange::where('day', 'sunday')->where('openable_id', $directory->id)->first();
                    if($dateTimeRange){
                        $dateTimeRange->is_open = 0;
                        $dateTimeRange->save();
                    }
                  }
                  break;
                }
            }

         }
         $directory = Directory::findOrFail($directory->id);
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
             $directory->is_published = false;
         }
         if($request->has('undraft')) {
             $directory->is_draft = false;
         }

         if($request->has('undotop') || $request->has('top')) {
           if(auth()->user()->can('disable listing')) {
             $directory->toggleTopbrand();
           }
         }

         //Verify branding
         if($request->has('verify')) {
         // disable listing might do it various places
         if(auth()->user()->can('basic approval')) {
             if($directory->is_admin_verified && !$directory->is_disabled) {
                 $directory->is_disabled = Carbon::now();
                 $directory->is_admin_verified = null;
             } elseif(!$directory->is_admin_verified && $directory->is_disabled) {
                 $directory->is_admin_verified = Carbon::now();
                 $directory->is_disabled = null;
                 //dd($branding->name);
                 //\Mail::to($branding->user->email)->send(new BusinessVerified($branding));
             } elseif($directory->is_admin_verified && $directory->is_disabled){
               $directory->is_disabled = null;
               //dd($branding->name);
               //\Mail::to($branding->user->email)->send(new BusinessVerified($branding));
             } elseif(!$directory->is_admin_verified && !$directory->is_disabled){
               $directory->is_admin_verified = Carbon::now();
             }

             /*
             if(!$directory->is_admin_verified) {
                 $directory->is_admin_verified = Carbon::now();
                 $directory->is_disabled = null;
             }
             */

          }
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

           if($directory->topbrand == null){
            if($directory->phone && $directory->address && $directory->city && $directory->logo && $directory->photos && $directory->description){

             $directory->is_draft = false;
             if(!$directory->is_published && !$directory->is_admin_verified) {
               //dd(config('mail.from.address'));
               //\Mail::to(config('mail.from.address'))->send(new NewBrandListing($directory));
               // updated email
               $mail_array = [];
               array_push($mail_array, config('mail.from.address'));
               if($directory->email && $directory->email != ""){
                 array_push($mail_array, $directory->email);
               }
               \Mail::to($mail_array)->send(new NewAdminBrandListing($directory));
             }
             $directory->is_published = true;
             $directory->save();

             alert()->success( __('Successfully published.') );
             return back();
            }else{
            alert()->warning( __('You cannot publish until you fill required fields. *') );
            return back();
            }
          }else{
            if($directory->phone && $directory->address && $directory->city
            && $directory->logo && $directory->description && $directory->about && $directory->services){

             $directory->is_draft = false;
             if(!$directory->is_published && !$directory->is_admin_verified) {
               //dd(config('mail.from.address'));
               //\Mail::to(config('mail.from.address'))->send(new NewBrandListing($directory));
               // Updated email
               $mail_array = [];
               array_push($mail_array, config('mail.from.address'));
               if($directory->email && $directory->email != ""){
                 array_push($mail_array, $directory->email);
               }
               \Mail::to($mail_array)->send(new NewAdminBrandListing($directory));
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
         }

         alert()->success( __('Successfully saved.') );
         return back();
     }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($directory)
    {
        $directory->delete();

        alert()->success('Successfully deleted');
        return redirect()->route('panel.directories.index');
    }

    public function ledgerindex($directory, FormBuilder $formBuilder)
    {
      //dd( $user->getRoleNames()->first() );
      $brandLedger = $directory->ledger;
      $directory = $directory;
      $form = $formBuilder->create('Modules\Panel\Forms\DirectoryLegderForm', [
          'method' => 'POST',
          'url' => route('panel.ledger.update', $directory),
          'model' => $brandLedger
      ]);
      return view('panel::directories.ledger', compact('form', 'directory'));
    }
    public function ledgerupdate($directory, Request $request)
    {
      $ledger = DirectoryLedger::where('directory_id', $directory->id)->first();
      if($request->has('owner_id')){
         $owner = User::find($request->get('owner_id'));
        if($owner && ($owner->id != $directory->user_id)){
          $directory->user_id = $owner->id;
          $directory->save();
          $this->updateUserStore($directory);
          $mail_array = [];
          array_push($mail_array, $directory->user->email);
          \Mail::to($mail_array)->send(new NewAdminBrandListing($directory));
        }
        if($owner){
          if($ledger){
            if((int)$ledger->owner_id != (int)$request->get('owner_id')){
              $ledger->owner_id = $request->get('owner_id');
              $ledger->save();
            }
          }else{
            $ledger = new DirectoryLedger();
            $ledger->directory_id = $directory->id;
            $ledger->owner_id = $request->get('owner_id');
            $ledger->save();
          }

        }
      }
      if($request->has('officer_id')){
         $officer = User::find($request->get('officer_id'));
         if($officer){
           if($ledger){
             if((int)$ledger->officer_id != (int)$request->get('officer_id')){
               $ledger->officer_id = $request->get('officer_id');
               $ledger->save();
             }
           }else{
             $ledger = new DirectoryLedger();
             $ledger->directory_id = $directory->id;
             $ledger->officer_id = $request->get('officer_id');
             $ledger->save();
           }

         }
      }
      alert()->success('Successfully deleted');
      return redirect()->route('panel.directories.index');
    }

    public function topledgerindex($directory, FormBuilder $formBuilder)
    {
      //dd( $user->getRoleNames()->first() );
      $brandLedger = $directory->ledger;
      $directory = $directory;
      $form = $formBuilder->create('Modules\Panel\Forms\DirectoryLegderForm', [
          'method' => 'POST',
          'url' => route('panel.topledger.update', $directory),
          'model' => $brandLedger
      ]);
      return view('panel::directories.ledger', compact('form', 'directory'));
    }
    public function topledgerupdate($directory, Request $request)
    {
      $ledger = DirectoryLedger::where('directory_id', $directory->id)->first();
      if($request->has('owner_id')){
         $owner = User::find($request->get('owner_id'));
        if($owner && ($owner->id != $directory->user_id)){
          $directory->user_id = $owner->id;
          $directory->save();
          $this->updateUserStore($directory);
          $mail_array = [];
          array_push($mail_array, $directory->user->email);
          \Mail::to($mail_array)->send(new NewAdminBrandListing($directory));
        }
        if($owner){
          if($ledger){
            if((int)$ledger->owner_id != (int)$request->get('owner_id')){
              $ledger->owner_id = $request->get('owner_id');
              $ledger->save();
            }
          }else{
            $ledger = new DirectoryLedger();
            $ledger->directory_id = $directory->id;
            $ledger->owner_id = $request->get('owner_id');
            $ledger->save();
          }

        }
      }
      if($request->has('officer_id')){
         $officer = User::find($request->get('officer_id'));
         if($officer){
           if($ledger){
             if((int)$ledger->officer_id != (int)$request->get('officer_id')){
               $ledger->officer_id = $request->get('officer_id');
               $ledger->save();
             }
           }else{
             $ledger = new DirectoryLedger();
             $ledger->directory_id = $directory->id;
             $ledger->officer_id = $request->get('officer_id');
             $ledger->save();
           }

         }
      }
      alert()->success('Successfully deleted');
      return redirect()->route('panel.topbrands');
    }

    private function updateUserStore(Directory $directory){
      $store_id = Store::where('directory_id', $directory->id)->pluck('id')->toArray();
      Store::where('directory_id', $directory->id)->update(['user_id' => $directory->user->id]);
      StoreLedger::whereIn('store_id', $store_id)->update(['owner_id' => $directory->user->id]);
    }
}
