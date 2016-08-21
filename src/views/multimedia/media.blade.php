<!-- media preview -->
<div>
    <div id="media-list"></div>
    <div class="clear"></div>
</div>
<!-- end of media preview -->

<div class="media_upload">
    <div id="media_upload_swf" style="width:320px;height:60px">
        @include('clixy/admin::multimedia.multi_file_upload')
    </div>
    <script src="/js/state.js"></script>
    <script src="/js/media.js"></script>
    <script>
        $(document).ready(function() {
            media.init(0, {{ $item_id }}, {{ $cat_id }});
        });
    </script>
</div>