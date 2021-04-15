<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
#use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;
use App\Traits\MergedTrait;
use DB;
use ChristianKuri\LaravelFavorite\Traits\Favoriteable;
use Illuminate\Database\Eloquent\SoftDeletes;
use PhpUnitsOfMeasure\PhysicalQuantity\Length;
use App\Traits\Commentable;
use App\Traits\HashId;
use Date;
use Nicolaslopezj\Searchable\SearchableTrait;
use App\Models\Category;
#use Sofa\Eloquence\Eloquence; keep for later


class Listing extends Model
{
    #use Eloquence;
    use MergedTrait;
    use SearchableTrait;
    use Favoriteable;
    use Commentable;
    use SoftDeletes;
	  use HashId;

    protected $canBeRated = true;
    protected $mustBeApproved = false;

    protected $searchable = [
        'columns' => [
            'listings.title' => 10,
            'listings.tags' => 10,
            'listings.description' => 10,
            //'users.display_name' => 10,
        ],
        'joins' => [
            'users' => ['users.id','listings.user_id'],
        ],
    ];
    protected $searchableColumns = ['title', 'tags', 'description'];
    protected $appends = ['thumbnail', 'price_formatted', 'url', 'short_description', 'category_name', 'ratings'];

    protected $fillable = [
        'key', 'title', 'quantity', 'price', 'stock', 'unit', 'store_id', 'category_id', 'user_id', 'short_address', 'description', 'spotlight', 'staff_pick', 'is_hidden', 'location', 'lat', 'lng', 'pricing_model_id', 'photos', 'city',
        'country', 'currency', 'is_draft', 'session_duration', 'min_duration', 'max_duration', 'brand_id', 'discount', 'weight', 'length', 'width', 'height', 'is_whole_sale'
    ];
    protected $casts = [
          'photos' => 'array',
          'meta' => 'json',
          'tags' => 'array',
          'shipping_options' => 'json',
          'variant_options' => 'json',
          'timeslots' => 'json',
    ];
    protected $spatialFields = [
        'location',
    ];
    protected $dates = ['expires_at', 'spotlight', 'priority_until', 'deleted_at'];

    /*protected function newBaseQueryBuilder()
    {
        $conn = $this->getConnection();
        $grammar = $conn->getQueryGrammar();
        return new QueryBuilder(
            $conn,
            $grammar,
            $conn->getPostProcessor(),
            app()->make('lada.handler')
        );
    }*/

    public function toggleSpotlight()
    {
        $this->spotlight = ($this->spotlight)?null:Carbon::now();
        $this->save();
    }

    public function getIsVerifiedAttribute()
    {
        return ($this->is_admin_verified && !$this->is_disabled);
    }

    public function getDaysAgoAttribute($value) {
		return Date::parse($this->created_at)->ago();
	  }
    public function getHumanDistanceAttribute($value) {
		$distance = new Length($this->distance, 'meters');
		return __(":value miles", ['value' => number_format($distance->toUnit('miles'), 2)]);
	  }

    public function getPhotosLimitAttribute($value) {
        if(is_null($value)) {
            return setting('photos_per_listing', 20);
        }
        return $value;
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

	public function getSlugAttribute(): string
    {
        return str_slug($this->title);
    }

    public function getShortDescriptionAttribute() {
		$truncateService = new \Urodoz\Truncate\TruncateService();
		return $truncateService->truncate($this->description, 255);
    }

    public function getCategoryNameAttribute() {

		return $this->category->name;
    }

    public function getRatingsAttribute() {
       return $this->averageRate();
    }

    public function getEditUrlAttribute() {
        return route('post.edit', [$this]);
    }

    public function getUrlAttribute() {
        return route('listing', [$this, $this->slug]);
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

    public function getPriceFormattedAttribute() {
        $price = null;
        if($this->price) {
            $price = format_money($this->price, $this->currency);
		}

		if($this->pricing_model && $this->pricing_model->widget == 'book_date') {
            $price .= " per ".$this->pricing_model->duration_name;
		}
		if($this->pricing_model && $this->pricing_model->widget == 'book_time') {
            $price .= " per ".$this->pricing_model->duration_name;
		}

		if($price) {
            return $price;
        }
        return null;
    }

    public function getCoverImagePathAttribute() {
        $hash = md5($this->id);
        return 'cover-images/'.$hash[0].'/'.$hash[1].'/'.$hash.'.png';
    }

    public function getShortAddressAttribute() {
        return $this->city . ", " . $this->country;
    }

    public function shipping_options()
    {
        return $this->hasMany('\App\Models\ListingShippingOption');
    }
    public function additional_options()
    {
        return $this->hasMany('\App\Models\ListingAdditionalOption');
    }

    public function booked_dates()
    {
        return $this->hasMany('\App\Models\ListingBookedDate');
    }

    public function booked_times()
    {
        return $this->hasMany('\App\Models\ListingBookedTime');
    }

    public function variants()
    {
        return $this->hasMany('\App\Models\ListingVariant');
    }

    public function orders()
    {
        return $this->hasMany('\App\Models\OrderItem');
    }

    public function category()
    {
        return $this->belongsTo('\App\Models\Category');
    }

    public function user()
    {
        return $this->belongsTo('\App\Models\User');
    }

    public function pricing_model() {
        return $this->belongsTo('\App\Models\PricingModel');
    }

    public function services() {
        return $this->hasMany('\App\Models\ListingService');
    }

    public function brand()
    {
        return $this->belongsTo('\App\Models\Brand');
    }

    public function store()
    {
        return $this->belongsTo('\App\Models\Store');
    }

    public function scopeActive($query)
    {
        return $query->where('is_published', 1)->where('is_draft', 0)->whereNotNull('is_admin_verified')->whereNull('is_disabled');
    }

    public function totalSales()
    {
        //return $this->orders->sum('quantity');
        return \App\Models\Listing::select('listings.*',
                        \DB::raw('SUM(suburb_near_one.quantity) as quantity')
                    )
                    ->join('order_items as suburb_near_one', 'listings.id', 'suburb_near_one.listing_id')
                    ->join('orders as orders_one', 'orders_one.id', "suburb_near_one.order_id")
                    ->where('orders_one.status', '=', 'open')
                    ->where('listings.id', $this->id)
                    ->get();
    }

    public function showWholeSale()
    {
        $show_whole_sale = false;
        //dd($this->store->directory->is_topbrand);
        /*
        if($this->store->directory->is_topbrand){
          if($this->is_whole_sale && $this->whole_sale_price && $this->whole_sale_price > 0){
            $show_whole_sale = true;
          }
        }
        */
        return $show_whole_sale;
    }

    public  static  function search($table, $column1,$column2, $searchHint){

        $results = $table::where("$column1", 'like', "$searchHint%")
            ->orWhere("$column2", 'like', "$searchHint%")->get();
        return $results;
    }
    public static function searchcategory($column1,$column2, $searchHint){

        $results = Category::where("$column1", 'like', "$searchHint%")
            ->orWhere("$column2", 'like', "$searchHint%")->get();
        return $results;
    }

}
