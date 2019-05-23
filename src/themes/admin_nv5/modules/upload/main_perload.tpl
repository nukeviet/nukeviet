<div class="filemanager-wraper filemanager-inline" id="upload-container"></div>
<script type="text/javascript" src="{$NV_BASE_ADMINURL}index.php?{$NV_LANG_VARIABLE}={$NV_LANG_DATA}&amp;{$NV_NAME_VARIABLE}={$MODULE_NAME}&amp;js"></script>
<script>
$(document).on("nv.upload.ready", function() {
    $("#upload-container").nvstaticupload({
        adminBaseUrl: '{$NV_BASE_ADMINURL}',
        path: '{$PATH}',
        currentpath: '{$CURRENTPATH}',
        type: '{$TYPE}'
    });
});
</script>
