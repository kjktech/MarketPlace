<?php

namespace App\Http\Controllers;

//use App\Events\OrderPlaced;
use Illuminate\Http\Request;
use App\Models\Listing;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\WholeSaleOrder;
use App\Models\WholeSaleOrderItem;
use App\Models\DeliveryAddress;
use App\Models\OrderContact;
use App\Models\State;
use App\Models\City;
use Carbon\Carbon;
use App\Models\Shipping;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use \Cart as Cart;
use Paystack;

// Mock shipping
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request as Req;
use GuzzleHttp\Exception\RequestException;

// End mock

class CheckoutbController extends Controller
{

  public $client = null;

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
      // Setup mock responses
      // Create a mock and queue two responses.
   $stream = \GuzzleHttp\Psr7\stream_for(json_encode(['sessionId' => 'zVnlSRK6JCQpviQEzBED9FX5cBYTULU6ryqXO1Hqu677vHJqxO8gSLUPVD9jAvChwZcFuSLiFuOGJ9Znjrha2dMAfUPEycT44siXAzUXqUIW nLGr3hl4QnXUKt8bvgKAVQL4LhC57fGlvzthG0XNxRRmKlUzGZ2',
                      'prices' => [["shipping_partner" => "1", "cost"=> 400], ["shipping_partner" => "2", "cost"=> 700]]]));
   $mock = new MockHandler([
    new Response(200, ['Content-Type' => 'application/json'], $stream),
    new Response(202, ['Content-Length' => 0]),
    new RequestException("Error Communicating with Server", new Req('GET', 'test'))
   ]);

   $handler = HandlerStack::create($mock);
    $this->client = new Client(['handler' => $handler]);
  }

  public function index(Request $request){
    //dd(Paystack::genTranxRef());
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
    $data['city_array'] = $cities_array;
    $data['identity'] = array("payment_type" => 3);
    if(auth()->check()){
       if($request->has('id') && $request->has('reference')){
         $data['items'] = Order::where('id', $request->get('id'))->where('reference', $request->get('reference'))->first();
       }else{
         //return view('cart.checkout');
         $id = (string)auth()->user()->id."_"."cart_items";
         $cart_instance = DB::table('cart_storage')->where('id', $id)->first();
         if($cart_instance != null){

          Cart::session(auth()->user()->id);
          $amount = Cart::getTotal();
          if($amount > 0){
          DB::transaction(function() use ($amount, $cart_instance){
            $newOrder = Order::create([
              'reference' => Paystack::genTranxRef(),
              'cart_data' => Cart::getContent(),
              'amount' => $amount,
              'user_id' => auth()->user()->id,
              'last_name' => auth()->user()->last_name(),
              'email' => auth()->user()->email,
         ]);
         foreach(Cart::getContent() as $item){
            $listing = Listing::where('id', $item->attributes['listing_id'])->first();
            $seller_id = $listing->user->id;
            $newOrderItem = OrderItem::create([
              'order_id' => $newOrder->id,
              'seller_id' => $seller_id,
              'listing_id' => $item->attributes['listing_id'],
              'store_id' => $listing->store->id,
              'price' => $item->price,
              'quantity' => $item->quantity,
              'choices' => $item->attributes,
            ]);
          }
          Cart::clear();
         });
         }
        }
        $data['items'] = Order::where('user_id', auth()->user()->id)->orderBy('created_at', 'DESC')->first();
       }


      }else{
         if($request->has('id') && $request->has('reference')){
          $data['items'] = Order::where('id', $request->get('id'))->where('reference', $request->get('reference'))->first();
         }else{

         $anonym_cart = app('anonymcart');
          if($anonym_cart->getContent()){
          $uuid = \Uuid::generate()->string;
          if (session()->has('checkout_id')) {
          $uuid = session()->get('checkout_id');
          }else{
          session()->put('checkout_id', $uuid);
          }
          $amount = $anonym_cart->getTotal();
          if($amount > 0){
          DB::transaction(function() use ($amount, $anonym_cart, $uuid){
           $newOrder = Order::create([
             'reference' => Paystack::genTranxRef(),
             'cart_data' => $anonym_cart->getContent(),
             'amount' => $amount,
             'session_key' => $uuid,
           ]);

         foreach($anonym_cart->getContent() as $item){
           $listing = Listing::where('id', $item->attributes['listing_id'])->first();
           $seller_id = $listing->user->id;
           $newOrderItem = OrderItem::create([
             'order_id' => $newOrder->id,
             'seller_id' => $seller_id,
             'listing_id' => $item->attributes['listing_id'],
             'store_id' => $listing->store->id,
             'price' => $item->price,
             'quantity' => $item->quantity,
             'choices' => $item->attributes,
           ]);
         }
         $anonym_cart->clear();
        });
       }
       $data['items'] = Order::where('session_key', $uuid)->orderBy('created_at', 'DESC')->first();
      }else{
        var_dump(\Uuid::generate()->string);
        die();
      }
     }
    }
    $lga_array = [];
    if($data['items']->contact){
      $state_obj = State::where('name', 'LIKE', $data['items']->contact->city)->first();
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
    $order_test = Order::find(1);
    //$this->sendshippment($order_test);
    return view('cart.checkout_latest', $data);
  }

  public function wholesale(Request $request){
    //dd(Paystack::genTranxRef());

    $data = [];
    $data['identity'] = array("payment_type" => 4);
    if($request->has('id') && $request->has('reference')){
         $data['items'] = WholeSaleOrder::where('id', $request->get('id'))->where('reference', $request->get('reference'))->first();
         //dd($data['items']);
    }else{
      abort(404);
    }
    //return view('cart.checkout_latest', $data);
    return view('cart.checkout_wholesale', $data);
  }

  public function getordercontact(Request $request){
    if(auth()->check()){

    }
  }

  public function postordercontact(Request $request){
    $order_address = OrderContact::where('order_id', $request->get('order_id'))->first();
    if($order_address){
      $order_address->address = $request->get('address');
      $order_address->city = $request->get('city');
      $order_address->city_id = $request->get('city_id');
      $order_address->first_name = $request->get('first_name');
      $order_address->last_name = $request->get('last_name');
      $order_address->phone = $request->get('phone');
      $order_address->email = $request->get('email');
      $order_address->save();
      if(auth()->check()){
         $delivery_address = DeliveryAddress::where('user_id', auth()->user()->id)->first();
         if(!$delivery_address){
           $delivery_address = new DeliveryAddress();
           $delivery_address->user_id = auth()->user()->id;
           $delivery_address->address = $request->get('address');
           $delivery_address->city = $request->get('city');
           $delivery_address->city_id = $request->get('city_id');
           $delivery_address->first_name = $request->get('first_name');
           $delivery_address->last_name = $request->get('last_name');
           $delivery_address->phone = $request->get('phone');
           $delivery_address->save();
         }
      }
    }else{
      $order_address = new OrderContact();
      $order_address->order_id = $request->get('order_id');
      $order_address->address = $request->get('address');
      $order_address->city = $request->get('city');
      $order_address->city_id = $request->get('city_id');
      $order_address->first_name = $request->get('first_name');
      $order_address->last_name = $request->get('last_name');
      $order_address->phone = $request->get('phone');
      $order_address->email = $request->get('email');
      $order_address->save();
      if(auth()->check()){
         $delivery_address = DeliveryAddress::where('user_id', auth()->user()->id)->first();
         if(!$delivery_address){
           $delivery_address = new DeliveryAddress();
           $delivery_address->user_id = auth()->user()->id;
           $delivery_address->address = $request->get('address');
           $delivery_address->city = $request->get('city');
           $delivery_address->city_id = $request->get('city_id');
           $delivery_address->first_name = $request->get('first_name');
           $delivery_address->last_name = $request->get('last_name');
           $delivery_address->phone = $request->get('phone');
           $delivery_address->save();
         }
      }
    }
    $state_obj = State::where('name', 'LIKE', $request->get('city'))->first();
    $city_obj = City::where('state_id', $state_obj->id)->get();
    $city_arr = City::where('id', $request->get('city_id'))->first();

    $cost = null;
    $cost = null;
    $order_obj = Order::find($request->get('order_id'));
    $this->set_shipping($order_obj, $order_address, 1);
    $cost = Shipping::where('order_id', $order_obj->id)->sum('cost');

    return response()->json([
        'status' => true,
        'lga' => $city_obj,
        'lga_id' => $city_arr->id,
        'lga_name' => $city_arr->name,
        'cost' => $cost
    ], 200);
  }

  public function postpickordercontact(Request $request){
    $delivery_address = DeliveryAddress::where('id', $request->get('address_id'))->first();
    $order_address = OrderContact::where('order_id', $request->get('order_id'))->first();
    if($order_address){
      $order_address->address = $delivery_address->address;
      $order_address->city = $delivery_address->city;
      $order_address->city_id = $delivery_address->city_id;
      $order_address->first_name = $delivery_address->first_name;
      $order_address->last_name = $delivery_address->last_name;
      $order_address->phone = $delivery_address->phone;
      $order_address->email = $delivery_address->user->email;
      $order_address->save();

    }else{
      $order_address = new OrderContact();
      $order_address->order_id = $request->get('order_id');
      $order_address->address = $delivery_address->address;
      $order_address->city = $delivery_address->city;
      $order_address->city_id = $delivery_address->city_id;
      $order_address->first_name = $delivery_address->first_name;
      $order_address->last_name = $delivery_address->last_name;
      $order_address->phone = $delivery_address->phone;
      $order_address->email = $delivery_address->user->email;
      $order_address->save();
    }
    $state_obj = State::where('name', 'LIKE', $delivery_address->city)->first();
    $city_obj = City::where('state_id', $state_obj->id)->get();
    $city_arr = City::where('id', $delivery_address->city_id)->first();


    $cost = null;
    $order_obj = Order::find($request->get('order_id'));
    $this->set_shipping($order_obj, $order_address, 1);
    $cost = Shipping::where('order_id', $order_obj->id)->sum('cost');

    return response()->json([
        'status' => true,
        'fname' => $delivery_address->first_name,
        'lname' => $delivery_address->last_name,
        'address' => $delivery_address->address,
        'city' => $delivery_address->city,
        'lga' => $city_obj,
        'lga_id' => $city_arr->id,
        'lga_name' => $city_arr->name,
        'phone' => $delivery_address->phone,
        'email' => $delivery_address->user->email,
        'cost' => $cost
    ], 200);
  }

  private function set_shipping(Order $order, OrderContact $orderContact, $shipping_type){
     //Ship to
     $city = $orderContact->city;
     $lga = $orderContact->lga->name;
     $client = new Client();
     $headers = [
     "Auth-Token" => getenv('SHIPPING_KEY'/*'PAYSTACK_SECRET_KEY'*/),
       "Accept" => "application/json"
     ];
     $distinct_store_id = OrderItem::where('order_id', $order->id)->distinct()->get(['store_id'])->toArray();
     foreach($distinct_store_id as $store_id){
       //dd($store_id['store_id']);
       //build array
       $total_weight = 0;
       $shipp_array = [];
       $order_items = OrderItem::where('order_id', $order->id)->where('store_id', $store_id['store_id'])->get();
       foreach($order_items as $item){
         $total_weight = $total_weight + (1 * $item->quantity);
         array_push($shipp_array, array("quantity" => $item->quantity, "weight" => 1));
       }
       //dd($shipp_array);
       $items_param = json_encode($shipp_array);
       //dd($items_param);
       $query = ['weight' => $total_weight, 'shipTo' => $city, 'areaTo' => $lga, 'items' => $items_param];
       //dd($query);
       try {
       $result = $client->get('http://shipping.nipost.gov.ng/api/v1/shipping/calculate', [
         'headers' => $headers,
         'query' => $query,
         'on_stats' => function (\GuzzleHttp\TransferStats $stats) use (&$url) {
           $url = $stats->getEffectiveUri();
         }
        ]);

        $response_body =  $result->getBody();
        $body_test = json_decode($response_body, true);
        if(array_key_exists("data", $body_test)){
          $default_partner = $body_test['data'][0];
          $shipping_obj = Shipping::where('order_id', $order->id)->where('store_id', $store_id['store_id'])->first();
          $default_shipping_class = $default_partner['ShippingClasses'][$this->shipping_class($default_partner['ShippingClasses'], $shipping_type)];
          if($shipping_obj){
            $shipping_obj->session_id = $body_test['requestSessionId'];
            $shipping_obj->partner_id = $default_partner['Shipper']['id'];
            $shipping_obj->partner_class_id = $default_shipping_class['Id'];
            $shipping_obj->cost = $default_shipping_class['Price'];
            $shipping_obj->save();
          }else{
            $shipping_obj = new Shipping();
            $shipping_obj->order_id = $order->id;
            $shipping_obj->store_id = $store_id['store_id'];
            $shipping_obj->session_id = $body_test['requestSessionId'];;
            $shipping_obj->partner_id = $default_partner['Shipper']['id'];
            $shipping_obj->partner_class_id = $default_shipping_class['Id'];
            $shipping_obj->cost = $default_shipping_class['Price'];
            $shipping_obj->save();
          }
        }
        //dd($url);
      }catch(\GuzzleHttp\Exception\ConnectException  $e){
        // Connection error
        $shipping_obj = Shipping::where('order_id', $order->id)->where('store_id', $store_id['store_id'])->first();
        if(!$shipping_obj){
          $shipping_obj = new Shipping();
          $shipping_obj->order_id = $order->id;
          $shipping_obj->store_id = $store_id['store_id'];
          $shipping_obj->session_id = null;
          $shipping_obj->partner_id = null;
          $shipping_obj->partner_class_id = null;
          $shipping_obj->cost = 0;
          $shipping_obj->save();
        }
        //dd("error");

      }catch(\GuzzleHttp\Exception\ClientException  $e){
        // Bad code
        $shipping_obj = Shipping::where('order_id', $order->id)->where('store_id', $store_id['store_id'])->first();
        if($shipping_obj){
          $shipping_obj->session_id = null;
          $shipping_obj->partner_id = null;
          $shipping_obj->partner_class_id = null;
          $shipping_obj->cost = 0;
          $shipping_obj->save();
        }else{
          $shipping_obj = new Shipping();
          $shipping_obj->order_id = $order->id;
          $shipping_obj->store_id = $store_id['store_id'];
          $shipping_obj->session_id = null;
          $shipping_obj->partner_id = null;
          $shipping_obj->partner_class_id = null;
          $shipping_obj->cost = 0;
          $shipping_obj->save();
        }
        //dd("error 2");

      }catch(\Exception $e){
        // Likely Conection error
        $shipping_obj = Shipping::where('order_id', $order->id)->where('store_id', $store_id['store_id'])->first();
        if(!$shipping_obj){
          $shipping_obj = new Shipping();
          $shipping_obj->order_id = $order->id;
          $shipping_obj->store_id = $store_id['store_id'];
          $shipping_obj->session_id = null;
          $shipping_obj->partner_id = null;
          $shipping_obj->partner_class_id = null;
          $shipping_obj->cost = 0;
          $shipping_obj->save();
        }
        //dd($e);
      }
     }
  }

  private function shipping_class($ship_class_arr, $shipping_type){
    $shipping_class = null;
    if(count($ship_class_arr) == 1){
      $shipping_class = 0;
    }else if(count($ship_class_arr) > 1){
      //Cheapest shipping
      $first = intval($ship_class_arr[0]['Price']);
      $min = $first;
      $max = $first;
      $index__loop = 0;
      $min_loop = null;
      $max__loop = null;
      foreach($ship_class_arr as $data) {
       $price = intval($data['Price']);
       if($price <= $min ) {
         $min =  $price;
         $min_loop = $index__loop;
       }
       if($price > $max ) {
        $max = $price;
        $max__loop = $index__loop;
       }
       $index__loop++;
      }
      if($shipping_type == 1){
         $shipping_class = $min_loop;
      }else{
         $shipping_class = $max_loop;
      }
    }
    return $shipping_class;
  }

  private function sendshippment(Order $order){
    $client = new Client();
    $headers = [
    "Auth-Token" => getenv('SHIPPING_KEY'/*'PAYSTACK_SECRET_KEY'*/),
      "Content-Type" => "application/json",
      "Accept" => "application/json"
    ];
    $distinct_store_id = OrderItem::where('order_id', $order->id)->distinct()->get(['store_id'])->toArray();
    foreach($distinct_store_id as $store_id){
      $total_weight = 0;
      $shipp_array = [];
      $order_items = OrderItem::where('order_id', $order->id)->where('store_id', $store_id['store_id'])->get();
      foreach($order_items as $item){
        $total_weight = $total_weight + (1 * $item->quantity);
        array_push($shipp_array, array("title" => $item->listing->title, "quantity" => $item->quantity, "price" => $item->quantity * $item->price, "weight" => 1));
      }
      $query_arr = array("items" => $shipp_array, "totalWeight" => $total_weight, "count" => count($shipp_array));
      $items_param = json_encode($query_arr);
      $shipping_obj = Shipping::where('order_id', $order->id)->where('store_id', $store_id['store_id'])->first();
      /*
      $botch = ["shippingClass" => $shipping_obj->partner_class_id, "customerPhone" => $order->contact->phone, "customerAddress" => $order->contact->address . "," . $order->contact->lga->name . "," . $order->contact->city,
      "description" => "Purchase shippment from store", "customerName" => $order->contact->first_name ." ". $order->contact->last_name, "shippingPartner" => $shipping_obj->partner_id,
      "customerEmail" => $order->contact->email, "items" => $query_arr, "sessionId" => $shipping_obj->session_id];
      dd($botch);
      */
      if($shipping_obj && $shipping_obj->session_id != null){

      try {
      $result = $client->post('http://shipping.nipost.gov.ng/api/v1/shipping/submit', [
        'headers' => $headers,
        \GuzzleHttp\RequestOptions::JSON => ["shippingClass" => $shipping_obj->partner_class_id, "customerPhone" => $order->contact->phone, "customerAddress" => $order->contact->address . "," . $order->contact->lga->name . "," . $order->contact->city,
        "description" => "Purchase shippment from store", "customerName" => $order->contact->first_name ." ". $order->contact->last_name, "shippingPartner" => $shipping_obj->partner_id,
        "customerEmail" => $order->contact->email, "items" => $items_param, "sessionId" => $shipping_obj->session_id]
       ]);

       $response_body =  $result->getBody();
       $body_test = json_decode($response_body, true);
       if(array_key_exists("status", $body_test)){
         if($body_test["status"] == "success"){
           $shipping_obj->status = "success";
           $shipping_obj->save();
         }
       }
       //dd($body_test);
       }catch(\GuzzleHttp\Exception\ConnectException  $e){
       // Connection error
       //dd("error 1");

       }catch(\GuzzleHttp\Exception\ClientException  $e){
       // Bad code
       //dd("error 2");

       }catch(\Exception $e){
       // Likely Conection error
       //dd($e);

       }
     }
    }
  }
}
