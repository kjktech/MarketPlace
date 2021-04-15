<?php

namespace Modules\Ratings\Http\Controllers;

use App\Http\Requests\StoreComment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use App\Models\Filter;
use App\Models\Listing;
use App\Models\Category;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Location;
use Mapper;
use MetaTag;

class BrandReviewsController extends Controller
{

    protected $category_id;

    public function __construct() {
           $this->middleware('auth', ['except' => ['index']]);
     }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index($directory, $slug, Request $request)
    {
        #dd(\Config::get('view.paths'));
        //var_dump($directory);
        //die();
        $data = [];
        $data['has_map'] = false;
        $breadcrumbs = [];
        $category = $directory->directory_category;
        while($category = $category->child) {
            $breadcrumbs[] = $category;
        }
        $data['breadcrumbs'] = array_reverse($breadcrumbs);
        if($directory->location) {
            Mapper::map($directory->location->getLat(), $directory->location->getLng(), ['zoom' => 14, 'zoomControl' => false, 'streetViewControl' => false, 'mapTypeControl' => false, 'center' => true, 'marker' => true]);
            $data['has_map'] = true;
        }

        $data['directory'] = $directory;
        $data['comments'] = $directory->comments()->with('commenter')->paginate(2);

        if(view()->exists('reviews.brandindex')){
            return view('reviews.brandindex', $data);
        }
        return view('ratings::reviews.brandindex', $data);
    }


    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create($directory, $slug)
    {

        //has user ordered item?
        $user = auth()->user();

        $data = [];
        $data['has_map'] = false;
        $data['directory'] = $directory;
        if(view()->exists('reviews.brandcreate')){
            return view('reviews.brandcreate', $data);
        }
        return view('ratings::reviews.brandcreate', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(StoreComment $request, $directory, $slug)
    {
        auth()->user()->brandcomment($directory, $request->get('comment'), $request->get('score'));
		    alert()->success(__('Thanks for submitting a review!'));
        //dd(dirname(url()->current()));
        return redirect($directory->url);
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function profile($user, Request $request)
    {
        $data = [];
        $data['profile'] = $user;
        $data['comments'] = $user->comments()->with('commenter')->paginate(20);

        MetaTag::set('title', __(":name's reviews", ['name' => $user->display_name]));
        MetaTag::set('description', $user->bio);
        MetaTag::set('image', url($user->avatar));

        return view('profile.reviews', $data);
    }
}
