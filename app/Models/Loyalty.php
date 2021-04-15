<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;

class Loyalty extends Model{

  use SearchableTrait;

  protected $searchable = [
      'columns' => [
          'loyalties.name' => 10,
          'loyalties.points' => 10,
          'loyalties.value' => 10,
          //'users.display_name' => 10,
      ],
  ];
  protected $searchableColumns = ['name', 'points', 'value'];

  protected $fillable = [
      "name", "value", "operator", "status", "points", "duration"
  ];

  protected $casts = [
        'operator' => 'array',
  ];
}
