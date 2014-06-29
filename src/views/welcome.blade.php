@section('content')
	<div class="jumbotron jumbotron-laravel">
		<h1>{{ trans('laravel-docs::texts.welcome.title') }}</h1>
		<p class="lead">{{ trans('laravel-docs::texts.welcome.sub-title') }}</p>
	</div>

	@if( Config::get('laravel-docs::config.docs.display-rows', true) )
		<div class="panel-group" id="accordion">
			@foreach($allDocs as $docs)
				<?php
					$idVersion = 'collapse' . str_replace('.', '_', $docs->getVersion());
				 ?>
				<div class="panel panel-laravel pannel-row">
					<div class="panel-heading panel-version">
						<a data-toggle="collapse" data-parent="#accordion" href="#{{ $idVersion }}">
							{{ $docs->getVersion() }}
        				</a>
					</div>
					<div id="{{ $idVersion }}" class="panel-collapse collapse">
						<div class="panel-body">
							<div class="row">
								@foreach($docs->getTOCLinks() as $title => $links)
									<div class="col-xs-12 col-sm-6 col-md-3">
										<div class="panel-heading panel-title">{{ $title }}</div>
										<ul class="list-group doc-items">
											@foreach($links as $link)
												<li class="list-group-item">{{ $link }}</li>
											@endforeach
										</ul>
									</div>
								@endforeach
							</div>
						</div>
					</div>
				</div>
			@endforeach
		</div>
	@else
		@foreach($allDocs as $docs)
			<div class="row">
				<div class="col-sm-12 col-md-4">
					<div class="panel panel-laravel">
						<div class="panel-heading panel-version">{{ $docs->getVersion() }}</div>
						@foreach($docs->getTOCLinks() as $title => $links)
							<div class="panel-heading panel-title">{{ $title }}</div>
							<ul class="list-group doc-items">
								@foreach($links as $link)
									<li class="list-group-item">{{ $link }}</li>
								@endforeach
							</ul>
						@endforeach
					</div>
				</div>
			</div>
		@endforeach
	@endif
@stop