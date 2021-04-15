<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\UpdateUserProfile;
use Image;
use Storage;
use App\Models\BrandComment;
use App\Models\Comment;
use App\Models\DeliveryAddress;
use App\Models\City;
use App\Models\State;
class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
		$user = auth()->user();
		return view('account.profile', compact('user'));
    }

    public function overview()
    {
        //
		$user = auth()->user();
		return view('account.overview', compact('user'));
    }

    public function store(UpdateUserProfile $request)
    {
        $user = auth()->user();
        if($request->file('image')) {
            $image = Image::make($request->file('image'))
                    ->fit(300, 300, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })
                    ->resizeCanvas(300, 300);
            Storage::cloud()->put('avatars/'.$user->path, (string) $image->encode());
            $user->avatar = Storage::cloud()->url("avatars/".$user->path);
            $user->save();
        }
        $user->fill($request->except('email', 'username'))->save();
		    alert()->success(__('Successfully saved!'));
        return redirect(route('account.edit_profile.index'));
    }

    public function review(Request $request)
    {
     $user = auth()->user();
     if($request->has('filter')){
       switch ($request->has('filter')) {
         case 'low':
           // code...
           $brand_comments = BrandComment::where('commenter_id', $user->id)->orderBy('rate', 'ASC')->paginate(4);
           break;
           case 'yr':
             // code...
             break;
          case 'month':
             // code...
            break;
          case 'high':
            // code...
            $brand_comments = BrandComment::where('commenter_id', $user->id)->orderBy('rate', 'DESC')->paginate(4);
            break;
         default:
           // code...
           break;
       }
       $brand_comments = BrandComment::where('commenter_id', $user->id)->paginate(4);
     }else{
       $brand_comments = BrandComment::where('commenter_id', $user->id)->paginate(4);
     }
		 $data['brand_comments'] = $brand_comments;
     ($brand_comments);
		 return view('account.review', $data);
    }

    public function createaddress(Request $request){
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
      $data = [];
      $state_obj = State::where('name', 'LIKE', 'Abia')->first();
      $lga_obj = City::where('state_id', $state_obj->id)->get();
      $lga_array = [];
      foreach ($lga_obj as $key => $value) {
        $lga_array[$value["id"]] = $value["name"];
      }
      $data['city_array'] = $cities_array;
      $data['lga_array'] = $lga_array;
      $address = new DeliveryAddress();
      $data['address'] = $address;
      return view('account.create_address', $data);
    }

    public function address(Request $request){

      $data = [];
      $addresses = DeliveryAddress::where('user_id', auth()->user()->id)->paginate(24);
      $data['addresses'] = $addresses;
      return view('account.address', $data);
    }

    public function editaddress(Request $request){

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
      $address = DeliveryAddress::where('id', $request->get('id'))->first();
      $data = [];
      $data['city_array'] = $cities_array;
      $state_obj = State::where('name', 'LIKE', $address->city)->first();
      $lga_obj = City::where('state_id', $state_obj->id)->get();
      $lga_array = [];
      foreach ($lga_obj as $key => $value) {
        $lga_array[$value["id"]] = $value["name"];
      }
      $data['lga_array'] = $lga_array;
      $data['address'] = $address;
      return view('account.edit_address', $data);
    }

    public function updateaddress(Request $request, $id){
       $address = DeliveryAddress::findOrFail($id);
       $address->fill($request->all());
       $address->save();
       return redirect(route('account.edit_profile.address'));
    }

    public function storeaddress(Request $request){

      $user = auth()->user();
      $params = $request->all();
      $address = DeliveryAddress::create($params);
      $address->user_id = $user->id;
      $address->save();
      return redirect(route('account.edit_profile.address'));
    }

    public function getcities(Request $request){
      $state_obj = State::where('name', 'LIKE', $request->get('state_name'))->first();
      $cities = City::where('state_id', $state_obj->id)->orderBy('name', 'ASC')->get();

      return response()->json([
          'cities' => $cities,
          'status' => true
      ], 200);
    }
    public function wishlist(Request $request){
      $user = auth()->user();
      $data = [];
      $data['wishlist'] = $user->favorite(\App\Models\Listing::class);
      return view('account.wishlist', $data);
    }
}
