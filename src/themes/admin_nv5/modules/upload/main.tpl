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
<div class="d-none" id="fmMainArea" data-value=""></div>
<div class="d-none" id="fmMainAlt" data-value=""></div>
<div class="d-none" id="fmMainLogo" data-value=""></div>
<div class="d-none" id="fmMainLogoConfig" data-value=""></div>
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
{* Lưu nội dung menu khi ấn chuột phải *}
<div class="d-none" id="contextMenu"></div>
{/if}

{*
<!-- BEGIN: uploadPage -->
<iframe src="{IFRAME_SRC}" id="uploadframe"></iframe>
<!-- END: uploadPage -->
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

<div class="upload-hide">
    <iframe id="Fdownload" src="" width="0" height="0" frameborder="0"></iframe>
</div>

<div id="renamefolder" class="upload-hide" title="{LANG.renamefolder}">
    <div class="form-horizontal" role="form">
        <div class="form-group">
            <label class="control-label col-xs-6">{LANG.rename_newname}:</label>
            <div class="col-xs-18">
                <input type="text" name="foldername" class="form-control dynamic"/>
            </div>
        </div>
    </div>
</div>

<div id="recreatethumb" class="upload-hide" title="{LANG.recreatethumb}">
    <div class="form-horizontal" role="form">
        <div class="form-group" id="recreatethumb_loading">
            {LANG.recreatethumb_note}
        </div>
    </div>
</div>

<div id="errorInfo" class="upload-hide" title="{LANG.errorInfo}"></div>

<div id="imgpreview" title="{LANG.preview}">
    <div id="fileInfoAlt" class="dynamic file-title"></div>
    <div id="fileView" class="dynamic file-content"></div>
    <div id="fileInfoName" class="dynamic file-title"></div>
    <div id="fileInfoDetail" class="dynamic file-detail m-bottom"></div>
    <div class="clearfix" id="fileInfoLink">
        <label for="FileRelativePath" class="text-left display-block">{LANG.filerelativepath}:</label>
        <div class="input-group input-group-sm m-bottom">
            <input type="text" class="form-control" id="FileRelativePath"/>
            <span class="input-group-btn">
                <button class="btn btn-default" data-clipboard-target="#FileRelativePath" id="FileRelativePathBtn" data-title="{LANG.filepathcopied}" data-placement="left" data-container="body" data-trigger="manual" data-animation="0"><i class="fa fa-copy"></i></button>
            </span>
        </div>
        <label for="FileAbsolutePath" class="text-left">{LANG.fileabsolutepath}:</label>
        <div class="input-group input-group-sm">
            <input type="text" class="form-control" id="FileAbsolutePath"/>
            <span class="input-group-btn">
                <button class="btn btn-default" data-clipboard-target="#FileAbsolutePath" id="FileAbsolutePathBtn" data-title="{LANG.filepathcopied}" data-placement="left" data-container="body" data-trigger="manual" data-animation="0"><i class="fa fa-copy"></i></button>
            </span>
        </div>
    </div>
</div>

<div id="imgcreate" title="{LANG.upload_createimage}">
    <div class="row">
        <div class="col-xs-10">
            <input type="hidden" name="origWidth" value="" class="dynamic" />
            <input type="hidden" name="origHeight" value="" class="dynamic" />
            <div class="title">{LANG.newSize}</div>
            <div class="form-horizontal" role="form">
                <div class="form-group">
                    <label class="col-xs-4 control-label">X:</label>
                    <div class="col-xs-8"><input type="text" name="newWidth" maxlength="4" class="dynamic form-control" /></div>
                    <label class="col-xs-4 control-label">Y:</label>
                    <div class="col-xs-8"><input type="text" name="newHeight" maxlength="4" class="dynamic form-control" /></div>
                </div>
            </div>
            <div class="text-center form-group">
                <button class="btn btn-default" type="button" name="prView"><em class="fa fa-search fa-lg"></em> {LANG.prView}</button>
                <button class="btn btn-primary" type="button" name="newSizeOK">{LANG.addlogosave}</button>
            </div>
            <div title="createInfo" class="dynamic text-center text-muted"></div>
        </div>
        <div class="col-xs-14 text-center">
            <div class="image-preview-wrap clearfix">
                <div id="fileInfoName2" class="dynamic title"></div>
                <div id="fileInfoDetail2" class="dynamic"></div>
                <div class="image-preview">
                    <img name="myFile2" alt="{LANG.clickSize}" width="0" height="0" src="" />
                </div>
            </div>
        </div>
    </div>
</div>

<div id="filemove" title="{LANG.move}">
    <div title="pathFileName" class="dynamic filename"></div>
    <div class="form-group">
        <label>{LANG.movefolder}:</label>
        <select name="newPath" class="form-control"></select>
    </div>
    <div class="checkbox">
        <label>
            <input name="mirrorFile" type="checkbox" class="dynamic" /> {LANG.mirrorFile}.
        </label>
    </div>
    <div class="checkbox">
        <label>
            <input name="goNewPath" type="checkbox" class="dynamic" /> {LANG.goNewPath}.
        </label>
    </div>
    <div class="text-center">
        <input type="button" value="{LANG.addlogosave}" name="newPathOK" class="btn btn-primary"/>
    </div>
</div>

<div id="filesearch" title="{LANG.search}">
    <form method="get" onsubmit="return searchfile();" role="form">
        <div class="form-group">
            <label>{LANG.searchdir}:</label>
            <select name="searchPath" class="form-control"></select>
        </div>
        <div class="form-group">
            <label>{LANG.searchkey}:</label>
            <input name="q" type="text" class="form-control dynamic" />
        </div>
        <div class="text-center">
            <button type="submit" name="search" class="btn btn-primary">{LANG.search}</button>
        </div>
    </form>
</div>

<div id="filerename" title="{LANG.rename}">
    <div id="filerenameOrigName" class="dynamic origname text-center"></div>
    <div class="form-horizontal" role="form">
        <div class="form-group">
            <label class="col-xs-8 control-label">{LANG.rename_newname}:</label>
            <div class="col-xs-14">
                <input type="text" name="filerenameNewName" maxlength="255" class="dynamic form-control" />
            </div>
            <div class="col-xs-2">
                <span title="Ext">Ext</span>
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-8 control-label">{LANG.altimage}:</label>
            <div class="col-xs-16">
                <input type="text" name="filerenameAlt" maxlength="255" class="dynamic form-control" />
            </div>
        </div>
        <div class="text-center">
            <input class="btn btn-primary" type="button" value="{LANG.addlogosave}" name="filerenameOK" />
        </div>
    </div>
</div>

<div id="rorateimage" title="{LANG.rotate}">
    <input type="hidden" class="dynamic" name="roratePath" value=""/>
    <input type="hidden" class="dynamic" name="rorateFile" value=""/>
    <h4 class="text-center"><strong id="rorateimageName" class="dynamic">&nbsp;</strong></h4>
    <div id="rorateContent" class="rorate-content">

    </div>
    <div class="text-center form-inline">
        <input type="text" class="form-control w50 dynamic" name="rorateDirection" value="0"/>
        <button id="rorate90Anticlockwise" type="button" class="btn btn-default">
            <em class="fa fa-lg fa-undo"></em> 90
        </button>
        <button id="rorateLeft" type="button" class="btn btn-default btn-reset">
            <em class="fa fa-lg fa-undo"></em>
        </button>
        <button id="rorateRight" type="button" class="btn btn-default btn-reset">
            <em class="fa fa-lg fa-repeat"></em>
        </button>
        <button id="rorate90Clockwise" type="button" class="btn btn-default">
            <em class="fa fa-lg fa-repeat"></em> 90
        </button>
        <input id="rorateimageOK" type="button" class="btn btn-primary" value="{LANG.addlogosave}"/>
    </div>
</div>

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

<div id="cropimage" title="{LANG.crop}">
    <div id="cropContent" class="crop-content"></div>
    <div id="cropButtons" class="text-center form-inline dynamic"></div>
</div>

<div id="addlogo" title="{LANG.addlogo}">
    <div id="addlogoContent" class="addlogo-content"></div>
    <div id="addlogoButtons" class="text-center form-inline dynamic"></div>
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
