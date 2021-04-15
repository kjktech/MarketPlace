<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Traits\MergedTrait;
use ChristianKuri\LaravelFavorite\Traits\Favoriteable;
use App\Traits\BrandCommentable;
use App\Traits\HashId;
use App\Traits\HasOpeningHours;
use Nicolaslopezj\Searchable\SearchableTrait;
use Overtrue\LaravelFollow\Traits\CanBeFollowed;
use CyrildeWit\EloquentViewable\Viewable;
use CyrildeWit\EloquentViewable\Contracts\Viewable as ViewableContract;

use App\Models\DayOpenTimeRange;

class Directory extends Model implements ViewableContract
{
  use MergedTrait;
  use SearchableTrait;
  use Favoriteable;
  use HasOpeningHours;
  use BrandCommentable;
  use HashId;
  use CanBeFollowed;
  use Viewable;

  protected $canBeRated = true;
  protected $mustBeApproved = false;

  protected $searchable = [
      'columns' => [
          'directories.name' => 10,
          'directories.tags' => 10,
          'directories.description' => 10,
          //'users.display_name' => 10,
      ],
      'joins' => [
          'users' => ['users.id','directories.user_id'],
      ],
  ];
  protected $searchableColumns = ['name', 'tags', 'description'];

  protected $appends = ['thumbnail', 'category_name', 'stores', 'is_topbrand', 'ratings', 'url', 'placeholder', 'category_image', 'hours'];
  protected $fillable = [
      'name', 'directory_category_id', 'user_id', 'description', 'slug', 'staff_pick', 'is_hidden', 'location', 'lat', 'lng', 'photos', 'city',
      'country', 'phone', 'address', 'email', 'website', 'is_admin_verified', 'is_open', 'galleries', 'about', 'logo', 'is_draft', 'services',
      'setup_id'
  ];

  protected $casts = [
        'photos' => 'array',
        'coverphotos' => 'array',
        'galleries' => 'array',
        'meta' => 'json',
        'tags' => 'array',
  ];

  protected $spatialFields = [
      'location',
  ];

  public function directory_category()
  {
      return $this->belongsTo('\App\Models\DirectoryCategory');
  }

  public function user()
  {
      return $this->belongsTo('\App\Models\User');
  }
  public function teams() {
    return $this->hasMany('App\Models\Team');
  }
  public function getCategoryNameAttribute() {

     return $this->directory_category->name;
  }

  public function getStoresAttribute() {

     //return $this->hasMany('App\Models\Store');
     $stores_arry = [];
     $stores = \App\Models\Store::where('directory_id', $this->id)->get();

     if($stores) {
         $stores_arry = $stores;
     }
     return $stores_arry;
    }

    public function getHoursAttribute(){
      $openings = [];
      $dateTimeRangeMon = DayOpenTimeRange::where('day', 'monday')->where('openable_id', $this->id)->first();
      if($dateTimeRangeMon){
        if($dateTimeRangeMon->is_open == 1){
           $openings[0] = Array("day" => "Mon", "isActive" => true, "timeFrom" => substr($dateTimeRangeMon->start,0,5),"timeTill" => substr($dateTimeRangeMon->end,0,5),
                                "from" => substr($dateTimeRangeMon->start,0,5),"to" => substr($dateTimeRangeMon->end,0,5));
        }else{
          $openings[0] = Array("day" => "Mon", "isActive" => false,"timeFrom" => null,"timeTill" => null, "from" => null, "to" => null);
        }
      }else{
        $openings[0] = Array("day" => "Mon", "isActive" => false,"timeFrom" => null,"timeTill" => null, "from" => null, "to" => null);
      }
      $dateTimeRangeTue = DayOpenTimeRange::where('day', 'tuesday')->where('openable_id', $this->id)->first();
      if($dateTimeRangeTue){
        //$openings[1] = Array("isActive" => true,"timeFrom" => substr($dateTimeRangeTue->start,0,5),"timeTill" => substr($dateTimeRangeTue->end,0,5));
        if($dateTimeRangeTue->is_open == 1){
           $openings[1] = Array("day" => "Tue", "isActive" => true, "timeFrom" => substr($dateTimeRangeTue->start,0,5), "timeTill" => substr($dateTimeRangeTue->end,0,5),
                                "from" => substr($dateTimeRangeTue->start,0,5), "to" => substr($dateTimeRangeTue->end,0,5));
        }else{
          $openings[1] = Array("day" => "Tue", "isActive" => false, "timeFrom" => null, "timeTill" => null, "from" => null, "to" => null);
        }
      }else{
        $openings[1] = Array("day" => "Tue", "isActive" => false, "timeFrom" => null, "timeTill" => null, "from" => null, "to" => null);
      }
      $dateTimeRangeWed = DayOpenTimeRange::where('day', 'wednesday')->where('openable_id', $this->id)->first();
      if($dateTimeRangeWed){
        //$openings[2] = Array("isActive" => true,"timeFrom" => substr($dateTimeRangeWed->start,0,5),"timeTill" => substr($dateTimeRangeWed->end,0,5));
        if($dateTimeRangeWed->is_open == 1){
           $openings[2] = Array("day" => "Wed", "isActive" => true, "timeFrom" => substr($dateTimeRangeWed->start,0,5), "timeTill" => substr($dateTimeRangeWed->end,0,5),
                                 "from" => substr($dateTimeRangeWed->start,0,5), "to" => substr($dateTimeRangeWed->end,0,5));
        }else{
          $openings[2] = Array("day" => "Wed", "isActive" => false, "timeFrom" => null, "timeTill" => null, "from" => null, "to" => null);
        }
      }else{
        $openings[2] = Array("day" => "Wed", "isActive" => false,"timeFrom" => null,"timeTill" => null, "from" => null, "to" => null);
      }
      $dateTimeRangeThu = DayOpenTimeRange::where('day', 'thursday')->where('openable_id', $this->id)->first();
      if($dateTimeRangeThu){
        //$openings[3] = Array("isActive" => true,"timeFrom" => substr($dateTimeRangeThu->start,0,5),"timeTill" => substr($dateTimeRangeThu->end,0,5));
        if($dateTimeRangeThu->is_open == 1){
           $openings[3] = Array("day" => "Thu", "isActive" => true, "timeFrom" => substr($dateTimeRangeThu->start,0,5), "timeTill" => substr($dateTimeRangeThu->end,0,5),
                                "from" => substr($dateTimeRangeThu->start,0,5), "to" => substr($dateTimeRangeThu->end,0,5));
        }else{
          $openings[3] = Array("day" => "Thu", "isActive" => false, "timeFrom" => null, "timeTill" => null, "from" => null, "to" => null);
        }
      }else{
        $openings[3] = Array("day" => "Thu", "isActive" => false,"timeFrom" => null,"timeTill" => null, "from" => null, "to" => null);
      }
      $dateTimeRangeFri = DayOpenTimeRange::where('day', 'friday')->where('openable_id', $this->id)->first();
      if($dateTimeRangeFri){
        //$openings[4] = Array("isActive" => true,"timeFrom" => substr($dateTimeRangeFri->start,0,5),"timeTill" => substr($dateTimeRangeFri->end,0,5));
        if($dateTimeRangeFri->is_open == 1){
           $openings[4] = Array("day" => "Fri", "isActive" => true, "timeFrom" => substr($dateTimeRangeFri->start,0,5), "timeTill" => substr($dateTimeRangeFri->end,0,5),
                                 "from" => substr($dateTimeRangeFri->start,0,5), "to" => substr($dateTimeRangeFri->end,0,5));
        }else{
          $openings[4] = Array("day" => "Fri", "isActive" => false, "timeFrom" => null, "timeTill" => null, "from" => null, "to" => null);
        }
      }else{
        $openings[4] = Array("day" => "Fri", "isActive" => false, "timeFrom" => null, "timeTill" => null, "from" => null, "to" => null);
      }
      $dateTimeRangeSat = DayOpenTimeRange::where('day', 'saturday')->where('openable_id', $this->id)->first();
      if($dateTimeRangeSat){
        //$openings[5] = Array("isActive" => true,"timeFrom" => substr($dateTimeRangeSat->start,0,5),"timeTill" => substr($dateTimeRangeSat->end,0,5));
        if($dateTimeRangeSat->is_open == 1){
           $openings[5] = Array("day" => "Sat", "isActive" => true, "timeFrom" => substr($dateTimeRangeSat->start,0,5), "timeTill" => substr($dateTimeRangeSat->end,0,5),
                                 "from" => substr($dateTimeRangeSat->start,0,5), "to" => substr($dateTimeRangeSat->end,0,5));
        }else{
          $openings[5] = Array("day" => "Sat", "isActive" => false, "timeFrom" => null, "timeTill" => null, "from" => null, "to" => null);
        }
      }else{
        $openings[5] = Array("day" => "Sat", "isActive" => false,"timeFrom" => null,"timeTill" => null, "from" => null, "to" => null);
      }
      $dateTimeRangeSun = DayOpenTimeRange::where('day', 'sunday')->where('openable_id', $this->id)->first();
      if($dateTimeRangeSun){
        //$openings[6] = Array("isActive" => true,"timeFrom" => substr($dateTimeRangeSun->start,0,5),"timeTill" => substr($dateTimeRangeSun->end,0,5));
        if($dateTimeRangeSun->is_open == 1){
           $openings[6] = Array("day" => "Sun", "isActive" => true, "timeFrom" => substr($dateTimeRangeSun->start,0,5), "timeTill" => substr($dateTimeRangeSun->end,0,5),
                                 "from" => substr($dateTimeRangeSun->start,0,5), "to" => substr($dateTimeRangeSun->end,0,5));
        }else{
          $openings[6] = Array("day" => "Sun", "isActive" => false, "timeFrom" => null, "timeTill" => null, "from" => null, "to" => null);
        }
      }else{
        $openings[6] = Array("day" => "Sun", "isActive" => false,"timeFrom" => null,"timeTill" => null, "from" => null, "to" => null);
      }
      //$openings_json =  json_encode($openings);
      return $openings;
    }

    public function getRatingsAttribute() {
       return $this->averageRate();
    }

    public function getEditUrlAttribute() {
        return route('create.edit', [$this]);
    }

    public function getUrlAttribute() {
        if($this->topbrand && $this->is_admin_verified && !$this->is_disabled){
          return route('branding.showtopbrands', [$this, $this->slug]);
        }
        return route('branding', [$this, $this->slug]);
    }

    public function getImagesAttribute() {
        if(!$this->photos) {
            return ["http://via.placeholder.com/680x460?text=No%20Image"];
        }
        return $this->photos;
    }

    public function getCarouselAttribute() {
        $images = [];
        $this->photos = collect($this->photos)->slice(0, setting('photos_per_listing', 20));
        if($this->photos) {
            foreach($this->photos as $item) {
                $images[] = $item;
            }
        }
        return $images;
    }

    public function getCarouselCoverAttribute() {
        $images = [];
        $this->coverphotos = collect($this->coverphotos)->slice(0, setting('photos_per_listing', 20));
        if($this->coverphotos) {
            foreach($this->coverphotos as $item) {
                $images[] = $item;
            }
        }
        return $images;
    }

    public function getGalleryAttribute() {
        $images = [];
        $this->galleries = collect($this->galleries)->slice(0, setting('photos_per_listing', 20));
        if($this->galleries) {
            foreach($this->galleries as $item) {
                $images[] = $item;
            }
        }
        return $images;
    }
    public function getThumbnailAttribute() {
        #var_dump($this->photos);die();
        if($this->photos) {
            foreach($this->photos as $photo) {
                return $photo;
            }
        }

        return "/images/no_image.png";
    }

    public function getCoverImageAttribute() {
        #var_dump($this->photos);die();
        if($this->photos) {
            foreach($this->photos as $photo) {
                return $photo;
            }
        }

        return "/images/no_image.png";
    }

    public function getLogoAttribute() {
      if(!$this->attributes['logo']) {
          $colors = ['E91E63', '9C27B0', '673AB7', '3F51B5', '0D47A1', '01579B', '00BCD4', '009688', '33691E', '1B5E20', '33691E', '827717', 'E65100',  'E65100', '3E2723', 'F44336', '212121'];
          $background = $colors[$this->id%count($colors)];
          return "https://ui-avatars.com/api/?size=256&background=".$background."&color=fff&name=".urlencode($this->name);
          #return "https://www.gravatar.com/avatar/".md5($this->email).'?d=mm&s=300&d=mm&';
      }
      return $this->attributes['logo'] ."?stale=".strtotime($this->attributes['updated_at']);
    }

    public function getPlaceholderAttribute() {
    $colors = ['E91E63', '9C27B0', '673AB7', '3F51B5', '0D47A1', '01579B', '00BCD4', '009688', '33691E', '1B5E20', '33691E', '827717', 'E65100',  'E65100', '3E2723', 'F44336', '212121'];
        $background = $colors[$this->id%count($colors)];
        return "https://ui-avatars.com/api/?size=256&background=".$background."&color=fff&name=".urlencode($this->name);
  }

    public function toggleSpotlight()
    {
        $this->spotlight = ($this->spotlight)?null:Carbon::now();
        $this->save();
    }

    public function toggleTopbrand()
    {
        $this->topbrand = ($this->topbrand)?null:Carbon::now();
        $this->save();
    }

    public function getIsVerifiedAttribute()
    {
        return ($this->is_admin_verified && !$this->is_disabled);
    }
    public function getIsTopbrandAttribute()
    {
        return ($this->topbrand && !$this->is_disabled);
        //return ($this->topbrand);
    }
    public function scopeActive($query)
    {
        return $query->whereNotNull('is_admin_verified')->whereNull('is_disabled');
    }

    public function getPathAttribute() {
      return store(getDir($this->attributes['id'].'.jpg', 4), $this->attributes['id'].'.jpg');
    }

    /*
    public function getPathVidAttribute() {
      return store( getDir( $this->attributes['id'].'.mp4', 4), $this->attributes['id'].'.mp4');
    }
    */
    public function getPathVidAttribute() {
      return storedir( getDir( $this->attributes['id'].'.mp4', 4), $this->attributes['id'].'.mp4');
    }

    public function getCategoryImageAttribute(){
       $banner = "/images/no_image.png";
       $category_query = $this->directory_category()->first();

       if($category_query->banner == "" || $category_query->banner == null){
         if($category_query->parent_id != 0){
           if($category_query->parent()->first()->banner != ""){
           $banner = $category_query->parent()->first()->banner;
           }
         }
       }else{
         $banner = $category_query->banner;
       }

        return $banner;
    }
    public function ledger() {
      return $this->hasOne('App\Models\DirectoryLedger');
    }

    public function hasStore(){
      $storeQueryBuilder = \App\Models\Store::where('directory_id', $this->id);
      $count = $storeQueryBuilder->count();
      $url = null;
      if($count > 0){
        $storeArr =$storeQueryBuilder->get();
        $url = route("shopping.vendor", ["shopping" => $storeArr[0], "slug" => $storeArr[0]->slug]);
      }
      return $url;
    }
}
