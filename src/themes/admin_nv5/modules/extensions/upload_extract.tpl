{if $IS_ERROR}
<div role="alert" class="alert alert-danger alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="far fa-times-circle"></i></div>
    <div class="message">{$LANG->get('autoinstall_error_downloaded')}</div>
</div>
{/if}
{if !empty($NO_EXTRACT)}
<div class="card card-table">
    <div class="card-header">
        {$LANG->get('autoinstall_cantunzip')}
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <tbody>
                    {foreach from=$NO_EXTRACT item=file}
                    <tr>
                        <td class="text-danger">{$file}</td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>
{/if}
{if empty($NO_EXTRACT) and not $IS_ERROR}
{if !empty($ERROR_CREATE_FOLDER)}
<div class="card card-table">
    <div class="card-header">
        {$LANG->get('autoinstall_error_warning_permission_folder')}
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <tbody>
                    {foreach from=$ERROR_CREATE_FOLDER item=file}
                    <tr>
                        <td class="text-danger">{$file}</td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>
{elseif !empty($ERROR_MOVE_FOLDER)}
<div class="card card-table">
    <div class="card-header">
        {$LANG->get('autoinstall_error_movefile')}
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <tbody>
                    {foreach from=$ERROR_MOVE_FOLDER item=file}
                    <tr>
                        <td class="text-danger">{$file}</td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>
{elseif !empty($ARRAY_ERROR_MINE)}
<div class="card card-table">
    <div class="card-header">
        {$LANG->get('autoinstall_error_mimetype')}
        <div class="card-subtitle">
            <a href="#" class="upload-dismiss-mime">{$LANG->get('autoinstall_error_mimetype_pass')}</a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <tbody>
                    {foreach from=$ARRAY_ERROR_MINE item=file}
                    <tr>
                        <td class="text-danger">{$file.filename}</td>
                        <td class="text-danger">{$file.mime}</td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    $('.upload-dismiss-mime').click(function(e) {
        e.preventDefault();
        $("#filelist").html('<div class="text-center"><i class="fa fa-spin fa-spinner fa-2x m-bottom wt-icon-loading"></i></div>');
        $("#filelist").load("{$DISMISS_LINK}");
    });
});
</script>
{else}
<div class="card">
    <div class="card-body text-center">
        <h4 class="text-success">{$LANG->get('autoinstall_unzip_success')}</h4>
        <a href="{$URL_GO}">{$LANG->get('autoinstall_unzip_setuppage')}</a>
    </div>
</div>
<script>
setTimeout(function() {
    window.location = "{$URL_GO}";
}, 5000);
</script>
{/if}
{/if}
