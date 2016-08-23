<!doctype html>
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
		{!! Html::style('assets/lib/foundation-icon-fonts/foundation-icons.css') !!}
		{!! Html::style('assets/admin/css/app.css') !!}
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
									<a href="{!! URL::to($prefix) !!}">
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
								<li>{!! HTML::link('cms', 'Начало') !!}</li>
								@if (Auth::id())
									<li>{!! HTML::link("{$prefix}/news", 'Новини') !!}</li>
									<li>{!! HTML::link("{$prefix}/slide", 'Слайдър') !!}</li>
									<li>{!! HTML::link("{$prefix}/category", 'Категории') !!}</li>
									<li>{!! HTML::link("{$prefix}/item", 'Продукти') !!}</li>
									<li>{!! HTML::link("{$prefix}/navigation", 'Навигация') !!}</li>
									<li>{!! HTML::link("{$prefix}/page", 'Страници') !!}</li>
									<li>{!! HTML::link("{$prefix}/conf", 'Настройки') !!}</li>
									<li>{!! HTML::link("{$prefix}/user", 'Потребители') !!}</li>
									<li>{!! HTML::link("{$prefix}/logout", 'Изход') !!}</li>
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
				gate: "{{ URL::to('cms') }}/",
				token: "{{ csrf_token() }}"
			};
		</script>
		{!! Html::script('assets/lib/jquery/dist/jquery.min.js') !!}
		{!! Html::script('assets/lib/modernizr-min/dist/modernizr.min.js') !!}
		{!! Html::script('assets/lib/what-input/what-input.min.js') !!}
		{!! Html::script('assets/lib/foundation-sites/dist/foundation.min.js') !!}
		{!! Html::script('assets/lib/ckeditor/ckeditor.js') !!}
		{!! Html::script('assets/admin/js/app.js') !!}
		@if (!empty($module))
			{!! Html::script("assets/admin/js/model/{$module}.js") !!}
		@endif
	</body>
</html>
