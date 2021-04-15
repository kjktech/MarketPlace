<?php

namespace App\Support;

use Illuminate\View\Factory;

class APIViewFactory extends Factory {
    public function make($view, $data = array(), $mergeData = array())
    {
        $data = array_merge($mergeData, $this->parseData($data));

        if (\Request::wantsJson() && \Request::getPathInfo() != "/api/auth/account") {
            //var_dump($data);
            //die();
            return $data;
        }

        return parent::make($view, $data, $mergeData);
    }
}
