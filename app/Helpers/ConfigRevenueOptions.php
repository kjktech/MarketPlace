<?php

namespace App\Helpers;

class ConfigRevenueOptions
{

  function __construct() {
    $this->type = 1;
  }
  public function getType(){
    return $this->type;
  }
}
