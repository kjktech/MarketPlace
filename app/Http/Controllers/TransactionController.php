<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    //
    public  function  transaction(Request $request, $store){
        $user = auth()->user();
        $toDate = date("Y-m-d H:m:s");
        $fromDate = date('Y-m') . "-01 00:00:00";
        $store_id = $store->id;//\Session::get('store_id');
        $orders = OrderItem::select('order_items.*')->where('order_items.store_id', $store_id)/*->whereDate('created_at', ">=", $fromDate)*/->whereDate('order_items.created_at', "<=", $toDate)
        ->join('orders as orders_one', 'orders_one.id', "order_items.order_id")
        ->where('orders_one.status', '=', 'paid')
        ->get();
        $orders_payout = OrderItem::select('order_items.*', \DB::raw('SUM(order_items.quantity * order_items.price) as total_pay'))->where('order_items.store_id', $store_id)/*->whereDate('created_at', ">=", $fromDate)*/->whereDate('order_items.created_at', "<=", $toDate)
        ->join('orders as orders_one', 'orders_one.id', "order_items.order_id")
        ->where('orders_one.status', '=', 'paid')
        ->get();
        if(count($orders_payout) > 0){
          $orders_payout = $orders_payout[0]['total_pay'];
        }else{
          $orders_payout = "0.00";
        }
        return view('account.transaction', compact('orders', 'orders_payout'));

    }
}
