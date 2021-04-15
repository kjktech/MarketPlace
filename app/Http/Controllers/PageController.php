<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Filter;
use App\Models\Listing;
use App\Models\Category;
use App\Models\DirectoryCategory;
use App\Models\PageTranslation;
use App\Models\Page;
use App\Models\Directory;
use App\Models\DirectoryPayment;
use App\Models\NletterSubscription;
use App\Models\User;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Location;
use App;
use MetaTag;

use App\Mail\ContactMessage;

class PageController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }


    public function index($slug, Request $request)
    {


        $locale = App::getLocale();
        $page = PageTranslation::where('slug', $slug)->where('locale', $locale)->first();
        if(!$page) {
            return abort(404);
        }

        $data = [];
        $data['page'] = $page;

        MetaTag::set('title', $page->title);
        MetaTag::set('description', $page->content);

        return view('page', $data);
    }

    public function notverified(Request $request){
      return view('temp.not_verified');
    }

    public function directory(Request $request)
    {
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
      $listcategory = [];
        $parent_directories = DirectoryCategory::where('parent_id', 0)->orderBy('order', 'ASC')->get();
        $categories = array();
        $nested_categories = array();
        foreach($parent_directories as $directory){
          $child_directory = DirectoryCategory::where('parent_id', $directory->id)->orderBy('order', 'ASC');
          $test_get = $child_directory->get();
          if(count($test_get) > 0){
           $categories[] = $child_directory->get()[0];
           $nested_categories[] = array("header" => $directory, "child" => $child_directory->take(4)->get());
          }
        }
        $listcategory[0] = "Category";
        foreach($parent_directories as $category) {
          $listcategory[$category['id']] = $category['name'];
        }
        $data = [];
        $data["recommended"] = $categories;
        $data["nested_categories"] = $nested_categories;
        $data['categories'] = $listcategory;
        $data['city_array'] = $cities_array;

        return view('page.directory', $data);
    }

    public function services(Request $request)
    {
       if($request->has('category')){
         $child_directory = DirectoryCategory::where('parent_id', $request->get('category'))->orderBy('name', 'ASC')->paginate(18);
         $data["categories"] = $child_directory;
       }else{
        $child_directory = DirectoryCategory::where('parent_id', '!=', 0)->orderBy('name', 'ASC')->paginate(18);
        $data["categories"] = $child_directory;
       }
        return view('page.service', $data);
    }

    public function topbrands(Request $request)
    {
      $cities_array = array("all"=>"Location","Abia"=>"Abia","Adamawa"=>"Adamawa",
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
      $listcategory = [];
      $listfiltercategory = [];
      $categories = null;
      $top_brands = Directory::whereNotNull('topbrand');
      $top_brands = $top_brands->active();

      //search by title, description, tags
      if($request->get('q') && $request->get('q') != '') {
          $top_brands = $top_brands->search($request->get('q'));
          #dd(debug_backtrace ());
      }
      $categories = DirectoryCategory::where('parent_id', 0)->orderBy('name', 'ASC')->get();
      $listcategory[0] = "All";
      foreach($categories as $category) {
        $listcategory[$category['id']] = $category['name'];
      }
      //$top_brands = Directory::whereNotNull("topbrand")->paginate(18);
      $is_filtered = false;

      //get listings with category and child categories
      $category_id = $request->get('category', 0) ? :0; //get the category
      if($category_id != "0" && $category_id != 0){
      //dd(debug_backtrace ());
      $full_categories = DirectoryCategory::all();
      $categories_full_rel = $this->getSearchableCategories($full_categories, $category_id); //get all child categories
      $top_brands = $top_brands->whereIn('directory_category_id', $categories_full_rel);
      $child_categories = DirectoryCategory::where('parent_id', $category_id)->orWhere('id', '=', $category_id)->get();
      $listfiltercategory = [];
        $listfiltercategory[0] = "Category";
        foreach($child_categories as $category) {
          $listfiltercategory[$category['id']] = $category['name'];
        }
      }else{
         $listfiltercategory = $listcategory;
      }
      if($request->has('city') && $request->get('city') != "all"){
           $top_brands = $top_brands->where('city', $request->get('city'));
      }
      if($request->has('ratefilter') && $request->get('ratefilter') != "all"){
        $is_filtered = true;
          $asc_des = 'DESC';
          if($request->get('ratefilter') == 'bottom'){
            $asc_des = 'ASC';
          }
        $top_brands = $top_brands->select('directories.*',
                          \DB::raw('AVG(suburb_near_one.rate) as average')
                      )
                      ->join('brand_comments as suburb_near_one', 'directories.id', 'suburb_near_one.directory_id')
                      ->groupBy('suburb_near_one.directory_id')
                      ->orderBy('average', $asc_des);
      }
      $cat_filter = $request->get('catfilter', 0) ? :0;
      if($cat_filter != 0){
        $is_filtered = true;

        $top_brands = $top_brands->where('directory_category_id', $cat_filter);
      }
      $alpha_filter = $request->get('alphfilter', 'all') ? :'all';
      if($alpha_filter != "all"){
        $is_filtered = true;
        $asc_des = 'ASC';
        if($alpha_filter == 'ascending'){
            $asc_des = 'DESC';
        }
        $top_brands = $top_brands->orderBy('name', $asc_des);
      }
      if(!$is_filtered){

         $top_brands = $top_brands->orderBy('name', 'ASC');
      }

      $data['categories'] = $listcategory;
      $data['filtercategories'] = $listfiltercategory;
      $data['city_array'] = $cities_array;
      $data["listings"] = $top_brands->paginate(18);
      return view('page.top_directory', $data);
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

    public function comingsoon(Request $request)
    {
        return view('page.comingsoon');
    }

    public function aboutus(Request $request)
    {
        return view('page.aboutus');
    }

    public function pricing(Request $request)
    {
        $data = [];
        $data['user_id'] = 1;
        $data['user_email'] = "olabayo96@yahoo.com";
        if(auth()->check()){
          $data['user_id'] = auth()->user()->id;
          $data['user_email'] = auth()->user()->email;
        }else{
          return redirect(route('home'));
        }

        $data['identity'] = array("payment_type" => 1);
        return view('page.payment_pricing', $data);
    }

    public function storepricing(Request $request)
    {
        //brand_id get payment_type and deduct
        if($request->has('directory')){
          $check = Directory::where('id', $request->get('directory'))->exists();
          if(!$check){
            alert()->warning( __('You cannot create a store without a valid business.') );
            return back();
          }
        }
        $directory_id = $request->get('directory');
        //store_id get payment_type and deduct
        $payment_val = DirectoryPayment::where('directory_id', $directory_id)->first();
        $payment_type = 1;
        if($payment_val){
          $payment_type = $payment_val->payment_type;
        }
        $amount = 10000.00;
        switch ($payment_type) {
          case 1:
            // code...
            $amount = $amount - 1000;
            break;
          case 2:
            // code...
            $amount = $amount - 2500;
            break;
          case 3:
            // code...
            $amount = $amount - 5000;
            break;
          default:
            // code...
            break;
        }
        $data = [];
        $data['amount'] = $amount;
        $data['kobo_amount'] = $amount * 100;
        $data['directory_id'] = $directory_id;
        $data['identity'] = array("payment_type" => 2);
        $data['payment_type'] = $payment_type;
        return view('page.store_payment_pricing', $data);
    }
    public function paymentconfirmation(Request $request)
    {
        return view('page.payment_confirmation');
    }

    public function termsuse(Request $request)
    {
        return view('page.terms_use');
    }

    public function faq(Request $request)
    {
        return view('page.faq');
    }

    public function privacypolicy(Request $request)
    {
        return view('page.privacy_policy');
    }

    public function disclaimer(Request $request)
    {
        return view('page.disclaimer');
    }

    public function billingpolicy(Request $request)
    {
        return view('page.billing_policy');
    }

    public function trustsafety(Request $request)
    {
        return view('page.trust_safety');
    }

    public function contactus(Request $request)
    {
        return view('page.contactus');
    }

    public function contactmessage(Request $request)
    {
      //$request_all = $request->all();
      //dd($request_all['brand-mail']);
      //dd($request->get('brand-mail'));
      $name = $request->get('contact-name');
      $email = $request->get('contact-email');
      $message = $request->get('contact-message');

       $data = ['name' => $name, 'email' => $email,
                'message' => $message];
       \Mail::to('contact@afiaanyi.com')->send(new ContactMessage($data));
      return ['success' => true];
    }

    public function subscribe(Request $request){
      $data = [];
      $data['subscribed'] = null;
      if($request->has('email')){
        $data['email'] = $request->get('email');
        $check = NletterSubscription::where('email', $request->get('email'))->first();
        if($check){
         $data['subscribed'] = true;
        }else{
          $check = User::where('email', $request->get('email'))->first();
          if($check){
           $subscribe = new NletterSubscription();
           $subscribe->name = $check->name;
           $subscribe->email = $check->email;
           $subscribe->subscribed = true;
           $subscribe->user_id = $check->id;
           $subscribe->save();
           $data['subscribed'] = true;
          }
        }

      }else{
        $data['email'] = null;
      }
      return view('page.subscription', $data);
    }

    public function postsubscribe(Request $request){
      $messages = [
          'indisposable' => __('Disposable email addresses are not allowed.'),
      ];
      $validator = Validator::make($request->all(), [
          'name' => 'required|string|max:255',
          'email' => 'required|string|email|max:255', #indisposable
          //'terms' => 'required',
      ], $messages);
      if($validator->fails()){
        $error = $validator->messages();
        return response()->json(array("msg" =>$error, "status"=> false), 200);
      }
      $check = NletterSubscription::where('email', $request->get('email'))->first();
      if(!$check){
        $check_user = User::where('email', $request->get('email'))->first();
        $subscribe = new NletterSubscription();
        $subscribe->name = $request->get('name');
        $subscribe->email = $request->get('email');
        if($check_user){
          $subscribe->name = $check_user->name;
          $subscribe->user_id = $check_user->id;
        }
        $subscribe->subscribed = true;
        $subscribe->save();
      }
      return response()->json(['status' => true], 200);
    }
}
