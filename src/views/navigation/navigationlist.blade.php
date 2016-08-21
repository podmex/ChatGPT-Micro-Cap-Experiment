@foreach($group_list as $g)
@if($g->parent_id != 0)
<div class="row">
    <div class="large-12 columns">
        <h3 style="margin-bottom: 3px">{{ $g->alias }}</h3>
    @foreach ($list as $v)
    @if($v->group_id == $g->id)
    <div class="row" style="border-top: 1px solid #ccc">
        <div class="large-8 columns">
            <p>{{ $v->lang->title }}<br /><small>{{ $v->slug }}</small></p>
        </div>
        <div class="large-4 columns" style="padding-top: 12px">
            <a data-id="{{ $v->id }}" data-action="edit" href="javascript:;" class="button tiny success radius">@lang('clixy/admin::common.btn.edit')</a>
            <a data-id="{{ $v->id }}" data-action="remove" href="javascript:;" class="button tiny alert radius">@lang('clixy/admin::common.btn.delete')</a>
        </div>
    </div>
    @endif
    @endforeach
    </div>
</div>
@endif
@endforeach