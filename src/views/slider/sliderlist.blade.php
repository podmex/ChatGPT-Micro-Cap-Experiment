@foreach ($list as $v)
<div class="row">
    <div class="large-10 columns">
        <p>{{ $v->lang->title }}</p>
    </div>
    <div class="large-2 columns">
        <a data-id="{{ $v->id }}" data-action="edit" href="javascript:;" class="button tiny radius success">@lang('clixy/admin::common.btn.edit')</a>
        <a data-id="{{ $v->id }}" data-action="remove" href="javascript:;" class="button tiny radius alert">@lang('clixy/admin::common.btn.delete')</a>
    </div>
</div>
@endforeach