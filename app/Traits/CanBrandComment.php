<?php

namespace App\Traits;

use App\Models\BrandComment;

trait CanBrandComment
{

    /**
     * @param $commentable
     * @param string $commentText
     * @param int $rate
     * @return $this
     */
    public function brandcomment($directory, $commentText = '', $rate = 0)
    {
        //Add code to update instead of creating new comment
        $comments = BrandComment::where("directory_id", $directory->id)->where("commenter_id", $this->id);
        $count = $comments->count();
        if($count > 0 ){
          if($count == 1){
            $comment = $comments->get()[0];
            $comment->comment = $commentText;
            $comment->rate = ($directory->getCanBeRated()) ? $rate : null;
            $comment->save();
          }else{
            $comments->delete();
            $comment = new BrandComment([
               'comment'        => $commentText,
               'rate'           => ($directory->getCanBeRated()) ? $rate : null,
               'approved'       => ($directory->mustBeApproved() && ! $this->isAdmind()) ? false : true,
               'commenter_id'   => $this->id,
               'owner_id'   	 => $directory->user_id,
             ]);

             $directory->comments()->save($comment);
          }
        }else{


         $comment = new BrandComment([
            'comment'        => $commentText,
            'rate'           => ($directory->getCanBeRated()) ? $rate : null,
            'approved'       => ($directory->mustBeApproved() && ! $this->isAdmind()) ? false : true,
            'commenter_id'   => $this->id,
            'owner_id'   	 => $directory->user_id,
          ]);

          $directory->comments()->save($comment);
        }
        return $this;
    }

    /**
     * @return bool
     */
    public function isAdmind()
    {
        return false;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
   /* public function comments()
    {
        return $this->morphMany(Comment::class, 'commented');
    }*/
}
