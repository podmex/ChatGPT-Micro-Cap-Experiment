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

Route::group(['prefix' => config('clixy.admin.prefix'), 'middleware' => ['admin']], function () {
	
	$c = '\Clixy\Admin\Controllers\Auth\AuthController';
	Route::get('login',   $c . '@getLogin');
	Route::post('login',	  $c . '@postLogin');
	Route::get('logout',	  $c . '@logout');

	Route::group(['middleware' => array('auth.admin')], function() {

		$c = '\Clixy\Admin\Controllers\HomeController';
		Route::get('/', $c . '@index');

		// user
		Route::group(['prefix' => 'user'], function () {
			$c = '\Clixy\Admin\Controllers\UserController';
			Route::get('/',        $c . '@index');
			Route::post('list',    $c . '@postList');
			Route::post('get',     $c . '@postGet');
			Route::post('create',  $c . '@postCreate');
			Route::post('remove',  $c . '@postRemove');
			Route::post('save',    $c . '@postSave');
		});

		// conf
		Route::group(['prefix' => 'conf'], function () {
			$c = '\Clixy\Admin\Controllers\ConfController';
			Route::get('/',        $c . '@index');
			Route::post('list',    $c . '@postList');
			Route::post('get',     $c . '@postGet');
			Route::post('create',  $c . '@postCreate');
			Route::post('remove',  $c . '@postRemove');
			Route::post('save',    $c . '@postSave');
		});

		// page
		Route::group(['prefix' => 'page'], function () {
			$c = '\Clixy\Admin\Controllers\PageController';
			Route::get('/',				$c . '@index');
			Route::post('page/list',		$c . '@postList');
			Route::post('page/get',		$c . '@postGet');
			Route::post('page/create',	$c . '@postCreate');
			Route::post('page/remove',	$c . '@postRemove');
			Route::post('page/save',		$c . '@postSave');
		});
		
		// navidation
		Route::group(['prefix' => 'navigation'], function () {
			$c = '\Clixy\Admin\Controllers\NavigationController';
			Route::get('/',			$c . '@index');
			Route::post('list',		$c . '@postList');
			Route::post('get',		$c . '@postGet');
			Route::post('create',	$c . '@postCreate');
			Route::post('remove',	$c . '@postRemove');
			Route::post('save',		$c . '@postSave');
		});
		
		// category
		Route::group(['prefix' => 'category'], function () {
			$c = '\Clixy\Admin\Controllers\CategoryController';
			Route::get('/',        $c . '@index');
			Route::post('list',    $c . '@postList');
			Route::post('get',     $c . '@postGet');
			Route::post('create',  $c . '@postCreate');
			Route::post('remove',  $c . '@postRemove');
			Route::post('save',    $c . '@postSave');
		});
		
		// item
		Route::group(['prefix' => 'item'], function () {
			$c = '\Clixy\Admin\Controllers\ItemController';
			Route::get('/',        $c . '@index');
			Route::post('list',    $c . '@postList');
			Route::post('get',     $c . '@postGet');
			Route::post('create',  $c . '@postCreate');
			Route::post('remove',  $c . '@postRemove');
			Route::post('save',    $c . '@postSave');

			Route::post('date/list',	    $c . '@postDateList');
			Route::post('date/remove',	$c . '@postDateRemove');
			Route::post('date/create',	$c . '@postDateCreate');
		});
		
		// media
		Route::group(['prefix' => 'media'], function () {
			$c = '\Clixy\Admin\Controllers\MediaController';
			Route::post('upload',				$c . '@postUpload');
			Route::post('remove',				$c . '@postRemove');
			Route::post('getMediaPagination',	$c . '@postGetMediaPagination');
			Route::post('getMediaDetailList',	$c . '@postGetMediaDetailList');
		});
			
		// news
		Route::group(['prefix' => 'news'], function () {
			$c = '\Clixy\Admin\Controllers\NewsController';
			Route::get('/',        $c . '@index');
			Route::post('list',    $c . '@postList');
			Route::post('get',     $c . '@postGet');
			Route::post('create',  $c . '@postCreate');
			Route::post('remove',  $c . '@postRemove');
			Route::post('save',    $c . '@postSave');
		});
			
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
		Route::group(['prefix' => 'slide'], function () {
			$c = '\Clixy\Admin\Controllers\SliderController';
			Route::get('/',			$c . '@index');
			Route::post('list',	    $c . '@postList');
			Route::post('get',		$c . '@postGet');
			Route::post('create',	$c . '@postCreate');
			Route::post('remove',	$c . '@postRemove');
			Route::post('save',	    $c . '@postSave');
		});
			
		// newsletter
		Route::group(['prefix' => 'newsletter'], function () {
			$c = '\Clixy\Admin\Controllers\NewsletterController';
			Route::get('/',        $c . '@index');
			Route::post('list',    $c . '@postList');
			Route::post('get',     $c . '@postGet');
			Route::post('create',  $c . '@postCreate');
			Route::post('remove',  $c . '@postRemove');
			Route::post('save',    $c . '@postSave');
		});
			
		// newslettersubscribers
		Route::group(['prefix' => 'newslettersubscribers'], function () {
			$c = '\Clixy\Admin\Controllers\NewsletterSubscribersController';
			Route::get('/',          $c . '@index');
			Route::post('list',    $c . '@postList');
			Route::post('get',     $c . '@postGet');
			Route::post('create',  $c . '@postCreate');
			Route::post('remove',  $c . '@postRemove');
			Route::post('save',    $c . '@postSave');
		});
			
	});

});
	