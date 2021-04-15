<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use WebDevEtc\BlogEtc\Models\BlogEtcPost;

class BlogController extends controller{

  public function dashboardindex(){

    return view('panel::blogdashboard.index');
  }

  public function posts(){
    $posts = BlogEtcPost::orderBy("posted_at", "desc")
        ->paginate(20);
    $data = [];
    //dd($posts[0]->title);
    $data['posts'] = $posts;
    return view('panel::blogdashboard.posts', $data);
  }
}
