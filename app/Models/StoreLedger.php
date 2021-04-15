<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreLedger extends Model
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

    public function store()
    {
      return $this->belongsTo('App\Models\Store');
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
