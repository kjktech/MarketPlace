<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', 'Auth\JWTController@login');
    Route::post('account', 'Auth\RegisterController@apiregister')->name('apiregister');
    Route::post('logout', 'Auth\JWTController@logout');
    Route::post('forgot/password', 'Api\ForgotPasswordController')->name('apirecoverpass');
    Route::post('refresh', 'Auth\JWTController@refresh');
    Route::get('me', 'Auth\JWTController@me');
});

Route::group([

    'middleware' => 'api'

], function ($router) {
    Route::get('me/brands', 'Api\BrandController@mebrands');
    Route::post('me/fcm/register', 'Api\FcmRegistrationController@register');
    Route::post('me/apn/register', 'Api\ApnRegistrationController@register');
    Route::get('me/storeanalytics/{shopping}', 'Api\StoreController@meanalytics');
    Route::get('directorycategory', 'Api\DirectoryCategoryController@index');
    Route::get('directoryoverview', 'Api\BrandController@homebrands');

    // Business Api Routes
    Route::post('me/business', 'Api\BrandController@createbusiness');
    Route::post('me/business/{directory_id}', 'Api\BrandController@updatebusiness');
    Route::post('me/business/banner/{directory_id}', 'Api\BrandController@upload');
    Route::delete('me/business/banner/{directory_id}/{uuid?}', 'Api\BrandController@deleteUpload');
    Route::get('me/getpayments', 'Api\BrandController@getpaymentslots');
    Route::post('me/initiatepayments', 'Api\BrandController@initiatepayments');
    Route::post('me/verifypayments', 'Api\BrandController@verifypayment');

    Route::post('business/contact/{directory_id}', 'Api\BrandController@contactmessage');

    // General api
    Route::post('business/review/{directory_id}', 'Api\BrandController@review');
    Route::get('business/reviews/{directory_id}', 'Api\BrandController@getreviews');
    Route::get('business/userreview/{directory_id}', 'Api\BrandController@userreview');

    // Pages Api route
    Route::post('contact', 'Api\PageController@contactmessage');

    // Profile
    Route::get('me/profile', 'Api\ProfileController@index');
    Route::post('me/profile', 'Api\ProfileController@updateprofile');
    Route::post('me/test/push', 'Api\ProfileController@testpushnotif');
    Route::get('lga', 'Api\LocationController@getlga');
    Route::get('state', 'Api\LocationController@getstate');
});
