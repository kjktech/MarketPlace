<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Store;
use App\Models\Listing;

class StoreController extends Controller
{

  /**
   * Create a new AuthController instance.
   *
   * @return void
   */
  public function __construct()
  {
      $this->middleware('auth:api', []);
  }

  function meanalytics(Request $request){
    $id = $request->route('shopping')->id;
    $user = auth('api')->user();
    if($user != null){
    $product_sold = Listing::select('listings.*',
                      \DB::raw('SUM(suburb_near_one.quantity) as quantity')
                  )
                  ->join('order_items as suburb_near_one', 'listings.id', 'suburb_near_one.listing_id')
                  ->join('orders as orders_one', 'orders_one.id', "suburb_near_one.order_id")
                  ->where('orders_one.status', '=', 'open')
                  ->where('listings.store_id', $id)
                  ->groupBy('suburb_near_one.listing_id')
                  ->orderBy('quantity', 'DESC')
                  ->get();

      $product_total =  Listing::select('listings.*',
                        //\DB::raw('SUM(suburb_near_one.quantity) as quantity')
                        \DB::raw('SUM(suburb_near_one.price * suburb_near_one.quantity) AS total')
                    )
                    ->join('order_items as suburb_near_one', 'listings.id', 'suburb_near_one.listing_id')
                    ->join('orders as orders_one', 'orders_one.id', "suburb_near_one.order_id")
                    ->where('orders_one.status', '=', 'open')
                    ->where('listings.store_id', $id)
                    ->groupBy('suburb_near_one.listing_id')
                    ->orderBy('quantity', 'DESC')
                    ->first();

      return response()->json([
          'msg' => "Store analytics",
          'products_sold' => $product_sold,
          'products_total' => $product_total
      ], 200);
    }else{
      return response()->json([
          'msg' => "You are not authenticated"
      ], 401);
    }
  }
}
