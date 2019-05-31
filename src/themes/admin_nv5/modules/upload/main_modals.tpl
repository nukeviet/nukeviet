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
{* Form upload file từ internet *}
<div id="nv-filemanager-form-remoteupload" tabindex="-1" role="dialog" class="modal colored-header colored-header-primary inFileManagerModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form>
                <div class="modal-header modal-header-colored">
                    <h3 class="modal-title">{$LANG->get('upload_mode_remote')}</h3>
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close md-close"><span class="fas fa-times"></span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>{$LANG->get('enter_url')} <i class="text-danger">(*)</i>:</label>
                        <input type="text" class="form-control" name="uploadremoteFile" value="">
                    </div>
                    <div class="form-group mb-0">
                        <label>{$LANG->get('altimage')}{if $UPLOAD_ALT_REQUIRE eq 'true'} <i class="text-danger">(*)</i>{/if}:</label>
                        <input type="text" class="form-control" name="uploadremoteFileAlt" value="">
                    </div>
                </div>
                <div class="modal-footer">
                    <span class="d-none" data-toggle="loader">
                        <i class="fas fa-spinner fa-pulse"></i>
                    </span>
                    <button type="button" data-dismiss="modal" class="btn btn-secondary md-close">{$LANG->get('cancel')}</button>
                    <button type="submit" name="uploadremoteFileOK" value="submit" class="btn btn-primary md-close">{$LANG->get('upload_file')}</button>
                </div>
            </form>
        </div>
    </div>
</div>
{* Lưu nội dung menu khi ấn chuột phải *}
<div class="d-none" id="contextMenu"></div>
{* Iframe để tải file về *}
<a class="d-none" id="nv-filemanager-download-link" href="" download>&nbsp;</a>
