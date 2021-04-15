<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class JWTController extends Controller
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
    protected function guard()
    {
        return Auth::guard('api');
    }
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth('api')->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        //return $this->respondWithToken(auth('api')->refresh());
        try
    {
      if($token = \JWTAuth::getToken())
      {
        \JWTAuth::checkOrFail();
      }
      $user = \JWTAuth::authenticate();
    }
    catch(\Tymon\JWTAuth\Exceptions\TokenExpiredException $e)
    {
      \JWTAuth::setToken(\JWTAuth::refresh());
      $user = \JWTAuth::authenticate();
    }
    if($user /*&& check $user against parameter or not*/)
    {
       return response()->json([
          'name' => $user->first_name(),
          'access_token' => \JWTAuth::getToken()->get(),
          'token_type' => 'bearer',
          'expires_in' => auth('api')->factory()->getTTL() * 60
       ], 200);
    }
    else
    {
        return response()->json(['msg' => 'Token not valid, please login', 'status' => false], 401); //show login form
    }
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        \JWTAuth::setToken($token);
        $user = \JWTAuth::toUser();

        //get avatar
        $colors = ['E91E63', '9C27B0', '673AB7', '3F51B5', '0D47A1', '01579B', '00BCD4', '009688', '33691E', '1B5E20', '33691E', '827717', 'E65100',  'E65100', '3E2723', 'F44336', '212121'];
        $background = $colors[$user->id%count($colors)];
        $avatar =  "https://ui-avatars.com/api/?size=256&background=".$background."&color=fff&name=".urlencode($user->display_name);
        if($user->avatar){
          $avatar = $user->avatar;
        }


        return response()->json([
            'name' => $user->first_name(),
            'email' => $user->email,
            'phone' => $user->phone,
            'avatar' => $avatar,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}
