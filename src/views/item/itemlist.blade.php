@foreach ($list as $v)
<div class="row">
	<div class="large-8 columns">
		<p>{{ $v->lang->title }}<br />{{ $v->slug }}</p>
	</div>
	<div class="large-4 columns">
		<a data-id="{{ $v->id }}" data-action="edit" href="javascript:;" class="button tiny success radius">@lang('clixy/admin::common.btn.edit')</a>
		<a data-id="{{ $v->id }}" data-action="remove" href="javascript:;" class="button tiny alert radius">@lang('clixy/admin::common.btn.delete')</a>
	</div>
</div>
@endforeach