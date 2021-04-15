<?php

namespace App\Traits;

use App\Traits\Commentable;

use App\Models\BrandComment;

Trait BrandCommentable{

  use Commentable;

  /**
   * @return \Illuminate\Database\Eloquent\Relations\MorphMany
   */
  public function comments()
  {
      return $this->hasMany(BrandComment::class);
  }

  public function commentCount()
  {
    return $this->hasMany(BrandComment::class)->count();
  }
}
