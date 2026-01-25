<div class="fms-wraper">
    <div class="fms-ctn">
        <div class="fms-tree" data-toggle="trees">
            <div class="fms-tree-scroller" data-toggle="tree-scroller"></div>
        </div>
        <div class="fms-section">
            <div class="fms-contents">
                <div class="fms-actions-bar d-flex gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-sm btn-primary btn-toggle-tree" data-toggle="toggle-trees" aria-label="{$LANG->getModule('toggle_folders')}"><i class="fa-solid fa-folder-tree pe-none"></i></button>
                        <div data-toggle="filter-type" data-type="file" class="filter-desktop dropdown" title="{$LANG->getModule('selectfiletype')}" aria-label="{$LANG->getModule('selectfiletype')}">
                            <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" data-type="file">{$LANG->getModule('type_file')}</a></li>
                                <li><a class="dropdown-item" href="#" data-type="image">{$LANG->getModule('type_image')}</a></li>
                            </ul>
                        </div>
                        <div data-toggle="filter-author" data-author="0" class="filter-desktop dropdown" title="{$LANG->getModule('author')}" aria-label="{$LANG->getModule('author')}">
                            <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {$LANG->getModule('author0')}
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" data-author="0">{$LANG->getModule('author0')}</a></li>
                                <li><a class="dropdown-item" href="#" data-author="1">{$LANG->getModule('author1')}</a></li>
                            </ul>
                        </div>
                        <div data-toggle="filter-order" data-order="0" class="filter-desktop dropdown" title="{$LANG->getModule('order_type')}" aria-label="{$LANG->getModule('order_type')}">
                            <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {$LANG->getModule('order0')}
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" data-order="0">{$LANG->getModule('order0')}</a></li>
                                <li><a class="dropdown-item" href="#" data-order="1">{$LANG->getModule('order1')}</a></li>
                                <li><a class="dropdown-item" href="#" data-order="2">{$LANG->getModule('order2')}</a></li>
                            </ul>
                        </div>
                        <a class="p-1 filter-mobile" href="#" data-toggle="filter-extra" title="{$LANG->getModule('filter_title')}" aria-label="{$LANG->getModule('filter_title')}">
                            <i class="fa-solid fa-filter fa-lg fa-fw"></i>
                        </a>
                        <a class="p-1" href="#" data-toggle="filter-q" data-q="" title="{$LANG->getModule('search_by_key')}" aria-label="{$LANG->getModule('search_by_key')}" data-label-clear="{$LANG->getModule('search_clear_key')}" data-label-search="{$LANG->getModule('search_by_key')}">
                            <i class="fa-solid fa-magnifying-glass fa-lg fa-fw" data-icon-clear="fa-ban text-danger" data-icon-search="fa-magnifying-glass"></i>
                        </a>
                    </div>
                    <div class="ms-auto d-flex align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <div class="d-none p-1" data-toggle="upload-notallowed">
                                <span data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-title="{$LANG->getModule('notupload')}" title="{$LANG->getModule('notupload')}" aria-label="{$LANG->getModule('notupload')}"><i class="fa-solid fa-triangle-exclamation text-danger"></i></span>
                            </div>
                            <div class="d-none btn-group" data-toggle="upload-group">
                                <button type="button" class="btn btn-sm btn-primary" data-toggle="upload-local-btn">{$LANG->getModule('upload_mode_local')}</button>
                                <button type="button" class="btn btn-sm btn-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="visually-hidden">{$LANG->getModule('upload_mode')}</span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#" data-toggle="upload-remote-btn">{$LANG->getModule('upload_mode_remote')}</a></li>
                                </ul>
                            </div>
                            <a class="p-1" href="#" data-toggle="list-grid" data-icon-list="fa-list-ul" data-icon-grid="fa-border-all" data-view="grid" aria-label="{$LANG->getModule('upload_view_detail')}" data-label-list="{$LANG->getModule('upload_view_thumbnail')}" data-label-grid="{$LANG->getModule('upload_view_detail')}">
                                <i class="fa-solid fa-border-all fa-lg fa-fw"></i>
                            </a>
                            <a class="p-1" href="#" data-toggle="refresh" aria-label="">
                                <i class="fa-solid fa-arrows-rotate fa-lg"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="fms-files-ctn">
                    <div class="fms-files view-grid" data-toggle="file-scroller"></div>
                </div>
                <div class="fms-files-page">
                    <div class="pagination-wrap" data-toggle="pagination"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="fms-upqueue-outer d-none" data-toggle="queue-ctns">
        <div class="fms-upqueue">
            <div class="queue-tools d-flex gap-2 align-items-center">
                <div class="tool-btns">
                    <button data-toggle="queue-add" type="button" class="btn btn-sm btn-primary"><i class="fa-solid fa-plus"></i> {$LANG->getModule('upload_add_files')}</button>
                    <button data-toggle="queue-start" type="button" class="btn btn-sm btn-primary"><i class="fa-solid fa-play"></i> {$LANG->getModule('upload_mode_local')}</button>
                    <button data-toggle="queue-cancel" type="button" class="btn btn-sm btn-secondary"><i class="fa-solid fa-xmark text-danger"></i> {$LANG->getModule('upload_cancel')}</button>
                    <button data-toggle="queue-stop" type="button" class="btn btn-sm btn-secondary d-none"><i class="fa-solid fa-pause"></i> {$LANG->getModule('upload_stop')}</button>
                    <button data-toggle="queue-continue" type="button" class="btn btn-sm btn-secondary d-none"><i class="fa-solid fa-play"></i> {$LANG->getModule('upload_continue')}</button>
                    <button data-toggle="queue-finishloader" type="button" class="btn btn-sm btn-success d-none"><i class="fa-solid fa-spinner fa-spin-pulse"></i></button>
                    <button data-toggle="queue-finish" type="button" class="btn btn-sm btn-secondary d-none"><i class="fa-solid fa-check"></i> {$LANG->getModule('upload_finish')}</button>
                </div>
                <div class="tool-sizes" data-toggle="queue-size">0</div>
                <div class="tool-progress">
                    <div data-toggle="queue-progress-bar" class="progress" role="progressbar" aria-label="{$LANG->getModule('upload_progressbar')}" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                        <div data-toggle="queue-progress-value" class="progress-bar"></div>
                    </div>
                </div>
            </div>
            <div class="queue-opts{if empty($UPLOAD_LOGO)} d-none{/if}">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="1" name="queue_autologo" id="[prefix]-queue-autologo">
                    <label class="form-check-label text-truncate" for="[prefix]-queue-autologo">{$LANG->getModule('autologo_for_upload')}</label>
                </div>
            </div>
            <div class="queue-head">
                <div class="queue-col-name">{$LANG->getModule('file_name')}</div>
                <div class="queue-col-alt">{$LANG->getModule('altimage')}</div>
                <div class="queue-col-size">{$LANG->getModule('upload_size')}</div>
                <div class="queue-col-status">{$LANG->getModule('upload_status')}</div>
                <div class="queue-col-tool"></div>
            </div>
            <div class="queue-files" data-toggle="queue-scroller">
                <div class="queue-files-items" data-toggle="queue-items"></div>
            </div>
        </div>
    </div>
    <div class="dropzone-area" data-toggle="dropzone">
        <div class="text-center pe-none">
            <div class="mb-1 pe-none">
                <i class="fa-solid fa-cloud-arrow-up pe-none"></i>
            </div>
            <div class="fs-5 fw-medium pe-none">{$LANG->getModule('upload_drop')}</div>
        </div>
    </div>
    <div class="fms-iframe"><iframe data-toggle="fms-iframe"></iframe></div>
    <div class="fms-loader show" data-toggle="loader">
        <i class="fa-solid fa-spinner fa-spin-pulse fa-3x"></i>
    </div>
</div>
