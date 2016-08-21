@foreach ($list as $v)
<div class="row" data-date-id="{{ $v->id }}">
    <div class="large-10 columns">
        {{ $v->date_at }}
    </div>
    <div class="large-2 columns">
        <button data-id="{{ $v->id }}" data-action="date-remove" type="button" class="button tiny radius alert">@lang('clixy/admin::common.btn.delete')</button>
    </div>
</div>
@endforeach