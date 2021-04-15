<?php

namespace Modules\Ratings\Widgets;

use App\Models\Directory;
use Arrilot\Widgets\AbstractWidget;

class BrandingReviews extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [
        'limit' => 5,
        'branding' => 0,
    ];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        //
        $branding = Directory::find($this->config['branding']);
        $data = [];
        $data['config'] = $this->config;
        $data['profile'] = $branding->user;
        $data['branding'] = $branding;
        $data['comments'] = $branding->comments()->orderBy('created_at', 'DESC')->limit($this->config['branding'])->get();
        $data['comment_count'] = $branding->totalCommentCount();

        if(view()->exists('widgets.branding_reviews')){
            return view('widgets.branding_reviews', $data);
        }
        return view('ratings::widgets.branding_reviews_latest', $data);
    }
}
