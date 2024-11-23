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
<div class="fmd fade" data-dialog="preview" id="[prefix]-preview" tabindex="-1" aria-labelledby="[prefix]-preview-label" aria-hidden="true">
    <div class="fmd-dialog">
        <div class="fmd-content">
            <div class="fmd-header">
                <div class="fmd-title text-truncate fs-5 fw-medium" id="[prefix]-preview-label">{$LANG->getModule('preview')}</div>
                <button type="button" class="btn-close" data-dismiss="fmd" aria-label="{$LANG->getGlobal('close')}"></button>
            </div>
            <div class="fmd-body fmd-preview">
                <div class="text-center">
                    <div class="h2 mb-3 fw-medium" data-toggle="alt"></div>
                    <div class="mb-2 position-relative img-thumb-outer">
                        <img data-toggle="preview-zoom-in" src="{$smarty.const.ASSETS_STATIC_URL}/images/pix.gif" data-pix="{$smarty.const.ASSETS_STATIC_URL}/images/pix.gif" class="img-fluid img-thumb" alt="">
                        <div class="zoom-img" data-toggle="zoom-ctn">
                            <a href="#" data-toggle="preview-zoom-out" class="zoom-out link-light p-3 pe-4" aria-label="{$LANG->getGlobal('close')}"><i class="fa-solid fa-arrows-to-dot fa-lg"></i></a>
                            <img data-toggle="orig-img" src="{$smarty.const.ASSETS_STATIC_URL}/images/pix.gif" data-pix="{$smarty.const.ASSETS_STATIC_URL}/images/pix.gif" class="orig-img" alt="">
                        </div>
                    </div>
                    <div class="text-break mb-1 fw-medium" data-toggle="filename"></div>
                    <div class="mb-1">{$LANG->getModule('upload_size')}: <span data-toggle="size"></span></div>
                    <div class="mb-2">{$LANG->getModule('pubdate')}: <span data-toggle="mtime"></span></div>
                </div>
                <div class="mb-3">
                    <label for="[prefix]-preview-relative" class="form-label fw-medium">{$LANG->getModule('filerelativepath')}:</label>
                    <div class="input-group">
                        <input class="form-control" name="relative" type="text" value="" id="[prefix]-preview-relative" aria-describedby="[prefix]-preview-relative-btn">
                        <button class="btn btn-primary" type="button" id="[prefix]-preview-relative-btn" data-toggle="btn-relative" data-clipboard-target="#[prefix]-preview-relative">{$LANG->getGlobal('copy')}</button>
                    </div>
                </div>
                <div>
                    <label for="[prefix]-preview-absolute" class="form-label fw-medium">{$LANG->getModule('fileabsolutepath')}:</label>
                    <div class="input-group">
                        <input class="form-control" name="absolute" type="text" value="" id="[prefix]-preview-absolute" aria-describedby="[prefix]-preview-absolute-btn">
                        <button class="btn btn-primary" type="button" id="[prefix]-preview-absolute-btn" data-toggle="btn-absolute" data-clipboard-target="#[prefix]-preview-absolute">{$LANG->getGlobal('copy')}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="fmd fade" data-dialog="renamefile" id="[prefix]-renamefile" tabindex="-1" aria-labelledby="[prefix]-renamefile-label" aria-hidden="true">
    <div class="fmd-dialog">
        <div class="fmd-content">
            <div class="fmd-header">
                <div class="fmd-title text-truncate fs-5 fw-medium" id="[prefix]-renamefile-label">{$LANG->getModule('rename')}</div>
                <button type="button" class="btn-close" data-dismiss="fmd" aria-label="{$LANG->getGlobal('close')}"></button>
            </div>
            <div class="fmd-body">
                <form method="post" data-mode="ajform" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=renameimg" novalidate>
                    <div class="mb-2">
                        <div class="fw-medium fw-5 dialog-text" data-toggle="name"></div>
                    </div>
                    <div class="mb-3">
                        <label for="[prefix]-renamefile-name" class="form-label">{$LANG->getModule('rename_newname')} <span class="text-danger">(*)</span>:</label>
                        <div class="input-group">
                            <input class="form-control dialog-val" name="newname" type="text" value="" id="[prefix]-renamefile-name" aria-describedby="[prefix]-renamefile-name-text" autocomplete="off">
                            <div class="input-group-text dialog-text" data-toggle="ext" id="[prefix]-renamefile-name-text"></div>
                        </div>
                        <div class="invalid-feedback">{$LANG->getModule('rename_noname')}</div>
                    </div>
                    <div class="mb-3">
                        <label for="[prefix]-renamefile-alt" class="form-label">{$LANG->getModule('altimage')} <span class="text-danger">(*)</span>:</label>
                        <input class="form-control dialog-val" name="newalt" type="text" maxlength="200" value="" id="[prefix]-renamefile-alt">
                        <div class="invalid-feedback">{$LANG->getModule('upload_alt_note')}</div>
                    </div>
                    <div class="hstack justify-content-end gap-2">
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> {$LANG->getGlobal('submit')}</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="fmd"><i class="fa-solid fa-xmark text-danger"></i> {$LANG->getGlobal('cancel')}</button>
                    </div>
                    <input type="hidden" name="path" value="" class="dialog-val">
                    <input type="hidden" name="file" value="" class="dialog-val">
                    <input type="hidden" name="checkss" value="" class="dialog-val">
                </form>
            </div>
        </div>
    </div>
</div>
<div class="fmd fade" data-dialog="filter" id="[prefix]-filter" tabindex="-1" aria-labelledby="[prefix]-filter-label" aria-hidden="true">
    <div class="fmd-dialog">
        <div class="fmd-content">
            <div class="fmd-header">
                <div class="fmd-title text-truncate fs-5 fw-medium" id="[prefix]-filter-label">{$LANG->getModule('filter_title')}</div>
                <button type="button" class="btn-close" data-dismiss="fmd" aria-label="{$LANG->getGlobal('close')}"></button>
            </div>
            <div class="fmd-body">
                <form method="post" action="" novalidate>
                    <div class="mb-3">
                        <select class="form-select" name="type"></select>
                    </div>
                    <div class="mb-3">
                        <select class="form-select" name="author"></select>
                    </div>
                    <div class="mb-3">
                        <select class="form-select" name="order"></select>
                    </div>
                    <div class="hstack justify-content-end gap-2">
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-filter"></i> {$LANG->getGlobal('submit')}</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="fmd"><i class="fa-solid fa-xmark text-danger"></i> {$LANG->getGlobal('cancel')}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
