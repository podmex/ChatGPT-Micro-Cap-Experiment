<div class="s_buttons_actions">
@foreach($items as $k => $v)
    @include('clixy/admin::common.gallery.button', ['action' => $v, 'obj' => $obj, 'id' => $id])
@endforeach
</div>