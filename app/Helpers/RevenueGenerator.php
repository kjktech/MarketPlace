<?php

namespace App\Helpers;

use App\Helpers\ConfigRevenueOptions;
use App\Models\Order;

class RevenueGenerator
{
   function __construct(ConfigRevenueOptions $config)
   {
      $this->type = $config->getType();
   }

   public function calcRevenuePercentage()
   {
     $type = 1;
     $revenuePass = 0;
     if($this->type){
       $type = $this->type;
     }

     switch($type){
        case 1:
          $revenuePass = $this->getRevBase();
          break;

        default:
          $revenuePass = $this->getRevBase();
     }

     return $revenuePass;
   }

   public function calcAvgUserRev()
   {
     $type = 1;
     $revenuePass = [];
     if($this->type){
       $type = $this->type;
     }

     switch($type){
        case 1:
          $revenuePass = $this->getAvUserRev();
          break;

        default:
          $revenuePass = $this->getAvUserRev();
     }

     return $revenuePass;
   }

   private function getRevBase(){
     $revenueGrowth = 0;
     $currYearPeriod = date('Y-m-d h:m:s', strtotime('today midnight'));
     $currYear = date('Y', strtotime('today midnight'));

     $yearStart = $currYear."-01-01 00:00:00";

     $lastYearStart = ((int)$currYear -1)."-01-01 00:00:00";
     $earlier = new \DateTime($yearStart);

     $later = new \DateTime($currYearPeriod);
     $diff = $later->diff($earlier)->format("%a");
     $dateLastYear = new \DateTime($lastYearStart);
     $dateLastYear->modify('+'.$diff.' day');

     $lastYearPeriod = $dateLastYear->format('Y-m-d h:m:s');

     $revenueThisCycle = Order::where('status', 'paid')->where('created_at', '<=', $currYearPeriod)
        ->where('created_at', '>=', $yearStart)->sum('amount');

     $revenueSameLastYear = Order::where('status', 'paid')->where('created_at', '<=', $dateLastYear)
        ->where('created_at', '>=', $lastYearStart)->sum('amount');

     if($revenueThisCycle > $revenueSameLastYear){
       $revenueGrowth = $revenueSameLastYear / $revenueThisCycle * 100;
     }

     return $revenueGrowth;
   }

   private function getAvUserRev(){
     $revenueAvgUserStat = [];
     $revenueGrowth = 0.0;
     $currYearPeriod = date('Y-m-d h:m:s', strtotime('today midnight'));
     $currYear = date('Y', strtotime('today midnight'));

     $yearStart = $currYear."-01-01 00:00:00";

     $lastYearStart = ((int)$currYear -1)."-01-01 00:00:00";
     $earlier = new \DateTime($yearStart);

     $later = new \DateTime($currYearPeriod);
     $diff = $later->diff($earlier)->format("%a");
     $dateLastYear = new \DateTime($lastYearStart);
     $dateLastYear->modify('+'.$diff.' day');

     $lastYearPeriod = $dateLastYear->format('Y-m-d h:m:s');

     $revenueThisCycle = Order::where('status', 'paid')->where('created_at', '<=', $currYearPeriod)
        ->where('created_at', '>=', $yearStart)->sum('amount');

     // Get unique users
     $revenueAvgUserCycle = Order::where('status', 'paid')->where('created_at', '<=', $currYearPeriod)
        ->where('created_at', '>=', $yearStart)->distinct('user_id')->count('user_id');
        if($revenueThisCycle > 0 && $revenueAvgUserCycle > 0){
          $averageUserRev =  $revenueThisCycle / $revenueAvgUserCycle;
        }else{
          $averageUserRev =  0;
        }


     $revenueSameLastYear = Order::where('status', 'paid')->where('created_at', '<=', $dateLastYear)
        ->where('created_at', '>=', $lastYearStart)->sum('amount');


     $revenueAvgUserLastYear = Order::where('status', 'paid')->where('created_at', '<=', $dateLastYear)
        ->where('created_at', '>=', $lastYearStart)->distinct('user_id')->count('user_id');
     if($revenueSameLastYear > 0 && $revenueAvgUserLastYear > 0){
       $averageUserRevPrev =  $revenueSameLastYear / $revenueAvgUserLastYear;
     }else{
       $averageUserRevPrev =  0;
     }

     if($averageUserRev > 0 &&  $averageUserRevPrev > 0){
       if($averageUserRev > $averageUserRevPrev){
         $revenueGrowth = $averageUserRev / $averageUserRevPrev * 100;
      }
    }elseif ($averageUserRev > 0) {
      $revenueGrowth = 100;
    }

     return $revenueAvgUserStat = ["revenue" => $averageUserRev, "growth" => $revenueGrowth, "user" => $revenueAvgUserCycle];
   }

   private function getCustomerAcq(){
     $today_date = date('D', strtotime('today'));
     $days_arr = [];
     switch($today_date){
       case "Mon":
          $range = Array(0);
          $days_arr = ["Mon", "Tues"];
          break;
       case "Tue":
          $range = range(0, 2);
          $days_arr = ["Mon", "Tues", "Wed"];
          break;
       case "Wed":
          $range = range(0, 3);
          $days_arr = ["Mon", "Tues", "Wed", "Thu"];
          break;

       case "Thu":
          $range = range(0, 4);
          $days_arr = ["Mon", "Tues", "Wed", "Thu", "Fri"];
          break;
       case "Fri":
          $range = range(0, 5);
          $days_arr = ["Mon", "Tues", "Wed", "Thu", "Fri", "Sat"];
          break;
       case "Sat":
          $range = range(0, 6);
          $days_arr = ["Mon", "Tues", "Wed", "Thu", "Fri", "Sat", "Sun"];
          break;
       case "Sun":
          $range = range(0, 6);
          $days_arr = ["Mon", "Tues", "Wed", "Thu", "Fri", "Sat", "Sun"];
          break;
      }
      $today = date('Y-m-d', strtotime('today'));
      $date = new DateTime($today);
      $dat_sub = count($range) - 1;
      foreach($range as $key=>$value){
        // day - $dat_sub
        $today = date('Y-m-d', strtotime('today'));
        $date = new DateTime($today);
        $date->modify('-'.$dat_sub.' day');
        $start_day = $date->format('Y-m-d')." 00:00:00";
        $end_day = $date->format('Y-m-d')." 12:00:00";
        $user_orders = Order::where('status', 'paid')->where('updated_at', '>=', $start_day)
           ->where('updated_at', '<=', $end_day)->distinct('user_id')->pluck('user_id')->toArray();
        // How to query efficiently returning customers and new customers   
        $dat_sub = $dat_sub - 1;
      }
   }

}
