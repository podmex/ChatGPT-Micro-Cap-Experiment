<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(['middleware' => ['web']], function () {
	Route::get('login',		'\Clixy\Admin\Controllers\Auth\AuthController@getLogin');
	Route::post('login',		'\Clixy\Admin\Controllers\Auth\AuthController@postLogin');
	Route::get('logout',		'\Clixy\Admin\Controllers\Auth\AuthController@logout');

	Route::group(['middleware' => array('auth')], function() {

		Route::get('/', '\Clixy\Admin\Controllers\HomeController@index');

		// user
		Route::get('user',          '\Clixy\Admin\Controllers\UserController@index');
		Route::post('user/list',    '\Clixy\Admin\Controllers\UserController@postList');
		Route::post('user/get',     '\Clixy\Admin\Controllers\UserController@postGet');
		Route::post('user/create',  '\Clixy\Admin\Controllers\UserController@postCreate');
		Route::post('user/remove',  '\Clixy\Admin\Controllers\UserController@postRemove');
		Route::post('user/save',    '\Clixy\Admin\Controllers\UserController@postSave');

		// conf
		Route::get('conf',          '\Clixy\Admin\Controllers\ConfController@index');
		Route::post('conf/list',    '\Clixy\Admin\Controllers\ConfController@postList');
		Route::post('conf/get',     '\Clixy\Admin\Controllers\ConfController@postGet');
		Route::post('conf/create',  '\Clixy\Admin\Controllers\ConfController@postCreate');
		Route::post('conf/remove',  '\Clixy\Admin\Controllers\ConfController@postRemove');
		Route::post('conf/save',    '\Clixy\Admin\Controllers\ConfController@postSave');

		// page
		Route::get('page',          '\Clixy\Admin\Controllers\PageController@index');
		Route::post('page/list',    '\Clixy\Admin\Controllers\PageController@postList');
		Route::post('page/get',     '\Clixy\Admin\Controllers\PageController@postGet');
		Route::post('page/create',  '\Clixy\Admin\Controllers\PageController@postCreate');
		Route::post('page/remove',  '\Clixy\Admin\Controllers\PageController@postRemove');
		Route::post('page/save',    '\Clixy\Admin\Controllers\PageController@postSave');

		// navidation
		Route::get('navigation',				'\Clixy\Admin\Controllers\NavigationController@index');
		Route::post('navigation/list',		'\Clixy\Admin\Controllers\NavigationController@postList');
		Route::post('navigation/get',		'\Clixy\Admin\Controllers\NavigationController@postGet');
		Route::post('navigation/create',		'\Clixy\Admin\Controllers\NavigationController@postCreate');
		Route::post('navigation/remove',		'\Clixy\Admin\Controllers\NavigationController@postRemove');
		Route::post('navigation/save',		'\Clixy\Admin\Controllers\NavigationController@postSave');

		// category
		Route::get('category',          '\Clixy\Admin\Controllers\CategoryController@index');
		Route::post('category/list',    '\Clixy\Admin\Controllers\CategoryController@postList');
		Route::post('category/get',     '\Clixy\Admin\Controllers\CategoryController@postGet');
		Route::post('category/create',  '\Clixy\Admin\Controllers\CategoryController@postCreate');
		Route::post('category/remove',  '\Clixy\Admin\Controllers\CategoryController@postRemove');
		Route::post('category/save',    '\Clixy\Admin\Controllers\CategoryController@postSave');

		// item
		Route::get('item',          '\Clixy\Admin\Controllers\ItemController@index');
		Route::post('item/list',    '\Clixy\Admin\Controllers\ItemController@postList');
		Route::post('item/get',     '\Clixy\Admin\Controllers\ItemController@postGet');
		Route::post('item/create',  '\Clixy\Admin\Controllers\ItemController@postCreate');
		Route::post('item/remove',  '\Clixy\Admin\Controllers\ItemController@postRemove');
		Route::post('item/save',    '\Clixy\Admin\Controllers\ItemController@postSave');

		Route::post('item/date/list',	'\Clixy\Admin\Controllers\ItemController@postDateList');
		Route::post('item/date/remove',	'\Clixy\Admin\Controllers\ItemController@postDateRemove');
		Route::post('item/date/create',	'\Clixy\Admin\Controllers\ItemController@postDateCreate');

		// media
		Route::post('media/upload',				'\Clixy\Admin\Controllers\MediaController@postUpload');
		Route::post('media/remove',				'\Clixy\Admin\Controllers\MediaController@postRemove');
		Route::post('media/getMediaPagination',	'\Clixy\Admin\Controllers\MediaController@postGetMediaPagination');
		Route::post('media/getMediaDetailList',	'\Clixy\Admin\Controllers\MediaController@postGetMediaDetailList');

		// news
		Route::get('news',          '\Clixy\Admin\Controllers\NewsController@index');
		Route::post('news/list',    '\Clixy\Admin\Controllers\NewsController@postList');
		Route::post('news/get',     '\Clixy\Admin\Controllers\NewsController@postGet');
		Route::post('news/create',  '\Clixy\Admin\Controllers\NewsController@postCreate');
		Route::post('news/remove',  '\Clixy\Admin\Controllers\NewsController@postRemove');
		Route::post('news/save',    '\Clixy\Admin\Controllers\NewsController@postSave');

		/*
		//color
		Route::get('color',			'\Clixy\Admin\Controllers\ColorController@index');
		Route::post('color/list',	'\Clixy\Admin\Controllers\ColorController@postList');
		Route::post('color/get',	'\Clixy\Admin\Controllers\ColorController@postGet');
		Route::post('color/create',	'\Clixy\Admin\Controllers\ColorController@postCreate');
		Route::post('color/remove',	'\Clixy\Admin\Controllers\ColorController@postRemove');
		Route::post('color/save',	'\Clixy\Admin\Controllers\ColorController@postSave');
		*/

		// slide
		Route::get('slide',			'\Clixy\Admin\Controllers\SliderController@index');
		Route::post('slide/list',	'\Clixy\Admin\Controllers\SliderController@postList');
		Route::post('slide/get',		'\Clixy\Admin\Controllers\SliderController@postGet');
		Route::post('slide/create',	'\Clixy\Admin\Controllers\SliderController@postCreate');
		Route::post('slide/remove',	'\Clixy\Admin\Controllers\SliderController@postRemove');
		Route::post('slide/save',	'\Clixy\Admin\Controllers\SliderController@postSave');

		// newsletter
		Route::get('newsletter',          '\Clixy\Admin\Controllers\NewsletterController@index');
		Route::post('newsletter/list',    '\Clixy\Admin\Controllers\NewsletterController@postList');
		Route::post('newsletter/get',     '\Clixy\Admin\Controllers\NewsletterController@postGet');
		Route::post('newsletter/create',  '\Clixy\Admin\Controllers\NewsletterController@postCreate');
		Route::post('newsletter/remove',  '\Clixy\Admin\Controllers\NewsletterController@postRemove');
		Route::post('newsletter/save',    '\Clixy\Admin\Controllers\NewsletterController@postSave');

		// newslettersubscribers
		Route::get('newslettersubscribers',          '\Clixy\Admin\Controllers\NewsletterSubscribersController@index');
		Route::post('NewsletterSubscribers/list',    '\Clixy\Admin\Controllers\NewsletterSubscribersController@postList');
		Route::post('NewsletterSubscribers/get',     '\Clixy\Admin\Controllers\NewsletterSubscribersController@postGet');
		Route::post('NewsletterSubscribers/create',  '\Clixy\Admin\Controllers\NewsletterSubscribersController@postCreate');
		Route::post('NewsletterSubscribers/remove',  '\Clixy\Admin\Controllers\NewsletterSubscribersController@postRemove');
		Route::post('NewsletterSubscribers/save',    '\Clixy\Admin\Controllers\NewsletterSubscribersController@postSave');

	});

});
	