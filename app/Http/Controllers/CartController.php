<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Response;
//use \Cart as Cart;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
//use Gloudemans\Shoppingcart\Exceptions\InvalidRowIDException;
use Validator;

use App\Models\Order;
use App\Models\WholeSaleOrder;
use App\Models\WholeSaleOrderItem;
use App\Models\Listing;
use App\Models\Category;

use Paystack;

class CartController extends Controller{
  public function index()
  {
   return view('cart');
  }

  public function storeold(Request $request)
  {
    // First we'll add the item to the cart.
    $cart_instance = DB::table('shoppingcart')->where('identifier', 1)->count();
    foreach (Cart::content() as $item){
      if($item->model->id == (int)$request->listing_id){
         Cart::remove($item->rowId);
      }
    }
    //dd($cart_instance);
    if($cart_instance == 0){
      $options_arr = [];
      if($request->options){
      $options = explode(",", $request->options);
       foreach ($options as $key => $val) {
        $val = explode("_", $val);
        $options_arr[$val[0]] = $val[1];
       }
      }
      if(count($options_arr) > 0){
        $cartItem = Cart::add($request->listing_id, $request->listing_name, $request->quantity, $request->total, $options_arr);
      }else{
        $cartItem = Cart::add($request->listing_id, $request->listing_name, $request->quantity, $request->total);
      }

      // Or even easier, call the associate method on the CartItem!
      $cartItem->associate('Listing');
      Cart::store(1);
    }else{
      //Cart::destroy();
      Cart::restore(1);
      foreach (Cart::content() as $item){
        if($item->model->id == (int)$request->listing_id){
           Cart::remove($item->rowId);
        }
      }
      $options_arr = [];
      if($request->options){
      $options = explode(",", $request->options);
       foreach ($options as $key => $val) {
        $val = explode("_", $val);
        $options_arr[$val[0]] = $val[1];
       }
      }
      try{
        Cart::remove($request->listing_id);
      } catch (InvalidRowIDException $e) {
        $var = "";
      }
      if(count($options_arr) > 0){
        $cartItem =Cart::add($request->listing_id, $request->listing_name, $request->quantity, $request->total, $options_arr);
      }else{
        $cartItem = Cart::add($request->listing_id, $request->listing_name, $request->quantity, $request->total);
      }
      $cartItem->associate('Listing');
      Cart::store('1');
      // Or even easier, call the associate method on the CartItem!
    }

    //return redirect('cart')->withSuccessMessage('Item was added to your cart!');
    return Response::json(array('status' => 'success'));

  }

  public function store(Request $request)
  {
    //return Response::json(array('status' => 'success'));
    $listing = Listing::findOrFail($request->listing_id);
    $total_quantity = $listing->stock;
    $cart_quantity = 0;
    $is_lowered = false;
    $is_invalid = false;
    if(auth()->check()){
      $quant = 1;
      if($request->has('quantity')){
        $quant = $request->quantity;
      }
      $options_arr = [];
      if($request->options){
      $options = explode(",", $request->options);
       foreach ($options as $key => $val) {
        $val = explode("_", $val);
        $options_arr[$val[0]] = $val[1];
       }
      }
      $options_arr['listing_id'] = $request->listing_id;
      //generate_id
      $array_id = hash('md5', json_encode($options_arr));
      \Cart::session(auth()->user()->id);
      //$id = auth()->user()->id + "_" + "cart_items";
      //$cart_instance = DB::table('cart_storage')->where('id', $id)->count();

      $cart_content = \Cart::getContent();
      foreach($cart_content as $item){
        if($item->attributes->listing_id == $listing->id){
          if($item->id == $array_id){
           if($item->quantity >= $request->quantity){
             $is_lowered = true;
           }
           $cart_quantity = $cart_quantity + $request->quantity;
         }else{
           $cart_quantity = $cart_quantity + $item->quantity;
         }
        }
      }
      if($total_quantity < $cart_quantity){
         $is_invalid = true;
         if($is_lowered){
           $cart_item =\Cart::get($array_id);
           if($cart_item){
             \Cart::update($array_id, array(
              'price' => $request->total,
              'quantity' => array(
              'relative' => false,
              'value' => $quant
              ),
              'attributes' => $options_arr));
           }else{
            // First we'll add the item to the cart.
           /*$cart_instance = DB::table('shoppingcart')->where('identifier', 1)->count();
           foreach (Cart::content() as $item){
             if($item->model->id == (int)$request->listing_id){
                Cart::remove($item->rowId);
             }
           }
           */
           //dd($cart_instance);

            $cartItem =\Cart::add($array_id, $request->listing_name, $request->total, $quant, $options_arr);
           }
         }
      }else{
        $cart_item =\Cart::get($array_id);
        if($cart_item){
          \Cart::update($array_id, array(
           'price' => $request->total,
           'quantity' => array(
           'relative' => false,
           'value' => $quant
           ),
           'attributes' => $options_arr));
        }else{
         // First we'll add the item to the cart.
        /*$cart_instance = DB::table('shoppingcart')->where('identifier', 1)->count();
        foreach (Cart::content() as $item){
          if($item->model->id == (int)$request->listing_id){
             Cart::remove($item->rowId);
          }
        }
        */
        //dd($cart_instance);

         $cartItem =\Cart::add($array_id, $request->listing_name, $request->total, $quant, $options_arr);
        }
      }

    }else{
      $quant = 1;
      if($request->has('quantity')){
        $quant = $request->quantity;
      }

      $options_arr = [];
      if($request->options){
      $options = explode(",", $request->options);
       foreach ($options as $key => $val) {
        $val = explode("_", $val);
        $options_arr[$val[0]] = $val[1];
       }
      }
      $options_arr['listing_id'] = $request->listing_id;
      //generate_id
      $array_id = hash('md5', json_encode($options_arr));
      $anonym_cart = app('anonymcart');
      //$id = auth()->user()->id + "_" + "cart_items";
      //$cart_instance = DB::table('cart_storage')->where('id', $id)->count();
      $cart_content = $anonym_cart->getContent();
      foreach($cart_content as $item){
        if($item->attributes->listing_id == $listing->id){
          if($item->id == $array_id){
           if($item->quantity >= $request->quantity){
             $is_lowered = true;
           }
           $cart_quantity = $cart_quantity + $request->quantity;
         }else{
           $cart_quantity = $cart_quantity + $item->quantity;
         }
        }
      }
      if($total_quantity < $cart_quantity){
         $is_invalid = true;
         if($is_lowered){
           $cart_item =$anonym_cart->get($array_id);
           if($cart_item){
             $anonym_cart->update($array_id, array(
              'price' => $request->total,
              'quantity' => array(
              'relative' => false,
              'value' => $quant
              ),
              'attributes' => $options_arr));
           }else{
            // First we'll add the item to the cart.
           /*$cart_instance = DB::table('shoppingcart')->where('identifier', 1)->count();
           foreach (Cart::content() as $item){
             if($item->model->id == (int)$request->listing_id){
                Cart::remove($item->rowId);
             }
           }
           */
           //dd($cart_instance);

            $cartItem =$anonym_cart->add($array_id, $request->listing_name, $request->total, $quant, $options_arr);
           }
         }
      }else{
        $cart_item =$anonym_cart->get($array_id);
        if($cart_item){
          $anonym_cart->update($array_id, array(
           'price' => $request->total,
           'quantity' => array(
           'relative' => false,
           'value' => $quant
           ),
           'attributes' => $options_arr));
        }else{
         // First we'll add the item to the cart.
        /*$cart_instance = DB::table('shoppingcart')->where('identifier', 1)->count();
        foreach (Cart::content() as $item){
          if($item->model->id == (int)$request->listing_id){
             Cart::remove($item->rowId);
          }
        }
        */
        //dd($cart_instance);

         $cartItem =$anonym_cart->add($array_id, $request->listing_name, $request->total, $quant, $options_arr);
        }
      }

    }
    //return redirect('cart')->withSuccessMessage('Item was added to your cart!');
    return Response::json(array('status' => 'success'));

  }

  public function postwholesale(Request $request)
  {
    $listing = Listing::findOrFail($request->listing_id);
    if(auth()->check()){
      $quant = 1;
      if($request->has('quantity')){
        $quant = $request->quantity;
      }
      $options_arr = [];
      if($request->options){
      $options = explode(",", $request->options);
       foreach ($options as $key => $val) {
        $val = explode("_", $val);
        $options_arr[$val[0]] = $val[1];
       }
      }
      $options_arr['listing_id'] = $request->listing_id;
      //$cartItem =\Cart::add($array_id, $request->listing_name, $request->total, $quant, $options_arr);
      $amount = $listing->whole_sale_price * $quant;
      DB::transaction(function() use ($amount, $listing, $quant, $options_arr){
         $newOrder = WholeSaleOrder::create([
           'reference' => Paystack::genTranxRef(),
           'amount' => $amount,
           'user_id' => auth()->user()->id,
           'first_name' => auth()->user()->last_name(),
           'last_name' => auth()->user()->first_name(),
           'email' => auth()->user()->email,
      ]);
      $seller_id = $listing->user->id;
      $newOrderItem = WholeSaleOrderItem::create([
        'whole_sale_order_id' => $newOrder->id,
        'seller_id' => $seller_id,
        'listing_id' => $listing->id,
        'price' => $listing->whole_sale_price,
        'quantity' => $quant,
        'choices' => $options_arr,
      ]);
      });
    }
    return Response::json(array('status' => 'success'));
  }

  public function update(Request $request, $id)
    {
        $listing = Listing::findOrFail($request->listing_id);
        $total_quantity = $listing->stock;
        $cart_quantity = 0;
        $is_lowered = false;
        $is_invalid = false;
        if(auth()->check()){
           \Cart::session(auth()->user()->id);
            $validator = Validator::make($request->all(), [
               'quantity' => 'required|numeric|between:1,5'
            ]);
            if ($validator->fails()) {
               session()->flash('error_message', 'Quantity must be between 1 and 5.');
               return response()->json(['success' => false]);
            }
            $cart_content = \Cart::getContent();
            foreach($cart_content as $item){
              if($item->attributes->listing_id == $listing->id){
                if($item->id == $id){
                 if($item->quantity >= $request->quantity){
                   $is_lowered = true;
                 }
                 $cart_quantity = $cart_quantity + $request->quantity;
               }else{
                 $cart_quantity = $cart_quantity + $item->quantity;
               }
              }
            }
            if($total_quantity < $cart_quantity){
               $is_invalid = true;
               if($is_lowered){
                 \Cart::update($id, array(
                  'quantity' => array(
                   'relative' => false,
                   'value' => $request->quantity
                  ), // new item price, price can also be a string format like so: '98.67'
                 ));
                session()->flash('success_message', 'Quantity was updated successfully!');
               }
            }else{
            \Cart::update($id, array(
             'quantity' => array(
              'relative' => false,
              'value' => $request->quantity
             ), // new item price, price can also be a string format like so: '98.67'
            ));
           session()->flash('success_message', 'Quantity was updated successfully!');
          }
           return response()->json(['success' => true,
           'is_lowered' => $is_lowered, 'is_invalid'=> $is_invalid, 'total_quantity' => $total_quantity]);
        }else{
           $anonym_cart = app('anonymcart');
           $validator = Validator::make($request->all(), [
               'quantity' => 'required|numeric|between:1,5'
           ]);
            if ($validator->fails()) {
               session()->flash('error_message', 'Quantity must be between 1 and 5.');
               return response()->json(['success' => false]);
            }
            $cart_content = $anonym_cart->getContent();
            foreach($cart_content as $item){
              if($item->attributes->listing_id == $listing->id){
                if($item->id == $id){
                 if($item->quantity >= $request->quantity){
                   $is_lowered = true;
                 }
                 $cart_quantity = $cart_quantity + $request->quantity;
               }else{
                 $cart_quantity = $cart_quantity + $item->quantity;
               }
              }
            }
            if($total_quantity < $cart_quantity){
               $is_invalid = true;
               if($is_lowered){
                 $anonym_cart->update($id, array(
                  'quantity' => array(
                   'relative' => false,
                   'value' => $request->quantity
                  ), // new item price, price can also be a string format like so: '98.67'
                 ));
                 session()->flash('success_message', 'Quantity was updated successfully!');
               }
            }else{
            //$anonym_cart->update($id, $request->quantity);
            $anonym_cart->update($id, array(
             'quantity' => array(
              'relative' => false,
              'value' => $request->quantity
             ), // new item price, price can also be a string format like so: '98.67'
            ));
            session()->flash('success_message', 'Quantity was updated successfully!');
           }
            return response()->json(['success' => true,
            'is_lowered' => $is_lowered, 'is_invalid'=> $is_invalid, 'total_quantity' => $total_quantity]);
        }
        // Validation on max quantity

    }

  public function destroy($id)
  {
    if(auth()->check()){
      \Cart::session(auth()->user()->id);
      \Cart::remove($id);
      return response()->json(['success' => true]);
    }else{
      $anonym_cart = app('anonymcart');
      $anonym_cart->remove($id);
      return response()->json(['success' => true]);
    }

  }

  /**
     * Remove the resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function emptyCart()
    {
        if(auth()->check()){
          \Cart::session(auth()->user()->id);
          \Cart::clear();
          return response()->json(['success' => true]);
        }else{
          $anonym_cart = app('anonymcart');
          $anonym_cart->clear();
          return response()->json(['success' => true]);
        }
    }

  public function overview(){
    //var_dump(session()->get('checkout_id'));
    //die();
    $data = [];
    //dd($result);
    $categories_nested = Category::orderBy('order', 'ASC')->nested()->get();
    $flatten_home = flattenhome($categories_nested, 0, 4);
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
            $result_other[] = $cat;
            $result_test["other"] = $result_other;
            $result[] = $result_test;
            $result_cat_ids[] = $result_cat_ids_ind;
          }else{
            $result_other[] = $cat;
            $result_cat_ids_ind[] = $cat["id"];
          }

        }
    }
    $result = $this->genNavbar($result);
    $result_nav_res = $this->genNavbarRes($result);

    $data['categories_nested'] = $result;
    $data['categories_nested_res'] = $result_nav_res;
    if(auth()->check()){
     $userId = auth()->user()->id;
     //$this->mergeCheckout($userId);
     //$this->mergeCartFunction($userId);
     //$data['cart_test'] = \Cart::session($userId)->getContent();
     $data['cart_test'] = $this->cartValidation(\Cart::session($userId)->getContent());
     $data['cart_test_cart'] = \Cart::session($userId);
     //var_dump($data['cart_test']);
     //die();
   }else{
     $anonym_cart = app('anonymcart');
     //$data['cart_test'] = $anonym_cart->getContent();
     $data['cart_test'] = $this->cartValidation($anonym_cart->getContent());
     $data['cart_test_cart'] = $anonym_cart;
   }
   // Do Ordering validation

   return view('cart.cart_responsive', $data);
  }

  private function cartValidation($data){
    $all_data = []; //array(1=> "Me", 2=> "And", 1=> "Me");
    foreach($data as $item){
      $all_data = $all_data + array($item->attributes->listing_id => $item->name);
    }

    //$all_data_id = array_map("unserialize", array_unique(array_map("serialize", $all_data)));
    //$all_data_id = array_unique($all_data);
    asort($all_data);
    $major_arr = [];
    $valid_major_arr = [];
    foreach($all_data as $listing_id => $val){
      $quantity = 0;
      $head = true;
      $header = false;
      $arr = [];
      //$arr_test = [];
      foreach($data as $traversed_item){
        if($traversed_item->attributes->listing_id == $listing_id){
          $quantity = $quantity + $traversed_item->quantity;
          if($head == true){
            $header = true;
            $head = false;
          }
          $traversed_item['header'] = $header;
          $header = false;
          // Move below
          //$arr = $traversed_item;
          array_push($major_arr, $traversed_item);

        }
        $listing = Listing::findOrFail($listing_id);
        $total_quantity = $listing->stock;
        if($total_quantity < $quantity){
          array_push($valid_major_arr, array("id" => $listing_id, "quantity" => $quantity, "valid" => false, "total" => $total_quantity));
        }else{
          array_push($valid_major_arr, array("id" => $listing_id, "quantity" => $quantity, "valid" => true, "total" => $total_quantity));
        }

      }

    }
    $new_major = [];
    foreach($valid_major_arr as $validity){

      for ($x = 0; $x <= count($major_arr) - 1; $x++) {
        if($major_arr[$x]->attributes->listing_id == $validity['id']){
            $major_arr[$x]["quantity_orig"] = $validity['quantity'];
            $major_arr[$x]["valid"] = $validity['valid'];
            $major_arr[$x]["total"] = $validity['total'];
        }
      }
    }

    return $major_arr;
  }

  private function mergeCartFunction($userId){
   if(app('anonymcart')->getContent()->count() > 0){
     \Cart::session($userId);
     foreach (app('anonymcart')->getContent() as $item){
       $check_item = \Cart::get($item->id);
       if($check_item == null){
          \Cart::add($item->id, $item->name, $item->price, $item->quantity, $item->attributes);
       }
     }
     app('anonymcart')->clear();
   }
  }

  private function mergeCheckout($userId){
    if (session()->has('checkout_id')) {
       $uuid = session()->get('checkout_id');
       Order::where('session_key', $uuid)
          ->update(['user_id' => $userId]);
    }
  }

  private function genNavbar($result){
    $new_array =[];
    $iti = 0;
    foreach($result as $cat){
        $itiration = 0;
        $html_append = "";
        $result_array = [];
        $cat_other = $cat["other"];
        foreach($cat_other as $other){
           $itiration++;
           if( $other["depth"] == 1){
             if($html_append != ""){
               $html_append .= "</ul>";
               $result_array[] = $html_append;
             }
             $html_append = "<h4 class='drop-drop__list-title'>$other[name]</h4><ul>";
           }else{
             $html_append .= "<li><a href=''>$other[name]</a></li>";
             if($itiration == count($cat["other"])){
               $html_append .= "</ul>";
               $result_array[] = $html_append;
               $html_append= "";
             }
           }
        }
        $cat["new_array"]= $result_array;
        $new_array[] = $cat;
        //dd($result);
        /*
        $itiration_b = 0;
        $loop_b = 0;
        $wrap = false;
        $count_b = count($result_array);
        $html_new_app = "";
        for($result_array as $html){
          if(){

          }
        }
        */
    }
    return $new_array;
  }
  private function genNavbarRes($result){
    $new_array =[];
    $iti = 0;
    foreach($result as $cat){
        $itiration = 0;
        $html_append = "";
        $result_array = [];
        $cat_other = $cat["other"];
        foreach($cat_other as $other){
           $itiration++;
           if( $other["depth"] == 1){
             if($html_append != ""){
               $html_append .= "</div>";
               $result_array[] = $html_append;
             }
             $route = categoryUrl($other['id'], $other['slug']);
             $html_append = "<div class='resp_menu__mega-inner-container'><a href='$route' class='resp-menu__mega-link resp-menu__mega-link-title'>$other[name]</a>";
           }else{
             $route = categoryUrl($other['id'], $other['slug']);
             $html_append .= "<a class='resp-menu__mega-link' href='$route'>$other[name]</a>";
             if($itiration == count($cat["other"])){
               $html_append .= "</div>";
               $result_array[] = $html_append;
               $html_append= "";
             }
           }
        }
        $cat["new_array"]= $result_array;
        $new_array[] = $cat;
        //dd($result);
        /*
        $itiration_b = 0;
        $loop_b = 0;
        $wrap = false;
        $count_b = count($result_array);
        $html_new_app = "";
        for($result_array as $html){
          if(){

          }
        }
        */
    }
    return $new_array;
  }
}
