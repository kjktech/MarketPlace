<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FcmDevice;

class FcmRegistrationController extends Controller
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

  public function register(Request $request){

    $user = auth('api')->user();
    if(!$request->has('registration_id') || !$request->has('device_id')){
      return response()->json([
          "msg" => "Missing parameters",
          "status" => false
      ], 400);
    }
    if($user != null){
      $device_id = hexdec($request->get('device_id'));
      $device = FcmDevice::where("user_id", $user->id)->where('device_id', $device_id)->first();
      if($device){
        if($device->registration_id != $request->get('registration_id')){
          $device->registration_id = $request->get('registration_id');
          $device->save();
        }
      }else{
        $device = new FcmDevice();
        $device->user_id = $user->id;
        $device->device_id = $device_id;
        $device->registration_id = $request->get('registration_id');
        $device->save();
      }
      return response()->json([
          "msg" => "Device registered",
          "registration_id" => $request->get('registration_id'),
          "status" => true
      ]);
    }else{
      return response()->json([
          "msg" => "You are not authenticated",
          "status" => false
      ], 401);
    }
  }

}
