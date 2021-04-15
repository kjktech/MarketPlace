<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Nestable\NestableTrait;

class ApnDevice extends Model
{
  protected $fillable = [
      'device_id', 'registration_id', 'user_id'
  ];

}
