<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\DirectoryPayment;
use App\Models\StorePayment;
use App\Models\Order;
use App\Models\Shipping;
use App\Models\User;
use Paystack;
use App\Mail\BusinessPurchase;
use App\Mail\PurchaseOrder;

use App\Jobs\ProcessShipping;

class PaymentController extends Controller
{

    /**
     * Redirect the User to Paystack Payment Page
     * @return Url
     */
    public function redirectToGateway(Request $request)
    {
        //$request->key = config('paystack.secretKey');
        $request->merge(["callback_url"=> "http://localhost/afiaanyi_backend/public/payment/callback"]);
        return Paystack::getAuthorizationUrl()->redirectNow();
    }

    /**
     * Obtain Paystack payment information
     * @return void
     */
    public function handleGatewayCallback(Request $request)
    {

      //['data']['authorization_url']
      $data = [];
      $data['check'] = true;
      $data['trxref'] = null;
      $data['payment_type'] = 1;
      $data['directory'] = null;

      if($request->get('trxref') && $request->get('trxref') != ""){
        $data['trxref'] = $request->get('trxref');
      }
      if($request->get('trxref') && $request->get('trxref') != ""){
      try {
        $paymentDetails = Paystack::getPaymentData();
        if(array_key_exists("metadata", $paymentDetails['data'])){
          if(array_key_exists('payment_type', $paymentDetails['data']['metadata'])){
            if($paymentDetails['data']['metadata']["payment_type"] == 2){
              $data['payment_type'] = 2;
              $dir_pay = StorePayment::where('paystack_reference', $paymentDetails['data']['reference'])->first();
              if($paymentDetails['data']['status'] == "success"){
                $dir_pay->verified = true;
                $dir_pay->status = "paid";
                $dir_pay->save();
                $data['status'] = "success";
                $data['directory'] = $dir_pay->directory_id;
              }else{
                $data['status'] = "fail";
              }
            } elseif ($paymentDetails['data']['metadata']["payment_type"] == 3) {
              $data['payment_type'] = 3;
              // code...
              $dir_pay = Order::where('paystack_reference', $paymentDetails['data']['reference'])->first();
              if($paymentDetails['data']['status'] == "success"){
                $dir_pay->verified = true;
                $dir_pay->status = "paid";
                $dir_pay->save();

                $data['status'] = "success";
                //ProcessShipping::dispatch($dir_pay);
                // May need to create a boolean filled to represent point given
                try{
                   $this->addLoyaltyPoints($dir_pay);
                }catch(\Exception $e){
                   // Logger
                }

                $this->sendshippment($dir_pay);
                return redirect()->route('page.orderconfirm', ['id' => $dir_pay->id, 'reference' => $dir_pay->paystack_reference]);
                // Send email
              }else{
                $data['status'] = "fail";
              }
            }else{
              $dir_pay = DirectoryPayment::where('paystack_reference', $paymentDetails['data']['reference'])->first();
              if($paymentDetails['data']['status'] == "success"){
                $send_flag = false;
                if($dir_pay->status != "paid"){
                  $send_flag = true;
                }
                $dir_pay->verified = true;
                $dir_pay->status = "paid";
                $dir_pay->save();
                $data['status'] = "success";
                // send verification
                if($send_flag && auth()->check()){
                  $send_name = auth()->user()->name;
                  $send_email = auth()->user()->email;
                  $send_type = $dir_pay->payment_type;
                  $this->sendpurchasemail($send_name, $send_email, $send_type);
                }
              }else{
                $data['status'] = "fail";
              }
            }
          }else{
            $dir_pay = DirectoryPayment::where('paystack_reference', $paymentDetails['data']['reference'])->first();
            if($paymentDetails['data']['status'] == "success"){
              $send_flag = false;
              if($dir_pay->status != "paid"){
                $send_flag = true;
              }
              $dir_pay->verified = true;
              $dir_pay->status = "paid";
              $dir_pay->save();
              $data['status'] = "success";
              // send verification
              if($send_flag && auth()->check()){
                $send_name = auth()->user()->name;
                $send_email = auth()->user()->email;
                $send_type = $dir_pay->payment_type;
                $this->sendpurchasemail($send_name, $send_email, $send_type);
              }
            }else{
              $data['status'] = "fail";
            }
          }

        }else{
          $dir_pay = DirectoryPayment::where('paystack_reference', $paymentDetails['data']['reference'])->first();
          if($paymentDetails['data']['status'] == "success"){
            $send_flag = false;
            if($dir_pay->status != "paid"){
              $send_flag = true;
            }
            $dir_pay->verified = true;
            $dir_pay->status = "paid";
            $dir_pay->save();
            $data['status'] = "success";
            // send verification
            if($send_flag && auth()->check()){
              $send_name = auth()->user()->name;
              $send_email = auth()->user()->email;
              $send_type = $dir_pay->payment_type;
              $this->sendpurchasemail($send_name, $send_email, $send_type);
            }
          }else{
            $data['status'] = "fail";
          }
        }


       }catch(\Exception $e) {
         $data['status'] = "fail";
         $data['check'] = false;
       }catch(\Throwable $e){
        $data['status'] = "fail";
        $data['check'] = false;
       }
       }else{
        $data['check'] = false;
       }

       return view('page.payment_confirmation', $data);
        // Now you have the payment details,
        // you can store the authorization_code in your db to allow for recurrent subscriptions
        // you can then redirect or do whatever you want
    }

    public function getpayid(Request $request)
    {
      //checkpayment
      $user_id = $request->get('user_id');
      $payment_type = $request->get('payment_type');
      $reference = $request->get('reference');
      $amount = $request->get('amount');
      $check = DirectoryPayment::where('user_id', $user_id)->where('payment_type', $payment_type)->whereNull('directory_id')->orderBy('created_at', 'DESC')->first();
      if($check){
        if($check->status == 'paid' && $check->verified == true){
          //need to skup
          return response()->json([
              'proceed' => 'paid',
              'reference' => $check->paystack_reference
          ], 200);
        }else if($check->status == 'paid'){
          //need to verify
          return response()->json([
              'proceed' => 'verify',
              'reference' => $check->paystack_reference
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
                        'reference' => $check->paystack_reference
                    ], 200);
                    // redirect no need to pay
                  }else{
                    //$data['status'] = "fail"; Generate another
                    $check->status = 'fail';
                    $check->save();
                    $reference = Paystack::genTranxRef();
                    $dir_pay = new DirectoryPayment();
                    $dir_pay->user_id = $user_id;
                    $dir_pay->paystack_reference = $reference;
                    $dir_pay->amount = $amount;
                    $dir_pay->payment_type = $payment_type;
                    $dir_pay->save();

                    return response()->json([
                        'proceed' => 'proceed',
                        'reference' => $reference,
                        'order_id' => $dir_pay->id
                    ], 200);

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
                       'reference' => $check->paystack_reference
                   ], 200);
                   // redirect no need to pay
                }else{
                   //$data['status'] = "fail"; Generate another retry
                   $check->status = 'fail';
                   $check->save();
                   $reference = Paystack::genTranxRef();
                   $dir_pay = new DirectoryPayment();
                   $dir_pay->user_id = $user_id;
                   $dir_pay->paystack_reference = $reference;
                   $dir_pay->amount = $amount;
                   $dir_pay->payment_type = $payment_type;
                   $dir_pay->save();

                   return response()->json([
                       'proceed' => 'proceed',
                       'reference' => $reference,
                       'order_id' => $dir_pay->id
                   ], 200);
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
                    'reference' => $check->paystack_reference
                ], 200);
              }else{
                //$data['status'] = "fail"; Generate another
                $check->status = 'fail';
                $check->save();
                $reference = Paystack::genTranxRef();
                $dir_pay = new DirectoryPayment();
                $dir_pay->user_id = $user_id;
                $dir_pay->paystack_reference = $reference;
                $dir_pay->amount = $amount;
                $dir_pay->payment_type = $payment_type;
                $dir_pay->save();
                return response()->json([
                    'proceed' => 'proceed',
                    'reference' => $reference,
                    'order_id' => $dir_pay->id
                ], 200);
              }
            }
          }catch(\GuzzleHttp\Exception\ConnectException $e) {
            //$data['status'] = "fail"; Generate another
            //$data['check'] = false;
            return response()->json([
                'proceed' => 'unresolved',
                'reference' => $reference
            ], 200);
          }catch(\GuzzleHttp\Exception\ClientException  $e){
            // Reference error
            //creates
            $check->status = 'fail';
            $check->save();
            $reference = Paystack::genTranxRef();
            $dir_pay = new DirectoryPayment();
            $dir_pay->user_id = $user_id;
            $dir_pay->paystack_reference = $reference;
            $dir_pay->amount = $amount;
            $dir_pay->payment_type = $payment_type;
            $dir_pay->save();
            return response()->json([
                'proceed' => 'proceed',
                'reference' => $reference,
                'order_id' => $dir_pay->id
            ], 200);

          }catch(\Exception $e){
           //$data['status'] = "fail";
           //$data['check'] = false; Generate another
           //creates
           $check->status = 'fail';
           $check->save();
           $reference = Paystack::genTranxRef();
           $dir_pay = new DirectoryPayment();
           $dir_pay->user_id = $user_id;
           $dir_pay->paystack_reference = $reference;
           $dir_pay->amount = $amount;
           $dir_pay->payment_type = $payment_type;
           $dir_pay->save();
           return response()->json([
               'proceed' => 'proceed',
               'reference' => $reference,
               'order_id' => $dir_pay->id
           ], 200);
          }
          //End verification
          /*
          return response()->json([
              'proceed' => 'verify',
              'reference' => $check->paystack_reference
           ], 200);
           */
         }

      }else{
        //creates
        $if_ref = DirectoryPayment::where('paystack_reference', $reference)->first();
        if($if_ref){
          $reference = Paystack::genTranxRef();
        }
        $dir_pay = new DirectoryPayment();
        $dir_pay->user_id = $user_id;
        $dir_pay->paystack_reference = $reference;
        $dir_pay->amount = $amount;
        $dir_pay->payment_type = $payment_type;
        $dir_pay->save();
        return response()->json([
            'proceed' => 'proceed',
            'reference' => $reference,
            'order_id' => $dir_pay->id
        ], 200);
      }

    }

    public function storegetpayid(Request $request)
    {
      //checkpayment
      //use session
      $user_id = $request->get('user_id');
      $directory_id = $request->get('directory_id');
      $directory_payment_type = $request->get('payment_type');
      $reference = $request->get('reference');
      $amount = $request->get('amount');
      $check = StorePayment::where('user_id', $user_id)->where('directory_id', $directory_id)->whereNull('store_id')->orderBy('created_at', 'DESC')->first();
      if($check){
        if($check->status == 'paid' && $check->verified == true){
          //need to skip
          return response()->json([
              'proceed' => 'paid',
              'reference' => $check->paystack_reference
          ], 200);
        }else if($check->status == 'paid'){
          //need to verify
          return response()->json([
              'proceed' => 'verify',
              'reference' => $check->paystack_reference
          ], 200);
        }else{
          //need to verify
          $request->merge(["trxref"=> $check->paystack_reference, "reference" => $check->paystack_reference]);
          try {
            $paymentDetails = Paystack::getPaymentData();
            if($paymentDetails['data']['status'] == "success"){
              //$data['status'] = "success";
              $check->status = 'paid';
              $check->verified = true;
              $check->save();
              return response()->json([
                  'proceed' => 'paid',
                  'reference' => $check->paystack_reference
              ], 200);
            }else{
              //$data['status'] = "fail"; Generate another
              $check->status = 'fail';
              $check->save();
              $reference = Paystack::genTranxRef();

              $dir_pay = new StorePayment();
              $dir_pay->user_id = $user_id;
              //$dir_pay->store_id = $store_id;
              $dir_pay->directory_id = $directory_id;
              $dir_pay->paystack_reference = $reference;
              $dir_pay->amount = $amount;
              $dir_pay->directory_payment_type = $directory_payment_type;
              $dir_pay->save();
              return response()->json([
                  'proceed' => 'proceed',
                  'reference' => $reference,
                  'order_id' => $dir_pay->id
              ], 200);
            }
          }catch(\GuzzleHttp\Exception\ConnectException $e) {
            //$data['status'] = "fail"; Generate another
            //$data['check'] = false;
            return response()->json([
                'proceed' => 'unresolved',
                'reference' => $reference
            ], 200);
          }catch(\GuzzleHttp\Exception\ClientException  $e){
            // Reference error
            //creates
            $check->status = 'fail';
            $check->save();
            $reference = Paystack::genTranxRef();
            $dir_pay = new StorePayment();
            $dir_pay->user_id = $user_id;
            //$dir_pay->store_id = $store_id;
            $dir_pay->directory_id = $directory_id;
            $dir_pay->paystack_reference = $reference;
            $dir_pay->amount = $amount;
            $dir_pay->directory_payment_type = $directory_payment_type;
            $dir_pay->save();
            return response()->json([
                'proceed' => 'proceed',
                'reference' => $reference,
                'order_id' => $dir_pay->id
            ], 200);

          }catch(\Exception $e){
           //$data['status'] = "fail";
           //$data['check'] = false; Generate another
           //creates
           $check->status = 'fail';
           $check->save();
           $reference = Paystack::genTranxRef();
           $dir_pay = new StorePayment();
           $dir_pay->user_id = $user_id;
           //$dir_pay->store_id = $store_id;
           $dir_pay->directory_id = $directory_id;
           $dir_pay->paystack_reference = $reference;
           $dir_pay->amount = $amount;
           $dir_pay->directory_payment_type = $directory_payment_type;
           $dir_pay->save();
           return response()->json([
               'proceed' => 'proceed',
               'reference' => $reference,
               'order_id' => $dir_pay->id
           ], 200);
        }
      }
    }else{
      //creates
      $if_ref = StorePayment::where('paystack_reference', $reference)->first();
      if($if_ref){
        $reference = Paystack::genTranxRef();
      }
      $dir_pay = new StorePayment();
      $dir_pay->user_id = $user_id;
      //$dir_pay->store_id = $store_id;
      $dir_pay->directory_id = $directory_id;
      $dir_pay->paystack_reference = $reference;
      $dir_pay->amount = $amount;
      $dir_pay->directory_payment_type = $directory_payment_type;
      $dir_pay->save();
      return response()->json([
          'proceed' => 'proceed',
          'reference' => $reference,
          'order_id' => $dir_pay->id
      ], 200);
    }

    }

    public function productgetpayid(Request $request)
    {
      //checkpayment
      //use session
      $order_id = $request->get('order_id');
      $reference = $request->get('reference');
      $check = Order::where('id', $order_id)->first();
      if($check){

        if(!$check->contact){
          return response()->json([
              'proceed' => 'address'
          ], 200);
        }

        $amount = ($check->amount + $check->shipping->cost) * 100;
        if($check->status == 'paid' && $check->verified == true){
          //need to skip
          return response()->json([
              'proceed' => 'paid',
              'reference' => $check->paystack_reference
          ], 200);
        }else if($check->status == 'paid'){
          //need to verify
          return response()->json([
              'proceed' => 'verify',
              'reference' => $check->paystack_reference
          ], 200);
        }else{
          //need to verify
          $request->merge(["trxref"=> $check->paystack_reference, "reference" => $check->paystack_reference]);
          try {
            $paymentDetails = Paystack::getPaymentData();
            if($paymentDetails['data']['status'] == "success"){
              //$data['status'] = "success";
              $check->status = 'paid';
              $check->verified = true;
              $check->save();
              return response()->json([
                  'proceed' => 'paid',
                  'reference' => $check->paystack_reference
              ], 200);
            }else{
              //$data['status'] = "fail"; Generate another
              $check->status = 'fail';
              $check->save();
              $reference = Paystack::genTranxRef();
              $check->paystack_reference = $reference;
              $check->save();
              return response()->json([
                  'proceed' => 'proceed',
                  'reference' => $reference,
                  'amount' => $amount,
                  'email' => $check->contact->email
              ], 200);
            }
          }catch(\GuzzleHttp\Exception\ConnectException $e) {
            //$data['status'] = "fail"; Generate another
            //$data['check'] = false;
            return response()->json([
                'proceed' => 'unresolved',
                'reference' => $reference
            ], 200);
          }catch(\GuzzleHttp\Exception\ClientException  $e){
            // Reference error
            //creates
            $check->status = 'fail';
            $check->save();
            $reference = Paystack::genTranxRef();
            $check->paystack_reference = $reference;
            $check->save();
            return response()->json([
                'proceed' => 'proceed',
                'reference' => $reference,
                'amount' => $amount,
                'email' => $check->contact->email
            ], 200);

          }catch(\Exception $e){
           //$data['status'] = "fail";
           //$data['check'] = false; Generate another
           //creates
           $check->status = 'fail';
           $check->save();
           $reference = Paystack::genTranxRef();
           $check->paystack_reference = $reference;
           $check->save();
           return response()->json([
               'proceed' => 'proceed',
               'reference' => $reference,
               'amount' => $amount,
               'email' => $check->contact->email
           ], 200);
        }
      }
     }

    }

    public function orderconfirmation(Request $request){
      $data = [];
      if($request->has('id') && $request->has('reference')){
        //Order->status must be paid
        $order_obj = Order::where('id', $request->get('id'))->where('paystack_reference', $request->get('reference'))->where('status', 'paid')->first();
        if($order_obj){
          $data['items'] = $order_obj;
          $this->sendpurchasorderemail($data['items']);
          return view('page.order_payment_confirmation', $data);
        }else{
          abort(404);
        }
      }else{
          abort(404);
      }
    }


    public function testoutput(Request $request){

      /*
      $send_name = auth()->user()->name;
      $send_email = auth()->user()->email;
      $send_type = 1;
      $this->sendpurchasemail($send_name, $send_email, $send_type);
      */
      $this->sendpurchasorderemail(Order::first());
      dd("sent");
      /*
      $request->merge(["trxref"=>"BLIC5scJfGTULHfLicWyd8Riv", "reference" => "BLIC5scJfGTULHfLicWyd8Riv"]);
      //$request->trxref = 'b0VvqnTzjcxS0nwM2x4zeO0wK';
      //$request->reference = 'b0VvqnTzjcxS0nwM2x4zeO0wK';
      try {
        $paymentDetails = Paystack::getPaymentData();
        dd('Success');
      }catch(\GuzzleHttp\Exception\ConnectException  $e){
        dd('Connect');
      }catch(\GuzzleHttp\Exception\ClientException $e) {
        dd($e);
      }catch(\Exception $e){
        dd("General issues");
      }
      */
    }

    private function sendpurchasemail($name, $email, $payment_type){
      $pay_type = "Individual Business";
      switch ($payment_type) {
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

      $data_mail = ['name' => $name, 'type' => $pay_type];
      \Mail::to($email)
      ->cc(['payment@afiaanyi.com'])
      ->send(new BusinessPurchase($data_mail));
    }

    private function sendpurchasorderemail($order){
      if($order->sent_mail == false){
      \Mail::to($order->contact->email)
      ->send(new PurchaseOrder($order));
      $order->sent_mail = true;
      $order->save();
     }
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
         }catch(\GuzzleHttp\Exception\ConnectException  $e){
         // Connection error

         }catch(\GuzzleHttp\Exception\ClientException  $e){
         // Bad code

         }catch(\Exception $e){
         // Likely Conection error

         }
       }
      }
    }

    // Code to add units to user points
    // Note might need to use user in db so as to factor payment on behalfs
    private function addLoyaltyPoints(Order $order){
      $points = $order->amount/500;
      $point_rounded = round(doubleval($points), 2);
      if(auth()->check()){
        $user = auth()->user();
        $user->addPoints($point_rounded, "store purchase point");
      }
    }
}
