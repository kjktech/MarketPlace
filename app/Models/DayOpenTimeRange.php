<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DayOpenTimeRange extends Model
{
    protected $table = 'opening_hours';
    protected $guarded = ['id'];
}
