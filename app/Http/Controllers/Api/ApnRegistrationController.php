<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ApnDevice;

class ApnRegistrationController extends Controller
{

  /**
   * Create a new AuthController instance.
   *
   * @return void
   */
  public function __construct()
  {
      $this->middleware('auth:api', ['except' => ['login']]);
  }

  public function register(Register $register){

    $user = auth('api')->user();
    if(!$request->has('registration_id') || !$request->has('device_id')){
      return response()->json([
          "msg" => "Missing parameters",
          "status" => false
      ], 400);
    }
    if($user != null){
      $device = ApnDevice::where("user_id", $user->id)->where('device_id', $request->get('device_id'))->first();
      if($device){
        if($device->registration_id != $request->get('registration_id')){
          $device->registration_id = $request->get('registration_id');
          $device->save();
        }
      }else{
        $device = new ApnDevice();
        $device->user_id = $user->id;
        $device->device_id = $request->get('device_id');
        $device->registration_id = $request->get('registration_id');
        $device->save();
      }
      return response()->json([
          "msg" => "Device registered",
          "registration_id" => $request->get('registration_id'),
          "status" => true
      ], 200);
    }else{
      return response()->json([
          "msg" => "You are not authenticated",
          "status" => false
      ], 401);
    }
  }

}
