<?php

namespace App\Http\Controllers\Admin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Directory;
use App\Models\NletterSubscription;
use Illuminate\Support\Facades\Hash;
use App\DataTables\UsersDataTable;
use Kris\LaravelFormBuilder\FormBuilder;

use Modules\Panel\Helpers\UserValidator;
use Modules\Panel\Helpers\UserCreateUtil;
use Modules\Panel\Helpers\UtilHelper;

use App\Mail\AdminCreateUser;
use Spatie\Permission\Models\Role;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = new User;
        if($request->get('q')) {
            $users = User::where('name', 'like', "%{$request->get('q')}%")
                        ->orWhere('email', 'like', "%{$request->get('q')}%");
        }

        $data['users'] = $users->orderBy('created_at', 'DESC')->paginate(10);

        return view('panel::users.index', $data);
    }

    public function adminindex(Request $request)
    {
        $users = new User;
        if($request->get('q')) {
            $users = User::where('name', 'like', "%{$request->get('q')}%")
                        ->orWhere('email', 'like', "%{$request->get('q')}%");
        }

        $users_ad = $users->with('roles')->get();
        $users = $users_ad->reject(function ($user, $key) {
          return $user->hasRole('member');
        });
        $data['users'] =$users;

        //$data['users'] = $users->orderBy('created_at', 'DESC')->paginate(10);

        return view('panel::users.adminindex', $data);
    }

    public function admindirectoryindex(Request $request)
    {
      // run script to updated setup_id business
      /*
      $directories_list = Directory::all();
      foreach($directories_list as $directory_obj){
        if($directory_obj->setup_id == null){
          if($directory_obj->user->hasAnyRole(Role::all()) && !$directory_obj->user->hasRole('member')){
              $directory_obj->setup_id = $directory_obj->user->id;
              $directory_obj->save();
          }
        }
      }
      */
      $user_id = $request->get('user_id');
      $directories = Directory::where('setup_id', $user_id)->orderBy('created_at', 'desc');
      $data['directories'] = $directories->paginate(50);
      return view('panel::users.directory', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(FormBuilder $formBuilder)
    {
        //
        //dd( $user->getRoleNames()->first() );
        $form = $formBuilder->create('Modules\Panel\Forms\RegularUserForm', [
           'method' => 'POST',
           'url' => route('panel.users.store')
        ]);
        return view('panel::users.create_user', compact('form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $user_exists = User::where('email', $request->email)->first();
        if($user_exists){
          return redirect()->route('panel.users.create')
                      ->withErrors(array("email" => "The email has already been taken."))
                      ->withInput();
        }
        $user_result = UtilHelper::createUser($request, new UserValidator(), new UserCreateUtil());
        if($user_result["error"] != ""){
          return redirect()->route('panel.users.create')
                      ->withErrors($user_result["error"])
                      ->withInput();
        }else{
          if($user_result){
            $user = $user_result["user"];
            $password = $user_result["password"];
            $data = Array("user" => $user, "password" => $password);
            $mail = \Mail::to($user->email)->send(new AdminCreateUser($data));
          }
          alert()->success('Successfully saved');
          return redirect()->route('panel.users.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($user, FormBuilder $formBuilder)
    {

        //dd( $user->getRoleNames()->first() );
        $form = $formBuilder->create('Modules\Panel\Forms\UserForm', [
            'method' => 'PUT',
            'url' => route('panel.users.update', $user),
            'model' => $user
        ]);
        return view('panel::users.create', compact('form'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $user)
    {
        //
        $validatedData = $request->validate([
            'email' => 'unique:users,email,' . $user->id . ',id',
        ]);

        if($user->hasRole('admin') && $request->get('is_banned')) {
            alert()->danger('Cannot ban admin');
            return back();
        }

        if($user->hasRole('admin') && !$request->has('verified')) {
            alert()->danger('Cannot unverify admin');
            return back();
        }else if(!$user->hasRole('admin')){
          if($request->has('verified')){
            $user->verified = true;
          }else{
            if(auth()->user()->id == $user->id){
              alert()->danger('Cannot unverify yourself');
              return back();
            }else{
              $user->verified = false;
            }
          }
        }

        /*if($user->is_admin && $user->id == 1 && !$request->get('is_admin', 0)) {
            alert()->danger('Cannot remove superadmin');
            return back();
        }*/

        $user->fill($request->all());

        if($request->get('is_banned')) {
            $user->ban();
        } else {
            $user->unban();
        }

        //Check password and update if matches
        if($request->has('password_new') && $request->has('password_confirmation')){
          if($request->get('password_new') != "" && ($request->get('password_new') == $request->get('password_confirmation'))){
             //dd($request->get('password_new'));
             $user->password = Hash::make($request->get('password_new'));
          }
        }

        $user->save();
        //dd($request->get('role'));
        if($request->get('role') && !$user->hasRole($request->get('role'))) {
          //dd($request->get('role'));
            $user->syncRoles([$request->get('role')]);
        }elseif ($request->get('role') && count($user->getRoleNames()) > 1) {
          $user->syncRoles([$request->get('role')]);
        }

        alert()->success('Successfully saved');

        return redirect()->route('panel.users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function searchuser(Request $request){
      $users = new User;
      if($request->get('q')) {
        if($request->has('type')){
          $users = User::where('name', 'like', "%{$request->get('q')}%")
                      ->orWhere('email', 'like', "%{$request->get('q')}%");
            //$users = $users->role('member');
            $data['users'] =$users->orderBy('created_at', 'DESC')->take(10)->get();
            return response()->json(
                $data['users']
            );
          }else{
            //$users = $users->role('admin|super-admin');
            //$users = $users->role('admin');
            //$users = $users->whereHas("roles", function($q){ $q->where("name", "admin") });
            $users = User::where('name', 'like', "%{$request->get('q')}%")
                        ->orWhere('email', 'like', "%{$request->get('q')}%");
            $users_ad = $users->with('roles')->get();
            $users = $users_ad->reject(function ($user, $key) {
              return $user->hasRole('member');
            });
            $data['users'] =$users;
            return response()->json(
                $data['users']
            );
          }
      }

    }

    public function newslettersubscribers(Request $request){
      $users = new NletterSubscription;
      if($request->get('q')) {
          $users = NletterSubscription::where('name', 'like', "%{$request->get('q')}%")
                      ->orWhere('email', 'like', "%{$request->get('q')}%");
      }
      $subscribers = $users->orderBy('created_at', 'DESC')->paginate(20);
      $data['subscribers'] = $subscribers;
      return view('panel::users.newsletter_users', $data);
    }
}
