<form id="cmt-edit" method="post" class="g-3 ajax-submit" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}">
    <input type="hidden" name="checkss" value="{$CHECKSS}">
    <div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-sm-2 col-form-label text-sm-end">{$LANG->getModule('content')}</div>
                <div class="col-sm-9">
                    {$ROW.content}
                </div>
            </div>
            <div class="row mb-3">
                <label for="post-file" class="col-sm-2 col-form-label text-sm-end">{$LANG->getModule('attach')}</label>
                <div class="col-sm-9 col-lg-8 col-xxl-6">
                    <div class="input-group">
                    <input class="form-control" type="text" name="attach" id="post-file" value="{$ROW.attach}" readonly="readonly" />
                    <button type="button" data-toggle="selectfile" data-target="post-file" data-path="{$smarty.const.NV_UPLOADS_DIR}/{$MODULE_UPLOAD}" data-currentpath="{$smarty.const.NV_UPLOADS_DIR}/{$MODULE_UPLOAD}/{$DIR}" data-type="file" class="btn btn-info" title="{$LANG->getGlobal('browse_file')}" aria-label="{$LANG->getGlobal('browse_file')}"><i class="fa-solid fa-folder-open"></i></button>
                    <button id="post-file-download" class="btn btn-secondary" type="button" title="{$LANG->getModule('attach_download')}" aria-label="{$LANG->getModule('attach_download')}"><i class="fa-solid fa-file-arrow-down"></i>&nbsp;<span class="d-none d-lg-inline">{$LANG->getModule('attach_download')}</span></button>
                    <button id="post-file-remove" class="btn btn-danger" type="button" title="{$LANG->getGlobal('delete')}"><i class="fa-solid fa-trash"></i>&nbsp;<span class="d-none d-lg-inline">{$LANG->getGlobal('delete')}</span></button>
                </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-2">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="active" value="1" {if $ROW.status}checked="checked"{/if} role="switch" id="cmt-active">
                        <label class="form-check-label" for="cmt-active">{$LANG->getModule('edit_active')}</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-2">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="delete" value="1" role="switch" id="cmt-delete">
                        <label class="form-check-label" for="cmt-delete">{$LANG->getModule('edit_delete')}</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <input type="hidden" value="{$CID}" name="cid">
                <input type="hidden" name="save" value="1">
                <div class="col-sm-8 offset-sm-2">
                    <button type="submit" class="btn btn-primary">{$LANG->getGlobal('submit')}</button>
                </div>
            </div>
        </div>
    </div>
</form>
