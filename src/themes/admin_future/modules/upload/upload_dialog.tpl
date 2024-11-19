<div class="fmd fade" data-dialog="search" id="[prefix]-search" tabindex="-1" aria-labelledby="[prefix]-search-label" aria-hidden="true">
    <div class="fmd-dialog">
        <div class="fmd-content">
            <div class="fmd-header">
                <div class="fmd-title text-truncate fs-5 fw-medium" id="[prefix]-search-label">{$LANG->getModule('search')}</div>
                <button type="button" class="btn-close" data-dismiss="fmd" aria-label="{$LANG->getGlobal('close')}"></button>
            </div>
            <div class="fmd-body">
                <form method="post" action="">
                    <div class="mb-3">
                        <label for="[prefix]-search-dir" class="form-label">{$LANG->getModule('searchdir')}:</label>
                        <select class="form-select" data-toggle="select2" name="dir" id="[prefix]-search-dir">
                            <option>----</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="[prefix]-search-key" class="form-label">{$LANG->getModule('searchkey')}:</label>
                        <input class="form-control" name="q" type="text" maxlength="200" value="" id="[prefix]-search-key" autocomplete="off">
                        <div class="invalid-feedback">{$LANG->getGlobal('required_invalid')}</div>
                    </div>
                    <div class="hstack justify-content-end gap-2">
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i> {$LANG->getModule('search')}</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="fmd"><i class="fa-solid fa-xmark text-danger"></i> {$LANG->getGlobal('cancel')}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="fmd fade" data-dialog="upload-remote" id="[prefix]-upload-remote" tabindex="-1" aria-labelledby="[prefix]-upload-remote-label" aria-hidden="true">
    <div class="fmd-dialog">
        <div class="fmd-content">
            <div class="fmd-header">
                <div class="fmd-title text-truncate fs-5 fw-medium" id="[prefix]-upload-remote-label">{$LANG->getModule('upload_mode_remote')}</div>
                <button type="button" class="btn-close" data-dismiss="fmd" aria-label="{$LANG->getGlobal('close')}"></button>
            </div>
            <div class="fmd-body">
                <form method="post" action="">
                    <div class="mb-3">
                        <label for="[prefix]-upload-remote-url" class="form-label">{$LANG->getModule('enter_url')} <span class="text-danger">(*)</span>:</label>
                        <input class="form-control" name="fileurl" type="text" value="" id="[prefix]-upload-remote-url" autocomplete="off">
                        <div class="invalid-feedback">{$LANG->getModule('uploadError2')}</div>
                    </div>
                    <div class="mb-3">
                        <label for="[prefix]-upload-remote-altimage" class="form-label">{$LANG->getModule('altimage')}{if $UPLOAD_ALT_REQUIRE eq 'true'} <span class="text-danger">(*)</span>{/if}:</label>
                        <input class="form-control" name="filealt" type="text" maxlength="200" value="" id="[prefix]-upload-remote-altimage">
                        <div class="invalid-feedback">{$LANG->getModule('upload_alt_note')}</div>
                    </div>
                    <div class="mb-3{if empty($UPLOAD_LOGO)} d-none{/if}">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" name="autologo" id="[prefix]-upload-remote-autologo">
                            <label class="form-check-label" for="[prefix]-upload-remote-autologo">{$LANG->getModule('autologo_for_upload')}</label>
                        </div>
                    </div>
                    <div class="hstack justify-content-end gap-2">
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-cloud-arrow-up"></i> {$LANG->getGlobal('submit')}</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="fmd"><i class="fa-solid fa-xmark text-danger"></i> {$LANG->getGlobal('cancel')}</button>
                    </div>
                    <input type="hidden" name="path" value="">
                </form>
            </div>
        </div>
    </div>
</div>
