<html lang="{{ Lang::locale() }}">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="Laravel Docs">
		<meta name="author" content="ARCANEDEV">

		<title>Laravel Docs</title>
		{{-- CSS --}}
		{{ HTML::style('packages/arcanedev-maroc/laravel-docs/css/style.css') }}

		{{-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries --}}
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>

	<body>

		@include('laravel-docs::_templates.navigation', ['versions' => array_keys($allDocs)])

		<div class="container-fluid">
			<div class="row">
				@if( Route::is('arcanedev.docs.welcome') )
					<div class="col-sm-12 main">
						@yield('content')
					</div>
				@else
					<div class="col-sm-3 col-md-2 sidebar">
						@include('laravel-docs::_partials.sidebar', ['docSidebar' => $allDocs[$selectedVersion]])
					</div>
					<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
						@yield('content')
					</div>
				@endif
			</div>
		</div>

		@include('laravel-docs::_templates.footer')

		{{-- JavaScripts --}}
		{{ HTML::script('packages/arcanedev-maroc/laravel-docs/js/main.min.js') }}
	</body>
</html>