{if not $POPUP}
<div class="filemanager-wraper filemanager-inline" id="upload-container"></div>
<script type="text/javascript" src="{$NV_BASE_ADMINURL}index.php?{$NV_LANG_VARIABLE}={$NV_LANG_DATA}&amp;{$NV_NAME_VARIABLE}={$MODULE_NAME}&amp;js"></script>
<script>
$(document).on("nv.upload.ready", function() {
    $("#upload-container").nvstaticupload({
        modal: false,
        adminBaseUrl: '{$NV_BASE_ADMINURL}',
        path: '{$PATH}',
        currentpath: '{$CURRENTPATH}',
        type: '{$TYPE}',
        imgfile: '{$SELFILE}'
    });
});
</script>
{else}
<div class="card card-filemanager card-border-color card-border-color-primary" id="nv-filemanager">
    <div class="card-body">
        <div class="fm-container">
            <div class="fm-folders">
                <div class="fm-folders-wrap">
                    <div class="fm-folders-head">
                        <i class="icon fas fa-th-large"></i>{$LANG->get('mod_upload')}
                    </div>
                    <div class="fm-folders-body nv-scroller">
                        <div class="fm-folders-tree">
                            <span class="fm-folders-toggle" id="nv-filemanager-folder-btn-toggle"><i class="icon fas fa-folder-open"></i><span></span></span>
                            {* Danh sách thư mục sẽ ở đây *}
                            <ul id="nv-filemanager-folder"></ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="fm-contents">
                <div class="fm-contents-wrap">
                    <div class="fm-tools">
                        <div class="fm-tools-wrap">
                            <div class="btn-icon">
                                <div class="btn-group btn-space btn-upload">
                                    <button type="button" class="btn btn-primary">{$LANG->get('upload_file')}</button>
                                    <button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle"><span class="fas fa-chevron-down"></span></button>
                                    <div role="menu" class="dropdown-menu  dropdown-menu-right">
                                        <a href="#" class="dropdown-item">{$LANG->get('upload_mode_remote')}</a>
                                    </div>
                                </div>
                            </div>
                            <ul class="tools">
                                <li class="iconp-search"><a href="#nv-filemanager-form-search" data-toggle="modal" data-backdrop="static" class="icon-search nv-filemanager-btn-search"><i class="fas fa-search"></i></a></li>
                                <li class="iconp-cview"><a href="#" class="icon-cview" id="nv-filemanager-btn-change-viewmode"><i class="fas"></i></a></li>
                                <li class="iconp-reload"><a href="#" class="icon-reload" id="nv-filemanager-btn-reload" title="{$LANG->get('refresh')}"><i class="fas fa-sync-alt"></i></a></li>
                                <li class="iconp-collapse"><a href="#" class="icon-collapse" id="nv-filemanager-btn-toggle-form-filter"><i class="fas fa-cog"></i></i></a></li>
                            </ul>
                            <div class="form" id="nv-filemanager-form-filter">
                                <div class="btn-group btn-space">
                                    <button type="button" data-toggle="dropdown" class="btn btn-secondary dropdown-toggle" data-value="all" id="nv-filemanager-ctn-filter-type"><span class="text">{$LANG->get('type_file')}</span> <span class="icon-dropdown fas fa-chevron-down"></span></button>
                                    <div role="menu" class="dropdown-menu">
                                        <a href="#" class="dropdown-item nv-filemanager-btn-filter-type" data-value="all">{$LANG->get('type_file')}</a>
                                        <a href="#" class="dropdown-item nv-filemanager-btn-filter-type" data-value="image">{$LANG->get('type_image')}</a>
                                        <a href="#" class="dropdown-item nv-filemanager-btn-filter-type" data-value="flash">{$LANG->get('type_flash')}</a>
                                    </div>
                                </div>
                                <div class="btn-group btn-space">
                                    <button type="button" data-toggle="dropdown" class="btn btn-secondary dropdown-toggle" data-value="all" id="nv-filemanager-ctn-filter-user"><span class="text">{$LANG->get('author0')}</span> <span class="icon-dropdown fas fa-chevron-down"></span></button>
                                    <div role="menu" class="dropdown-menu">
                                        <a href="#" class="dropdown-item nv-filemanager-btn-filter-user" data-value="all">{$LANG->get('author0')}</a>
                                        <a href="#" class="dropdown-item nv-filemanager-btn-filter-user" data-value="me">{$LANG->get('author1')}</a>
                                    </div>
                                </div>
                                <div class="btn-group btn-space">
                                    <button type="button" data-toggle="dropdown" class="btn btn-secondary dropdown-toggle" data-value="newest" id="nv-filemanager-ctn-filter-sort"><span class="text">{$LANG->get('order0')}</span> <span class="icon-dropdown fas fa-chevron-down"></span></button>
                                    <div role="menu" class="dropdown-menu">
                                        <a href="#" class="dropdown-item nv-filemanager-btn-filter-sort" data-value="newest">{$LANG->get('order0')}</a>
                                        <a href="#" class="dropdown-item nv-filemanager-btn-filter-sort" data-value="oldest">{$LANG->get('order1')}</a>
                                        <a href="#" class="dropdown-item nv-filemanager-btn-filter-sort" data-value="name">{$LANG->get('order2')}</a>
                                    </div>
                                </div>
                                <a href="#nv-filemanager-form-search" class="icon-search nv-filemanager-btn-search" data-toggle="modal" data-backdrop="static" title="{$LANG->get('search')}"><i class="fas fa-search"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="fm-files nv-scroller">
                        <div class="files-container" id="nv-filemanager-files-container"></div>
                    </div>
                    <nav class="fm-pagination d-none" id="nv-filemanager-files-nav"></nav>
                </div>
            </div>
        </div>
    </div>
    <div class="filemanager-dropzone" id="filemanager-dropzone-ctn">
        <div class="dropzone-bg"></div>
        <div class="dropzone-ctn">
            <div class="spacer"></div>
            <div class="body">
                <div class="icon"><i class="fas fa-cloud-upload-alt"></i></div>
                <h2>Kéo và thả file vào đây để tải lên</h2>
            </div>
            <div class="spacer"></div>
        </div>
        <div class="dropzone-mark" id="filemanager-dropzone-area"></div>
    </div>
    <div class="filemanager-loader">
        <div><i class="fas fa-spinner fa-pulse"></i></div>
    </div>
</div>
{* Các thành phần ẩn ẩn để lưu các giá trị *}
<div class="d-none" id="fmMainCurrentFileURL" data-value=""></div>
<div class="d-none" id="fmMainCurrentFile" data-value=""></div>
<div class="d-none" id="fmMainArea" data-value="{$AREA}"></div>
<div class="d-none" id="fmMainAlt" data-value="{$ALT}"></div>
<div class="d-none" id="fmMainLogo" data-value="{$UPLOAD_LOGO}"></div>
<div class="d-none" id="fmMainLogoConfig" data-value="{$UPLOAD_LOGO_CONFIG}"></div>
<div class="d-none" id="fmCKEditorFuncNum" data-value="{$FUNNUM}"></div>
{* Tìm kiếm *}
<div id="nv-filemanager-form-search" tabindex="-1" role="dialog" class="modal colored-header colored-header-primary inFileManagerModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form>
                <div class="modal-header modal-header-colored">
                    <h3 class="modal-title">{$LANG->get('search')}</h3>
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close md-close"><span class="fas fa-times"></span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>{$LANG->get('searchdir')}:</label>
                        <select class="form-control" name="searchPath"></select>
                    </div>
                    <div class="form-group mb-0">
                        <label>{$LANG->get('searchkey')}.</label>
                        <input type="text" class="form-control" name="q" value="">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-secondary md-close">{$LANG->get('cancel')}</button>
                    <button type="submit" name="search" value="search" class="btn btn-primary md-close">{$LANG->get('search')}</button>
                </div>
            </form>
        </div>
    </div>
</div>
{* Form tạo thư mục mới *}
<div id="nv-filemanager-form-newfolder" tabindex="-1" role="dialog" data-backdrop="static" class="modal colored-header colored-header-primary inFileManagerModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form>
                <div class="modal-header modal-header-colored">
                    <h3 class="modal-title">{$LANG->get('createfolder')}</h3>
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close md-close"><span class="fas fa-times"></span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-0">
                        <label>{$LANG->get('foldername')} <i class="text-danger">(*)</i></label>
                        <input type="text" placeholder="{$LANG->get('foldername')}" class="form-control" name="foldername" value="">
                        <i class="form-text text-muted">{$LANG->get('foldernamerule')}.</i>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-secondary md-close">{$LANG->get('cancel')}</button>
                    <button type="submit" class="btn btn-primary md-close">{$LANG->get('submit')}</button>
                </div>
            </form>
        </div>
    </div>
</div>
{* Form đổi tên thư mục *}
<div id="nv-filemanager-form-renamefolder" tabindex="-1" role="dialog" data-backdrop="static" class="modal colored-header colored-header-primary inFileManagerModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form>
                <div class="modal-header modal-header-colored">
                    <h3 class="modal-title">{$LANG->get('renamefolder')}</h3>
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close md-close"><span class="fas fa-times"></span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-0">
                        <label>{$LANG->get('rename_newname')} <i class="text-danger">(*)</i></label>
                        <input type="text" placeholder="{$LANG->get('foldername')}" class="form-control" name="foldername" value="">
                        <i class="form-text text-muted">{$LANG->get('foldernamerule')}.</i>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-secondary md-close">{$LANG->get('cancel')}</button>
                    <button type="submit" class="btn btn-primary md-close">{$LANG->get('submit')}</button>
                </div>
            </form>
        </div>
    </div>
</div>
{* Form tạo lại ảnh thumb *}
<div id="nv-filemanager-form-recreatthumb" tabindex="-1" role="dialog" data-backdrop="static" class="modal colored-header colored-header-primary inFileManagerModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form>
                <div class="modal-header modal-header-colored">
                    <h3 class="modal-title">{$LANG->get('recreatethumb')}</h3>
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close md-close"><span class="fas fa-times"></span></button>
                </div>
                <div class="modal-body">
                    <div data-toggle="welcome" class="d-none">{$LANG->get('recreatethumb_note')}</div>
                    <div data-toggle="load" class="text-center d-none">
                        <div class="mb-1"><i class="fas fa-2x fa-spinner fa-pulse"></i></div>
                        {$LANG->get('waiting')}
                    </div>
                    <div data-toggle="resultwrap" class="d-none">
                        <div class="progress mb-2">
                            <div role="progressbar" style="width: 70%" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" class="progress-bar bg-success progress-bar-striped progress-bar-animated">70%</div>
                        </div>
                        <div class="text-center">
                            {$LANG->get('recreatethumb')} <strong data-toggle="creatcurrent"></strong> / <strong data-toggle="creattotal"></strong> file.
                        </div>
                    </div>
                    <div data-toggle="resultall" class="text-center text-success d-none">
                        {$LANG->get('recreatethumb_result')} <strong data-toggle="resultnum"></strong> file.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-secondary md-close">{$LANG->get('cancel')}</button>
                    <button type="submit" class="btn btn-primary md-close">{$LANG->get('submit')}</button>
                </div>
            </form>
        </div>
    </div>
</div>
{* Form xem chi tiết file *}
<div id="nv-filemanager-form-previewfile" tabindex="-1" role="dialog" data-backdrop="static" class="modal colored-header colored-header-primary inFileManagerModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form>
                <div class="modal-header modal-header-colored">
                    <h3 class="modal-title">{$LANG->get('preview')}</h3>
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close md-close"><span class="fas fa-times"></span></button>
                </div>
                <div class="modal-body pt-4 pb-0">
                    <div class="text-center">
                        <h3 class="mt-0" data-toggle="alt"></h3>
                        <div class="mb-2" data-toggle="thumb"></div>
                        <p class="mb-1" data-toggle="name"></p>
                        <p class="mb-1" data-toggle="size"></p>
                        <p class="mb-1" data-toggle="mtime"></p>
                        <hr>
                    </div>
                    <div class="form-group">
                        <label for="FileRelativePath">{$LANG->get('filerelativepath')}.</label>
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control" id="FileRelativePath" value="">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button" data-clipboard-target="#FileRelativePath" id="FileRelativePathBtn" data-title="{$LANG->get('filepathcopied')}" data-placement="left" data-container="body" data-trigger="manual" data-animation="false"> <i class="fas fa-copy"></i> </button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-0">
                        <label for="FileAbsolutePath">{$LANG->get('fileabsolutepath')}.</label>
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control" id="FileAbsolutePath" value="">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button" data-clipboard-target="#FileAbsolutePath" id="FileAbsolutePathBtn" data-title="{$LANG->get('filepathcopied')}" data-placement="left" data-container="body" data-trigger="manual" data-animation="false"> <i class="fas fa-copy"></i> </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-secondary md-close">{$LANG->get('close')}</button>
                </div>
            </form>
        </div>
    </div>
</div>
{* Form đổi tên file *}
<div id="nv-filemanager-form-renamefile" tabindex="-1" role="dialog" data-backdrop="static" class="modal colored-header colored-header-primary inFileManagerModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form>
                <div class="modal-header modal-header-colored">
                    <h3 class="modal-title">{$LANG->get('rename')}</h3>
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close md-close"><span class="fas fa-times"></span></button>
                </div>
                <div class="modal-body">
                    <h3 data-toggle="orgfile" class="mt-0 text-center"></h3>
                    <div class="form-group">
                        <label>{$LANG->get('rename_newname')} <i class="text-danger">(*)</i></label>
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1"><input type="text" class="form-control" name="name" value=""></div>
                            <div class="pl-2 flex-shrink-0" data-toggle="ext"></div>
                        </div>
                    </div>
                    <div class="form-group mb-0">
                        <label>{$LANG->get('altimage')}</label>
                        <input type="text" class="form-control" name="alt" value="">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-secondary md-close">{$LANG->get('cancel')}</button>
                    <button type="submit" class="btn btn-primary md-close">{$LANG->get('submit')}</button>
                </div>
            </form>
        </div>
    </div>
</div>
{* Form di chuyển file *}
<div id="nv-filemanager-form-movefile" tabindex="-1" role="dialog" data-backdrop="static" class="modal colored-header colored-header-primary inFileManagerModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form>
                <div class="modal-header modal-header-colored">
                    <h3 class="modal-title">{$LANG->get('move')}</h3>
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close md-close"><span class="fas fa-times"></span></button>
                </div>
                <div class="modal-body">
                    <h3 data-toggle="orgpath" class="mt-0 text-center"></h3>
                    <div class="form-group">
                        <label>{$LANG->get('movefolder')} <i class="text-danger">(*)</i></label>
                        <select name="newPath" class="form-control"></select>
                    </div>
                    <div class="form-group mb-0">
                        <label class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox" value="1" name="mirrorFile"><span class="custom-control-label">{$LANG->get('mirrorFile')}</span>
                        </label>
                        <label class="custom-control custom-checkbox mb-0">
                            <input class="custom-control-input" type="checkbox" value="1" name="goNewPath"><span class="custom-control-label">{$LANG->get('goNewPath')}</span>
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-secondary md-close">{$LANG->get('cancel')}</button>
                    <button type="submit" class="btn btn-primary md-close">{$LANG->get('submit')}</button>
                </div>
            </form>
        </div>
    </div>
</div>
{* Form xoay ảnh *}
<div id="nv-filemanager-form-rotatefile" tabindex="-1" role="dialog" data-backdrop="static" class="modal colored-header colored-header-primary inFileManagerModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form>
                <div class="modal-header modal-header-colored">
                    <h3 class="modal-title">{$LANG->get('rotate')}</h3>
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close md-close"><span class="fas fa-times"></span></button>
                </div>
                <div class="modal-body pb-0">
                    <h3 data-toggle="name" class="mt-0 text-center"></h3>
                    <div data-toggle="img"></div>
                    <div class="d-flex justify-content-center">
                        <div class="form-inline">
                            <input type="text" class="form-control form-control-xs ml-1 btn-space" name="rorateDirection" value="0"/>
                            <button data-toggle="rleft90" type="button" class="btn btn-secondary btn-space">
                                <i class="fas fa-undo"></i> 90
                            </button>
                            <button data-toggle="rleft" type="button" class="btn btn-secondary btn-space">
                                <i class="fas fa-undo"></i>
                            </button>
                            <button data-toggle="rright" type="button" class="btn btn-secondary btn-space">
                                <i class="fas fa-redo"></i>
                            </button>
                            <button data-toggle="rright90" type="button" class="btn btn-secondary btn-space">
                                <i class="fas fa-redo"></i> 90
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-secondary md-close">{$LANG->get('cancel')}</button>
                    <button type="submit" class="btn btn-primary md-close">{$LANG->get('submit')}</button>
                </div>
            </form>
        </div>
    </div>
</div>
{* Form cắt ảnh *}
<div id="nv-filemanager-form-cropfile" tabindex="-1" role="dialog" data-backdrop="static" class="modal colored-header colored-header-primary inFileManagerModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form>
                <div class="modal-header modal-header-colored">
                    <h3 class="modal-title">{$LANG->get('crop')}</h3>
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close md-close"><span class="fas fa-times"></span></button>
                </div>
                <div class="modal-body pb-0 pt-4">
                    <p class="text-danger d-none" data-toggle="note"></p>
                    <div data-toggle="img" class="mb-2">{* Ảnh sẽ điền vào đây *}</div>
                    <div data-toggle="getw" class="d-flex justify-content-center">
                        <div>
                            <div class="form-inline">
                                X: <input type="text" name="x" value="" class="mx-1 form-control form-control-xs" readonly="readonly" style="width: 50px;">
                                Y: <input type="text" name="y" value="" class="mx-1 form-control form-control-xs" readonly="readonly" style="width: 50px;">
                                W: <input type="text" name="w" value="" class="mx-1 form-control form-control-xs" readonly="readonly" style="width: 50px;">
                                H: <input type="text" name="h" value="" class="mx-1 form-control form-control-xs" readonly="readonly" style="width: 50px;">
                            </div>
                            <hr class="my-2">
                            <label class="nv-checkbox custom-control custom-checkbox mb-0">
                                <input class="custom-control-input" type="checkbox" name="keeporg" value="1"><span class="custom-control-label">{$LANG->get('crop_keep_original')}</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-secondary md-close">{$LANG->get('cancel')}</button>
                    <button type="submit" class="btn btn-primary md-close">{$LANG->get('submit')}</button>
                </div>
            </form>
        </div>
    </div>
</div>
{* Form tạo ảnh mới *}
<div id="nv-filemanager-form-createimage" tabindex="-1" role="dialog" data-backdrop="static" class="modal colored-header colored-header-primary inFileManagerModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form>
                <div class="modal-header modal-header-colored">
                    <h3 class="modal-title">{$LANG->get('upload_createimage')}</h3>
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close md-close"><span class="fas fa-times"></span></button>
                </div>
                <div class="modal-body pb-0 pt-4">
                    <div class="row">
                        <div class="col-12 col-md-5">
                            <h4 class="mt-0 text-center">{$LANG->get('newSize')}</h4>
                            <div class="d-flex justify-content-center mb-2">
                                <div>
                                    <div class="form-inline">
                                        X:
                                        <input type="text" name="newWidth" maxlength="4" class="mx-1 form-control form-control-xs" style="width: 50px;" autocomplete="off">
                                        Y:
                                        <input type="text" name="newHeight" maxlength="4" class="mx-1 form-control form-control-xs" style="width: 50px;" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center mb-2">
                                <div>
                                    <button class="btn btn-secondary" type="button" name="prView"><i class="fas fa-search"></i> {$LANG->get('prView')}</button>
                                </div>
                            </div>
                            <div class="text-center" data-toggle="limit"></div>
                            <div class="text-center text-danger d-none" data-toggle="error">{* Thông tin lỗi *}</div>
                        </div>
                        <div class="col-12 col-md-7">
                            <h4 class="mt-0 text-center" data-toggle="imgname">{* Tên ảnh *}</h4>
                            <p class="text-center" data-toggle="orgsize">{* Kích thước gốc *}</p>
                            <div class="text-center">
                                <img data-toggle="img" src="" class="img-fluid">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-secondary md-close">{$LANG->get('cancel')}</button>
                    <button type="submit" class="btn btn-primary md-close">{$LANG->get('submit')}</button>
                </div>
            </form>
        </div>
    </div>
</div>
{* Form chèn logo vào ảnh *}
<div id="nv-filemanager-form-addlogo" tabindex="-1" role="dialog" data-backdrop="static" class="modal colored-header colored-header-primary inFileManagerModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form>
                <div class="modal-header modal-header-colored">
                    <h3 class="modal-title">{$LANG->get('addlogo')}</h3>
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close md-close"><span class="fas fa-times"></span></button>
                </div>
                <div class="modal-body pb-0 pt-4">
                    <p class="text-danger d-none" data-toggle="note"></p>
                    <div class="mb-2" data-toggle="img"></div>
                    <div data-toggle="btns" class="d-flex justify-content-center">
                        <div>
                            <div class="form-inline">
                                X: <input type="text" name="x" value="" class="mx-1 form-control form-control-xs" readonly="readonly" style="width: 50px;">
                                Y: <input type="text" name="y" value="" class="mx-1 form-control form-control-xs" readonly="readonly" style="width: 50px;">
                                W: <input type="text" name="w" value="" class="mx-1 form-control form-control-xs" readonly="readonly" style="width: 50px;">
                                H: <input type="text" name="h" value="" class="mx-1 form-control form-control-xs" readonly="readonly" style="width: 50px;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-secondary md-close">{$LANG->get('cancel')}</button>
                    <button type="submit" class="btn btn-primary md-close">{$LANG->get('submit')}</button>
                </div>
            </form>
        </div>
    </div>
</div>
{* Lưu nội dung menu khi ấn chuột phải *}
<div class="d-none" id="contextMenu"></div>
{* Iframe để tải file về *}
<a class="d-none" id="nv-filemanager-download-link" href="" download>&nbsp;</a>
{/if}

{*
<!-- BEGIN: main -->
<div class="footer">
    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-1">
                    <div class="refresh text-right">
                        <em title="{LANG.refresh}" class="fa fa-refresh fa-pointer" data-busy="false"></em>
                    </div>
                </div>
                <div class="col-sm-1">
                    <div class="viewmode text-right">
                        <em class="fa fa-hourglass-o fa-pointer fa-spin" data-auto="true" data-langthumb="{LANG.upload_view_thumbnail}" data-langdetail="{LANG.upload_view_detail}"></em>
                    </div>
                </div>
                <div class="col-sm-5">
                    <select name="imgtype" title="{LANG.selectfiletype}" class="form-control input-sm vchange">
                        <option value="file"{SFILE}>{LANG.type_file}</option>
                        <option value="image"{SIMAGE}>{LANG.type_image}</option>
                        <option value="flash"{SFLASH}>{LANG.type_flash}</option>
                    </select>
                </div>
                <div class="col-sm-5">
                    <select name="author" title="{LANG.author}" class="form-control input-sm vchange">
                        <option value="0">{LANG.author0}</option>
                        <option value="1">{LANG.author1}</option>
                    </select>
                </div>
                <div class="col-sm-8">
                    <select name="order" class="form-control input-sm vchange">
                        <option value="0">{LANG.order0}</option>
                        <option value="1">{LANG.order1}</option>
                        <option value="2">{LANG.order2}</option>
                    </select>
                </div>
                <div class="col-sm-4">
                    <div class="search text-left">
                        <em title="{LANG.search}" class="fa fa-search fa-lg fa-pointer"></em>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div id="upload-button-area" title="{LANG.nv_max_size}: {NV_MAX_SIZE}">&nbsp;</div>
        </div>
    </div>
    <div class="clearfix"></div>
</div>

<input type="hidden" name="currentFileUrl" value=""/>
<input type="hidden" name="selFile" value=""/>
<input type="hidden" name="CKEditorFuncNum" value="{FUNNUM}"/>
<input type="hidden" name="area" value="{AREA}"/>
<input type="hidden" name="alt" value="{ALT}"/>
<input type="hidden" name="upload_logo" value="{UPLOAD_LOGO}"/>
<input type="hidden" name="upload_logo_config" value="{UPLOAD_LOGO_CONFIG}"/>

<div class="upload-hide" id="contextMenu"></div>

<div id="errorInfo" class="upload-hide" title="{LANG.errorInfo}"></div>

<div id="uploadremote" title="{LANG.upload_mode_remote}">
    <div class="row">
        <label for="uploadremoteFile">{LANG.enter_url}</label>
    </div>
    <div class="row">
        <input type="text" class="form-control dynamic" name="uploadremoteFile" id="uploadremoteFile"/>
    </div>
    <div class="dynamic text-center" id="upload-remote-info"></div>
    <!-- BEGIN: alt_remote -->
    <div class="row">
        <label for="uploadremoteFileAlt">{LANG.altimage}</label>
    </div>
    <div class="row">
        <input type="text" class="form-control dynamic" name="uploadremoteFileAlt" id="uploadremoteFileAlt"/>
    </div>
    <!-- END: alt_remote -->
    <div class="row text-center">
        <input type="button" class="btn btn-primary" name="uploadremoteFileOK" value="{LANG.upload_file}"/>
    </div>
</div>

<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/upload.js"></script>
<script type="text/javascript">
$(function(){
    $("#imgfolder").load(nv_module_url + "folderlist&path={PATH}&currentpath={CURRENTPATH}&random=" + nv_randomNum(10));
    $("#imglist").load(nv_module_url + "imglist&path={CURRENTPATH}&type={TYPE}&imgfile={SELFILE}&random=" + nv_randomNum(10), function(){ LFILE.setViewMode(); });
});
</script>
<!--  END: main  -->
*}
