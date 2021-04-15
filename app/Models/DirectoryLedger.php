<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DirectoryLedger extends Model
{

  protected $appends = ['owner-email', 'officer-email'];

  public function officer()
  {
    return $this->belongsTo('App\Models\User', 'officer_id');
  }

  public function owner()
  {
    return $this->belongsTo('App\Models\User', 'owner_id');
  }

  public function directory()
  {
    return $this->belongsTo('App\Models\Directory');
  }

  public function getOwnerEmailAttribute() {
     if($this->owner){
       return $this->owner->email;
     }else{
       return null;
     }

  }

  public function getOfficerEmailAttribute() {
     //return $this->officer->email;
     if($this->officer){
       return $this->officer->email;
     }else{
       return null;
     }
  }
}
