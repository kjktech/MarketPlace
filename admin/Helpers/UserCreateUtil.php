<?php

namespace Modules\Panel\Helpers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;


class UserCreateUtil {

  public static function create(Request $data){
    $result = [];
    // gen random pass str
    $password = bin2hex(openssl_random_pseudo_bytes(4));
    $user = User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($password),
    ]);
    if($data->has('is_trader')){
      $user->is_trader = 1;
    }else{
      $user->is_trader = 1;
    }
    if($data->has('phone')){
      $user->phone = $data->get('phone');
    }
    $user->username = $data->get('name')."_".$user->id;
    $user->verified = 1;
    $user->save();
    $user->assignRole('member');
    return $result = Array("user" => $user, "password" => $password);
  }
}
