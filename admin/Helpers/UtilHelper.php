<?php
namespace Modules\Panel\Helpers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use Modules\Panel\Helpers\UserValidator;
use Modules\Panel\Helpers\UserCreateUtil;
use App\Models\User;

class UtilHelper{

    public static function createUser(Request $request, UserValidator $validator, UserCreateUtil $createUtil){
      $result = [];
      $validator_result = $validator::make($request);
      if($validator_result["success"]){
        $user_result = $createUtil::create($request);
        $result = Array("success" => true, "msg" => "User created", "error" => "",
        "user" => $user_result["user"], "password" => $user_result["password"]);
      }else{
        $result = Array("success" => false, "msg" => "Failed validation", "error" => $validator_result["error"]);
      }
      return $result;
    }
}
