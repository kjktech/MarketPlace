<?php

namespace Modules\Panel\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserValidator{

    public static function make(Request $request){
       $result = [];
       $messages = [
           'indisposable' => __('Disposable email addresses are not allowed.'),
       ];
       $validator = Validator::make($request->all(), [
         'name' => 'required|string|max:255|min:3',
         'email' => 'required|string|email|max:255|unique:users', #indisposable
         //'terms' => 'required',
       ], $messages);

       if($validator->fails()){

        $result = Array("success" => false, "msg" => "Failed validation", "error" => $validator);
       }else{
         $result = Array("success" => true, "msg" => "Passed validation", "error" => null);
       }
       return $result;
     }
}
