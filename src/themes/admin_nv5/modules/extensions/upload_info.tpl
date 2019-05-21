{if not empty($ERROR)}
<div role="alert" class="alert alert-danger alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="far fa-times-circle"></i></div>
    <div class="message">{$ERROR}</div>
</div>
{/if}
{if not empty($FILEINFO)}
<div class="card">
    <div class="card-header card-header-divider">
        {$LANG->get('autoinstall_uploadedfile')}
    </div>
    <div class="card-body">
        <dl class="row">
            <dt class="col-sm-3 text-truncate">{$LANG->get('autoinstall_uploadedfilesize')}</dt>
            <dd class="col-sm-9"><strong class="text-danger">{$FILEINFO.filesize}</strong></dd>
            <dt class="col-sm-3 text-truncate">{$LANG->get('autoinstall_uploaded_filenum')}</dt>
            <dd class="col-sm-9"><strong class="text-danger">{$FILEINFO.filenum}</strong></dd>
            <dt class="col-sm-3 text-truncate">{$LANG->get('extname')}</dt>
            <dd class="col-sm-9"><strong class="text-danger">{$FILEINFO.extname}</strong></dd>
            <dt class="col-sm-3 text-truncate">{$LANG->get('ext_type')}</dt>
            <dd class="col-sm-9"><strong class="text-danger">{$LANG->get("extType_`$FILEINFO.exttype`")}</strong></dd>
            <dt class="col-sm-3 text-truncate">{$LANG->get('file_version')}</dt>
            <dd class="col-sm-9"><strong class="text-danger">{$FILEINFO.extversion}</strong></dd>
            <dt class="col-sm-3 text-truncate">{$LANG->get('author')}</dt>
            <dd class="col-sm-9"><strong class="text-danger">{$FILEINFO.extauthor}</strong></dd>
            <dt class="col-sm-3 text-truncate">{$LANG->get('autoinstall_uploaded_num_exists')}</dt>
            <dd class="col-sm-9"><strong class="text-danger">{$FILEINFO.existsnum}</strong></dd>
            <dt class="col-sm-3 text-truncate">{$LANG->get('autoinstall_uploaded_num_invalid')}</dt>
            <dd class="col-sm-9"><strong class="text-danger">{$FILEINFO.invaildnum}</strong></dd>
        </dl>
    </div>
</div>
<div id="upload-ext-status">
    {if $FILEINFO.checkresult eq 'success'}
    <div role="alert" class="alert alert-success alert-dismissible">
        <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
        <div class="icon"><i class="fas fa-check"></i></div>
        <div class="message">{$LANG->get('autoinstall_error_check_success')}</div>
    </div>
    {elseif $FILEINFO.checkresult eq 'warning'}
    <div role="alert" class="alert alert-warning alert-dismissible">
        <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
        <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
        <div class="message">{$LANG->get('autoinstall_error_check_warning')}</div>
    </div>
    {else}
    <div role="alert" class="alert alert-danger alert-dismissible">
        <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
        <div class="icon"><i class="far fa-times-circle"></i></div>
        <div class="message">{$LANG->get('autoinstall_error_check_fail')}</div>
    </div>
    {/if}
</div>
<script type="text/javascript">
$(document).ready(function() {
    $('#upload-ext-status a').click(function(e) {
        e.preventDefault();
        $("#filelist").html('<div class="text-center"><i class="fa fa-spin fa-spinner fa-2x m-bottom wt-icon-loading"></i></div>');
        $("#filelist").load("{$EXTRACTLINK}");
    });
});
</script>
<div id="filelist">
    {if not empty($FILEINFO.filelist)}
    <div class="card card-table">
        <div class="card-header">
            {$LANG->get('autoinstall_uploaded_filelist')}
            <div class="card-subtitle">
                <i class="fa {$FILEINFO.classcfg.invaild} text-warning"></i> {$LANG->get('autoinstall_note_invaild')} &nbsp; &nbsp;
                <i class="fa {$FILEINFO.classcfg.exists} text-warning"></i> {$LANG->get('autoinstall_note_exists')}
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <tbody>
                        {foreach from=$FILEINFO.filelist item=file}
                        <tr>
                            <td>
                                {$file.title}
                                {if not empty($file.class)}
                                <div class="float-right">
                                    {foreach from=$file.class item=icon}
                                    <i class="fa {$icon} text-warning ml-1"></i>
                                    {/foreach}
                                </div>
                                {/if}
                            </td>
                        </tr>
                        {/foreach}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {/if}
</div>
{/if}
