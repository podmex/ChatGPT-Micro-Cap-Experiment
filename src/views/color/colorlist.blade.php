@foreach ($list as $v)
<div class="row">
    <div class="large-10 columns">
        <p>{{ $v->lang->title }}<br /><small style="color: #{{ $v->code }}">{{ $v->name }}</small></p>
    </div>
    <div class="large-2 columns">
        <a data-id="{{ $v->id }}" data-action="edit" href="javascript:;" class="button tiny radius">@lang('clixy/admin::common.btn.edit')</a>
        <a data-id="{{ $v->id }}" data-action="remove" href="javascript:;" class="button tiny radius">@lang('clixy/admin::common.btn.delete')</a>
    </div>
</div>
@endforeach