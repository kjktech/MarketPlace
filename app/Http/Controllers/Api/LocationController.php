<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\State;
use App\Models\City;

class LocationController extends Controller
{
  public function getlga(Request $request)
  {
    $state_obj = State::where('name', 'LIKE', $request->get('state'))->first();
    $cities = City::where('state_id', $state_obj->id)->get();
    return response()->json([
        'cities' => $cities,
        'status' => true
    ], 200);
  }

  public function getstate(Request $request)
  {
    $cities_array = array(["name"=>"Abia"], ["name"=>"Adamawa"],
    ["name"=>"Akwa Ibom"],["name"=>"Anambra"],["name"=>"Bauchi"],
    ["name"=>"Bayelsa"],["name"=>"Benue"],["name"=>"Borno"],["name"=>"Cross River"],
    ["name"=>"Delta"], ["name"=>"Ebonyi"],
    ["name"=>"Edo"], ["name"=>"Ekiti"], ["name"=>"Enugu"],
    ["name"=>"Gombe"],["name"=>"Imo"], ["name"=>"Jigawa"],
    ["name"=>"Kaduna"],["name"=>"Kano"],["name"=>"Katsina"],
    ["name"=>"Kebbi"],["name"=>"Kogi"],["name"=>"Kwara"],
    ["name"=>"Lagos"],["name"=>"Nasarawa"],["name"=>"Niger"],
    ["name"=>"Ogun"],["name"=>"Ondo"],["name"=>"Osun"], ["name"=>"Oyo"],
    ["name"=>"Plateau"],["name"=>"Rivers"],["name"=>"Sokoto"],["name"=>"Taraba"],
    ["name"=>"Yobe"],["name"=>"Zamfara"],["name"=>"FCT"]);

    return response()->json([
        'states' => $cities_array,
        'status' => true
    ], 200);
  }

}
