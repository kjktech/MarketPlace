<?php

namespace App\Http\Controllers\Account;

use App\Models\Listing;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Store;
use App\Models\Order;
use App\Models\Bank;
use App\Models\StoreSetup;
//use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

use Storage;
use Image;

class StoresController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $stores = Store::where('user_id', auth()->user()->id)->orderBy('created_at', 'DESC')->paginate(10);
      return view('account.stores', compact('stores'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function setup($id)
    {
        $setup_obj = StoreSetup::where('store_id', $id)->first();
        $banks_arr = Bank::orderBy('name', 'ASC')->get();
        $data = [];
        $data['banks'] = $banks_arr;
        $store = Store::findOrFail($id);
        $data['store'] = $store;
        $data['store_id'] = $id;
        $data['setup'] = null;
        if($setup_obj){
          $data['setup'] = $setup_obj;
        }
        return view('account.store_get_started', $data);

    }

    public function viewstore($id){
        $stores = Store::where('user_id', auth()->user()->id)->where('store_category_id', $id)->orderBy('created_at', 'DESC')->paginate(10);

        $orders = Order::where('seller_id', $id)->get();
        $daysdiff =  Order::calculateDateOrderDiff($orders); //orders

        $declined_orders = Order::where('seller_id', $id)->where('declined_at', '!=',  NULL)->get();//decline orders
        $total_declined_orders = count($declined_orders);

        $total_orders = count($orders);

        $shipping_orders = Order::where('seller_id', $id)->where('accepted_at', '!=',  NULL)->get();
        $shipping_orders48 = Order::calculateShippingOrder48($shipping_orders);

         $bestSellingProducts=Order::select('listing_id')->selectRaw('COUNT(*) AS count')->groupBy('listing_id')
                ->orderByDesc('count')->limit(1)->get();

        $no_approve_products = Listing::where('user_id', auth()->user()->id)->where('is_admin_verified', '!=',  NULL)->where('store_id', $id)->get();//decline orders
        $no_approve_products = count($no_approve_products);

        $no_decline_products = Listing::where('user_id', auth()->user()->id)->where('is_admin_verified', '=',  NULL)->where('store_id', $id)->get();//decline orders
        $no_decline_products = count($no_decline_products);


        $rejected_products_photo = Listing::where('user_id', auth()->user()->id)->where('is_admin_verified', '=',  NULL)->where('photo', '=',  NULL)->where('store_id', $id)->get();//rejected products
        $rejected_products_photo = count($rejected_products_photo);

        $rejected_products_quality = Listing::where('user_id', auth()->user()->id)->where('is_admin_verified', '=',  NULL)->where('quantity', '<',  1)->where('store_id', $id)->get();//rejected products
        $rejected_products_quality = count($rejected_products_quality);


        $outOfStock = count(Listing::where('user_id', auth()->user()->id)->where('quantity', '=',  0)->get());

        $products =  Listing::where('user_id', auth()->user()->id)->where('store_id', $id)->get();
        $newlyCreatedProducts = Order::calculateNewProducts14($products);

        \Session::put('store_id', $id);

       return view('account.store_overview', compact('stores', 'daysdiff', 'total_declined_orders',
        'shipping_orders48', 'bestSellingProducts','outOfStock','newlyCreatedProducts', 'no_approve_products',
        'no_decline_products', 'rejected_products_photo', 'rejected_products_quality' ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function uploadFile(Request $request)
    {
       $store = Store::findOrFail($request->store_id);
       $validator = Validator::make($request->all(),
        ['file' => 'image',],
        ['file.image' => 'The file must be an image (jpeg, png, bmp, gif, or svg)']);
       if ($validator->fails())
         return response()->json([
            'status' => false,
            'errors' => $validator->errors()
          ], 200);
        $extension = $request->file('file')->getClientOriginalExtension();
        if($extension == 'jpeg'){
          $extension = 'jpg';
        }
        if(!in_array($extension, ['jpg', 'png', 'bmp', 'gif'])){
          $error = "Extension not supported ". $extension;
          return response()->json([
             'status' => false,
             'errors' => ["file" => $error]
           ], 200);
        }
        //$dir = 'uploads/';
        //$filename = uniqid() . '_' . time() . '.' . $extension;
        $img = Image::make($request->file);
        $img = (string) $img->encode($extension, 90);
        $path = 'storesetup/'.getDir($request->store_id, 4). '/' .$request->store_id.'.'.$extension;

        //$request->file('file')->move($dir, $filename);
        $thumb = Storage::cloud()->put($path, $img, 'public');
        $url = Storage::cloud()->url($path);
        $store = StoreSetup::where('store_id', $request->store_id)->first();
        if($store){
          $store->identity = $url;
          $store->save();
        }else{
          $store = new StoreSetup();
          $store->store_id = $request->store_id;
          $store->identity = $url;
          $store->save();
        }
        //$store = StoreSetup::updateOrCreate(['identity' => $filename])->where('store_id', $request->store_id)->first();
        //return $filename;
        return response()->json([
            'filename' => $url,
            'status' => true
        ], 200);
    }
    public function updateProfile(Request $request)
    {
      $store = StoreSetup::where('store_id', $request->store_id)->first();
      if($store){
        $store->bank_id = $request->bank_id;
        $store->bank_number = $request->bank_number;
        $store->bank_account_name = $request->bank_account_name;
        $store->save();
      }else{
        $store = new StoreSetup();
        $store->store_id = $request->store_id;
        $store->bank_id = $request->bank_id;
        $store->bank_number = $request->bank_number;
        $store->bank_account_name = $request->bank_account_name;
        $store->save();
      }

      return response()->json([
          'status' => true
      ], 200);
      /*
      $stores = StoreSetup::where('user_id', auth()->user()->id)->where('bank_id', '!=', '')->where('bank_account_name', '!=', '')->orderBy('created_at', 'DESC')->paginate(10);
      if (count($stores) == 0){
        $banks  = Bank::all();
        return view('account.store_get_started', compact('banks'));
      }else {
        return view('account.stores', compact('stores'));
      }
      */
    }
}
