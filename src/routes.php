<?php

$groupOptions = [
	'prefix'	=> Config::get('laravel-docs::config.prefix', 'docs'),
	'namespace'	=> 'Arcanedev\LaravelDocs\Controllers'
];

if ( Config::get('laravel-docs::config.enabled', false) )
{

	// Route Patterns
	//========================================
	Route::pattern('docs_locale', '[a-z]{2}');
	Route::pattern('version', '\d.\d|dev');

	// Routes
	//========================================
	Route::group($groupOptions, function()
	{
		Route::get('/', [
			'as'	=> 'arcanedev.docs.travel',
			'uses'	=> 'Base\BaseController@flyAway'
		]);

		Route::group(['prefix' => '{docs_locale}'], function()
		{
			Route::get('/', [
				'as'	=> 'arcanedev.docs.welcome',
				'uses'	=> 'DocsController@welcome'
			]);

			Route::group(['prefix' => '{version}'], function()
			{
				Route::get('/', [
					'as'	=> 'arcanedev.docs.index',
					'uses'	=> 'DocsController@index'
				]);

				Route::get('{slug}', [
					'as'	=> 'arcanedev.docs.show',
					'uses'	=> 'DocsController@show'
				]);
			});
		});
	});
}
