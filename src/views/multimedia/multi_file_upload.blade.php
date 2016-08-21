<script>
    function uploadReady(e) {
        for (var i in e) {
            console.log(i + ' ' + e[i]);
            if (e[i] === 'uploadComplete') {
                media_init();
            }
        }
        return true;
    }
</script>
<object
    classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
    id="multi_file_upload" 
    width="320" 
    height="60"
    codebase="http://fpdownload.macromedia.com/get/flashplayer/current/swflash.cab">
    <param name="movie" value="/swf/ixMultiFileUpload.swf" />
    <param name="quality" value="high" />
    <param name="bgcolor" value="#efefef" />
    <param name="allowScriptAccess" value="sameDomain" />
    <param name="flashvars" value="debug=1&onEvent=uploadReady&item_id={{ $item_id }}&cat_id={{ $cat_id }}&uploadUrl=/media/upload" />
    <embed
        src="/swf/ixMultiFileUpload.swf" 
        quality="high" 
        bgcolor="#869ca7" 
        width="320" 
        height="60" 
        name="multi_file_upload" 
        align="middle" 
        play="true" 
        loop="false" 
        quality="high"
        allowScriptAccess="sameDomain"
        type="application/x-shockwave-flash"
        pluginspage="http://www.adobe.com/go/getflashplayer"
        flashvars="debug=1&onEvent=uploadReady&item_id={{ $item_id }}&cat_id={{ $cat_id }}&uploadUrl=/media/upload"
        />
    </embed>
</object>