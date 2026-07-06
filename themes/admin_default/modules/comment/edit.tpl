<!-- BEGIN: main -->
<div class="form-group">
    {ROW.content}
</div>
<div class="panel panel-default">
    <div class="panel-body">
        <form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post" class="form-horizontal">
            <div class="form-group">
                <label class="col-sm-6 control-label" for="select-file">{LANG.attach}:</label>
                <div class="col-sm-18">
                    <div class="form-inline">
                        <div class="form-group">
                            <div class="col-xs-24">
                                <input type="text" id="post-file" name="attach" value="{ROW.attach}" class="form-control w300" readonly="readonly">
                            </div>
                        </div>
                        <button data-path="{UPLOADS_DIR}" data-currentpath="{CURRENT_DIR}" id="select-file" class="btn btn-default" type="button">{LANG.attach_choose}</button>
                        <button id="post-file-download" class="btn btn-default" type="button">{LANG.attach_view}</button>
                        <button id="post-file-remove" class="btn btn-default" type="button">{GLANG.delete}</button>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-18 col-lg-10 col-sm-offset-6">
                    <div class="checkbox">
                        <label><input type="checkbox" name="active" value="1" {ROW.status}/> {LANG.edit_active}</label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-18 col-lg-10 col-sm-offset-6">
                    <div class="checkbox">
                        <label><input type="checkbox" name="delete" value="1"/> {LANG.edit_delete} </label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-18 col-sm-offset-6">
                    <input type="hidden" value="{CID}" name="cid"/>
                    <input type="hidden" name="save" value="1">
                    <button type="submit" class="btn btn-primary">{LANG.delete_accept}</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
    $("#select-file").click(function(){
        nv_open_browse(script_name + "?" + nv_name_variable + "=upload&popup=1&area=post-file&alt=&path=" + $(this).data('path') + "&type=file&currentpath=" + $(this).data('currentpath'), "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
    });
    $('#post-file-remove').click(function() {
        $('#post-file').val('');
    });
    $('#post-file-download').click(function() {
        var file = $('#post-file').val();
        if (file != '') {
            window.location = '{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&downloadfile=' + encodeURIComponent(file);
        }
    });
});
</script>
<!-- END: main -->
