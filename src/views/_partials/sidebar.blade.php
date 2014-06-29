@foreach($docSidebar->getTOCLinks() as $title => $links)
	<ul class="nav nav-sidebar nav-laravel">
		<li class="nav-title">{{ $title }}</li>
		@foreach($links as $key => $link)
			<li>
				{{ $link }}
			</li>
		@endforeach
	</ul>
@endforeach