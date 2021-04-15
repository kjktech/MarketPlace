<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class BrandComment extends Model
{
    use \Spiritix\LadaCache\Database\LadaCacheTrait;
	protected $fillable = [
        'comment',
        'rate',
        'approved',
        'directory_id',
        'commenter_id',
        'owner_id',
    ];

    protected $casts = [
        'approved' => 'boolean'
    ];

    public function branding()
    {
        return $this->belongsTo('App\Models\Directory', 'directory_id');
    }

    public function commenter()
    {
        return $this->belongsTo('App\Models\User', 'commenter_id');
    }

    public function owner()
    {
        return $this->belongsTo('App\Models\User', 'owner_id');
    }

    public function getRatingAttribute()
    {
        return (int) $this->rate;
    }

    /**
     * @return $this
     */
    public function approve()
    {
        $this->approved = true;
        $this->save();

        return $this;
    }
}
