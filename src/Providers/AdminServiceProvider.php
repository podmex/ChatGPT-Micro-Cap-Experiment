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
		$this->loadTranslationsFrom(__DIR__ . '/../lang', 'clixy/admin');
		$this->loadViewsFrom(__DIR__ . '/../views', 'clixy/admin');
		
		$this->publishes([
			//__DIR__ . '/../config.php' => config_path('clixy.admin.php'),
		], 'config');
				
		$this->publishes([
			__DIR__ . '/../Middleware' => app_path('Http/Middleware'),
		], 'middleware');

		$this->publishes([
			__DIR__ . '/../public' => public_path(),
		], 'public');
		
		$this->publishes([
			//__DIR__ . '/../lang' => resource_path('lang/vendor/clixy/admin'),
			//__DIR__ . '/../views' => resource_path('views/vendor/clixy/admin'),
		], 'resouces');
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
		require __DIR__ . '/../routes.php';
		
		$this->mergeConfigFrom(__DIR__ . '/../config.php', 'clixy.admin');
		
		// laravelcollective/html
		$this->app->register('Collective\Html\HtmlServiceProvider');
		$loader = \Illuminate\Foundation\AliasLoader::getInstance();
		$loader->alias('Form', 'Collective\Html\FormFacade');
		$loader->alias('Html', 'Collective\Html\HtmlFacade');
		
		// clixy/core
		$this->app->register('Clixy\Core\Providers\CoreServiceProvider');
		
		// extending controllers
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
