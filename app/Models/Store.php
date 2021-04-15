<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use ChristianKuri\LaravelFavorite\Traits\Favoriteable;
use App\Traits\HashId;
use Nicolaslopezj\Searchable\SearchableTrait;

class Store extends Model
{
    use HashId;
    use SearchableTrait;
    use Favoriteable;
    //
    protected $searchable = [
        'columns' => [
            'stores.name' => 10,
            'stores.tags' => 10,
            'stores.description' => 10,
            //'users.display_name' => 10,
        ],
        'joins' => [
            'users' => ['users.id','stores.user_id'],
        ],
    ];
    protected $searchableColumns = ['name', 'tags', 'description'];

    protected $appends = ['thumbnail', 'url', 'hid'];
    protected $fillable = [
        'name', 'store_category_id', 'user_id', 'directory_id','description', 'slug', 'staff_pick', 'is_hidden', 'photos', 'city', 'city_id', 'country',
        'setup_id'
    ];

    protected $casts = [
          'photos' => 'array',
          'meta' => 'json',
          'tags' => 'array',
    ];

    public function store_category()
    {
        return $this->belongsTo('\App\Models\StoreCategory');
    }

    public function user()
    {
        return $this->belongsTo('\App\Models\User');
    }

    public function directory()
    {
        return $this->belongsTo('\App\Models\Directory');
    }

    public function setup()
    {
        return $this->belongsTo('\App\Models\StoreSetup');
    }

    public function getEditUrlAttribute() {
        return route('createstore.edit', [$this]);
    }

    public function getHidAttribute() {
        return \Hashids::encode($this->getKey());
    }

    public function getUrlAttribute() {
        return route('shopping', [$this, $this->slug]);
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

    public function scopeActive($query) {
          return $query->whereNull('is_admin_verified')->whereNull('is_disabled');
    }

    public function ledger() {
      return $this->hasOne('App\Models\StoreLedger');
    }
}
