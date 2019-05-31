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
                                        <a href="#nv-filemanager-form-remoteupload" class="dropdown-item" data-toggle="modal" data-backdrop="static">{$LANG->get('upload_mode_remote')}</a>
                                    </div>
                                </div>
                            </div>
                            <ul class="tools">
                                <li class="iconp-search"><a href="#nv-filemanager-form-search" data-toggle="modal" data-backdrop="static" class="icon-search nv-filemanager-btn-search"><i class="fas fa-search"></i></a></li>
                                <li class="iconp-cview"><a href="#" class="icon-cview" id="nv-filemanager-btn-change-viewmode" data-auto="true"><i class="fas"></i></a></li>
                                <li class="iconp-reload"><a href="#" class="icon-reload" id="nv-filemanager-btn-reload" title="{$LANG->get('refresh')}"><i class="fas fa-sync-alt"></i></a></li>
                                <li class="iconp-collapse"><a href="#" class="icon-collapse" id="nv-filemanager-btn-toggle-form-filter"><i class="fas fa-cog"></i></i></a></li>
                            </ul>
                            <div class="form" id="nv-filemanager-form-filter">
                                <div class="btn-group btn-space">
                                    <button type="button" data-toggle="dropdown" class="btn btn-secondary dropdown-toggle" data-value="{$TYPE}" id="nv-filemanager-ctn-filter-type"><span class="text">{$LANG->get("type_`$TYPE`")}</span> <span class="icon-dropdown fas fa-chevron-down"></span></button>
                                    <div role="menu" class="dropdown-menu">
                                        <a href="#" class="dropdown-item nv-filemanager-btn-filter-type" data-value="file">{$LANG->get('type_file')}</a>
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
                    <div class="fm-files nv-scroller" id="nv-filemanager-files-scroller">
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
