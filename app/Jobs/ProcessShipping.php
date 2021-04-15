<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Shipping;

// Mock shipping
use GuzzleHttp\Client;

class ProcessShipping implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $order;

    public $tries=3;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        //
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $this->sendshippment($this->order);
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
        // How long do session ids last Also before payments confirm shipping session id equals store number
        if($shipping_obj && $shipping_obj->session_id != null && $shipping_obj->status != "success"){

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
         // Likely Connection error

         }
       }
      }
    }
}
