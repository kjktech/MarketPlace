<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Models\Order;
use App\Models\OrderItem;

use App\Helpers\ConfigRevenueOptions;
use App\Helpers\RevenueGenerator;

class StoreDashboardController extends Controller{

   public function index(Request $request){
     $data = [];
     $recent_items = OrderItem::select('order_items.*')
       ->join('orders as suburb_near_one', 'order_items.order_id', 'suburb_near_one.id')
       ->where('suburb_near_one.status', 'paid')
       ->take(10)->get();
     //dd($recent_items);
     $data['recent_items'] = $recent_items;
     $sum = Order::where('status', 'paid')->sum('amount');
     $data['revenue'] = $sum;
     //Get revenue growth in percentages
     $config = new ConfigRevenueOptions();
     $revGenerator = new RevenueGenerator($config);
     $data['revenue_growth'] = $revGenerator->calcRevenuePercentage();
     $data['avg_user_revenue'] = $revGenerator->calcAvgUserRev();
     return view('panel::storedashboard.index', $data);
   }
}
