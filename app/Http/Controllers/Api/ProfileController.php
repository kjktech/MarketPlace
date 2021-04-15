<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Storage;
use Image;
//use PushNotification;
use App\Models\FcmDevice;

class ProfileController extends Controller{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $user = auth('api')->user();
     if($user == null){
      return response()->json([
       'msg' => "You are not authenticated",
       "status"=> false
      ], 401);
     }
    return response()->json([
      'status' => true,
      'user' => $user,
      'msg' => 'User retrieved'
    ], 200);
  }

  public function updateprofile(Request $request)
  {
      $user = auth('api')->user();
      if($user == null){
       return response()->json([
        'msg' => "You are not authenticated",
        "status"=> false
       ], 401);
      }

      /*
      if($request->file('image')) {
          $image = Image::make($request->file('image'))
                  ->fit(300, 300, function ($constraint) {
                      $constraint->aspectRatio();
                      $constraint->upsize();
                  })
                  ->resizeCanvas(300, 300);
          Storage::cloud()->put('avatars/'.$user->path, (string) $image->encode());
          $user->avatar = Storage::cloud()->url("avatars/".$user->path);
          $user->save();
      }
      */

      if($request->has('image_base64')){
        $image = Image::make($request->get('image_base64'))
                ->fit(300, 300, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->resizeCanvas(300, 300);
        Storage::cloud()->put('avatars/'.$user->path, (string) $image->encode());
        $user->avatar = Storage::cloud()->url("avatars/".$user->path);
        $user->save();
      }

      $user->fill($request->except('email', 'username'))->save();
      //Check password and update if matches
      if($request->has('password_new') && $request->has('password_confirmation')){
        if($request->get('password_new') != "" && ($request->get('password_new') == $request->get('password_confirmation'))){
           //dd($request->get('password_new'));
           $user->password = Hash::make($request->get('password_new'));
        }
      }
      $user->save();
      return response()->json([
       'status' => true,
       'user' => $user,
       'msg' => 'User retrieved'
      ], 200);
  }

  public function testpushnotif(Request $request){
    $user = auth('api')->user();
     if($user == null){
      return response()->json([
       'msg' => "You are not authenticated",
       "status"=> false
      ], 401);
     }
    if($request->has('type')){
      if($request->get('type') == "fcm"){
         $device_tokens = FcmDevice::where("user_id", $user->id)->pluck('registration_id')->toArray();
         if(count($device_tokens) > 0){

           $push = new \Edujugon\PushNotification\PushNotification('fcm');
           $feedback = $push->setMessage([
           'notification' => [
                   'title'=>'This is the title',
                   'body'=>'This is the message',
                   'sound' => 'default'
                   ],
           'data' => [
                   'data_title' => 'Test title from Afiaanyi',
                   'data_message' => 'Test message to be read when online',
                   'click_action' => 'FLUTTER_NOTIFICATION_CLICK'
                   ]
           ])->setApiKey(getenv('FCM_KEY'))
           ->setDevicesToken($device_tokens)
           ->send()
           ->getFeedback();
           return response()->json([
            "msg" => $feedback,
            "status"=> true
          ], 200);
         }else{
           return response()->json([
            "msg" => "No registered device found",
            "status"=> false
          ], 200);
         }
      }else{
        return response()->json([
         "msg" => "Not Ios yet implemented",
         "status"=> false
       ], 200);
      }
    }else{
      return response()->json([
       "msg" => "You are missing the type param: fcm or apn",
       "status"=> false
     ], 400);
    }

  }
}
