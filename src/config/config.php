<?php

return [
	'enabled'		=> true, // Or App::environment('local') instead of true or false

	'prefix'		=> 'docs',

	'use-sessions'	=> true,

	'locales'		=> ['en', 'fr'],

	'versions'		=> ['4.2', '4.1', '4.0'],

	'docs'			=> [

		'display-rows'	=> true,

	],

	'layout'		=> 'laravel-docs::_templates.layout',
];