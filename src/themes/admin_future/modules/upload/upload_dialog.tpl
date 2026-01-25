{* Dialog tìm kiếm *}
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
{* Dialog upload file từ internet *}
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
{* Dialog xem chi tiết file *}
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
{* Dialog đổi tên file *}
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
{* Dialog lọc theo loại, tác giả và sắp xếp (chế độ giao diện mobile) *}
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
{* Dialog tạo thư mục con *}
<div class="fmd fade" data-dialog="createfolder" id="[prefix]-createfolder" tabindex="-1" aria-labelledby="[prefix]-createfolder-label" aria-hidden="true">
    <div class="fmd-dialog">
        <div class="fmd-content">
            <div class="fmd-header">
                <div class="fmd-title text-truncate fs-5 fw-medium" id="[prefix]-createfolder-label">{$LANG->getModule('createfolder')}</div>
                <button type="button" class="btn-close" data-dismiss="fmd" aria-label="{$LANG->getGlobal('close')}"></button>
            </div>
            <div class="fmd-body">
                <form method="post" data-mode="ajform" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=createfolder" novalidate>
                    <div class="mb-3">
                        <label for="[prefix]-createfolder-newname" class="form-label">{$LANG->getModule('foldername')} <span class="text-danger">(*)</span>:</label>
                        <input class="form-control dialog-val" name="newname" type="text" value="" id="[prefix]-createfolder-newname" autocomplete="off">
                        <div class="invalid-feedback">{$LANG->getGlobal('required_invalid')}</div>
                    </div>
                    <div class="hstack justify-content-end gap-2">
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> {$LANG->getGlobal('submit')}</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="fmd"><i class="fa-solid fa-xmark text-danger"></i> {$LANG->getGlobal('cancel')}</button>
                    </div>
                    <input type="hidden" name="path" value="" class="dialog-val">
                    <input type="hidden" name="checkss" value="" class="dialog-val">
                </form>
            </div>
        </div>
    </div>
</div>
{* Dialog đổi tên thư mục *}
<div class="fmd fade" data-dialog="renamefolder" id="[prefix]-renamefolder" tabindex="-1" aria-labelledby="[prefix]-renamefolder-label" aria-hidden="true">
    <div class="fmd-dialog">
        <div class="fmd-content">
            <div class="fmd-header">
                <div class="fmd-title text-truncate fs-5 fw-medium" id="[prefix]-renamefolder-label">{$LANG->getModule('renamefolder')}</div>
                <button type="button" class="btn-close" data-dismiss="fmd" aria-label="{$LANG->getGlobal('close')}"></button>
            </div>
            <div class="fmd-body">
                <form method="post" data-mode="ajform" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=renamefolder" novalidate>
                    <div class="mb-3">
                        <label for="[prefix]-renamefolder-newname" class="form-label">{$LANG->getModule('rename_newname')} <span class="text-danger">(*)</span>:</label>
                        <input class="form-control dialog-val" name="newname" type="text" value="" id="[prefix]-renamefolder-newname" autocomplete="off">
                        <div class="invalid-feedback">{$LANG->getGlobal('required_invalid')}</div>
                    </div>
                    <div class="hstack justify-content-end gap-2">
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> {$LANG->getGlobal('submit')}</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="fmd"><i class="fa-solid fa-xmark text-danger"></i> {$LANG->getGlobal('cancel')}</button>
                    </div>
                    <input type="hidden" name="path" value="" class="dialog-val">
                    <input type="hidden" name="checkss" value="" class="dialog-val">
                </form>
            </div>
        </div>
    </div>
</div>
{* Dialog tạo lại ảnh thumb *}
<div class="fmd fade" data-dialog="rethumb" id="[prefix]-rethumb" tabindex="-1" aria-labelledby="[prefix]-rethumb-label" aria-hidden="true">
    <div class="fmd-dialog">
        <div class="fmd-content">
            <div class="fmd-header">
                <div class="fmd-title text-truncate fs-5 fw-medium" id="[prefix]-rethumb-label">{$LANG->getModule('recreatethumb')}</div>
                <button type="button" class="btn-close" data-dismiss="fmd" aria-label="{$LANG->getGlobal('close')}"></button>
            </div>
            <div class="fmd-body">
                <div class="alert alert-info" data-toggle="note" role="alert">{$LANG->getModule('recreatethumb_note')}</div>
                <div class="text-center d-none" data-toggle="load">
                    <div class="mb-2">
                        <i class="fa-solid fa-spinner fa-spin-pulse fa-3x"></i>
                    </div>
                    {$LANG->getModule('waiting')}
                </div>
                <div class="d-none" data-toggle="progress">
                    <div data-toggle="progress-aria" class="progress mb-3" role="progressbar" aria-label="{$LANG->getModule('recreatethumb')}" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                        <div data-toggle="progress-val" class="progress-bar progress-bar-striped progress-bar-animated dialog-text" style="width: 0%;"></div>
                    </div>
                    <div class="text-center">
                        {$LANG->getModule('recreatethumb')}: <strong class="dialog-text" data-toggle="current"></strong>/<strong class="dialog-text" data-toggle="total"></strong> file(s)
                    </div>
                </div>
                <div class="d-none text-center alert alert-success mb-0" role="alert" data-toggle="finish">
                    {$LANG->getModule('recreatethumb_result')} <strong class="dialog-text" data-toggle="total"></strong> file(s)
                </div>
                <div class="hstack justify-content-end gap-2" data-toggle="btns">
                    <button type="button" class="btn btn-primary" data-toggle="rethumb-btn-submit"><i class="fa-solid fa-play"></i> {$LANG->getGlobal('submit')}</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="fmd"><i class="fa-solid fa-xmark text-danger"></i> {$LANG->getGlobal('cancel')}</button>
                </div>
            </div>
        </div>
    </div>
</div>
{* Dialog di chuyển file *}
<div class="fmd fade" data-dialog="move" id="[prefix]-move" tabindex="-1" aria-labelledby="[prefix]-move-label" aria-hidden="true">
    <div class="fmd-dialog">
        <div class="fmd-content">
            <div class="fmd-header">
                <div class="fmd-title text-truncate fs-5 fw-medium" id="[prefix]-move-label">{$LANG->getModule('move')}</div>
                <button type="button" class="btn-close" data-dismiss="fmd" aria-label="{$LANG->getGlobal('close')}"></button>
            </div>
            <div class="fmd-body">
                <form method="post" data-mode="ajform" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=moveimg" novalidate>
                    <div class="mb-2">
                        <div class="fw-medium fw-5 dialog-text text-break" data-toggle="name"></div>
                    </div>
                    <div class="mb-3">
                        <label for="[prefix]-move-newpath" class="form-label">{$LANG->getModule('movefolder')} <span class="text-danger">(*)</span>:</label>
                        <select class="form-select dialog-html" data-toggle="select2" name="newpath" id="[prefix]-move-newpath"></select>
                        <div class="invalid-feedback">{$LANG->getGlobal('required_invalid')}</div>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="mirror" value="1" id="[prefix]-move-mirror">
                        <label class="form-check-label" for="[prefix]-move-mirror">{$LANG->getModule('mirrorFile')}</label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="gonewpath" value="1" id="[prefix]-move-gonewpath">
                        <label class="form-check-label" for="[prefix]-move-gonewpath">{$LANG->getModule('goNewPath')}</label>
                    </div>
                    <div class="hstack justify-content-end gap-2">
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> {$LANG->getGlobal('submit')}</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="fmd"><i class="fa-solid fa-xmark text-danger"></i> {$LANG->getGlobal('cancel')}</button>
                    </div>
                    <input type="hidden" name="files" value="" class="dialog-val">
                    <input type="hidden" name="checkss" value="" class="dialog-val">
                </form>
            </div>
        </div>
    </div>
</div>
{* Dialog giảm chất lượng ảnh *}
<div class="fmd fade" data-dialog="qualitychange" id="[prefix]-qualitychange" tabindex="-1" aria-labelledby="[prefix]-qualitychange-label" aria-hidden="true">
    <div class="fmd-dialog">
        <div class="fmd-content">
            <div class="fmd-header">
                <div class="fmd-title text-truncate fs-5 fw-medium" id="[prefix]-qualitychange-label">{$LANG->getModule('qualitychange')}</div>
                <button type="button" class="btn-close" data-dismiss="fmd" aria-label="{$LANG->getGlobal('close')}"></button>
            </div>
            <div class="fmd-body fmd-preview">
                <form method="post" data-mode="ajform" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=qualitychange" novalidate>
                    <div class="text-center">
                        <div class="mb-2 position-relative img-thumb-outer">
                            <img data-toggle="preview-zoom-in" src="{$smarty.const.ASSETS_STATIC_URL}/images/pix.gif" data-pix="{$smarty.const.ASSETS_STATIC_URL}/images/pix.gif" class="img-fluid img-thumb is-img" alt="">
                            <div class="zoom-img" data-toggle="zoom-ctn">
                                <a href="#" data-toggle="preview-zoom-out" class="zoom-out link-light p-3 pe-4" aria-label="{$LANG->getGlobal('close')}"><i class="fa-solid fa-arrows-to-dot fa-lg"></i></a>
                                <img data-toggle="orig-img" src="{$smarty.const.ASSETS_STATIC_URL}/images/pix.gif" data-pix="{$smarty.const.ASSETS_STATIC_URL}/images/pix.gif" class="orig-img" alt="">
                            </div>
                        </div>
                        <div class="mb-1">{$LANG->getModule('original_filesize')}: <span class="dialog-text" data-toggle="sizeoriginal"></span></div>
                        <div class="mb-2">{$LANG->getModule('filesize')}: <span class="dialog-text" data-toggle="sizenew"></span></div>
                    </div>
                    <div class="mb-3 hstack align-items-center justify-content-center gap-2 flex-wrap">
                        <label for="[prefix]-qualitychange-quality">{$LANG->getModule('quality')}</label>
                        <select class="dialog-select form-select w-auto mw-100" data-toggle="qualitychangeopt" name="quality" id="[prefix]-qualitychange-quality">
                            <option value="">{$LANG->getModule('original_image')}</option>
                            <option value="100">100</option>
                            <option value="95">95</option>
                            <option value="90">90</option>
                            <option value="85">85</option>
                            <option value="80">80</option>
                            <option value="75">75</option>
                            <option value="70">70</option>
                            <option value="65">65</option>
                            <option value="60">60</option>
                            <option value="55">55</option>
                            <option value="50">50</option>
                            <option value="45">45</option>
                            <option value="40">40</option>
                            <option value="35">35</option>
                            <option value="30">30</option>
                            <option value="25">25</option>
                            <option value="20">20</option>
                            <option value="15">15</option>
                            <option value="10">10</option>
                        </select>
                    </div>
                    <div class="hstack justify-content-end gap-2 border-top pt-3">
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> {$LANG->getGlobal('submit')}</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="fmd"><i class="fa-solid fa-xmark text-danger"></i> {$LANG->getGlobal('cancel')}</button>
                    </div>
                    <input type="hidden" name="img" value="" class="dialog-val">
                    <input type="hidden" name="path" value="" class="dialog-val">
                    <input type="hidden" name="checkss" value="" class="dialog-val">
                </form>
            </div>
        </div>
    </div>
</div>
{* Dialog thêm logo *}
<div class="fmd fade" data-dialog="addlogo" id="[prefix]-addlogo" tabindex="-1" aria-labelledby="[prefix]-addlogo-label" aria-hidden="true">
    <div class="fmd-dialog">
        <div class="fmd-content">
            <div class="fmd-header">
                <div class="fmd-title text-truncate fs-5 fw-medium" id="[prefix]-addlogo-label">{$LANG->getModule('addlogo')}</div>
                <button type="button" class="btn-close" data-dismiss="fmd" aria-label="{$LANG->getGlobal('close')}"></button>
            </div>
            <div class="fmd-body">
                <form method="post" data-mode="ajform" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=addlogo" novalidate>
                    <div class="mb-3 px-3">
                        <div class="fmd-addlogo" data-toggle="logo-ctn">
                            <div class="inner" data-toggle="logo-area">
                                <img data-toggle="image" src="{$smarty.const.ASSETS_STATIC_URL}/images/pix.gif" data-pix="{$smarty.const.ASSETS_STATIC_URL}/images/pix.gif" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 hstack align-items-center justify-content-center gap-2 flex-wrap">
                        <span>X</span><input type="text" readonly name="x" value="" class="form-control dialog-val fmd-addlogo-ipt">
                        <span>Y</span><input type="text" readonly name="y" value="" class="form-control dialog-val fmd-addlogo-ipt">
                        <span>W</span><input type="text" readonly name="w" value="" class="form-control dialog-val fmd-addlogo-ipt">
                        <span>H</span><input type="text" readonly name="h" value="" class="form-control dialog-val fmd-addlogo-ipt">
                    </div>
                    <div class="hstack justify-content-end gap-2 border-top pt-3">
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> {$LANG->getGlobal('submit')}</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="fmd"><i class="fa-solid fa-xmark text-danger"></i> {$LANG->getGlobal('cancel')}</button>
                    </div>
                    <input type="hidden" name="file" value="" class="dialog-val">
                    <input type="hidden" name="path" value="" class="dialog-val">
                    <input type="hidden" name="checkss" value="" class="dialog-val">
                </form>
            </div>
        </div>
    </div>
</div>
{* Dialog cắt ảnh *}
<div class="fmd fade" data-dialog="cropfile" id="[prefix]-cropfile" tabindex="-1" aria-labelledby="[prefix]-cropfile-label" aria-hidden="true">
    <div class="fmd-dialog">
        <div class="fmd-content">
            <div class="fmd-header">
                <div class="fmd-title text-truncate fs-5 fw-medium" id="[prefix]-cropfile-label">{$LANG->getModule('crop')}</div>
                <button type="button" class="btn-close" data-dismiss="fmd" aria-label="{$LANG->getGlobal('close')}"></button>
            </div>
            <div class="fmd-body">
                <form method="post" data-mode="ajform" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=cropimg" novalidate>
                    <div class="mb-3 px-3">
                        <div class="fmd-addlogo" data-toggle="logo-ctn">
                            <div class="inner" data-toggle="logo-area">
                                <img data-toggle="image" src="{$smarty.const.ASSETS_STATIC_URL}/images/pix.gif" data-pix="{$smarty.const.ASSETS_STATIC_URL}/images/pix.gif" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="mb-2 hstack align-items-center justify-content-center gap-2 flex-wrap">
                        <span>X</span><input type="text" readonly name="x" value="" class="form-control dialog-val fmd-addlogo-ipt">
                        <span>Y</span><input type="text" readonly name="y" value="" class="form-control dialog-val fmd-addlogo-ipt">
                        <span>W</span><input type="text" readonly name="w" value="" class="form-control dialog-val fmd-addlogo-ipt">
                        <span>H</span><input type="text" readonly name="h" value="" class="form-control dialog-val fmd-addlogo-ipt">
                    </div>
                    <div class="mb-3 d-flex align-items-center justify-content-center">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="k" value="1" id="[prefix]-cropfile-k">
                            <label class="form-check-label" for="[prefix]-cropfile-k">{$LANG->getModule('crop_keep_original')}</label>
                        </div>
                    </div>
                    <div class="hstack justify-content-end gap-2 border-top pt-3">
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> {$LANG->getGlobal('submit')}</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="fmd"><i class="fa-solid fa-xmark text-danger"></i> {$LANG->getGlobal('cancel')}</button>
                    </div>
                    <input type="hidden" name="file" value="" class="dialog-val">
                    <input type="hidden" name="path" value="" class="dialog-val">
                    <input type="hidden" name="checkss" value="" class="dialog-val">
                </form>
            </div>
        </div>
    </div>
</div>
{* Dialog công cụ ảnh *}
<div class="fmd fade" data-dialog="imgcreate" id="[prefix]-imgcreate" tabindex="-1" aria-labelledby="[prefix]-imgcreate-label" aria-hidden="true">
    <div class="fmd-dialog">
        <div class="fmd-content">
            <div class="fmd-header">
                <div class="fmd-title text-truncate fs-5 fw-medium" id="[prefix]-imgcreate-label">{$LANG->getModule('upload_createimage')}</div>
                <button type="button" class="btn-close" data-dismiss="fmd" aria-label="{$LANG->getGlobal('close')}"></button>
            </div>
            <div class="fmd-body">
                <form method="post" data-mode="ajform" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=createimg" novalidate>
                    <div class="text-center mb-1 fw-medium text-break fs-5 dialog-text" data-toggle="name"></div>
                    <div class="text-center mb-2">{$LANG->getModule('origSize')}: <span class="dialog-text" data-toggle="ogrisize"></span></div>
                    <div class="mb-3 px-2">
                        <div class="fmd-imgcreate-img">
                            <div class="inner">
                                <img data-toggle="image" src="{$smarty.const.ASSETS_STATIC_URL}/images/pix.gif" data-pix="{$smarty.const.ASSETS_STATIC_URL}/images/pix.gif" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="mb-2 hstack align-items-center justify-content-center gap-2 flex-wrap">
                        <span>{$LANG->getModule('upload_width')}</span><input type="number" name="width" data-toggle="imgcreate-val" data-type="w" value="" class="form-control dialog-val fw-75" autocomplete="off">
                        <span>{$LANG->getModule('upload_height')}</span><input type="number" name="height" data-toggle="imgcreate-val" data-type="h" value="" class="form-control dialog-val fw-75" autocomplete="off">
                    </div>
                    <div class="mb-2 text-center">
                        <div class="form-text dialog-text" data-toggle="limitsize"></div>
                    </div>
                    <div class="hstack justify-content-end gap-2 border-top pt-3">
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> {$LANG->getGlobal('submit')}</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="fmd"><i class="fa-solid fa-xmark text-danger"></i> {$LANG->getGlobal('cancel')}</button>
                    </div>
                    <input type="hidden" name="img" value="" class="dialog-val">
                    <input type="hidden" name="path" value="" class="dialog-val">
                    <input type="hidden" name="checkss" value="" class="dialog-val">
                </form>
            </div>
        </div>
    </div>
</div>
{* Dialog xoay ảnh *}
<div class="fmd fade" data-dialog="rotatefile" id="[prefix]-rotatefile" tabindex="-1" aria-labelledby="[prefix]-rotatefile-label" aria-hidden="true">
    <div class="fmd-dialog">
        <div class="fmd-content">
            <div class="fmd-header">
                <div class="fmd-title text-truncate fs-5 fw-medium" id="[prefix]-rotatefile-label">{$LANG->getModule('rotate')}</div>
                <button type="button" class="btn-close" data-dismiss="fmd" aria-label="{$LANG->getGlobal('close')}"></button>
            </div>
            <div class="fmd-body">
                <form method="post" data-mode="ajform" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=rotateimg" novalidate>
                    <div class="text-center mb-1 fw-medium text-break fs-5 dialog-text" data-toggle="name"></div>
                    <div class="mb-2 px-2">
                        <div class="fmd-imgcreate-img">
                            <div class="inner" data-toggle="display">
                                <img data-toggle="image" src="{$smarty.const.ASSETS_STATIC_URL}/images/pix.gif" data-pix="{$smarty.const.ASSETS_STATIC_URL}/images/pix.gif" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="mb-2 hstack align-items-center justify-content-center gap-2 flex-wrap">
                        <input type="number" min="0" max="359" name="direction" value="0" data-toggle="rotatefile-direction" class="form-control dialog-zero-val fw-75" aria-label="{$LANG->getModule('rotate_direction')}">
                        <button type="button" class="btn btn-secondary" data-toggle="rotatefile-btn90" data-type="l" aria-label="{$LANG->getModule('rotate_left90')}"><i class="fa-solid fa-rotate-left"></i> 90</button>
                        <button type="button" class="btn btn-secondary" data-toggle="rotatefile-btn" data-type="l" aria-label="{$LANG->getModule('rotate_left')}"><i class="fa-solid fa-rotate-left pe-none"></i></button>
                        <button type="button" class="btn btn-secondary" data-toggle="rotatefile-btn" data-type="r" aria-label="{$LANG->getModule('rotate_right')}"><i class="fa-solid fa-rotate-right pe-none"></i></button>
                        <button type="button" class="btn btn-secondary" data-toggle="rotatefile-btn90" data-type="r" aria-label="{$LANG->getModule('rotate_right90')}"><i class="fa-solid fa-rotate-right"></i> 90</button>
                    </div>
                    <div class="hstack justify-content-end gap-2 border-top pt-3">
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> {$LANG->getGlobal('submit')}</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="fmd"><i class="fa-solid fa-xmark text-danger"></i> {$LANG->getGlobal('cancel')}</button>
                    </div>
                    <input type="hidden" name="file" value="" class="dialog-val">
                    <input type="hidden" name="path" value="" class="dialog-val">
                    <input type="hidden" name="checkss" value="" class="dialog-val">
                </form>
            </div>
        </div>
    </div>
</div>
