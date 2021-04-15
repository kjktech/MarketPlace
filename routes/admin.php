<?php

Route::group(['as' => 'panel.', 'prefix' => 'panel', 'middleware' => ['web', 'role:admin|super-admin'], 'namespace' => 'Admin'], function()
{
    Route::get('/', 'PanelController@index');
    Route::resource('listings', 'ListingsController');
    Route::resource('directories', 'DirectoriesController');
    // Ledger url
    Route::get('/ledger/{directory}/', 'DirectoriesController@ledgerindex')->name('ledger.index');
    Route::post('/ledger/{directory}/', 'DirectoriesController@ledgerupdate')->name('ledger.update');

    // Ledger url
    Route::get('/topledger/{directory}/', 'DirectoriesController@topledgerindex')->name('topledger.index');
    Route::post('/topledger/{directory}/', 'DirectoriesController@topledgerupdate')->name('topledger.update');


    Route::get('/top-directories/', 'DirectoriesController@indextop')->name('topbrands');
    Route::resource('stores', 'StoresController');
    // store setup
    Route::get('/store/setup/{id}', 'StoresController@setup')->name('setupstore');
    Route::get('/store/transaction/{id}', 'StoresController@transaction')->name('transaction');

    // TrShhot
    //Route::get('/store/test', 'StoresController@teststore')->name('test.store');

    // Ledger url
    Route::get('/storeledger/{shopping}/', 'StoresController@ledgerindex')->name('storeledger.index');
    Route::post('/storeledger/{shopping}/', 'StoresController@ledgerupdate')->name('storeledger.update');

    Route::resource('brands', 'BrandsController');
    Route::resource('loyalties', 'LoyaltiesController');
    Route::resource('categories', 'CategoriesController');
    Route::resource('storecategories', 'StoreCategoriesController');
    Route::resource('directorycategories', 'DirectoryCategoriesController');
    Route::resource('users', 'UsersController');
    // Search user url
    Route::get('/user/search', 'UsersController@searchuser')->name('search.user');
    // Admin user list
    Route::get('/user/admins', 'UsersController@adminindex')->name('users.adminindex');
    Route::get('/user/admins/directory', 'UsersController@admindirectoryindex')->name('users.admindirectoryindex');
    Route::get('/user/newsletter', 'UsersController@newslettersubscribers')->name('newsletter.user');

    Route::resource('pages', 'PagesController');
    Route::resource('menu', 'MenuController');
    Route::any('/settings/remove', 'SettingsController@remove')->name('settings.remove');
    Route::resource('settings', 'SettingsController');
    Route::resource('orders', 'OrdersController');
    Route::resource('home', 'HomeController');
    Route::resource('addons', 'AddonsController');
    Route::get('/addon/{id}/toggle', 'AddonsController@toggle')->name('customtoggle');
    Route::resource('themes', 'ThemesController');
    Route::get('/theme/{id}/toggle', 'ThemesController@toggle')->name('customtheme');
    Route::resource('pricing-models', 'PricingModelsController');
    Route::resource('fields', 'FieldsController');
    Route::resource('variants', 'VariantsController');
    Route::get('/blogcategory-icons/', 'BlogCategoryIconsController@index')->name('blog.icon');
    Route::get('/blogdashboard/', 'BlogController@dashboardindex')->name('blog.dashboard');
    Route::get('/blogposts/', 'BlogController@posts')->name('blog.posts');
    Route::post('/blogcategory-icons-up/', 'BlogCategoryIconsController@update')->name('blog.iconupdate');
    Route::get('/storedashboard/', 'StoreDashboardController@index')->name('store.dashboard');
    Route::get('/businessdashboard/', 'DirectoriesController@dashboardindex')->name('business.dashboard');
    Route::get('/payments/directory', 'PaymentController@directory')->name('payment.directory');

    // Variant options
    Route::get('/categories/variants/{id}', 'CategoriesController@variant')->name('categories.variant');
    Route::put('/categories/variants/{id}', 'CategoriesController@updatevariant')->name('categories.updatevariant');

});

Route::group(['as' => 'panel.', 'prefix' => 'panel', 'middleware' => ['web', 'role:admin|moderator|super-admin'], 'namespace' => 'Admin'], function()
{
    Route::get('/', 'PanelController@index');
    Route::resource('users', 'UsersController');
});
