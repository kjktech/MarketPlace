<?php

namespace App\Models;

use Carbon\Carbon;
use App\Traits\CanBrandComment;
use App\Traits\CanComment;
use ChristianKuri\LaravelFavorite\Traits\Favoriteability;
use App\Notifications\ResetPassword as ResetPasswordNotification;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Cog\Contracts\Ban\Bannable as BannableContract;
use Cog\Laravel\Ban\Traits\Bannable;
use Overtrue\LaravelFollow\Traits\CanFollow;
use Trexology\Pointable\Contracts\Pointable;
use Trexology\Pointable\Traits\Pointable as PointableTrait;

class User extends Authenticatable implements BannableContract, JWTSubject, Pointable
{
    use Notifiable;
    use HasRoles;
    use Favoriteability;
    use Bannable;
    use PointableTrait;
    use CanFollow;
    use CanBrandComment;
    use CanComment;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $fillable = [
         'name', 'email', 'avatar', 'password', 'display_name', 'username', 'provider', 'provider_id', 'phone'
     ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
     protected $hidden = [
         'password', 'remember_token', 'facebook_app_key', 'facebook_app_secret', 'provider', 'provider_id',
     ];
    protected $append = [
        'first_name', 'last_name'
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getDisplayNameAttribute($value) {
		if(!$value)
			$value = $this->name;
		return $value;
	}

  public function getPathAttribute() {
    return store(getDir($this->attributes['id'].'.jpg', 4), $this->attributes['id'].'.jpg');
  }
  public function getAvatarAttribute() {
    if(!$this->attributes['avatar']) {
        $colors = ['E91E63', '9C27B0', '673AB7', '3F51B5', '0D47A1', '01579B', '00BCD4', '009688', '33691E', '1B5E20', '33691E', '827717', 'E65100',  'E65100', '3E2723', 'F44336', '212121'];
        $background = $colors[$this->id%count($colors)];
        return "https://ui-avatars.com/api/?size=256&background=".$background."&color=fff&name=".urlencode($this->display_name);
        #return "https://www.gravatar.com/avatar/".md5($this->email).'?d=mm&s=300&d=mm&';
    }
    return $this->attributes['avatar'];
  }

   public function first_name() {
      try {
          $nameparser = new \HumanNameParser\Parser();
          $name = $nameparser->parse($this->attributes['name']);
          return (string) $name->getFirstName();
      } catch (\Exception $e) {
          return $this->attributes['name'];
      }
   }
   public function last_name() {
      try {
          $nameparser = new \HumanNameParser\Parser();
          $name = $nameparser->parse($this->attributes['name']);
          return (string) $name->getLastName();
      } catch (\Exception $e) {
          return $this->attributes['name'];
      }
    }
    public function is_verified() {
       $is_verified = false;
       $directory_check = \App\Models\Directory::where('user_id', $this->attributes['id'])->whereNotNull('is_admin_verified')->get();
       if(count($directory_check) > 0){
         $is_verified = true;
       }
       return $is_verified;
    }
    public function listings() {
      return $this->hasMany('App\Models\Listing');
    }

    public function directories() {
      return $this->hasMany('App\Models\Directory');
    }

    public function stores() {
      return $this->hasMany('App\Models\Store');
    }

    public function addresses() {
      return $this->hasMany('App\Models\DeliveryAddress');
    }

    public function getIsBannedAttribute()
    {
        return false;
    }
    public function canManageBlogEtcPosts(){
        if ($this->hasRole('admin') || $this->hasRole('super-admin')) {
            return true;
        }
        return false;
    }

    public function canAuthorBlogEtcPosts(){
        $users = \App\Models\User::role('admin')->get();
        return $users;
    }

    public function userProfileState(){
        if(!$this->attributes['avatar']) {
            return 80;
        }else{
          return 100;
        }
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token, $this));
    }
}
