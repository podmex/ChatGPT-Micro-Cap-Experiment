<?php

namespace Clixy\Admin\Providers;

use Illuminate\Support\ServiceProvider;

class AdminServiceProvider extends ServiceProvider
{
	/**
	 * Indicates of loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		// artisan vendor:publish
		$this->publishes([
			__DIR__ . '/../public/assets/admin' => public_path('assets/admin'),
		], 'public');
		
		// translations
		$this->loadTranslationsFrom(__DIR__ . '/../lang', 'clixy/admin');
		
		// views
		$this->loadViewsFrom(__DIR__ . '/../views', 'clixy/admin');

		// template global variables
		$this->app->view->share('prefix', config('clixy.admin.prefix') );
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{

		// config
		$this->mergeConfigFrom(__DIR__ . '/../config.php', 'clixy.admin');
		
		// middleware
		$this->app['router']->middleware('auth.admin', '\Clixy\Admin\Middleware\Authenticate');
		$this->app['router']->middleware('guest.admin', '\Clixy\Admin\Middleware\RedirectIfAuthenticated');
		
		//middleware groups
		$this->app['router']->middlewareGroup('admin',[
			\Clixy\Admin\Middleware\EncryptCookies::class,
			\Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
			\Illuminate\Session\Middleware\StartSession::class,
			\Illuminate\View\Middleware\ShareErrorsFromSession::class,
			\Clixy\Admin\Middleware\VerifyCsrfToken::class,
		]);
		
		// adding admin routes
		require __DIR__ . '/../routes.php';

		// clixy/core
		$this->app->register('Clixy\Core\Providers\CoreServiceProvider');
		
		// override laravel controllers
		$this->app->make('Clixy\Admin\Controllers\Auth\AuthController');

		// controllers
		$this->app->make('Clixy\Admin\Controllers\CategoryController');
		$this->app->make('Clixy\Admin\Controllers\ColorController');
		$this->app->make('Clixy\Admin\Controllers\ConfController');
		$this->app->make('Clixy\Admin\Controllers\HomeController');
		$this->app->make('Clixy\Admin\Controllers\ItemController');
		$this->app->make('Clixy\Admin\Controllers\MediaController');
		$this->app->make('Clixy\Admin\Controllers\NavigationController');
		$this->app->make('Clixy\Admin\Controllers\NewsController');
		$this->app->make('Clixy\Admin\Controllers\NewsletterController');
		$this->app->make('Clixy\Admin\Controllers\NewsletterSubscribersController');
		$this->app->make('Clixy\Admin\Controllers\PageController');
		$this->app->make('Clixy\Admin\Controllers\SliderController');
		$this->app->make('Clixy\Admin\Controllers\UserController');
	}

}
