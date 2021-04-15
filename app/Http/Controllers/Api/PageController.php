<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Validator;
use App\Mail\ContactMessage;

class PageController extends Controller{

  public function contactmessage(Request $request)
  {
    $validator = Validator::make($request->all(), [
        'name' => 'required|min:5|max:255',
        'email' => 'required|string|email|max:255',
        'message' => 'required|min:5',
    ]);

    if($validator->fails()){
     $error = $validator->messages();
     //$error =$error false;
     return response()->json(array("msg" =>$error, "status"=> false), 400);
    }
    $name = $request->get('name');
    $email = $request->get('email');
    $message = $request->get('message');

     $data = ['name' => $name, 'email' => $email,
              'message' => $message];
     \Mail::to('contact@afiaanyi.com')->send(new ContactMessage($data));
     return response()->json([
         'status' => true,
         'msg' => "Mail Sent"
     ], 200);
  }

}
