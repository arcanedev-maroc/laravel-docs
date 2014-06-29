<?php namespace Arcanedev\LaravelDocs;

use Illuminate\Support\ServiceProvider;

class LaravelDocsServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	public function boot()
	{
		$this->package('arcanedev/laravel-docs');

		include __DIR__ . '/../../start.php';
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->singleton('lara-docs', function($app) {
			$base_docs_dir = __DIR__ . '/../../docs';

			return new Services\Docs\Manager($base_docs_dir);
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return ['lara-docs'];
	}

}
