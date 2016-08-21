<!doctype html>
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
		{!! Html::style('bower_components/foundation-icon-fonts/foundation-icons.css') !!}
		{!! Html::style('assets/css/app.css') !!}
        @yield('refresh')
	</head>
	<body>
		<div class="row">
			<div class="small-12 medium-12 large-12 columns">
				<header class="head">
					<nav class="top-bar" data-topbar>
						<ul class="title-area">
							<li class="name">
								<h1>
									<a href="{!! URL::to('/') !!}">
										{{ $name or 'Clixy CMS' }}
									</a>
								</h1>
							</li>
							<li class="toggle-topbar menu-icon">
								<a href="#">
									<span>Меню</span>
								</a>
							</li>
						</ul>
						<section class="top-bar-section">
							<!-- Right Nav Section -->
							<ul class="right">
								<li>{!! HTML::link('/', 'Начало') !!}</li>
								@if (Auth::id())
									<li>{!! HTML::link('news', 'Новини') !!}</li>
									<li>{!! HTML::link('slide', 'Слайдър') !!}</li>
									<li>{!! HTML::link('category', 'Категории') !!}</li>
									<li>{!! HTML::link('item', 'Продукти') !!}</li>
									<li>{!! HTML::link('navigation', 'Навигация') !!}</li>
									<li>{!! HTML::link('page', 'Страници') !!}</li>
									<li>{!! HTML::link('conf', 'Настройки') !!}</li>
									<li>{!! HTML::link('user', 'Потребители') !!}</li>
									<li>{!! HTML::link('logout', 'Изход') !!}</li>
								@endif
							</ul>
						</section>
					</nav>
				</header>
			</div>
		</div>
		
		@yield('content')
		
		<script type="text/javascript">
			Conf = {
				token: "{{ csrf_token() }}"
			};
		</script>
		{!! Html::script('bower_components/jquery/dist/jquery.min.js') !!}
		{!! Html::script('bower_components/modernizr-min/dist/modernizr.min.js') !!}
		{!! Html::script('bower_components/what-input/what-input.min.js') !!}
		{!! Html::script('bower_components/foundation-sites/dist/foundation.min.js') !!}
		{!! Html::script('bower_components/ckeditor/ckeditor.js') !!}
		{!! Html::script('js/app.js') !!}
		@if (!empty($module))
			{!! Html::script("js/model/{$module}.js") !!}
		@endif
	</body>
</html>
