<div class="navbar navbar-laravel navbar-fixed-top" role="navigation">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="{{ route('arcanedev.docs.travel') }}">
				Laravel Docs
			</a>
		</div>
		<div class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
				@foreach($supportedLocales as $loc)
					<li class="{{ $selectedLocale == $loc ? 'active' : '' }}">
						{{ HTML::linkRoute('arcanedev.docs.welcome', strtoupper($loc), $loc, []) }}
					</li>
				@endforeach
			</ul>

			<ul class="nav navbar-nav navbar-right">
				@foreach($versions as $version)
					<li class="{{ $selectedVersion == $version ? 'active' : '' }}">
						{{ HTML::linkRoute('arcanedev.docs.index', $version, [$selectedLocale, $version], []) }}
					</li>
				@endforeach
			</ul>
		</div>
	</div>
</div>