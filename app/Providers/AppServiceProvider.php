<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Contracts\Auth\Guard;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Guard $auth)
    {
        //
        Schema::defaultStringLength(191);
        //dd($auth->user());
        view()->composer('*', function ($view) use($auth) {
         if($auth->user()){
          \Cart::session($auth->user()->id);
          //$id = auth()->user()->id + "_" + "cart_items";
          //$cart_instance = DB::table('cart_storage')->where('id', $id)->count();

          $cart_content = \Cart::getContent();
          view()->share('cart_count', count($cart_content));
          //dd(count($cart_content));
        }else{
          $anonym_cart = app('anonymcart');
          //$id = auth()->user()->id + "_" + "cart_items";
          //$cart_instance = DB::table('cart_storage')->where('id', $id)->count();
          $cart_content = $anonym_cart->getContent();
          view()->share('cart_count', count($cart_content));
          //dd(count($cart_content));
        }
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
