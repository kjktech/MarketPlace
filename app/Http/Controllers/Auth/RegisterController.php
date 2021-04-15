<?php

namespace App\Http\Controllers\Auth;

use App\Events\EmailVerified;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Jrean\UserVerification\Traits\VerifiesUsers;
use Jrean\UserVerification\Facades\UserVerification;
use Illuminate\Http\Request;
use MetaTag;
use App\Mail\TestEmail;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;
    use VerifiesUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(Request $request)
    {
      $messages = [
          'indisposable' => __('Disposable email addresses are not allowed.'),
      ];
      $validator = Validator::make($request->all(), [
          'name' => 'required|string|max:255',
          'email' => 'required|string|email|max:255|unique:users', #indisposable
          'password' => 'required|string|min:6|confirmed',
          //'terms' => 'required',
      ], $messages);
      $redirect_url = 'register';
      if($request->has('is_trader')){
        $redirect_url = $redirect_url.'?tab=tab';
      }
      if($validator->fails()){
        return redirect($redirect_url)
                    ->withErrors($validator)
                    ->withInput();
      }
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    protected function redirectTo() {
        return '/path';
    }


    public function showRegistrationForm()
    {
        MetaTag::set('title', __("Register"));
        session()->put('from', request('redirect')?:url()->previous());
        return view('auth.register_new');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {

        $messages = [
          'indisposable' => __('Disposable email addresses are not allowed.'),
        ];
        $validator = Validator::make($request->all(), [
          'name' => 'required|string|max:255',
          'email' => 'required|string|email|max:255|unique:users', #indisposable
          'password' => 'required|string|min:6|confirmed',
          //'terms' => 'required',
        ], $messages);
        $redirect_url = 'register';
        if($request->has('is_trader')){
        $redirect_url = $redirect_url.'?tab=tab';
        }
        if($validator->fails()){
         return redirect($redirect_url)
                    ->withErrors($validator)
                    ->withInput();
        }


        $user = $this->create($request->all());

        event(new Registered($user));

        $this->guard()->login($user);

        if($request->has('is_trader')){
          $user->is_trader = 1;
        }else{
          $user->is_trader = 1;
        }
        if($request->has('phone')){
          $user->phone = $request->get('phone');
        }
        $user->username = $request->get('name')."_".$user->id;
        $user->save();

        $user->assignRole('member'); //make a member

        UserVerification::generate($user);

        UserVerification::send($user, __('Welcome and Email Verification'));

        return $this->registered($request, $user)
            ?: redirect(route("email-verification.index"));
    }

    public function apiregister(Request $request)
    {

        // check if email exists
        $user_exists = User::where('email', $request->email)->first();
        if($user_exists){
          return response()->json(array("msg" => array("email" => "The email has already been taken."), "status"=> false), 200);
        }

        $messages = [
          'indisposable' => __('Disposable email addresses are not allowed.'),
        ];
        $validator = Validator::make($request->all(), [
          'name' => 'required|string|max:255',
          'email' => 'required|string|email|max:255|unique:users', #indisposable
          'password' => 'required|string|min:6|confirmed',
          //'terms' => 'required',
        ], $messages);

        if($validator->fails()){
         $error = $validator->messages();
         //$error =$error false;
         return response()->json(array("msg" =>$error, "status"=> false), 200);
        }

        $user = $this->create($request->all());

        event(new Registered($user));

        if($request->has('is_trader')){
          $user->is_trader = 1;
        }else{
          $user->is_trader = 1;
        }
        if($request->has('phone')){
          $user->phone = $request->get('phone');
        }
        $user->username = $request->get('name')."_".$user->id;
        $user->save();

        $user->assignRole('member'); //make a member

        UserVerification::generate($user);

        UserVerification::send($user, __('Welcome and Email Verification'));


        //Demo code
        //$user = User::find(15);
        //UserVerification::send($user, __('Welcome and Email Verification'));
        //$data = ['message' => 'This is a test!'];
        //\Mail::to($user->email)->send(new TestEmail($data));

        return response()->json(['status' => true], 200);
    }

    public function ajaxregister(Request $request)
    {

        $messages = [
          'indisposable' => __('Disposable email addresses are not allowed.'),
        ];

        $validator = Validator::make($request->all(), [
          'name' => 'required|string|max:255',
          'email' => 'required|string|email|max:255|unique:users,email', #indisposable
          'password' => 'required|string|min:6|confirmed',
          //'terms' => 'required',
        ], $messages);

        if($validator->fails()){
         $error = $validator->messages();
         return response()->json(array("msg" =>$error, "status"=> false), 400);
        }

        $user = $this->create($request->all());

        event(new Registered($user));

        //$this->guard()->login($user);

        if($request->has('is_trader')){
          $user->is_trader = 1;
        }else{
          $user->is_trader = 1;
        }
        if($request->has('phone')){
          $user->phone = $request->get('phone');
        }
        $user->username = $request->get('name')."_".$user->id;
        $user->save();

        $user->assignRole('member'); //make a member

        UserVerification::generate($user);

        UserVerification::send($user, __('Welcome and Email Verification'));


        //Demo code
        //$user = User::find(15);
        //UserVerification::send($user, __('Welcome and Email Verification'));
        //$data = ['message' => 'This is a test!'];
        //\Mail::to($user->email)->send(new TestEmail($data));

        return response()->json(['status' => true], 200);
    }

    public function uniqueEmail($request) {
      $email = $request->get('email');
      return User::where('email', $email)-> exists() ? '' : 'unique:users';
    }
}
