@foreach($list as $v)
	@if($v->parent_id == 0)
		<div class="row">
			<div class="large-8 columns">
				<p>{{ $v->lang->title }}</p>
			</div>
			<div class="large-4 columns">
				<a data-id="{{ $v->id }}" data-action="edit" href="javascript:;" class="button tiny success radius">@lang('clixy/admin::common.btn.edit')</a>
				<a data-id="{{ $v->id }}" data-action="remove" href="javascript:;" class="button tiny alert radius">@lang('clixy/admin::common.btn.delete')</a>
			</div>
		</div>
		@foreach($list as $vv)
			@if($vv->parent_id == $v->id)
				<div class="row">
					<div class="large-8 columns">
						<p>&bull; {{ $vv->lang->title }}</p>
					</div>
					<div class="large-4 columns">
						<a data-id="{{ $vv->id }}" data-action="edit" href="javascript:;" class="button tiny success radius">@lang('clixy/admin::common.btn.edit')</a>
						<a data-id="{{ $vv->id }}" data-action="remove" href="javascript:;" class="button tiny alert radius">@lang('clixy/admin::common.btn.delete')</a>
					</div>
				</div>
			@endif
		@endforeach
	@endif
@endforeach