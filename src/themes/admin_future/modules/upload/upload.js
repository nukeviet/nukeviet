'use strict';

/**
 * NukeViet có cơ chế kiểm soát tệp js local load nhiều lần
 * nên không cần quan tâm xử lý cơ chế tệp này được gọi nhiều lần
 * {* Lưu ý: Tệp này được gọi bằng smarty *}
 */
(() => {
    const loadApp = () => {
        let cssNum = 0, jsNum = 0, ready = false;
        let amountCss = 2, amountJs = 7;

        // Extra{* Vui lòng giữ đúng cấu trúc này để render ra đúng
        /**}

        {if $EXTRA_JS neq ''}amountJs++;
        // Tải callback của trình soạn thảo
        loadScript("{$EXTRA_JS}");
        {/if}
        {**/
        //*}

        // Tải Jquery Cropper
        if (typeof $.fn.cropper == "undefined") {
            loadScript(nv_base_siteurl + "assets/js/cropper/cropper.min.js");
            loadCSS(nv_base_siteurl + "assets/js/cropper/cropper.min.css");
        } else {
            jsNum++;
            cssNum++;
        }
        // Tải Jquery clipboard
        if (typeof ClipboardJS == "undefined") {
            loadScript(nv_base_siteurl + "assets/js/clipboard/clipboard.min.js");
        } else {
            jsNum++;
        }
        // Tải Plpuload
        if (typeof plupload == "undefined") {
            loadScript(nv_base_siteurl + "assets/js/plupload/plupload.full.min.js", nv_base_siteurl + "assets/js/language/plupload-" + nv_lang_interface + ".js");
        } else {
            jsNum += 2;
        }
        // Tải PerfectScrollbar
        if (typeof PerfectScrollbar == "undefined") {
            loadScript(nv_base_siteurl + "assets/js/perfect-scrollbar/min.js");
            loadCSS(nv_base_siteurl + "assets/js/perfect-scrollbar/style.css");
        } else {
            jsNum++;
            cssNum++;
        }
        // Tải select2
        if (typeof $.fn.select2 == "undefined") {
            loadScript(nv_base_siteurl + "assets/js/select2/select2.min.js", nv_base_siteurl + "assets/js/select2/i18n/" + nv_lang_interface + ".js");
        } else {
            jsNum += 2;
        }

        // Xuất ra event sẵn sàng (chỉ chạy 1 lần duy nhất)
        function fireReady() {
            if (cssNum < amountCss || jsNum < amountJs || ready || window.nvPickerReady) {
                return;
            }
            ready = true;
            // Event cho js thuần
            document.dispatchEvent(new Event('nv.picker.ready'));

            // Event cho Jquery
            $(document).trigger('nv.picker.ready');

            window.nvPickerReady = true;
        }

        // Hàm tải JS
        function loadCSS(url, urlnext) {
            url += (url.includes('?') ? '&' : '?') + 't=' + nv_cache_timestamp;
            const link = document.createElement("link");
            link.rel = "stylesheet";
            link.href = url;
            link.onload = () => {
                cssNum++;
                fireReady();
                if (urlnext) {
                    loadCSS(urlnext);
                }
            };
            link.onerror = () => {
                nvToast("Error load CSS: " + url, "error");
            };
            document.head.appendChild(link);
        }

        // Hàm tải JS
        function loadScript(url, urlnext) {
            url += (url.includes('?') ? '&' : '?') + 't=' + nv_cache_timestamp;
            const script = document.createElement("script");
            script.src = url;
            script.onload = () => {
                jsNum++;
                fireReady();
                if (urlnext) {
                    loadScript(urlnext);
                }
            };
            script.onerror = function() {
                nvToast("Error load JS: " + url, "error");
            };
            document.body.appendChild(script);
        }

        fireReady();
    };
    /**
     * Nếu tệp này được gọi phạm vi toàn cục trong html của trang thì xử lý sau khi DOMContentLoaded
     * Nếu tệp này được gọi sau khi DOM trang được tải xong thì thực thi ngay
     */
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', loadApp);
    } else {
        loadApp();
    }
})();

var nukeviet = nukeviet || {};

/**
 * Class xử lý trình quản lý tệp tin
 */
!nukeviet.Picker && (nukeviet.Picker = class {
    htmlModal = `{$HTML_POPUP}`;
    htmlContainer = `{$HTML_CONTENT}`;
    htmlDialog = `{$HTML_DIALOG}`;
    htmlQueueItem = `{$HTML_QUEUE_ITEM}`;
    htmlModalBackdrop = `<div class="fmm-backdrop fade"></div>`;
    htmlDialogBackdrop = `<div class="fmd-backdrop fade"></div>`;
    lang = {
        errorMinX: `{$LANG->getModule('errorMinX')}`,
        errorMaxX: `{$LANG->getModule('errorMaxX')}`,
        errorMinY: `{$LANG->getModule('errorMinY')}`,
        errorMaxY: `{$LANG->getModule('errorMaxY')}`,
        errorEmptyY: `{$LANG->getModule('errorEmptyY')}`,
        errorEmptyX: `{$LANG->getModule('errorEmptyX')}`,
        limitMin: `{$LANG->getModule('limit_min')}`,
        limitMax: `{$LANG->getModule('limit_max')}`,
        notlogo: `{$LANG->getModule('notlogo')}`,
        moveMultiple: `{$LANG->getModule('move_multiple')}`,
        delFolderConfirm: `{$LANG->getModule('delete_folder')}`,
        delImgConfirm: `{$LANG->getModule('upload_delimg_confirm')}`,
        delImgsConfirm: `{$LANG->getModule('upload_delimgs_confirm')}`,
        copied: `{$LANG->getModule('filepathcopied')}`,
        select: `{$LANG->getModule('select')}`,
        compressImage: `{$LANG->getModule('compressimage')}`,
        webpConvert: `{$LANG->getModule('webpconvert')}`,
        qualityChange: `{$LANG->getModule('qualitychange')}`,
        deleteFile: `{$LANG->getModule('upload_delfile')}`,
        rename: `{$LANG->getModule('rename')}`,
        move: `{$LANG->getModule('move')}`,
        rotate: `{$LANG->getModule('rotate')}`,
        crop: `{$LANG->getModule('crop')}`,
        imgTool: `{$LANG->getModule('upload_createimage')}`,
        addLogo: `{$LANG->getModule('addlogo')}`,
        preview: `{$LANG->getModule('preview')}`,
        download: `{$LANG->getModule('download')}`,
        deleteDir: `{$LANG->getModule('deletefolder')}`,
        renameDir: `{$LANG->getModule('renamefolder')}`,
        reThumb: `{$LANG->getModule('recreatethumb')}`,
        createDir: `{$LANG->getModule('createfolder')}`,
        altRequired: `{$LANG->getModule('upload_alt_note')}`,
        filesSelected: `{$LANG->getModule('files_selected')}`
    }
    menuTpl = '<ul class="dropdown-menu dropdown-menu-fms"></ul>';
    imageExts = ["gif", "jpg", "jpeg", "pjpeg", "png", "webp"];

    // Hàm khởi tạo
    constructor(element, options) {
        this.$element = $(element);
        this.settings = $.extend({
            show: 'button', // button|inline
            path: '', // Thư mục được tải lên dạng uploads/module/...
            currentpath: '', // Active thư mục này
            popup: 0, // 1 là dạng windowed 0 là dạng current page
            type: 'file', // image|file
            imgfile: '', // Select tệp này
            CKEditorFuncNum: 0, // >0 là mở cho CKEditor4
            editorId: '', // Khác rỗng là mở cho CKEditor5
            trigger: 'auto', // auto|manual auto là tự động gắn sự kiện click vào element, manual là gọi hàm show() để mở
            area: '', // ID thẻ đổ src về khi pick.
            alt: '', // ID thẻ đổ alt về khi pick.
            onSelect: null // Hàm trả về khi select
        }, options);

        this.fmm = null;
        this.fms = null;
        this.fmd = null;
        this.fs = null;
        this.ts = null;
        this.qs = null;
        this.bodyEndPadding = 0;
        this.bodyOverflow = '';
        this.bodyVScroll = false;
        this.bodyDigEndPadding = 0;
        this.bodyDigOverflow = '';
        this.bodyDigVScroll = false;

        this.refresh = false;
        this.page = 1;

        this.id = this.ranid();
        this.fmdId = 'fmd' + this.id;
        this.fmsId = 'fms' + this.id;
        this.htmlDialog = this.htmlDialog.replace(/\[prefix\]/g, this.fmdId);
        this.htmlContainer = this.htmlContainer.replace(/\[prefix\]/g, this.fmsId);

        this.up = null;
        this.isFocused = false;

        this.constant = {
            logo: '{$UPLOAD_LOGO}',
            compressImage: {$COMPRESS_IMAGE_ACTIVE},
            autoAlt: {$UPLOAD_AUTO_ALT},
            altRequire: {$UPLOAD_ALT_REQUIRE},
            logoSize: {
                width: {$LOGO_WIDTH},
                height: {$LOGO_HEIGHT},
                sizeS: {$LOGO_SIZE_S},
                sizeM: {$LOGO_SIZE_M},
                sizeL: {$LOGO_SIZE_L}
            },
            image: {
                minWidth: 10,
                minHeight: 10,
                maxWidth: {$MAX_WIDTH},
                maxHeight: {$MAX_HEIGHT}
            }
        }

        this.lastTap = 0;
        this.lastTouchMove = 0;
        this.selectionBox = null;
        this.selectionStartX = null;
        this.selectionStartY = null;
        this.intervalRotate = null;

        this.debug = {$DEBUG};
        this.init();
    }

    // Dựng trình quản lý tệp tin
    init() {
        let cfg = this.settings;

        const self = this;
        if (cfg.show == 'inline') {
            self.fms = $(self.htmlContainer);
            self.fmd = $(self.htmlDialog);
            self.$element.replaceWith(self.fms);
            self.fms.after(self.fmd);

            self.menu = $(self.menuTpl);
            $('body').append(self.menu);

            if ($('.fms-ctn', $('body')).length == 1) {
                self.isFocused = true;
            }

            self.initContainer();
            return;
        }

        cfg.trigger === 'auto' && this.$element.on('click', function(e) {
            e.preventDefault();
            self.showModal();
        });
    }

    // Hiển thị popup qua lệnh
    show() {
        const self = this;
        if (self.settings.show == 'inline' || self.fmm) {
            return;
        }
        self.showModal();
    }

    // Đóng popup qua lệnh
    hide() {
        const self = this;
        if (self.settings.show == 'inline' || !self.fmm) {
            return;
        }
        self.hideModal();
    }

    // Xử lý các sự kiện sau khi dựng được container
    initContainer() {
        const self = this;

        const input = self.detectInputSupport();
        if (input == 'none') {
            nvToast('Your browser does not support, please upgrade to modern browsers', 'warning');
        }
        self.setContainerType(input);

        // Xử lý lọc theo loại file
        const ftype = $('[data-toggle="filter-type"]', self.fms);
        if (self.settings.type == 'image') {
            ftype.data('type', 'image');
            $('button', ftype).text($('a[data-type="image"]', ftype).text());
        } else {
            ftype.data('type', 'file');
            $('button', ftype).text($('a[data-type="file"]', ftype).text());
        }
        $('a', ftype).on('click', function(e) {
            e.preventDefault();
            if ($(this).data('type') == ftype.data('type')) {
                return;
            }
            ftype.data('type', $(this).data('type'));
            $('button', ftype).text($(this).text());
            self.page = 1;
            self.fetchFile();
        });

        // Xử lý lọc theo tác giả
        const fauthor = $('[data-toggle="filter-author"]', self.fms);
        $('a', fauthor).on('click', function(e) {
            e.preventDefault();
            if ($(this).data('author') == fauthor.data('author')) {
                return;
            }
            fauthor.data('author', $(this).data('author'));
            $('button', fauthor).text($(this).text());
            self.page = 1;
            self.fetchFile();
        });

        // Xử lý kiểu sắp xếp
        const forder = $('[data-toggle="filter-order"]', self.fms);
        $('a', forder).on('click', function(e) {
            e.preventDefault();
            if ($(this).data('order') == forder.data('order')) {
                return;
            }
            forder.data('order', $(this).data('order'));
            $('button', forder).text($(this).text());
            self.fetchFile();
        });

        // Xử lý khi đổ thư mục con ra
        $(self.fms).on('show.bs.collapse', '[data-toggle="collapseTree"]', function(e) {
            e.stopPropagation();
            let btn = $('[aria-controls="' + $(e.target).attr('id') + '"]', self.fms);
            let icon = $('[data-toggle="tree-icon"]', btn);
            icon.removeClass(icon.data('icon')).addClass('fa-folder-open');
        });
        $(self.fms).on('shown.bs.collapse', '[data-toggle="collapseTree"]', function(e) {
            e.stopPropagation();
            self.ts && self.ts.update();
        });

        // Xử lý khi thu thư mục con lại
        $(self.fms).on('hide.bs.collapse', '[data-toggle="collapseTree"]', function(e) {
            e.stopPropagation();
            let btn = $('[aria-controls="' + $(e.target).attr('id') + '"]', self.fms);
            let icon = $('[data-toggle="tree-icon"]', btn);
            icon.removeClass('fa-folder-open').addClass(icon.data('icon'));
        });
        $(self.fms).on('hidden.bs.collapse', '[data-toggle="collapseTree"]', function(e) {
            e.stopPropagation();
            self.ts && self.ts.update();
        });

        // Tạo các thanh cuộn
        self.initScrollbar();

        // Xử lý khi bấm chuột trái vào thư mục
        $(self.fms).on('click', '[data-toggle="tree-name"]', function(e) {
            e.preventDefault();
            let tree = $(this).closest('li');
            $('[data-toggle="tree-scroller"] .active', self.fms).removeClass('active');
            tree.addClass('active');
            self.page = 1;
            self.fetchFile();
            self.initUploader();
        });

        // Nút tìm kiếm
        $('[data-toggle="filter-q"]', self.fms).on('click', function(e) {
            e.preventDefault();

            const fq = $(this);
            if (fq.data('q') != '') {
                self.removeFilterQ(fq);

                self.page = 1;
                self.fetchFile();
                return;
            }

            self.showDialog('search');
        });

        // Nút lọc mở rộng trên mobile
        $('[data-toggle="filter-extra"]', self.fms).on('click', function(e) {
            e.preventDefault();
            self.showDialog('filter');
        });

        // Nút upload tệp từ internet
        $('[data-toggle="upload-remote-btn"]', self.fms).on('click', function(e) {
            e.preventDefault();
            self.showDialog('upload-remote');
        });

        // Nút reload
        $('[data-toggle="refresh"]', self.fms).on('click', function(e) {
            e.preventDefault();
            self.page = 1;
            self.refresh = true;
            self.fetchAll();
        });

        // Link phân trang
        $(self.fms).on('click', '[data-toggle="pagination"] a', function(e) {
            e.preventDefault();
            const page = self.getPage(this);
            if (page === null || page == self.page) {
                return;
            }
            self.page = page;
            self.fetchFile();
        });

        // Đóng các dialog
        self.fmd.on('click', '[data-dismiss="fmd"]', function() {
            self.hideDialog($(this).closest('.fmd'));
        });

        // Select2 trong các dialog
        $('[data-toggle="select2"]', self.fmd).select2({
            language: nv_lang_interface,
            dir: $('html').attr('dir'),
            width: '100%'
        });

        // Submit form trong các dialog
        $('form', self.fmd).on('submit', function(e) {
            self.submitDialogCallback(this, e);
        });

        // Kiểu danh sách hoặc lưới
        $('[data-toggle="list-grid"]', self.fms).on('click', function(e) {
            e.preventDefault();
            self.switchView($(this).data('view') == 'list' ? 'grid' : 'list');
            self.fs && self.fs.update();
        });

        // Tooltip trong container
        $('[data-bs-toggle="tooltip"]', self.fms).each(function() {
            new bootstrap.Tooltip(this);
        });

        // Tự xác định alt khi nhập url upload remote
        self.fmd.filter('[data-dialog="upload-remote"]').on('keyup', '[name="fileurl"]', function() {
            if (!self.constant.autoAlt) {
                return;
            }
            const dig = $(this).closest('.fmd');
            $('[name="filealt"]', dig).val(self.getAlt($(this).val()));
        });

        // Đóng mở cây thư mục mobile
        $('[data-toggle="toggle-trees"]', self.fms).on('click', function(e) {
            e.preventDefault();

            $('[data-toggle="trees"]', self.fms).addClass('show');
        });

        document.addEventListener('mousedown', self.handleDocTapStart);
        document.addEventListener('touchstart', self.handleDocTapStart);
        document.addEventListener('mousemove', self.handleDocTapMove);
        document.addEventListener('touchmove', self.handleDocTapMove);
        document.addEventListener('mouseup', self.handleDocTapEnd);
        document.addEventListener('touchend', self.handleDocTapEnd);
        document.addEventListener('touchcancel', self.handleDocTapEnd);
        document.addEventListener('keydown', self.handleDocKeydown);

        // Xử lý sự kiện liên quan tệp tin
        self.initFileEvents();

        // Xử lý sự kiện liên quan thư mục
        self.initTreeEvents();

        // Xử lý các sự kiện liên quan upload
        self.initUploadEvents();

        // Xử lý các sự kiện kéo thả
        self.initDrop();

        // Xử lý các sự kiện trên menu
        self.initMenuEvents();

        // Xử lý các sự kiện trên dialog
        self.initDialogEvents();

        // Handle lỗi trong các dialog
        self.handleDialogError();

        // Lấy cây thư mục và file
        self.fetchAll({
            init: true
        });
    }

    // Thiết lập trình upload
    initUploader() {
        const self = this;
        if (self.up) {
            self.up.destroy();
            self.up = null;
        }
        const tree = $('[data-toggle="tree-scroller"]', self.fms).find('li.active');
        if (tree.length != 1 || !tree.data('allowed-upload-file')) {
            $('[data-toggle="upload-notallowed"]', self.fms).removeClass('d-none');
            $('[data-toggle="upload-group"]', self.fms).addClass('d-none');
            return;
        }
        $('[data-toggle="upload-notallowed"]', self.fms).addClass('d-none');
        $('[data-toggle="upload-group"]', self.fms).removeClass('d-none');

        self.up = new plupload.Uploader({
            runtimes: 'html5,flash,silverlight,html4',
            browse_button: $('[data-toggle="upload-local-btn"]', self.fms)[0],
            url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=upload&' + nv_fc_variable + '=upload&path=' + encodeURIComponent(self.getCurrentPath()) + '&nocache=' + new Date().getTime(),
            flash_swf_url: nv_base_siteurl + 'assets/js/plupload/Moxie.swf',
            silverlight_xap_url: nv_base_siteurl + 'assets/js/plupload/Moxie.xap',
            drop_element: $('[data-toggle="dropzone"]', self.fms)[0],
            file_data_name: 'upload',
            multipart: true,
            multipart_params: {
                "filealt": "--"
            },
            filters: {
                max_file_size: {$NV_MAX_SIZE_BYTES},
                mime_types: []
            },
            chunk_size: {$NV_CHUNK_SIZE},
            resize: false,
            init: {
                FilesAdded: (up, files) => {
                    self.debug && console.log('Plupload FilesAdded', files, up);
                    self.upQueueRender();
                    self.upAppendList();
                },
                UploadProgress: (up, file) => {
                    // Trạng thái upload của 1 tệp
                    self.debug && console.log('Plupload UploadProgress', file, up);
                    self.upStatusFile(file);
                    self.upTotalPercent();
                },
                FileUploaded: (up, file, response) => {
                    self.debug && console.log('Plupload FileUploaded', file, response, up);
                    self.upStatusFile(file, response.response);
                },
                QueueChanged: () => {
                    // Xóa hoặc thêm tệp
                    self.debug && console.log('Plupload QueueChanged');
                    if (self.up.files.length < 1) {
                        self.upQueueReset();
                        return;
                    }

                    // Tính toán tổng dung lượng
                    let totalSize = 0;
                    self.up.files.forEach(file => {
                        totalSize += file.size;
                    });
                    $('[data-toggle="queue-size"]', self.fms).text(plupload.formatSize(totalSize));

                    // Xử lý có độ trễ do event FilesAdded xảy ra trước
                    setTimeout(() => {
                        let qe = $('[data-toggle="queue-scroller"]', self.fms);
                        if (qe.length) {
                            qe[0].scrollTop = qe[0].scrollHeight;
                        }
                        self.qs && self.qs.update();
                    }, 10);
                },
                BeforeUpload: (up, file) => {
                    self.debug && console.log('Plupload BeforeUpload', file, up);

                    // Thêm một số thiết lập cho tệp tin trước khi upload
                    let filealt = '';
                    let fi = $('#' + file.id);
                    if (fi.length) {
                        filealt = trim($('[name="queue_item_alt"]', fi).val());
                    }
                    self.up.settings.multipart_params = {
                        filealt: filealt,
                        autologo: ($('[name="queue_autologo"]', self.fms).is(':checked') ? 1 : 0)
                    };
                },
                Error: (up, err) => {
                    self.debug && console.log('Plupload Error', up, err);
                    const msg = '[' + err.code + '] ' + err.status + ': ' + err.message;
                    if (err.file) {
                        self.upStatusFile(err.file, null, msg);
                        return;
                    }
                    nvToast(msg, 'error');
                },
                UploadComplete: (up, files) => {
                    self.debug && console.log('Plupload UploadComplete', up, files);

                    $('[data-toggle="queue-stop"]', self.fms).addClass('d-none');
                    $('[data-toggle="queue-continue"]', self.fms).addClass('d-none');

                    if (self.up.total.failed > 0) {
                        // Có tệp tải lên lỗi
                        $('[data-toggle="queue-finish"]', self.fms).removeClass('d-none');
                        return;
                    }

                    // Toàn bộ hoàn tất
                    $('[data-toggle="queue-finishloader"]', self.fms).removeClass('d-none');
                    setTimeout(() => {
                        self.upFinish();
                    }, 1000);
                }
            }
        });
        self.up.init();
    }

    // Xử lý các sự kiện khi upload
    initUploadEvents() {
        const self = this;

        // Nút thêm tệp vào queue
        $('[data-toggle="queue-add"]', self.fms).on('click', function() {
            $('[data-toggle="upload-local-btn"]', self.fms)[0].click();
        });

        // Nút huỷ queue
        $('[data-toggle="queue-cancel"]', self.fms).on('click', function() {
            self.upQueueReset();
            self.initUploader();
        });

        // Xóa tệp khỏi hàng đợi
        self.fms.on('click', '[data-toggle="qitem-del"]', function(e) {
            e.preventDefault();
            const file = $(this).closest('[data-toggle="qitem"]');
            self.up.removeFile(file.data('id'));
            file.remove();
        });

        // Nút bắt đầu upload
        $('[data-toggle="queue-start"]', self.fms).on('click', function() {
            if (!self.upQueueCheck()) {
                return;
            }

            // Cuộn lên đầu
            const qe = $('[data-toggle="queue-scroller"]', self.fms);
            qe.length && (qe[0].scrollTop = 0);

            // Build lại các nút
            const queue = $('[data-toggle="queue-ctns"]', self.fms);
            $('[data-toggle="queue-add"]', queue).addClass('d-none');
            $('[data-toggle="queue-start"]', queue).addClass('d-none');
            $('[data-toggle="queue-cancel"]', queue).addClass('d-none');
            $('[data-toggle="queue-stop"]', queue).removeClass('d-none');

            // Khởi động tiến trình progress
            $('[data-toggle="queue-progress-value"]', self.fms).addClass('progress-bar-striped progress-bar-animated');

            self.up.start();
        });

        // Nút dừng upload
        $('[data-toggle="queue-stop"]', self.fms).on('click', function() {
            const queue = $('[data-toggle="queue-ctns"]', self.fms);
            $('[data-toggle="queue-stop"]', queue).addClass('d-none');
            $('[data-toggle="queue-continue"]', queue).removeClass('d-none');
            $('[data-toggle="queue-progress-value"]', self.fms).removeClass('progress-bar-striped progress-bar-animated');
            self.up.stop();
        });

        // Nút tiếp tục upload
        $('[data-toggle="queue-continue"]', self.fms).on('click', function() {
            if (!self.upQueueCheck()) {
                return;
            }

            const queue = $('[data-toggle="queue-ctns"]', self.fms);
            $('[data-toggle="queue-continue"]', queue).addClass('d-none');
            $('[data-toggle="queue-stop"]', queue).removeClass('d-none');
            $('[data-toggle="queue-progress-value"]', self.fms).addClass('progress-bar-striped progress-bar-animated');
            self.up.start();
        });

        // Nút hoàn tất upload. Trong trường hợp có tệp lỗi
        $('[data-toggle="queue-finish"]', self.fms).on('click', function() {
            self.upFinish();
        });
    }

    // Xử lý event thả tệp vào để upload
    initDrop() {
        const self = this;
        const drp = $('[data-toggle="dropzone"]', self.fms);

        document.addEventListener('dragenter', self.handleDragenter);
        document.addEventListener('dragleave', self.handleDragleave);
        document.addEventListener('dragover', self.handleDragover);
        document.addEventListener('drop', self.handleDrop);

        drp.on('dragenter', function() {
            self.debug && console.log('dragenter droparea event');
            drp.addClass('dragover');
        });
        drp.on('dragleave', function(e) {
            self.debug && console.log('dragleave droparea event', e);
            if ($(e.target).is(drp)) {
                drp.removeClass('dragover');
            }
        });
        drp.on('dragover', function(e) {
            e.preventDefault();
            self.debug && console.log('dragover droparea event');
        });
        drp.on('drop', function() {
            self.debug && console.log('drop droparea event');
            drp.removeClass('dragging dragover');
        });
    }

    // Sự kiện trên menu
    initMenuEvents() {
        const self = this;

        // Chọn tệp và đóng trình quản lý file
        self.menu.on('click', '[data-toggle="menu-file-select"]', function(e) {
            e.preventDefault();
            self.closeMenu();
            const file = $('[data-toggle="file"][data-uuid="' + $(this).data('uuid') + '"]', self.fms);
            if (!file.length) {
                return;
            }
            self.handleSelectFile(file);
        });

        // Tải về
        self.menu.on('click', '[data-toggle="menu-file-download"]', function(e) {
            e.preventDefault();
            self.closeMenu();
            const file = $('[data-toggle="file"][data-uuid="' + $(this).data('uuid') + '"]', self.fms);
            const tree = $('[data-toggle="tree-scroller"] .active', self.fms);
            if (!file.length || !tree.length) {
                return;
            }
            const src = script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=upload&' + nv_fc_variable + '=dlimg&path=' + tree.data('path') + '&img=' + file.data('name');
            $('[data-toggle="fms-iframe"]', self.fms).attr('src', src);
        });

        // Xem chi tiết
        self.menu.on('click', '[data-toggle="menu-file-preview"]', function(e) {
            e.preventDefault();
            self.closeMenu();
            const file = $('[data-toggle="file"][data-uuid="' + $(this).data('uuid') + '"]', self.fms);
            if (!file.length) {
                return;
            }
            self.showDialog('preview', file);
        });

        // Xóa file
        self.menu.on('click', '[data-toggle="menu-file-del"]', function(e) {
            e.preventDefault();
            const files = self.getSelectedFile();
            if (!files.length) {
                self.closeMenu();
                return;
            }
            const btn = $(this);
            const icon = $('i', btn);
            if (icon.is('.fa-spinner')) {
                return;
            }
            const cMess = files.length > 1 ? self.lang.delImgsConfirm.replace('%s', files.length) : (self.lang.delImgConfirm + ' <strong class="text-break">' + files.data('name') + '<strong>');
            nvConfirm({
                html: true,
                message: cMess
            }, () => {
                let fss = [];
                files.each(function() {
                    fss.push($(this).data('dir-path'));
                });
                fss = fss.join('|');
                icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
                $.ajax({
                    type: 'POST',
                    url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=upload&' + nv_fc_variable + '=delimg&nocache=' + new Date().getTime(),
                    data: {
                        files: fss,
                        checkss: $('body').data('checksess')
                    },
                    dataType: 'json',
                    cache: false,
                    success: function(res) {
                        icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                        if (res.status == 'error') {
                            nvToast(res.mess, 'error');
                            return;
                        }
                        self.closeMenu();
                        self.fetchFile();
                    },
                    error: function(xhr, text, err) {
                        icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                        nvToast(err, 'error');
                        console.log(xhr, text, err);
                    }
                });
            }, () => {
                self.closeMenu();
            });
        });

        // Đổi tên file
        self.menu.on('click', '[data-toggle="menu-file-rename"]', function(e) {
            e.preventDefault();
            self.closeMenu();
            const file = $('[data-toggle="file"][data-uuid="' + $(this).data('uuid') + '"]', self.fms);
            if (!file.length) {
                return;
            }
            self.showDialog('renamefile', file);
        });

        // Tạo thư mục con
        self.menu.on('click', '[data-toggle="menu-tree-create"]', function(e) {
            e.preventDefault();
            self.closeMenu();
            const tree = $('[data-toggle="tree"][data-uuid="' + $(this).data('uuid') + '"]', self.fms);
            if (!tree.length) {
                return;
            }
            self.showDialog('createfolder', tree);
        });

        // Tạo lại ảnh thumb
        self.menu.on('click', '[data-toggle="menu-tree-rethumb"]', function(e) {
            e.preventDefault();
            self.closeMenu();
            const tree = $('[data-toggle="tree"][data-uuid="' + $(this).data('uuid') + '"]', self.fms);
            if (!tree.length) {
                return;
            }
            self.showDialog('rethumb', tree);
        });

        // Đổi tên thư mục
        self.menu.on('click', '[data-toggle="menu-tree-rename"]', function(e) {
            e.preventDefault();
            self.closeMenu();
            const tree = $('[data-toggle="tree"][data-uuid="' + $(this).data('uuid') + '"]', self.fms);
            if (!tree.length) {
                return;
            }
            self.showDialog('renamefolder', tree);
        });

        // Xóa thư mục
        self.menu.on('click', '[data-toggle="menu-tree-delete"]', function(e) {
            e.preventDefault();
            const tree = $('[data-toggle="tree"][data-uuid="' + $(this).data('uuid') + '"]', self.fms);
            if (!tree.length) {
                self.closeMenu();
                return;
            }
            const btn = $(this);
            const icon = $('i', btn);
            if (icon.is('.fa-spinner')) {
                return;
            }
            nvConfirm(self.lang.delFolderConfirm, () => {
                icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
                $.ajax({
                    type: 'POST',
                    url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=upload&' + nv_fc_variable + '=delfolder&nocache=' + new Date().getTime(),
                    data: {
                        path: tree.data('path'),
                        checkss: $('body').data('checksess')
                    },
                    dataType: 'json',
                    cache: false,
                    success: function(res) {
                        icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                        if (res.status == 'error') {
                            nvToast(res.mess, 'error');
                            return;
                        }
                        self.closeMenu();
                        self.page = 1;
                        // Load lại tất cả, active thư mục cha của nó
                        self.fetchAll({
                            currentpath: tree.data('path').slice(0, tree.data('path').lastIndexOf('/'))
                        });
                    },
                    error: function(xhr, text, err) {
                        icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                        nvToast(err, 'error');
                        console.log(xhr, text, err);
                    }
                });
            }, () => {
                self.closeMenu();
            });
        });

        // Di chuyển file
        self.menu.on('click', '[data-toggle="menu-file-move"]', function(e) {
            e.preventDefault();
            self.closeMenu();
            const files = self.getSelectedFile();
            if (!files.length) {
                return;
            }
            self.showDialog('move', files);
        });

        // Tạo file WEBP
        self.menu.on('click', '[data-toggle="menu-file-webpconvert"]', function(e) {
            e.preventDefault();
            const file = self.getSelectedFile();
            if (file.length != 1) {
                self.closeMenu();
                return;
            }
            const btn = $(this);
            const icon = $('i', btn);
            if (icon.is('.fa-spinner')) {
                return;
            }
            icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=upload&' + nv_fc_variable + '=webpconvert&nocache=' + new Date().getTime(),
                data: {
                    path: file.data('dir'),
                    img: file.data('name'),
                    checkss: $('body').data('checksess')
                },
                dataType: 'json',
                cache: false,
                success: function(res) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    if (res.status == 'error') {
                        nvToast(res.mess, 'error');
                        return;
                    }
                    if (res.status == 'info') {
                        nvToast(res.mess, 'info');
                        return;
                    }
                    self.closeMenu();
                    self.page = 1;
                    self.resetFilter()
                    self.fetchFile({
                        selected: [res.file]
                    });
                },
                error: function(xhr, text, err) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    nvToast(err, 'error');
                    console.log(xhr, text, err);
                }
            });
        });

        // Chất lượng ảnh
        self.menu.on('click', '[data-toggle="menu-file-qualitychange"]', function(e) {
            e.preventDefault();
            self.closeMenu();
            const file = $('[data-toggle="file"][data-uuid="' + $(this).data('uuid') + '"]', self.fms);
            if (!file.length) {
                return;
            }
            self.showDialog('qualitychange', file);
        });

        // Thêm logo
        self.menu.on('click', '[data-toggle="menu-file-addlogo"]', function(e) {
            e.preventDefault();
            const file = self.getSelectedFile();
            self.closeMenu();
            if (file.length != 1) {
                return;
            }
            if (self.constant.logo == '') {
                nvToast(self.lang.notlogo, 'warning');
                return;
            }
            self.showDialog('addlogo', file);
        });

        // Cắt ảnh
        self.menu.on('click', '[data-toggle="menu-file-cropfile"]', function(e) {
            e.preventDefault();
            const file = self.getSelectedFile();
            self.closeMenu();
            if (file.length != 1) {
                return;
            }
            self.showDialog('cropfile', file);
        });

        // Tạo ảnh mới
        self.menu.on('click', '[data-toggle="menu-file-imgcreate"]', function(e) {
            e.preventDefault();
            const file = self.getSelectedFile();
            self.closeMenu();
            if (file.length != 1) {
                return;
            }
            self.showDialog('imgcreate', file);
        });

        // Xoay ảnh
        self.menu.on('click', '[data-toggle="menu-file-rotatefile"]', function(e) {
            e.preventDefault();
            const file = self.getSelectedFile();
            self.closeMenu();
            if (file.length != 1) {
                return;
            }
            self.showDialog('rotatefile', file);
        });

        // Nén ảnh
        self.menu.on('click', '[data-toggle="menu-file-compress"]', function(e) {
            e.preventDefault();
            const file = self.getSelectedFile();
            if (file.length != 1) {
                self.closeMenu();
                return;
            }
            const btn = $(this);
            const icon = $('i', btn);
            if (icon.is('.fa-spinner')) {
                return;
            }
            icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=upload&' + nv_fc_variable + '=compressimage&nocache=' + new Date().getTime(),
                data: {
                    path: file.data('dir'),
                    img: file.data('name'),
                    checkss: $('body').data('checksess')
                },
                dataType: 'json',
                cache: false,
                success: function(res) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    if (res.status == 'error') {
                        nvToast(res.mess, 'error');
                        return;
                    }
                    self.closeMenu();
                    self.page = 1;
                    self.resetFilter()
                    self.fetchFile({
                        selected: [res.file]
                    });
                },
                error: function(xhr, text, err) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    nvToast(err, 'error');
                    console.log(xhr, text, err);
                }
            });
        });
    }

    // Sự kiện trên file
    initFileEvents() {
        const self = this;

        // Mở menu chuột phải ở file
        self.fms.on('contextmenu', '[data-toggle="file"]', function(e) {
            const file = self.findFileFromEvent(e);
            if (!file) {
                return;
            }
            e.preventDefault();

            let files = self.getSelectedFile();
            if (files.filter(file).length == 0) {
                files = file;
                self.setSelectedFile(file);
            }
            self.openFileMenu(files, e);
        });

        // Check chọn file bằng input check
        self.fms.on('change', '[data-toggle="file-check"]', function() {
            const file = $(this).closest('[data-toggle="file"]');
            if ($(this).is(':checked')) {
                file.addClass('selected');
            } else {
                file.removeClass('selected');
            }
        });

        // Mở menu file bằng nút đổ xuống
        self.fms.on('click', '[data-toggle="file-menu"]', function(e) {
            const file = self.findFileFromEvent(e);
            if (!file) {
                return;
            }

            let files = self.getSelectedFile();
            if (files.filter(file).length == 0) {
                files = file;
                self.setSelectedFile(file);
            }
            self.openFileMenu(files, e);
        });
    }

    // Sự kiện trên thư mục
    initTreeEvents() {
        const self = this;

        /**
         * Mở menu chuột phải ở thư mục
         * Trên mobile trượt phải hoặc trượt trái tên thư mục
         */
        self.fms.on('contextmenu', '[data-toggle="tree-name"]', function(e) {
            self.openFolderMenu($(this).closest('li'), e);
        });

        // Mở menu thư mục khi click vào nút đổ menu (mobile)
        self.fms.on('click', '[data-toggle="tree-menu"]', function(e) {
            e.preventDefault();
            self.openFolderMenu($(this).closest('li'), e);
        });
    }

    // Sự kiện trên các dialog
    initDialogEvents() {
        const self = this;

        // Zoom ảnh
        self.fmd.on('click', '[data-toggle="preview-zoom-in"]', function() {
            const dialog = $(this).closest('.fmd');
            if (!$(this).is('.is-img')) {
                return;
            }
            $('[data-toggle="zoom-ctn"]', dialog).addClass('show');
        });

        // Zoom đóng zoom
        self.fmd.on('click', '[data-toggle="preview-zoom-out"]', function(e) {
            e.preventDefault();
            const dialog = $(this).closest('.fmd');
            $('[data-toggle="zoom-ctn"]', dialog).removeClass('show');
        });

        // Submit tạo ảnh thumb
        self.fmd.on('click', '[data-toggle="rethumb-btn-submit"]', function(e) {
            e.preventDefault();
            const dialog = $(this).closest('.fmd');
            $('[data-toggle="btns"]', dialog).addClass('d-none');
            $('[data-toggle="note"]', dialog).addClass('d-none');
            $('[data-toggle="load"]', dialog).removeClass('d-none');
            self.runReThumb(-1);
        });

        // Thay đổi chất lượng ảnh (thay đổi tùy chọn xem trước)
        self.fmd.on('change', '[data-toggle="qualitychangeopt"]', function() {
            const btn = $(this);
            const dialog = btn.closest('.fmd');
            const file = $('[data-toggle="file"][data-uuid="' + dialog.data('uuid') + '"]', self.fms);
            if (!file.length) {
                return;
            }
            const quality = btn.val();
            const img1 = $('[data-toggle="preview-zoom-in"]', dialog);
            const img2 = $('[data-toggle="orig-img"]', dialog);
            // Không thay đổi chất lượng
            if (quality == '') {
                img1.attr('src', file.data('thumb-src'));
                img2.attr('src', file.data('nocache-path'));
                $('[data-toggle="sizenew"]', dialog).text(file.data('filesize'));
                return;
            }

            // Cố định kích thước ảnh nhỏ
            if (!img1.data('w')) {
                img1.data('w', true);
                img1.css({
                    width: (img1.innerWidth() + 'px'),
                    height: (img1.innerHeight() + 'px')
                });
            }

            btn.prop('disabled', true);
            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=upload&' + nv_fc_variable + '=qualitychange&nocache=' + new Date().getTime(),
                data: {
                    path: file.data('dir'),
                    img: file.data('name'),
                    quality: quality,
                    preview: 1,
                    checkss: $('body').data('checksess')
                },
                dataType: 'json',
                cache: false,
                success: function(res) {
                    btn.prop('disabled', false);
                    if (res.status == 'error') {
                        nvToast(res.mess, 'error');
                        return;
                    }
                    img1.attr('src', res.imgdata);
                    img2.attr('src', res.imgdata);
                    $('[data-toggle="sizenew"]', dialog).text(res.imglength);
                },
                error: function(xhr, text, err) {
                    btn.prop('disabled', false);
                    nvToast(err, 'error');
                    console.log(xhr, text, err);
                }
            });
        });

        // Dialog tạo ảnh mới
        self.fmd.on('keyup change', '[data-toggle="imgcreate-val"]', function() {
            const dialog = $(this).closest('.fmd');
            const file = $('[data-toggle="file"][data-uuid="' + dialog.data('uuid') + '"]', self.fms);
            if (!file.length) {
                return;
            }
            const ipt = $(this);
            const val = parseInt(ipt.val());
            const img = $('[data-toggle="image"]', dialog);

            if (isNaN(val)) {
                $('[name="width"]', dialog).val('');
                $('[name="height"]', dialog).val('');
                img.removeAttr('style');
                return;
            }

            if (ipt.data('type') == 'w') {
                // Nhập chiều rộng tính chiều cao
                const size = self.calImageSize(file.data('width'), file.data('height'), val, null);
                $('[name="height"]', dialog).val(size.height);
                img.css({ width: (val + 'px') });
            } else {
                // Nhập chiều cao tính chiều rộng
                const size = self.calImageSize(file.data('width'), file.data('height'), null, val);
                $('[name="width"]', dialog).val(size.width);
                img.css({ width: (size.width + 'px') });
            }
        });

        // Dialog xoay ảnh
        self.fmd.on('keyup change', '[data-toggle="rotatefile-direction"]', function() {
            const dialog = $(this).closest('.fmd');
            const file = $('[data-toggle="file"][data-uuid="' + dialog.data('uuid') + '"]', self.fms);
            if (!file.length) {
                return;
            }
            const ipt = $(this);
            let val = parseInt(ipt.val());
            if (isNaN(val) || val < 0 || val > 359) {
                val = 0;
                ipt.val(val);
            }
            const img = $('[data-toggle="image"]', dialog);
            img.css({
                transform: ('rotate(' + val + 'deg)')
            });
        });
        self.fmd.on('click', '[data-toggle="rotatefile-btn90"]', function() {
            const dialog = $(this).closest('.fmd');
            const file = $('[data-toggle="file"][data-uuid="' + dialog.data('uuid') + '"]', self.fms);
            if (!file.length) {
                return;
            }
            const btn = $(this);
            const ipt = $('[name="direction"]', dialog);
            let val = parseInt(ipt.val());
            if (isNaN(val) || val < 0 || val > 359) {
                val = 0;
            }

            val = self.roundToDirection(val, btn.data('type'));
            ipt.val(val);
            const img = $('[data-toggle="image"]', dialog);
            img.css({
                transform: ('rotate(' + val + 'deg)')
            });
        });
    }

    // Tạo các thanh cuộn
    initScrollbar() {
        this.destroyScrollbar();
        this.fs = new PerfectScrollbar($('[data-toggle="file-scroller"]', this.fms)[0], {
            wheelPropagation: false
        });
        this.ts = new PerfectScrollbar($('[data-toggle="tree-scroller"]', this.fms)[0], {
            wheelPropagation: false
        });
        this.qs = new PerfectScrollbar($('[data-toggle="queue-scroller"]', this.fms)[0], {
            wheelPropagation: false
        });
    }

    // Xóa các thanh cuộn
    destroyScrollbar() {
        if (this.fs) {
            this.fs.destroy();
            this.fs = null;
        }
        if (this.ts) {
            this.ts.destroy();
            this.ts = null;
        }
        if (this.qs) {
            this.qs.destroy();
            this.qs = null;
        }
    }

    // Đóng trình quản lý tệp tin dạng popup
    hideModal() {
        const self = this;
        self.isFocused = false;

        if (self.fmm) {
            self.fmm.removeClass('show');

            document.removeEventListener('dragenter', self.handleDragenter);
            document.removeEventListener('dragleave', self.handleDragleave);
            document.removeEventListener('dragover', self.handleDragover);
            document.removeEventListener('drop', self.handleDrop);

            document.removeEventListener('mousedown', self.handleDocTapStart);
            document.removeEventListener('touchstart', self.handleDocTapStart);
            document.removeEventListener('mousemove', self.handleDocTapMove);
            document.removeEventListener('touchmove', self.handleDocTapMove);
            document.removeEventListener('mouseup', self.handleDocTapEnd);
            document.removeEventListener('touchend', self.handleDocTapEnd);
            document.removeEventListener('touchcancel', self.handleDocTapEnd);
            document.removeEventListener('keydown', self.handleDocKeydown);

            setTimeout(() => {
                self.fmm.remove();
                self.fmm = null;
                self.fmd.remove();
                self.fmd = null;
                self.fms = null;
                self.fs = null;
                self.ts = null;
                self.qs = null;
                self.up = null;

                self.menu.remove();
                self.menu = null;
            }, 300);
        }

        if (self.backdrop) {
            self.backdrop.removeClass('show');
            setTimeout(() => {
                self.backdrop.remove();
                self.backdrop = null;
            }, 150);
        }

        // Trả lại các thuộc tính style của body
        setTimeout(() => {
            const body = document.body;
            body.style.paddingRight = self.bodyEndPadding;
            body.style.overflow = self.bodyOverflow;
            if (body.getAttribute('style') === '') {
                body.removeAttribute('style');
            }
            $('body').removeClass('fmm-open');
        }, 300);
    }

    // Mở modal lên để chuẩn bị xây dựng trình quản lý tệp tin
    showModal() {
        const self = this;
        self.isFocused = true;

        // Tạo HTML cho modal và lắng nghe các sự kiện
        self.fmm = $(self.htmlModal);
        self.fmd = $(self.htmlDialog);
        $('body').append(self.fmm);
        $('body').append(self.fmd);

        self.menu = $(self.menuTpl);
        $('body').append(self.menu);

        self.fmm.on('click', '[data-dismiss="fmm"]', function() {
            self.hideModal();
        });

        // Đình chỉ thanh cuộn của body
        const body = document.body;
        self.bodyEndPadding = body.style.paddingRight;
        self.bodyOverflow = body.style.overflow;
        self.bodyVScroll = document.documentElement.scrollHeight > window.innerHeight;

        self.bodyVScroll && (body.style.paddingRight = self.scrollbarWidth() + 'px');
        body.style.overflow = 'hidden';

        $('body').addClass('fmm-open');

        // Tạo hiệu ứng của nền mỗi lần mở modal
        self.backdrop = $(self.htmlModalBackdrop);
        $('body').append(self.backdrop);
        setTimeout(() => {
            self.backdrop && self.backdrop.addClass('show');
        }, 1);

        // Hiệu ứng mở modal lên
        self.fmm[0].style.display = 'block';
        setTimeout(() => {
            self.fmm.addClass('show');
            self.fmm.attr('aria-modal', 'true');
            self.fmm.removeAttr('aria-hidden');
        }, 1);

        setTimeout(() => {
            self.fms = $(self.htmlContainer);
            $('[data-toggle="fmm-body"]', self.fmm).html(self.fms);
            self.initContainer();
        }, 310);
    }

    fetchTree(options) {
        this.fetch(true, false, options);
    }

    fetchFile(options) {
        this.fetch(false, true, options);
    }

    fetchAll(options) {
        this.fetch(true, true, options);
    }

    fetch(tree, file, options) {
        const self = this;
        options = options || {};

        self.showLoader();

        let pr = {
            checkss: $('body').data('checksess'),
            show_file: file ? 1 : 0,
            show_folder: tree ? 1 : 0,
            path: self.settings.path,
            currentpath: self.settings.currentpath,
            type: self.settings.type,
            currentfile: '',
            type: $('[data-toggle="filter-type"]', self.fms).data('type'),
            author: $('[data-toggle="filter-author"]', self.fms).data('author'),
            order: $('[data-toggle="filter-order"]', self.fms).data('order'),
            page: self.page
        };
        const activeDir = $('[data-toggle="tree-scroller"] .active', self.fms);
        if (options.currentpath) {
            // Chỉ định thư mục active cụ thể
            pr.currentpath = options.currentpath;
        } else if (activeDir.length) {
            // Lấy thư mục active trong cây thư mục nếu có
            pr.currentpath = activeDir.data('dir');
        }
        // Tự chọn tệp active từ imgfile
        if (!options.selected && self.settings.imgfile != '' && options.init) {
            options.selected = [self.settings.imgfile.split('/').pop()];
        }
        // Thiết lập tệp được chọn ở lần đầu
        if (options.init) {
            pr.currentfile = self.settings.imgfile;
        }

        // Reload lại cây thư mục và tệp tin
        if (self.refresh) {
            self.refresh = false;
            pr.dirListRefresh = 1;
            pr.refresh = 1;
        }

        // Tìm theo từ khóa
        const q = $('[data-toggle="filter-q"]', self.fms);
        if (q.length && q.data('q') && q.data('q').toString().length > 0) {
            pr.q = q.data('q');
        }

        $.ajax({
            type: 'POST',
            url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=upload&' + nv_fc_variable + '=main&nocache=' + new Date().getTime(),
            data: pr,
            dataType: 'json',
            cache: false,
            success: function(respon) {
                self.hideLoader();
                if (respon.status != 'success') {
                    nvToast(respon.mess, 'error');
                    return;
                }
                if (tree) {
                    $('[data-toggle="tree-scroller"]', self.fms).html(respon.folders);
                    self.ts && self.ts.update();
                    self.initUploader();
                }
                if (file) {
                    const fileCtn = $('[data-toggle="file-scroller"]', self.fms);
                    fileCtn.html(respon.files);
                    $('[data-toggle="pagination"]', self.fms).html(respon.pagination);

                    self.switchView(respon.view);

                    // Chọn tệp
                    if (options.selected) {
                        $('.selected', fileCtn).removeClass('selected');
                        $('[data-toggle="file-check"]', fileCtn).prop('checked', false);
                        options.selected.forEach(fname => {
                            const file = $('[data-toggle="file"][data-name="' + fname + '"]', fileCtn);
                            if (file.length != 1) {
                                return;
                            }
                            file.addClass('selected');
                            $('[data-toggle="file-check"]', file).prop('checked', true);
                        });
                    }

                    self.fs && self.fs.update();
                }
            },
            error: function(xhr, text, err) {
                self.hideLoader();
                nvToast(err, 'error');
                console.log(xhr, text, err);
            }
        });
    }

    // Hiển thị loader, chặn thao tác
    showLoader() {
        $('[data-toggle="loader"]', this.fms).addClass('show');
    }

    // Ẩn loader, cho phép thao tác
    hideLoader() {
        $('[data-toggle="loader"]', this.fms).removeClass('show');
    }

    /**
     * Khởi tạo tĩnh theo cách nukeviet.Picker.getOrCreateInstance()
     */
    static getOrCreateInstance(selector, options) {
        let element;
        if (selector instanceof Element) {
            element = selector;
        } else {
            element = document.querySelector(selector);
        }
        if (!$.data(element, 'nv.picker')) {
            $.data(element, 'nv.picker', new nukeviet.Picker(element, options));
        }
        return $.data(element, 'nv.picker');
    }

    // Độ rộng thanh cuộn
    scrollbarWidth = () => {
        const outer = document.createElement('div');
        outer.style.visibility = 'hidden';
        outer.style.overflow = 'scroll';
        outer.style.msOverflowStyle = 'scrollbar';
        outer.style.position = 'fixed';
        document.body.appendChild(outer);

        const inner = document.createElement('div');
        outer.appendChild(inner);

        const scrollbarWidth = outer.offsetWidth - inner.offsetWidth;

        outer.parentNode.removeChild(outer);

        return scrollbarWidth;
    }

    // Lấy số trang từ link phân trang
    getPage(a) {
        const link = $(a).attr('href');
        if (link == '#') {
            return null;
        }
        const match = link.match(/\?page=(\d+)/);
        if (!match) {
            return 1;
        }
        return parseInt(match[1], 10);
    }

    // Đổi kiểu hiển thị danh sách - lưới
    switchView(view) {
        const self = this;
        const btn = $('[data-toggle="list-grid"]', self.fms);
        const icon = $('i', btn);
        if (view == btn.data('view')) {
            return;
        }
        if (view == 'grid') {
            icon.removeClass(btn.data('icon-list')).addClass(btn.data('icon-grid'));
            $('[data-toggle="file-scroller"]', self.fms).removeClass('view-list').addClass('view-grid');
            btn.data('view', 'grid');
            btn.attr('aria-label', btn.data('label-grid'));
        } else {
            icon.removeClass(btn.data('icon-grid')).addClass(btn.data('icon-list'));
            $('[data-toggle="file-scroller"]', self.fms).removeClass('view-grid').addClass('view-list');
            btn.data('view', 'list');
            btn.attr('aria-label', btn.data('label-list'));
        }
    }

    // Tạo is ngẫu nhiên
    ranid() {
        const characters = 'abcdefghijklmnopqrstuvwxyz123456789';
        const length = 10;
        let result = '';
        for (let i = 0; i < length; i++) {
            const randomIndex = Math.floor(Math.random() * characters.length);
            result += characters[randomIndex];
        }
        return result;
    }

    // Xử lý ẩn error trong các form ở dialog
    handleDialogError() {
        const self = this;
        $('select', self.fmd).on('change keyup', function() {
            $(this).removeClass('is-invalid is-valid');
            if ($(this).parent().is('.input-group')) {
                $(this).parent().removeClass('is-invalid is-valid');
            }
        });
        $('[type="text"], [type="password"], [type="number"], [type="email"], textarea', self.fmd).on('change keyup', function(e) {
            if (e.type == "keyup" && e.which == 13) {
                return;
            }
            let pr = $(this).parent();
            let prAlso = $(this).parent().is('.input-group');
            if (trim($(this).val()) == '' && $(this).is('.required')) {
                $(this).addClass('is-invalid');
                (prAlso && pr.addClass('is-invalid'));
            } else {
                $(this).removeClass('is-invalid is-valid');
                (prAlso && pr.removeClass('is-invalid is-valid'));
            }
        });
    }

    // Hiển thị các dialog
    showDialog(name, extra) {
        const self = this;
        const dig = self.fmd.filter('[data-dialog="' + name + '"]');
        if (dig.length != 1 || dig.is('.show')) {
            return;
        }

        // Đình chỉ thanh cuộn của body
        const body = document.body;
        self.bodyDigEndPadding = body.style.paddingRight;
        self.bodyDigOverflow = body.style.overflow;
        self.bodyDigVScroll = document.documentElement.scrollHeight > window.innerHeight;

        self.bodyDigVScroll && (body.style.paddingRight = self.scrollbarWidth() + 'px');
        body.style.overflow = 'hidden';
        $('body').addClass('fmd-open');

        // Tạo hiệu ứng của nền mỗi lần mở modal
        self.backdropDig = $(self.htmlDialogBackdrop);
        $('body').append(self.backdropDig);
        setTimeout(() => {
            self.backdropDig && self.backdropDig.addClass('show');
        }, 1);

        // Hiệu ứng mở modal lên
        dig[0].style.display = 'block';
        setTimeout(() => {
            dig.addClass('show');
            dig.attr('aria-modal', 'true');
            dig.removeAttr('aria-hidden');
            self.showDialogCallback(name, dig, extra);
        }, 1);
        setTimeout(() => {
            self.shownDialogCallback(name, dig);
        }, 301);
    }

    // Xử lý khi Dialog bắt đầu mở lên
    showDialogCallback(name, dialog, extra) {
        const self = this;

        // Form tìm kiếm
        if (name == 'search') {
            $('[name="dir"]', dialog).html('');

            $('[data-toggle="tree-name"]', self.fms).each(function() {
                const tree = $(this).closest('li');
                if (tree.data('dir') == '') {
                    return;
                }
                const opt = $('<option></option>');
                opt.attr('value', tree.data('uuid'));
                opt.text(tree.data('path'));
                if (tree.is('.active')) {
                    opt.prop('selected', true);
                }
                $('[name="dir"]', dialog).append(opt);
            });

            $('[name="q"]', dialog).val('');
            return;
        }

        // Upload file từ internet
        if (name == 'upload-remote') {
            $('[name="fileurl"]', dialog).val('');
            $('[name="filealt"]', dialog).val('');
            $('[name="path"]', dialog).val(self.getCurrentPath());
            return;
        }

        // Xem chi tiết
        if (name == 'preview') {
            const file = extra;
            $('[data-toggle="alt"]', dialog).text(file.data('alt'));
            $('[data-toggle="filename"]', dialog).text(file.data('name'));
            $('[data-toggle="mtime"]', dialog).text(file.data('mtime'));
            $('[data-toggle="size"]', dialog).text(file.data('preview-size'));
            $('[name="relative"]', dialog).val(file.data('path'));
            $('[name="absolute"]', dialog).val(file.data('abs-path'));

            const clipb1 = new ClipboardJS($('[data-toggle="btn-relative"]', dialog)[0]);
            clipb1.on('success', () => {
                nvToast(self.lang.copied, 'success');
            });
            $('[name="relative"]', dialog).data('clipb', clipb1);

            const clipb2 = new ClipboardJS($('[data-toggle="btn-absolute"]', dialog)[0]);
            clipb2.on('success', () => {
                nvToast(self.lang.copied, 'success');
            });
            $('[name="absolute"]', dialog).data('clipb', clipb2);

            // Ảnh nhỏ
            const img1 = $('[data-toggle="preview-zoom-in"]', dialog);
            img1.attr('alt', file.data('alt'));
            img1.attr('src', file.data('thumb-src'));

            if (file.data('type') == 'image') {
                img1.addClass('is-img');

                // Ảnh lớn
                const img2 = $('[data-toggle="orig-img"]', dialog);
                img2.attr('alt', file.data('alt'));
                img2.attr('src', file.data('nocache-path'));

                const rImg = file.data('width') / file.data('height');
                const rScr = window.innerWidth / window.innerHeight;

                if (rScr >= rImg) {
                    img2.addClass('orig-img-v');
                }
            }

            return;
        }

        // Đổi tên file
        if (name == 'renamefile') {
            const file = extra;

            $('[data-toggle="name"]', dialog).text(file.data('name'));
            $('[data-toggle="ext"]', dialog).text('.' + file.data('ext'));
            $('[name="newname"]', dialog).val(file.data('name').substring(0, file.data('name').lastIndexOf('.')));
            $('[name="newalt"]', dialog).val(file.data('alt'));
            $('[name="path"]', dialog).val(self.getCurrentPath());
            $('[name="file"]', dialog).val(file.data('name'));
            $('[name="checkss"]', dialog).val($('body').data('checksess'));
        }

        // Lọc trên mobile
        if (name == 'filter') {
            const sType = $('[name="type"]', dialog);
            const sAuthor = $('[name="author"]', dialog);
            const sOrder = $('[name="order"]', dialog);

            const dType = $('[data-toggle="filter-type"]', self.fms);
            const dAuthor = $('[data-toggle="filter-author"]', self.fms);
            const dOrder = $('[data-toggle="filter-order"]', self.fms);

            $('a', dType).each(function() {
                const opt = $('<option></option>');
                opt.text($(this).text());
                opt.attr('value', $(this).data('type'));
                if ($(this).data('type') == dType.data('type')) {
                    opt.prop('selected', true);
                }
                sType.append(opt);
            });
            $('a', dAuthor).each(function() {
                const opt = $('<option></option>');
                opt.text($(this).text());
                opt.attr('value', $(this).data('author'));
                if ($(this).data('author') == dAuthor.data('author')) {
                    opt.prop('selected', true);
                }
                sAuthor.append(opt);
            });
            $('a', dOrder).each(function() {
                const opt = $('<option></option>');
                opt.text($(this).text());
                opt.attr('value', $(this).data('order'));
                if ($(this).data('order') == dOrder.data('order')) {
                    opt.prop('selected', true);
                }
                sOrder.append(opt);
            });
        }

        // Tạo thư mục con
        if (name == 'createfolder') {
            const tree = extra;
            $('[name="path"]', dialog).val(tree.data('path'));
            $('[name="checkss"]', dialog).val($('body').data('checksess'));
            return;
        }

        // Đổi tên thư mục
        if (name == 'renamefolder') {
            const tree = extra;
            $('[name="newname"]', dialog).val(tree.data('title'));
            $('[name="path"]', dialog).val(tree.data('path'));
            $('[name="checkss"]', dialog).val($('body').data('checksess'));
            return;
        }

        // Tạo lại ảnh thumb
        if (name == 'rethumb') {
            const tree = extra;
            dialog.data('path', tree.data('path'));
            return;
        }

        // Di chuyển file
        if (name == 'move') {
            const files = extra;
            $('[name="checkss"]', dialog).val($('body').data('checksess'));

            if (files.length > 1) {
                $('[data-toggle="name"]', dialog).text(self.lang.moveMultiple.replace('%s', files.length));
            } else {
                $('[data-toggle="name"]', dialog).text(files.data('dir-path'));
            }

            let fss = [];
            files.each(function() {
                fss.push($(this).data('dir-path'));
            });
            fss = fss.join('|');
            $('[name="files"]', dialog).val(fss);

            const selPath = $(files[0]).data('dir');
            $('[data-toggle="tree-name"]', self.fms).each(function() {
                const tree = $(this).closest('li');
                if (tree.data('dir') == '' || !tree.data('allowed-create-file')) {
                    return;
                }
                const opt = $('<option></option>');
                opt.attr('value', tree.data('path'));
                opt.data('uuid', tree.data('uuid'));
                opt.text(tree.data('path'));
                if (tree.data('path') == selPath) {
                    opt.prop('selected', true);
                }
                $('[name="newpath"]', dialog).append(opt);
            });
        }

        // Chất lượng ảnh
        if (name == 'qualitychange') {
            const file = extra;

            dialog.data('uuid', file.data('uuid'));
            $('[name="checkss"]', dialog).val($('body').data('checksess'));
            $('[name="path"]', dialog).val(file.data('dir'));
            $('[name="img"]', dialog).val(file.data('name'));
            $('[data-toggle="sizeoriginal"]', dialog).text(file.data('filesize'));
            $('[data-toggle="sizenew"]', dialog).text(file.data('filesize'));

            // Ảnh nhỏ
            const img1 = $('[data-toggle="preview-zoom-in"]', dialog);
            img1.attr('alt', file.data('alt'));
            img1.attr('src', file.data('thumb-src'));

            // Ảnh lớn
            const img2 = $('[data-toggle="orig-img"]', dialog);
            img2.attr('alt', file.data('alt'));
            img2.attr('src', file.data('nocache-path'));

            const rImg = file.data('width') / file.data('height');
            const rScr = window.innerWidth / window.innerHeight;

            if (rScr >= rImg) {
                img2.addClass('orig-img-v');
            }
        }

        // Thêm logo
        if (name == 'addlogo') {
            const file = extra;
            const ctn = $('[data-toggle="logo-ctn"]', dialog);
            const maxWidth = ctn.innerWidth();
            const maxHeight = maxWidth;

            $('[name="checkss"]', dialog).val($('body').data('checksess'));
            $('[name="path"]', dialog).val(file.data('dir'));
            $('[name="file"]', dialog).val(file.data('name'));

            let imgWidth = file.data('width');
            let imgHeight = file.data('height');
            const ratio = imgWidth / imgHeight;

            if (imgWidth > maxWidth || imgHeight > maxHeight) {
                if (ratio > 1) {
                    // Ảnh theo chiều ngang
                    imgWidth = maxWidth;
                    imgHeight = Math.floor(maxWidth / ratio);
                } else {
                    // Ảnh theo chiều dọc
                    imgHeight = maxHeight;
                    imgWidth = Math.floor(maxHeight * ratio);
                }
            }

            $('[data-toggle="logo-area"]', dialog).css({
                width: (imgWidth + 'px'),
                height: (imgHeight + 'px')
            });
            $('[data-toggle="image"]', dialog).attr('src', file.data('nocache-path')).attr('alt', file.data('alt'));

            // Tính toán kích thước của logo
            let markW, markH;
            if (imgWidth <= 150) {
                markW = Math.ceil(imgWidth * self.constant.logoSize.sizeS / 100);
            } else if (imgWidth < 350) {
                markW = Math.ceil(imgWidth * self.constant.logoSize.sizeM / 100);
            } else {
                if (Math.ceil(imgWidth * self.constant.logoSize.sizeL / 100) > self.constant.logoSize.width) {
                    markW = self.constant.logoSize.width;
                } else {
                    markW = Math.ceil(imgWidth * self.constant.logoSize.sizeL / 100);
                }
            }

            markH = Math.ceil(markW * self.constant.logoSize.height / self.constant.logoSize.width);
            if (markH > imgHeight) {
                markH = imgHeight;
                markW = Math.ceil(markH * self.constant.logoSize.width / self.constant.logoSize.height);
            }

            $('[data-toggle="image"]', dialog).cropper({
                viewMode: 3,
                dragMode: 'none',
                aspectRatio: markW / markH,
                responsive: true,
                modal: true,
                guides: false,
                highlight: true,
                autoCrop: false,
                autoCropArea: .01,
                movable: false,
                rotatable: false,
                scalable: false,
                zoomable: false,
                zoomOnTouch: false,
                zoomOnWheel: false,
                cropBoxMovable: true,
                cropBoxResizable: true,
                minContainerWidth: 10,
                minContainerHeight: 10,
                crop: function(e) {
                    $('[name="x"]', dialog).val(parseInt(Math.floor(e.x)));
                    $('[name="y"]', dialog).val(parseInt(Math.floor(e.y)));
                    $('[name="w"]', dialog).val(parseInt(Math.floor(e.width)));
                    $('[name="h"]', dialog).val(parseInt(Math.floor(e.height)));
                },
                built: function() {
                    var imageData = $(this).cropper('getImageData');
                    var cropBoxScale = imageData.naturalWidth / imageData.width;
                    var cropBoxSize = {
                        width: markW / cropBoxScale,
                        height: markH / cropBoxScale
                    };
                    cropBoxSize.left = imageData.width - cropBoxSize.width - 10;
                    cropBoxSize.top = imageData.height - cropBoxSize.height - 10;
                    $(this).cropper('crop');
                    $(this).cropper('setCropBoxData', {
                        left: cropBoxSize.left,
                        top: cropBoxSize.top,
                        width: cropBoxSize.width,
                        height: cropBoxSize.height
                    });
                    var wrapCropper = $(this).parent();
                    $('.cropper-face', wrapCropper).css({
                        'opacity': 1,
                        'background-image': 'url(' + self.constant.logo + ')',
                        'background-size': '100%',
                        'background-color': 'transparent'
                    });
                }
            });
        }

        // Cắt ảnh
        if (name == 'cropfile') {
            const file = extra;
            const ctn = $('[data-toggle="logo-ctn"]', dialog);
            const maxWidth = ctn.innerWidth();
            const maxHeight = maxWidth;

            $('[name="checkss"]', dialog).val($('body').data('checksess'));
            $('[name="path"]', dialog).val(file.data('dir'));
            $('[name="file"]', dialog).val(file.data('name'));

            let imgWidth = file.data('width');
            let imgHeight = file.data('height');
            const ratio = imgWidth / imgHeight;

            if (imgWidth > maxWidth || imgHeight > maxHeight) {
                if (ratio > 1) {
                    // Ảnh theo chiều ngang
                    imgWidth = maxWidth;
                    imgHeight = Math.floor(maxWidth / ratio);
                } else {
                    // Ảnh theo chiều dọc
                    imgHeight = maxHeight;
                    imgWidth = Math.floor(maxHeight * ratio);
                }
            }

            $('[data-toggle="logo-area"]', dialog).css({
                width: (imgWidth + 'px'),
                height: (imgHeight + 'px')
            });
            $('[data-toggle="image"]', dialog).attr('src', file.data('nocache-path')).attr('alt', file.data('alt'));
            $('[data-toggle="image"]', dialog).cropper({
                viewMode: 3,
                dragMode: 'crop',
                aspectRatio: NaN,
                responsive: true,
                modal: true,
                guides: false,
                highlight: true,
                autoCrop: true,
                autoCropArea: 0.5,
                movable: false,
                rotatable: false,
                scalable: false,
                zoomable: false,
                zoomOnTouch: false,
                zoomOnWheel: false,
                cropBoxMovable: true,
                cropBoxResizable: true,
                minContainerWidth: 10,
                minContainerHeight: 10,
                crop: function(e) {
                    $('[name="x"]', dialog).val(parseInt(Math.floor(e.x)));
                    $('[name="y"]', dialog).val(parseInt(Math.floor(e.y)));
                    $('[name="w"]', dialog).val(parseInt(Math.floor(e.width)));
                    $('[name="h"]', dialog).val(parseInt(Math.floor(e.height)));
                }
            });
        }

        // Công cụ tạo ảnh mới
        if (name == 'imgcreate') {
            const file = extra;

            dialog.data('uuid', file.data('uuid'));
            $('[name="checkss"]', dialog).val($('body').data('checksess'));
            $('[name="path"]', dialog).val(file.data('dir'));
            $('[name="img"]', dialog).val(file.data('name'));

            $('[data-toggle="name"]', dialog).text(file.data('name'));
            $('[data-toggle="ogrisize"]', dialog).text(file.data('width') + ' x ' + file.data('height') + ' px');
            const img = $('[data-toggle="image"]', dialog);
            img.attr('alt', file.data('alt'));
            img.attr('src', file.data('nocache-path'));

            const limitMin = self.calImageSize(file.data('width'), file.data('height'), 1);
            const limitMax = self.calImageSize(file.data('width'), file.data('height'), 999999);
            $('[data-toggle="limitsize"]', dialog).text(self.lang.limitMax + ': ' + limitMax.width + ' x ' + limitMax.height + ', ' + self.lang.limitMin + ': ' + limitMin.width + ' x ' + limitMin.height + ' (pixels)');

            $('[name="width"]', dialog).prop('min', limitMin.width).prop('max', limitMax.width);
            $('[name="height"]', dialog).prop('min', limitMin.height).prop('max', limitMax.height);
        }

        // Xoay ảnh
        if (name == 'rotatefile') {
            const file = extra;

            dialog.data('uuid', file.data('uuid'));
            $('[name="checkss"]', dialog).val($('body').data('checksess'));
            $('[name="path"]', dialog).val(file.data('dir'));
            $('[name="file"]', dialog).val(file.data('name'));

            const size = self.calScaledSize(file.data('width'), file.data('height'), $('[data-toggle="display"]', dialog).innerWidth());
            const img = $('[data-toggle="image"]', dialog);
            img.attr('alt', file.data('alt'));
            img.attr('src', file.data('nocache-path'));
            img.css({
                width: (size.displayWidth + 'px'),
                height: (size.displayHeight + 'px')
            });
        }
    }

    // Xử lý sau khi Dialog được mở lên
    shownDialogCallback(name, dialog) {
        // Tìm kiếm
        if (name == 'search') {
            $('[name="q"]', dialog).focus();
            return;
        }

        // Upload file từ internet
        if (name == 'upload-remote') {
            $('[name="fileurl"]', dialog).focus();
            return;
        }

        // Đổi tên file, tạo thư mục con
        if (name == 'renamefile' || name == 'createfolder' || name == 'renamefolder') {
            $('[name="newname"]', dialog).focus();
            return;
        }

        // Tạo ảnh mới
        if (name == 'imgcreate') {
            $('[name="width"]', dialog).focus();
            return;
        }
    }

    // Xử lý sau khi Dialog đóng lại
    hideDialogCallback(dialog) {
        // Reset các đối tượng chung
        $('.is-invalid', dialog).removeClass('is-invalid');
        $('.dialog-text', dialog).text('');
        $('.dialog-val', dialog).val('');
        $('.dialog-zero-val', dialog).val('0');
        $('.dialog-html', dialog).html('');
        $('.dialog-select', dialog).find('option').prop('selected', false);

        // Xem chi tiết
        if (dialog.data('dialog') == 'preview') {
            $('[data-toggle="alt"]', dialog).text('');
            $('[data-toggle="filename"]', dialog).text('');
            $('[data-toggle="mtime"]', dialog).text('');
            $('[data-toggle="size"]', dialog).text('');
            $('[type="text"]', dialog).val('');
            $('[data-toggle="orig-img"]', dialog).removeClass('orig-img-v');
            $('[data-toggle="preview-zoom-in"]', dialog).removeClass('is-img');

            // Cả ảnh nhỏ và ảnh lớn
            const img = $('img', dialog);
            img.attr('alt', '');
            img.attr('src', img.data('pix'));

            $('[name="relative"]', dialog).data('clipb').destroy();
            $('[name="absolute"]', dialog).data('clipb').destroy();

            return;
        }

        // Lọc trên mobile
        if (dialog.data('dialog') == 'filter') {
            $('select', dialog).html('');
        }

        // Tạo lại ảnh thumb
        if (dialog.data('dialog') == 'rethumb') {
            $('[data-toggle="load"]', dialog).addClass('d-none');
            $('[data-toggle="progress"]', dialog).addClass('d-none');
            $('[data-toggle="finish"]', dialog).addClass('d-none');
            $('[data-toggle="btns"]', dialog).removeClass('d-none');
            $('[data-toggle="note"]', dialog).removeClass('d-none');

            $('[data-toggle="progress-aria"]', dialog).attr('aria-valuenow', '0');
            $('[data-toggle="progress-val"]', dialog).css({
                width: '0%'
            });
        }

        // Chất lượng ảnh
        if (dialog.data('dialog') == 'qualitychange') {
            $('[data-toggle="orig-img"]', dialog).removeClass('orig-img-v');
            $('[data-toggle="preview-zoom-in"]', dialog).removeAttr('style').data('w', false);

            // Cả ảnh nhỏ và ảnh lớn
            const img = $('img', dialog);
            img.attr('alt', '');
            img.attr('src', img.data('pix'));
            return;
        }

        // Thêm logo + cắt ảnh
        if (dialog.data('dialog') == 'addlogo' || dialog.data('dialog') == 'cropfile') {
            $('[data-toggle="logo-area"]', dialog).removeAttr('style');

            const img = $('[data-toggle="image"]', dialog);
            img.cropper('destroy');
            img.attr('alt', '');
            img.attr('src', img.data('pix'));
        }

        // Tạo ảnh mới, xoay ảnh
        if (dialog.data('dialog') == 'imgcreate' || dialog.data('dialog') == 'rotatefile') {
            const img = $('[data-toggle="image"]', dialog);
            img.attr('alt', '');
            img.attr('src', img.data('pix'));
            img.removeAttr('style');
        }
    }

    // Đóng dialog. dig là đối tượng của jquery
    hideDialog(dig) {
        const self = this;

        dig.removeClass('show');

        self.backdropDig.removeClass('show');
        setTimeout(() => {
            self.backdropDig.remove();
            self.backdropDig = null;
            dig[0].style.display = 'none';
        }, 150);

        // Trả lại các thuộc tính style của body
        setTimeout(() => {
            const body = document.body;
            body.style.paddingRight = self.bodyDigEndPadding;
            body.style.overflow = self.bodyDigOverflow;
            if (body.getAttribute('style') === '') {
                body.removeAttribute('style');
            }
            $('body').removeClass('fmd-open');
            self.hideDialogCallback(dig);
            dig.removeAttr('aria-modal');
            dig.attr('aria-hidden', 'true');
        }, 300);
    }

    // Xử lý khi submit các form trong Dialog
    submitDialogCallback(form, event) {
        event.preventDefault();

        const dig = $(form).closest('.fmd');
        const self = this;

        // Form tìm kiếm
        if (dig.data('dialog') == 'search') {
            if (trim($('[name="q"]', dig).val()) === '') {
                $('[name="q"]', dig).addClass('is-invalid').focus();
                return;
            }

            const fq = $('[data-toggle="filter-q"]', self.fms);
            const icon = $('i', fq);
            fq.data('q', trim($('[name="q"]', dig).val()));
            fq.attr('title', fq.data('label-clear'));
            fq.attr('aria-label', fq.data('label-clear'));
            icon.removeClass(icon.data('icon-search')).addClass(icon.data('icon-clear'));

            $('[data-toggle="tree-scroller"]', self.fms).find('.active').removeClass('active');
            $('[data-uuid="' + $('[name="dir"]', dig).val() + '"]', self.fms).addClass('active');
            self.openToTree($('[name="dir"]', dig).val());

            self.hideDialog(dig);
            self.page = 1;
            self.fetchFile();
            self.initUploader();
            return;
        }

        // Form upload file từ internet
        if (dig.data('dialog') == 'upload-remote') {
            let url = trim($('[name="fileurl"]', dig).val());
            // {literal}
            const regex = /^(?:(?:https?|ftp):\/\/)(?:\S+(?::\S*)?@|\d{1,3}(?:\.\d{1,3}){3}|(?:(?:[a-z\d\u00a1-\uffff]+-?)*[a-z\d\u00a1-\uffff]+)(?:\.(?:[a-z\d\u00a1-\uffff]+-?)*[a-z\d\u00a1-\uffff]+)*(?:\.[a-z\u00a1-\uffff]{2,6}))(?::\d+)?(?:\/[^\s]*)?$/gm;
            // {/literal}
            if (url != '' && /^(https?|ftp):\/\//i.test(url) === false) {
                url = 'https://' + url;
                $('[name="fileurl"]', dig).val(url);
            }
            if (url === '' || !regex.test(url)) {
                $('[name="fileurl"]', dig).addClass('is-invalid').focus();
                return;
            }
            if (self.constant.altRequire && trim($('[name="filealt"]', dig).val()) === '') {
                $('[name="filealt"]', dig).addClass('is-invalid').focus();
                return;
            }
            const data = $(form).serialize();
            $('input, textarea, select, button', $(form)).prop('disabled', true);
            $.ajax({
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=upload&' + nv_fc_variable + '=upload&nocache=' + new Date().getTime(),
                type: 'POST',
                data: data,
                cache: false,
                dataType: 'json',
                success: function(respon) {
                    $('input, textarea, select, button', $(form)).prop('disabled', false);
                    if (respon.error) {
                        nvToast(respon.error.message, 'error');
                        return;
                    }
                    self.hideDialog(dig);
                    self.resetFilter();
                    self.fetchFile({
                        selected: [respon.name]
                    });
                },
                error: function(xhr, text, err) {
                    $('input, textarea, select, button', $(form)).prop('disabled', false);
                    nvToast(err, 'error');
                    console.log(xhr, text, err);
                }
            });
            return;
        }

        // Lọc ở chế độ mobile
        if (dig.data('dialog') == 'filter') {
            const sType = $('[name="type"]', dig);
            const sAuthor = $('[name="author"]', dig);
            const sOrder = $('[name="order"]', dig);

            const dType = $('[data-toggle="filter-type"]', self.fms);
            const dAuthor = $('[data-toggle="filter-author"]', self.fms);
            const dOrder = $('[data-toggle="filter-order"]', self.fms);

            $('button', dType).text($('option:selected', sType).text());
            $('button', dAuthor).text($('option:selected', sAuthor).text());
            $('button', dOrder).text($('option:selected', sOrder).text());

            dType.data('type', sType.val());
            dAuthor.data('author', sAuthor.val());
            dOrder.data('order', sOrder.val());

            self.hideDialog(dig);
            self.page = 1;
            self.fetchFile();
            return;
        }

        // Form công cụ ảnh, kiểm tra lỗi trước khi cho submit bình thường
        if (dig.data('dialog') == 'imgcreate') {
            const file = self.getSelectedFile();
            if (file.length != 1) {
                nvToast('No file selected!', 'error');
                return;
            }
            const limitMin = self.calImageSize(file.data('width'), file.data('height'), 1);
            const limitMax = self.calImageSize(file.data('width'), file.data('height'), 999999);
            const width = parseInt($('[name="width"]', dig).val());
            const height = parseInt($('[name="height"]', dig).val());

            if (isNaN(width)) {
                nvToast(self.lang.errorEmptyX, 'error');
                return;
            }
            if (isNaN(height)) {
                nvToast(self.lang.errorEmptyY, 'error');
                return;
            }
            if (width < limitMin.width) {
                nvToast(self.lang.errorMinX, 'error');
                return;
            }
            if (height < limitMin.height) {
                nvToast(self.lang.errorMinY, 'error');
                return;
            }
            if (width > limitMax.width) {
                nvToast(self.lang.errorMaxX, 'error');
                return;
            }
            if (height > limitMax.height) {
                nvToast(self.lang.errorMaxY, 'error');
                return;
            }
        }

        // Các form ajax chung
        if ($(form).data('mode') == 'ajform') {
            if ($('.is-invalid:visible', $(form)).length > 0) {
                let ipt = $('.is-invalid:visible:first', $(form));
                if (ipt.is('.input-group')) {
                    ipt = $('input:first', ipt);
                }
                ipt.focus();
                return;
            }

            $('.is-invalid', $(form)).removeClass('is-invalid');
            $('.is-valid', $(form)).removeClass('is-valid');
            const data = $(form).serialize();
            $('input, textarea, select, button', $(form)).prop('disabled', true);
            $.ajax({
                url: $(form).attr('action'),
                type: 'POST',
                data: data,
                cache: false,
                dataType: 'json',
                success: function(a) {
                    if (a.status == 'NO' || a.status == 'no' || a.status == 'error') {
                        $('input, textarea, select, button', $(form)).prop('disabled', false);
                        if (a.input) {
                            let eleCtn = null;
                            if (a.input_parent) {
                                // Trường hơp nhiều input cùng tên có chỉ định ra thẻ cha của nó
                                eleCtn = $(a.input_parent, $(form));
                            } else {
                                eleCtn = $(form);
                            }
                            let ele = $('[name^=' + a.input + ']', eleCtn);
                            if (ele.length) {
                                let pr = ele.parent();
                                if (pr.is('.input-group')) {
                                    pr.addClass('is-invalid');
                                    pr = pr.parent();
                                }
                                if ($('.invalid-feedback', pr).length) {
                                    $('.invalid-feedback', pr).html(a.mess);
                                } else {
                                    nvToast(a.mess, 'error');
                                }
                                ele.addClass('is-invalid').focus();
                                return;
                            }
                        }
                        nvToast(a.mess, 'error');
                        return;
                    }

                    if (a.status == 'OK' || a.status == 'ok' || a.status == 'success') {
                        $('input, textarea, select, button', $(form)).prop('disabled', false);

                        // Xử lý form tạo thư mục
                        if (dig.data('dialog') == 'createfolder' || dig.data('dialog') == 'renamefolder') {
                            self.page = 1;
                            self.fetchAll({
                                currentpath: a.path
                            });
                            self.hideDialog(dig);
                            return;
                        }

                        // Xử lý form di chuyển file
                        if (dig.data('dialog') == 'move') {
                            self.page = 1;

                            if ($('[name="gonewpath"]', dig).is(':checked')) {
                                const uuid = $('option:selected', dig).data('uuid');
                                $('[data-toggle="tree-scroller"]', self.fms).find('.active').removeClass('active');
                                $('[data-uuid="' + uuid + '"]', self.fms).addClass('active');
                                self.openToTree(uuid);
                                self.resetFilter();
                                self.fetchFile({
                                    selected: a.files
                                });
                            } else {
                                self.fetchFile();
                            }
                            self.hideDialog(dig);
                            return;
                        }

                        // Cắt ảnh, thêm logo, tạo ảnh mới, xoay ảnh thì về trang đầu
                        if (dig.data('dialog') == 'rotatefile' || dig.data('dialog') == 'addlogo' || dig.data('dialog') == 'cropfile' || dig.data('dialog') == 'imgcreate') {
                            self.resetFilter();
                            self.page = 1;
                            self.fetchFile({
                                selected: [a.name]
                            });
                            self.hideDialog(dig);
                            return;
                        }

                        self.fetchFile({
                            selected: [a.name]
                        });
                        self.hideDialog(dig);
                    }
                },
                error: function(xhr, text, err) {
                    $('input, textarea, select, button', $(form)).prop('disabled', false);
                    nvToast(text, 'error');
                    console.log(xhr, text, err);
                }
            });
        }
    }

    // Mở cây thư mục ra đến khi thấy được thư mục có uuid hiện tại
    openToTree(uuid) {
        const self = this;
        const tree = $('[data-uuid="' + uuid + '"]', self.fms);
        const ctn = $('[data-toggle="tree-scroller"]', self.fms);
        tree.parentsUntil(ctn, 'li').each(function () {
            const tree = $('#fms-tree-' + $(this).data('uuid'));
            if (!tree.is('.show')) {
                bootstrap.Collapse.getOrCreateInstance(tree[0]).show();
            }
        });
    }

    // Trả về mảng các file (jquery) được chọn, nếu không có tệp trả về []
    getSelectedFile() {
        return $('[data-toggle="file"].selected', this.fms);
    }

    /**
     * Chọn tệp. File là jquery element
     * append = true thì chọn thêm, không thì chỉ chọn nó
     */
    setSelectedFile(file, append) {
        const self = this;
        if (!append) {
            self.clearSelectedFile();
        }
        file.addClass('selected');
        $('[data-toggle="file-check"]', file).prop('checked', true);
    }

    // Hủy chọn file
    clearSelectedFile(file) {
        const self = this;

        // Hủy một
        if (file) {
            $('[data-toggle="file-check"]', file).prop('checked', false);
            file.removeClass('selected');
            return;
        }

        // Hủy hết
        $('[data-toggle="file"].selected', self.fms).each(function() {
            $('[data-toggle="file-check"]', $(this)).prop('checked', false);
            $(this).removeClass('selected');
        });
    }

    // Lấy alt từ tên tệp tin
    getAlt(name) {
        const lastChar = name.charAt(name.length - 1);
        if (lastChar === '/' || lastChar === '\\') {
            name = name.slice(0, -1);
        }
        name = decodeURIComponent(this.strDecode(name.replace(/^.*[\/\\]/g, '')).replace(/%([^\d].)/, "%25$1"));
        name = name.split('.').slice(0, -1).join(' ');
        name = name.replace(/[\_\-\s]+/gi, ' ');

        return trim(name);
    }

    strDecode(string, quote_style) {
        /*
         * Source: http://phpjs.org/functions/htmlspecialchars_decode/
         * Author: Mirek Slugen
         */
        var optTemp = 0,
            i = 0,
            noquotes = false;
        if (typeof quote_style === 'undefined') {
            quote_style = 2;
        }
        string = string.toString()
            .replace(/&lt;/g, '<')
            .replace(/&gt;/g, '>');
        var OPTS = {
            'ENT_NOQUOTES': 0,
            'ENT_HTML_QUOTE_SINGLE': 1,
            'ENT_HTML_QUOTE_DOUBLE': 2,
            'ENT_COMPAT': 2,
            'ENT_QUOTES': 3,
            'ENT_IGNORE': 4
        };
        if (quote_style === 0) {
            noquotes = true;
        }
        if (typeof quote_style !== 'number') {
            quote_style = [].concat(quote_style);
            for (i = 0; i < quote_style.length; i++) {
                if (OPTS[quote_style[i]] === 0) {
                    noquotes = true;
                } else if (OPTS[quote_style[i]]) {
                    optTemp = optTemp | OPTS[quote_style[i]];
                }
            }
            quote_style = optTemp;
        }
        if (quote_style & OPTS.ENT_HTML_QUOTE_SINGLE) {
            string = string.replace(/&#0*39;/g, "'");
        }
        if (!noquotes) {
            string = string.replace(/&quot;/g, '"');
        }
        string = string.replace(/&amp;/g, '&');

        return string;
    }

    // Lấy path đang active hiện tại
    getCurrentPath() {
        const tree = $('[data-toggle="tree-scroller"]', this.fms).find('li.active:first');
        if (tree.length != 1) {
            return '';
        }
        return tree.data('path');
    }

    // Hàm kiểm tra loại trình duyệt
    detectInputSupport() {
        const hasTouch = 'ontouchstart' in window || navigator.maxTouchPoints > 0;
        const hasMouse = 'onmousedown' in window;

        if (hasTouch && hasMouse) {
            return 'both'; // Hỗ trợ cả touch và mouse
        } else if (hasTouch) {
            return 'touch'; // Chỉ hỗ trợ touch
        } else if (hasMouse) {
            return 'mouse'; // Chỉ hỗ trợ mouse
        } else {
            return 'none'; // Không hỗ trợ cả hai
        }
    }

    // Tìm file từ event
    findFileFromEvent(event) {
        let file = [];
        if ($(event.target).is('[data-toggle="file"]')) {
            file = $(event.target);
        } else {
            file = $(event.target).closest('[data-toggle="file"]');
        }
        if (file.length != 1 || file.closest(this.fms).length != 1) {
            return false;
        }
        return file;
    }

    // Tìm thư mục từ event
    findTreeFromEvent(event) {
        let tree = [];
        if ($(event.target).is('[data-toggle="tree-name"]')) {
            tree = $(event.target);
        } else {
            tree = $(event.target).closest('[data-toggle="tree-name"]');
        }
        if (tree.length != 1 || tree.closest(this.fms).length != 1) {
            return false;
        }
        return tree.closest('li');
    }

    // Reset các bộ lọc về mặc định
    resetFilter() {
        const self = this;
        const ftype = $('[data-toggle="filter-type"]', self.fms);
        const fauthor = $('[data-toggle="filter-author"]', self.fms);
        const forder = $('[data-toggle="filter-order"]', self.fms);
        const fq = $('[data-toggle="filter-q"]', self.fms);

        self.page = 1;
        self.removeFilterQ(fq);

        ftype.data('type', 'file');
        $('button', ftype).text($('[data-type="file"]', ftype).text());

        fauthor.data('author', '0');
        $('button', fauthor).text($('[data-author="0"]', fauthor).text());

        forder.data('order', '0');
        $('button', forder).text($('[data-order="0"]', forder).text());
    }

    // Hủy lọc từ khóa
    removeFilterQ(fq) {
        const icon = $('i', fq);
        fq.data('q', '');
        fq.attr('title', fq.data('label-search'));
        fq.attr('aria-label', fq.data('label-search'));
        icon.removeClass(icon.data('icon-clear')).addClass(icon.data('icon-search'));
    }

    // Hiển thị hàng đợi upload lên
    upQueueRender() {
        const self = this;
        const queue = $('[data-toggle="queue-ctns"]', self.fms);
        if (queue.is(':visible')) {
            return;
        }
        queue.removeClass('d-none');
    }

    // Reset hàng đợi tải lên về mặc định và ẩn nó
    upQueueReset() {
        const self = this;
        const queue = $('[data-toggle="queue-ctns"]', self.fms);
        $('[data-toggle="queue-items"]', queue).html('');
        self.qs && self.qs.update();
        queue.addClass('d-none');

        $('[name="queue_autologo"]', queue).prop('checked', false);
        $('[data-toggle="queue-size"]', queue).text('0');

        // Các nút công cụ upload
        $('[data-toggle="queue-add"]', queue).removeClass('d-none').prop('disabled', false);
        $('[data-toggle="queue-start"]', queue).removeClass('d-none').prop('disabled', false);
        $('[data-toggle="queue-cancel"]', queue).removeClass('d-none').prop('disabled', false);
        $('[data-toggle="queue-stop"]', queue).addClass('d-none').prop('disabled', false);
        $('[data-toggle="queue-continue"]', queue).addClass('d-none').prop('disabled', false);
        $('[data-toggle="queue-finishloader"]', queue).addClass('d-none').prop('disabled', false);
        $('[data-toggle="queue-finish"]', queue).addClass('d-none').prop('disabled', false);

        // Thanh tiến trình
        $('[data-toggle="queue-progress-bar"]', queue).attr('aria-valuenow', '0');
        $('[data-toggle="queue-progress-value"]', queue).removeClass('progress-bar-striped progress-bar-animated').text('').css({
            width: 0
        });
    }

    // Thêm tệp mới vào queue
    upAppendList() {
        const self = this;
        const queue = $('[data-toggle="queue-items"]', self.fms);

        self.up.files.forEach(file => {
            let fi = $('#' + file.id, queue);
            if (fi.length) {
                return;
            }
            fi = $(self.htmlQueueItem);
            fi.attr('id', file.id);
            fi.data('id', file.id);
            $('[data-toggle="qitem-name"]', fi).text(file.name);
            $('[data-toggle="qitem-size"]', fi).text(plupload.formatSize(file.size));

            if (self.constant.autoAlt) {
                $('[data-toggle="qitem-alt"]', fi).val(self.getAlt(file.name));
            }

            queue.append(fi);
        });
    }

    // Cập nhật trạng thái của một tệp
    upStatusFile(file, jsontext, message) {
        const self = this;
        const queue = $('[data-toggle="queue-items"]', self.fms);
        const fi = $('#' + file.id, queue);
        if (fi.length != 1) {
            return;
        }

        if (jsontext) {
            try {
                const jsonObj = JSON.parse(jsontext);
                if (jsonObj.error) {
                    file.status = plupload.FAILED;
                    file.hint = jsonObj.error.message;
                    self.up.total.uploaded--;
                    self.up.total.failed++;
                } else {
                    file.name = jsonObj.name;
                }
                $.each(self.up.files, function(i, f) {
                    if (f.id == file.id) {
                        self.up.files[i].status = file.status;
                        self.up.files[i].hint = file.hint;
                        self.up.files[i].name = file.name;
                    }
                });
            } catch (error) {
                console.log(error, jsontext);
            }
        }
        if (message) {
            file.hint = message;
        }

        $('[data-toggle="qitem-status"]', fi).text(file.percent + '%');

        if (file.status == plupload.QUEUED) {
            $('[data-toggle="qitem-del"]', fi).removeClass('d-none');
            $('[data-toggle="qitem-uploading"]', fi).addClass('d-none');
            $('[data-toggle="qitem-success"]', fi).addClass('d-none');
            $('[data-toggle="qitem-error"]', fi).addClass('d-none');
            return;
        }
        if (file.status == plupload.UPLOADING) {
            $('[data-toggle="qitem-del"]', fi).addClass('d-none');
            $('[data-toggle="qitem-uploading"]', fi).removeClass('d-none');
            $('[data-toggle="qitem-success"]', fi).addClass('d-none');
            $('[data-toggle="qitem-error"]', fi).addClass('d-none');
            return;
        }
        if (file.status == plupload.FAILED) {
            $('[data-toggle="qitem-del"]', fi).addClass('d-none');
            $('[data-toggle="qitem-uploading"]', fi).addClass('d-none');
            $('[data-toggle="qitem-success"]', fi).addClass('d-none');

            const ierr = $('[data-toggle="qitem-error"]', fi);
            ierr.removeClass('d-none').prop('title', file.hint);
            ierr.removeClass('d-none').prop('data-bs-title', file.hint);
            const tt = bootstrap.Tooltip.getOrCreateInstance(ierr[0]);
            tt.setContent({
                '.tooltip-inner': file.hint
            });

            return;
        }
        if (file.status == plupload.DONE) {
            $('[data-toggle="qitem-del"]', fi).addClass('d-none');
            $('[data-toggle="qitem-uploading"]', fi).addClass('d-none');
            $('[data-toggle="qitem-success"]', fi).removeClass('d-none');
            $('[data-toggle="qitem-error"]', fi).addClass('d-none');
            return;
        }
    }

    // Tổng tiến trình upload
    upTotalPercent() {
        $('[data-toggle="queue-progress-bar"]', this.fms).attr('aria-valuenow', this.up.total.percent);
        $('[data-toggle="queue-progress-value"]', this.fms).text(this.up.total.percent + '%').css({
            width: this.up.total.percent + '%'
        });
    }

    // Xử lý khi kết thúc upload tệp tin. Có thể có tệp thành công có thể có tệp lỗi
    upFinish() {
        const self = this;

        let upFiles = [];
        if (self.up && self.up.files) {
            self.up.files.forEach(file => {
                if (file.status == plupload.DONE) {
                    upFiles.push(file.name);
                }
            });
        }

        self.resetFilter();
        self.upQueueReset();
        self.initUploader();
        self.fetchFile({
            selected: upFiles
        });
    }

    // Kiểm tra các alt nếu có bắt buộc nhập
    upQueueCheck() {
        const self = this;
        if (!self.constant.altRequire) {
            return true;
        }
        for (const file of self.up.files) {
            if (file.status == plupload.QUEUED) {
                const fi = $('#' + file.id);
                if (!fi.length) {
                    continue;
                }
                const ipt = $('[data-toggle="qitem-alt"]', fi);
                if (trim(ipt.val()) == '') {
                    ipt.focus();
                    nvToast(self.lang.altRequired, 'error');
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * Các sự kiện liên quan kéo thả
     */
    handleDragenter = () => {
        this.debug && console.log('dragenter document event');

        const self = this;
        const drp = $('[data-toggle="dropzone"]', self.fms);

        // Không nhận sự kiện kéo thả vào nếu đã upload rồi
        if ($('[data-toggle="queue-ctns"]', self.fms).is(':visible') && !$('[data-toggle="queue-cancel"]', self.fms).is(':visible')) {
            drp.removeClass('dragging dragover');
            return;
        }

        drp.addClass('dragging');
    }
    handleDragleave = (event) => {
        this.debug && console.log('dragleave document event', event);
        const self = this;
        const drp = $('[data-toggle="dropzone"]', self.fms);
        if (event.target === document || event.relatedTarget === null) {
            drp.removeClass('dragging dragover');
        }
    }
    handleDragover = (event) => {
        this.debug && console.log('dragover document event');
        event.preventDefault();
    }
    handleDrop = () => {
        this.debug && console.log('drop document event');
        const self = this;
        const drp = $('[data-toggle="dropzone"]', self.fms);
        drp.removeClass('dragging dragover');
    }

    // Xử lý khi nhấn xuống, lên, di chuyển trên toàn bộ document
    handleDocTapStart = (event) => {
        const input = this.detectInputSupport();
        if (event.type == 'mousedown' && (input == 'both' || event.button != 0)) {
            // Nếu có cả mouse và touch thì thỉ lấy 1 cái touch
            return;
        }
        this.debug && console.log('document ' + event.type, event);
        const self = this;
        const cTime = new Date().getTime();
        const cDiff = cTime - self.lastTap;
        const doubleTap = (cDiff < 300 && cDiff > 0);
        self.lastTap = cTime;

        // Đóng menu khi click ra ngoài menu
        const isMobile = self.isMobile();
        const menuClick = ($(event.target).is(self.menu) || $(event.target).closest(self.menu).length == 1);
        const menuIsOpen = self.menu.is(':visible');
        const dialogIsOpen = $('body').is('.fmd-open');
        const alertIsOpen = $('body').is('.alert-open');
        const treeClick = ($(event.target).is('[data-toggle="toggle-trees"]') || $(event.target).is('[data-toggle="trees"]') || $(event.target).closest('[data-toggle="trees"]').length == 1);
        const treeIsOpen = (isMobile && $('[data-toggle="trees"]', self.fms).is('.show'));

        if (!menuClick && !dialogIsOpen && !alertIsOpen && menuIsOpen) {
            self.closeMenu();
        }

        if (treeIsOpen && !treeClick && !menuClick) {
            this.debug && console.log('Just close tree');
            $('[data-toggle="trees"]', self.fms).removeClass('show');
            return;
        }

        const fileClick = self.findFileFromEvent(event);
        const clickFileTool = (fileClick && ($(event.target).is('[data-toggle="file-check"]') || $(event.target).is('[data-toggle="file-menu"]') || $(event.target).closest('[data-toggle="file-menu"]').length > 0));

        if (!fileClick && !menuClick && !menuIsOpen && !dialogIsOpen && !alertIsOpen) {
            self.clearSelectedFile();
        }

        // Nhấp để chọn / bỏ chọn file
        if (fileClick && !clickFileTool && !menuIsOpen && !doubleTap && !dialogIsOpen && !alertIsOpen) {
            if (fileClick.is('.selected')) {
                self.clearSelectedFile(event.ctrlKey ? fileClick : null);
            } else {
                self.setSelectedFile(fileClick, event.ctrlKey);
            }
        }

        // Click đúp thì chọn tệp đó luôn
        if (doubleTap && fileClick) {
            self.setSelectedFile(fileClick);
            self.handleSelectFile(fileClick);
        }

        self.initSelectAble = false;
        const ctnSelect = $('[data-toggle="file-scroller"]', self.fms);
        if (event.type == 'mousedown' && !fileClick && !menuIsOpen && !dialogIsOpen && !alertIsOpen && !treeIsOpen && ($(event.target).is(ctnSelect) || $(event.target).closest(ctnSelect).length == 1)) {
            self.initSelectAble = true;
            ctnSelect.addClass('disabled-select');
        }

        if (dialogIsOpen) {
            const dialog = self.fmd.filter($('.fmd:visible:first'));
            const btn = $(event.target);
            if (dialog.length == 1 && dialog.data('dialog') == 'rotatefile' && btn.is('[data-toggle="rotatefile-btn"]')) {
                self.intervalRotate = setInterval(() => {
                    self.runIntervalRotate(btn, dialog);
                }, 20);
            }
        }
    }
    handleDocTapEnd = (event) => {
        const input = this.detectInputSupport();
        if (event.type == 'mouseup' && (input == 'both' || event.button != 0)) {
            // Nếu có cả mouse và touch thì thỉ lấy 1 cái touch
            return;
        }
        this.debug && console.log('document ' + event.type, event);
        const self = this;
        const ctnSelect = $('[data-toggle="file-scroller"]', self.fms);
        self.initSelectAble = false;
        ctnSelect.removeClass('disabled-select');
        if (self.selectionBox) {
            self.selectionBox.remove();
            self.selectionBox = null;
        }
        self.selectionStartX = null;
        self.selectionStartY = null;
        if (self.intervalRotate) {
            clearInterval(self.intervalRotate);
            self.intervalRotate = null;
        }
    }
    handleDocTapMove = (event) => {
        const input = this.detectInputSupport();
        if (event.type == 'mousemove' && (input == 'both' || event.button != 0)) {
            // Nếu có cả mouse và touch thì thỉ lấy 1 cái touch
            return;
        }
        const self = this;
        const now = Date.now();
        if (now - self.lastTouchMove < 20) {
            return;
        }
        self.lastTouchMove = now;
        if (!self.initSelectAble) {
            return;
        }

        const container = $('[data-toggle="file-scroller"]', self.fms)[0];
        const containerRect = container.getBoundingClientRect();

        /**
         * Tọa độ: tại con trỏ chuột trừ đi lề trái, lề trên
         * cộng với phần cuộn của container
         */
        let mouseX = event.clientX + container.scrollLeft - containerRect.left;
        let mouseY = event.clientY + container.scrollTop - containerRect.top;

        if (!self.selectionBox) {
            // Tạo vùng chọn khi bắt đầu chọn
            self.selectionBox = document.createElement('div');
            self.selectionBox.className = 'selection-box';
            self.selectionBox.style.left = mouseX + 'px';
            self.selectionBox.style.top = mouseY + 'px';
            container.appendChild(self.selectionBox);

            self.selectionStartX = mouseX;
            self.selectionStartY = mouseY;
            self.selectionScrollMax = container.scrollHeight - container.clientHeight;
        } else {
            // Xử lý khi tiếp tục kéo chọn
            let width = Math.abs(mouseX - self.selectionStartX);
            let height = Math.abs(mouseY - self.selectionStartY);

            self.selectionBox.style.width = width + 'px';
            self.selectionBox.style.height = height + 'px';

            // Nếu kéo ngược lại thì tính lại vị trí start
            self.selectionBox.style.left = Math.min(mouseX, self.selectionStartX) + 'px';
            self.selectionBox.style.top = Math.min(mouseY, self.selectionStartY) + 'px';

            // Tính toán khi kéo ngược lên và đi ra khỏi xuống dưới
            if (event.clientY > containerRect.bottom) {
                container.scrollTop = Math.min(container.scrollTop + 30, self.selectionScrollMax);
            }
            if (event.clientY < containerRect.top) {
                container.scrollTop = Math.max(container.scrollTop - 30, 0);
            }

            const selectionRect = {
                left: parseFloat(self.selectionBox.style.left),
                top: parseFloat(self.selectionBox.style.top),
                right:
                    parseFloat(self.selectionBox.style.left) +
                    parseFloat(self.selectionBox.style.width),
                bottom:
                    parseFloat(self.selectionBox.style.top) +
                    parseFloat(self.selectionBox.style.height),
            };

            $('[data-toggle="file"]', self.fms).each(function() {
                const item = this;
                const itemRect = item.getBoundingClientRect();
                const itemRectRelativeToContainer = {
                    left: itemRect.left - containerRect.left + container.scrollLeft,
                    top: itemRect.top - containerRect.top + container.scrollTop,
                    right: itemRect.right - containerRect.left + container.scrollLeft,
                    bottom: itemRect.bottom - containerRect.top + container.scrollTop,
                };
                if (
                    itemRectRelativeToContainer.left < selectionRect.right &&
                    itemRectRelativeToContainer.right > selectionRect.left &&
                    itemRectRelativeToContainer.top < selectionRect.bottom &&
                    itemRectRelativeToContainer.bottom > selectionRect.top
                ) {
                    self.setSelectedFile($(item), true);
                } else {
                    self.clearSelectedFile($(item));
                }
            });
        }
    }

    // Xử lý sự kiện bàn phím trên document
    handleDocKeydown = (event) => {
        const self = this;
        this.debug && console.log('document ' + event.type, event);

        let preventEvent = false;
        const isMobile = self.isMobile();
        const menuIsOpen = self.menu.is(':visible');
        const dialogIsOpen = $('body').is('.fmd-open');
        const alertIsOpen = $('body').is('.alert-open');
        const treeIsOpen = (isMobile && $('[data-toggle="trees"]', self.fms).is('.show'));

        if (self.isFocused) {
            if (!menuIsOpen && !dialogIsOpen && !alertIsOpen && !treeIsOpen) {
                if (event.keyCode == 65 && event.ctrlKey) {
                    // Chọn hết các file
                    preventEvent = true;

                    $('[data-toggle="file"]:not(.selected)', self.fms).each(function() {
                        self.setSelectedFile($(this), true);
                    });
                } else if (event.keyCode == 46) {
                    // Xóa
                    const files = self.getSelectedFile();
                    if (files.length > 0) {
                        const cMess = files.length > 1 ? self.lang.delImgsConfirm.replace('%s', files.length) : (self.lang.delImgConfirm + ' <strong class="text-break">' + files.data('name') + '<strong>');
                        nvConfirm({
                            html: true,
                            message: cMess
                        }, () => {
                            let fss = [];
                            files.each(function() {
                                fss.push($(this).data('dir-path'));
                            });
                            fss = fss.join('|');
                            $.ajax({
                                type: 'POST',
                                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=upload&' + nv_fc_variable + '=delimg&nocache=' + new Date().getTime(),
                                data: {
                                    files: fss,
                                    checkss: $('body').data('checksess')
                                },
                                dataType: 'json',
                                cache: false,
                                success: function(res) {
                                    if (res.status == 'error') {
                                        nvToast(res.mess, 'error');
                                        return;
                                    }
                                    self.fetchFile();
                                },
                                error: function(xhr, text, err) {
                                    nvToast(err, 'error');
                                    console.log(xhr, text, err);
                                }
                            });
                        });
                    }
                } else if (event.keyCode == 27) {
                    // Hủy chọn
                    const files = self.getSelectedFile();
                    if (files.length > 0) {
                        self.clearSelectedFile();
                    }
                }
            }

            if (menuIsOpen && !dialogIsOpen && !alertIsOpen && !treeIsOpen) {
                self.closeMenu();
            }
        }

        preventEvent && event.preventDefault();
    }

    // Tìm tọa độ con trỏ chuột trong document
    getMousePositionFromEvent(event) {
        let mouseX, mouseY;

        if (event.changedTouches && event.changedTouches[0] && event.changedTouches[0].clientY > 0) {
            mouseX = event.changedTouches[0].clientX;
            mouseY = event.changedTouches[0].clientY;
        } else if (event.originalEvent && typeof event.originalEvent.detail == 'object') {
            mouseX = event.originalEvent.detail.clientX;
            mouseY = event.originalEvent.detail.clientY;
        } else if (event.originalEvent && event.originalEvent.clientX) {
            mouseX = event.originalEvent.clientX;
            mouseY = event.originalEvent.clientY;
        } else if (event.clientX) {
            mouseX = event.clientX;
            mouseY = event.clientY;
        } else {
            nvToast('Error get mouse position!!!', 'error');
            return [false, false];
        }
        if (mouseX < 0) {
            mouseX = 0;
        }
        if (mouseY < 0) {
            mouseY = 0;
        }
        return [mouseX, mouseY];
    }

    /**
     * Mở menu file. File đã được bọc trong jquery.
     * Có thể có nhiều file được chọn
     */
    openFileMenu(files, event) {
        this.debug && console.log('Get file menu');
        const self = this;
        const [mouseX, mouseY] = self.getMousePositionFromEvent(event);
        const tree = $('[data-toggle="tree-scroller"]', this.fms).find('li.active:first');
        if (mouseX === false || tree.length != 1) {
            self.closeMenu();
            return;
        }

        let actions = 0;
        let menuHeader = '';
        if (files.length == 1) {
            menuHeader = files.data('name');
        } else {
            menuHeader = self.lang.filesSelected.replace('%d', files.length);
        }
        let html = '<li><div class="dropdown-header fw-medium text-truncate-2 mb-2 pb-0 text-break text-wrap text-primary" title="' + menuHeader + '">' + menuHeader + '</div></li>';

        if (files.length == 1) {
            if (typeof self.settings.onSelect == 'function' || self.settings.editorId != '' || self.settings.CKEditorFuncNum > 0 || self.settings.area != '') {
                actions++;
                html += '<li><a class="dropdown-item" href="#" data-toggle="menu-file-select" data-uuid="' + files.data('uuid') + '"><i class="fa-solid fa-check text-success fa-fw"></i> ' + self.lang.select + '</a></li>';
            }

            actions += 2;
            html += '<li><a class="dropdown-item" href="#" data-toggle="menu-file-download" data-uuid="' + files.data('uuid') + '"><i class="fa-solid fa-download fa-fw"></i> ' + self.lang.download + '</a></li>';
            html += '<li><a class="dropdown-item" href="#" data-toggle="menu-file-preview" data-uuid="' + files.data('uuid') + '"><i class="fa-solid fa-eye fa-fw"></i> ' + self.lang.preview + '</a></li>';

            // Công cụ cơ bản của ảnh
            if (self.imageExts.includes(files.data('ext')) && tree.data('allowed-create-file')) {
                actions += 4;
                html += '<li><a class="dropdown-item" href="#" data-toggle="menu-file-addlogo" data-uuid="' + files.data('uuid') + '"><i class="fa-solid fa-file-image fa-fw"></i> ' + self.lang.addLogo + '</a></li>';
                html += '<li><a class="dropdown-item" href="#" data-toggle="menu-file-imgcreate" data-uuid="' + files.data('uuid') + '"><i class="fa-solid fa-copy fa-fw"></i> ' + self.lang.imgTool + '</a></li>';
                html += '<li><a class="dropdown-item" href="#" data-toggle="menu-file-cropfile" data-uuid="' + files.data('uuid') + '"><i class="fa-solid fa-crop fa-fw"></i> ' + self.lang.crop + '</a></li>';
                html += '<li><a class="dropdown-item" href="#" data-toggle="menu-file-rotatefile" data-uuid="' + files.data('uuid') + '"><i class="fa-solid fa-arrows-spin fa-fw"></i> ' + self.lang.rotate + '</a></li>';
            }
            if (tree.data('allowed-create-file')) {
                // Tạo webp
                if (['jpg', 'jpeg', 'png'].includes(files.data('ext'))) {
                    actions++;
                    html += '<li><a class="dropdown-item" href="#" data-toggle="menu-file-webpconvert" data-uuid="' + files.data('uuid') + '"><i class="fa-solid fa-wand-magic fa-fw" data-icon="fa-wand-magic"></i> ' + self.lang.webpConvert + '</a></li>';
                }
                // Giảm chất lượng
                if (['jpg', 'jpeg', 'png', 'webp'].includes(files.data('ext'))) {
                    actions++;
                    html += '<li><a class="dropdown-item" href="#" data-toggle="menu-file-qualitychange" data-uuid="' + files.data('uuid') + '"><i class="fa-solid fa-arrow-down-short-wide fa-fw"></i> ' + self.lang.qualityChange + '</a></li>';

                    // Nén ảnh
                    if (self.constant.compressImage) {
                        actions++;
                        html += '<li><a class="dropdown-item" href="#" data-toggle="menu-file-compress" data-uuid="' + files.data('uuid') + '"><i class="fa-solid fa-compress fa-fw" data-icon="fa-compress"></i> ' + self.lang.compressImage + '</a></li>';
                    }
                }
            }
        }
        if (tree.data('allowed-move-file')) {
            actions++;
            html += '<li><a class="dropdown-item" href="#" data-toggle="menu-file-move" data-uuid="' + files.data('uuid') + '"><i class="fa-solid fa-folder-tree fa-fw"></i> ' + self.lang.move + '</a></li>';
        }
        if (tree.data('allowed-rename-file') && files.length == 1) {
            actions++;
            html += '<li><a class="dropdown-item" href="#" data-toggle="menu-file-rename" data-uuid="' + files.data('uuid') + '"><i class="fa-solid fa-pen fa-fw"></i> ' + self.lang.rename + '</a></li>';
        }
        if (tree.data('allowed-delete-file')) {
            actions++;
            html += '<li><a class="dropdown-item" href="#" data-toggle="menu-file-del" data-uuid="' + files.data('uuid') + '"><i class="fa-solid fa-trash text-danger fa-fw" data-icon="fa-trash"></i> ' + self.lang.deleteFile + '</a></li>';
        }

        if (actions < 1) {
            self.closeMenu();
            return;
        }

        self.menu.html(html);
        self.showMenuDependingMouse(mouseX, mouseY);
    }

    /**
     * Mở menu thư mục. Tree đã được bọc trong jquery
     * Chỉ có một thư mục
     */
    openFolderMenu(tree, event) {
        this.debug && console.log('Get file menu');
        const self = this;
        const [mouseX, mouseY] = self.getMousePositionFromEvent(event);
        if (mouseX === false) {
            self.closeMenu();
            return;
        }

        let actions = 0;
        let html = '<li><div class="dropdown-header fw-medium text-truncate-2 mb-2 pb-0 text-break text-wrap text-primary" title="' + tree.data('title') + '">' + tree.data('title') + '</div></li>';
        // Tạo thư mục con
        if (tree.data('allowed-create-dir')) {
            actions++;
            html += '<li><a class="dropdown-item" href="#" data-toggle="menu-tree-create" data-uuid="' + tree.data('uuid') + '"><i class="fa-solid fa-folder-plus fa-fw"></i> ' + self.lang.createDir + '</a></li>';
        }
        if (tree.data('allowed-rethumb')) {
            actions++;
            html += '<li><a class="dropdown-item" href="#" data-toggle="menu-tree-rethumb" data-uuid="' + tree.data('uuid') + '"><i class="fa-solid fa-repeat fa-fw"></i> ' + self.lang.reThumb + '</a></li>';
        }
        if (tree.data('allowed-rename-dir')) {
            actions++;
            html += '<li><a class="dropdown-item" href="#" data-toggle="menu-tree-rename" data-uuid="' + tree.data('uuid') + '"><i class="fa-solid fa-pen fa-fw"></i> ' + self.lang.renameDir + '</a></li>';
        }
        if (tree.data('allowed-delete-dir')) {
            actions++;
            html += '<li><a class="dropdown-item" href="#" data-toggle="menu-tree-delete" data-uuid="' + tree.data('uuid') + '"><i class="fa-solid fa-trash fa-fw text-danger"></i> ' + self.lang.deleteDir + '</a></li>';
        }
        if (actions < 1) {
            self.closeMenu();
            return;
        }

        event.preventDefault();
        self.menu.html(html);
        self.showMenuDependingMouse(mouseX, mouseY);
    }

    // Đóng menu lại
    closeMenu() {
        this.menu.removeClass('show').html('');
        this.initScrollbar();
    }

    // Hiển thị menu theo vị trí thao tác
    showMenuDependingMouse(mouseX, mouseY) {
        const self = this;
        const scrollLeft = $(window).scrollLeft();
        const scrollTop = $(window).scrollTop();

        let tranX = mouseX + scrollLeft + 2;
        let tranY = mouseY + scrollTop + 2;

        self.menu.css({
            transform: 'translate(0px, 0px)',
            left: -9999,
            top: -9999,
        });
        self.menu.addClass('show');
        let mW = self.menu.innerWidth();
        let mH = self.menu.innerHeight();
        let wW = document.documentElement.clientWidth;
        let wH = document.documentElement.clientHeight;

        const offset = 10;
        if (tranX + mW + offset > scrollLeft + wW) {
            tranX = scrollLeft + wW - mW - offset;
        }
        if (tranY + mH + offset > scrollTop + wH) {
            tranY = scrollTop + wH - mH - offset;
        }
        tranX = Math.max(tranX, scrollLeft + offset);
        tranY = Math.max(tranY, scrollTop + offset);

        self.menu.css({
            transform: 'translate(' + tranX + 'px, ' + tranY + 'px)',
            left: 0,
            top: 0,
        });

        self.destroyScrollbar();
    }

    // Xử lý trả về khi chọn file
    handleSelectFile(file) {
        const self = this;
        // Trả về dạng hàm callback
        if (typeof self.settings.onSelect == 'function') {
            self.settings.onSelect({
                name: file.data('name'),
                ext: file.data('ext'),
                thumb: file.data('thumb'),
                path: file.data('path'),
                absPath: file.data('abs-path'),
                alt: file.data('alt'),
                type: file.data('type'),
                width: file.data('width'),
                height: file.data('height'),
                size: file.data('filesize')
            }, self);
            self.hideModal();
            return;
        }

        // Trả về cho CKEditor 4 cũ
        if (self.settings.CKEditorFuncNum > 0 && window.opener && window.opener.CKEDITOR) {
            window.opener.CKEDITOR.tools.callFunction(self.settings.CKEditorFuncNum, file.data('path'), function() {
                var dialog = this.getDialog();
                if (dialog.getName() == 'image2') {
                    var element = dialog.getContentElement('info', 'alt');
                    if (element) {
                        element.setValue(file.data('alt'));
                    }
                }
            });
            window.close();
            return;
        }
        // Trả về cho CKEditor 5 mới
        if (self.settings.editorId != '' && nukeviet.Picker.EditorCallback) {
            nukeviet.Picker.EditorCallback.forEach(callback => {
                callback(self.settings.editorId, {
                    href: file.data('path'),
                    alt: file.data('alt')
                });
            });
            if (self.settings.popup) {
                window.close();
                return;
            }
        }

        // Trả về cho các dạng popup chọn cũ
        if (self.settings.popup) {
            if (!opener || !opener.document) {
                return;
            }
            // Trả về input của opener
            if (self.settings.area != '') {
                $('#' + self.settings.area, opener.document).val(file.data('path')).trigger('change');
                if (self.settings.alt != '') {
                    $('#' + self.settings.alt, opener.document).val(file.data('alt')).trigger('change');
                }
                window.close();
                return;
            }
            return;
        }

        // Dạng trong trang mới
        if (self.settings.area != '') {
            $('#' + self.settings.area).val(file.data('path')).trigger('change');
        }
        if (self.settings.alt != '') {
            $('#' + self.settings.alt).val(file.data('alt')).trigger('change');
        }
        self.hideModal();
    }

    // Có phải chế độ mobile không. Chế độ mobile tức là giao diện thu gọn cây thư mục
    // Không phải là trình duyệt trên mobile
    isMobile() {
        return $('[data-toggle="toggle-trees"]', this.fms).is(':visible');
    }

    // Phát hiện thiết bị không có con trỏ chuột, show các nút tool
    setContainerType(input) {
        if (window.matchMedia('(pointer: coarse) and (hover: none)').matches && (input == 'both' || input == 'touch')) {
            this.fms.addClass('touch-only');
        } else {
            this.fms.removeClass('touch-only');
        }
    }

    // Xử lý tiến trình tạo lại ảnh thumb
    runReThumb(idf) {
        const self = this;
        const dialog = self.fmd.filter('[data-dialog="rethumb"]');
        if (!dialog.length || !dialog.is(':visible')) {
            return;
        }

        $.ajax({
            type: 'POST',
            url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=upload&' + nv_fc_variable + '=recreatethumb&nocache=' + new Date().getTime(),
            data: {
                idf: idf,
                path: dialog.data('path'),
                checkss: $('body').data('checksess')
            },
            dataType: 'json',
            cache: false,
            success: function(res) {
                // Lỗi
                if (res.status == 'error') {
                    nvToast(res.mess, 'error');
                    return;
                }
                // Trường hợp đóng mất dialog hoặc modal
                if (!self.fmd || !dialog.is(':visible')) {
                    return;
                }

                let per = res.total <= 0 ? 100 : ((res.number / res.total) * 100);
                per = parseFloat(per.toFixed(2));
                $('[data-toggle="progress-aria"]', dialog).attr('aria-valuenow', per);
                $('[data-toggle="progress-val"]', dialog).text(per + '%').css({
                    width: (per + '%')
                });
                $('[data-toggle="current"]', dialog).text(res.number);
                $('[data-toggle="total"]', dialog).text(res.total);

                // Hoàn tất
                if (res.finish) {
                    $('[data-toggle="load"]', dialog).addClass('d-none');
                    $('[data-toggle="progress"]', dialog).addClass('d-none');
                    $('[data-toggle="finish"]', dialog).removeClass('d-none');
                    setTimeout(() => {
                        self.hideDialog(dialog);
                        self.page = 1;
                        self.fetchAll({
                            currentpath: dialog.data('path')
                        });
                    }, 2000);
                    return;
                }
                // Chạy tiếp
                if (idf < 0) {
                    // Build tiến trình
                    $('[data-toggle="load"]', dialog).addClass('d-none');
                    $('[data-toggle="progress"]', dialog).removeClass('d-none');
                }
                setTimeout(() => {
                    self.runReThumb(res.number);
                }, 1000);
            },
            error: function(xhr, text, err) {
                nvToast(err, 'error');
                console.log(xhr, text, err);
            }
        });
    }

    // Thiết lập option động
    setOption(name, value) {
        this.settings[name] = value;
    }

    // Tính toán kích thước của ảnh
    calImageSize(imageWidth, imageHeight, displayWidth = null, displayHeight = null) {
        const { minWidth, minHeight, maxWidth, maxHeight } = this.constant.image;

        // Tính tỉ lệ gốc của ảnh
        const aspectRatio = imageWidth / imageHeight;

        let calculatedWidth, calculatedHeight;

        // Xử lý khi truyền vào displayWidth hoặc displayHeight
        if (displayWidth !== null) {
            calculatedWidth = displayWidth;
            calculatedHeight = calculatedWidth / aspectRatio;
        } else if (displayHeight !== null) {
            calculatedHeight = displayHeight;
            calculatedWidth = calculatedHeight * aspectRatio;
        } else {
            calculatedWidth = imageWidth;
            calculatedHeight = imageHeight;
        }

        // Đảm bảo kích thước không vượt quá max
        if (calculatedWidth > maxWidth) {
            calculatedWidth = maxWidth;
            calculatedHeight = calculatedWidth / aspectRatio;
        }
        if (calculatedHeight > maxHeight) {
            calculatedHeight = maxHeight;
            calculatedWidth = calculatedHeight * aspectRatio;
        }

        // Đảm bảo kích thước không nhỏ hơn min
        if (calculatedWidth < minWidth) {
            calculatedWidth = minWidth;
            calculatedHeight = calculatedWidth / aspectRatio;
        }
        if (calculatedHeight < minHeight) {
            calculatedHeight = minHeight;
            calculatedWidth = calculatedHeight * aspectRatio;
        }

        return {
            width: Math.round(calculatedWidth),
            height: Math.round(calculatedHeight),
        };
    }

    // Tính toán kích thước ảnh sao cho đường chéo của nó không vượt container
    calScaledSize(width, height, containerWidth) {
        // Tính đường chéo của ảnh gốc
        const diagonal = Math.sqrt(width ** 2 + height ** 2);

        // Nếu đường chéo đã nhỏ hơn hoặc bằng containerWidth, không cần co
        if (diagonal <= containerWidth) {
            return { displayWidth: width, displayHeight: height };
        }

        // Tính tỉ lệ co
        const scale = containerWidth / diagonal;

        // Tính kích thước mới
        const displayWidth = width * scale;
        const displayHeight = height * scale;

        return {
            displayWidth: Math.round(displayWidth),
            displayHeight: Math.round(displayHeight),
        };
    }

    // Tính toán xoay ảnh góc 90 độ
    roundToDirection(value, direction) {
        const angles = [0, 90, 180, 270];

        // Nếu giá trị hiện tại khớp với một trong các giá trị trong mảng
        let exactIndex = angles.indexOf(value);
        if (exactIndex !== -1) {
            if (direction === "r") {
                return angles[(exactIndex + 1) % angles.length]; // Lấy giá trị tiếp theo (theo vòng)
            } else if (direction === "l") {
                return angles[(exactIndex - 1 + angles.length) % angles.length]; // Lấy giá trị trước đó (theo vòng)
            }
        }

        // Nếu không khớp, xử lý giá trị gần nhất
        if (direction === "l") {
            for (let i = angles.length - 1; i >= 0; i--) {
                if (value > angles[i]) {
                    return angles[i];
                }
            }
            return angles[angles.length - 1]; // Nếu nhỏ hơn góc nhỏ nhất, quay về 270
        } else if (direction === "r") {
            for (let i = 0; i < angles.length; i++) {
                if (value < angles[i]) {
                    return angles[i];
                }
            }
            return angles[0]; // Nếu lớn hơn góc lớn nhất, quay về 0
        }

        return null; // Trường hợp không hợp lệ
    }

    // Xoay ảnh liên tục
    runIntervalRotate(btn, dialog) {
        const ipt = $('[name="direction"]', dialog);
        let val = parseInt(ipt.val());
        const img = $('[data-toggle="image"]', dialog);
        if (isNaN(val)) {
            val = 0;
        }
        if (btn.data('type') == 'r') {
            val++;
        } else {
            val--;
        }
        if (val < 0) {
            val = 359;
        } else if (val > 359) {
            val = 0;
        }
        ipt.val(val);
        img.css({
            transform: ('rotate(' + val + 'deg)')
        });
    }
});

/*
 * Xử lý trình quản lý file ở các nút duyệt file
 * Dạng Jquery
 */
(($) => {
    $.fn.nvPicker = function(options) {
        return this.each(function() {
            if (!$.data(this, 'nv.picker')) {
                // Đảm bảo chỉ khởi tạo 1 lần duy nhất
                $.data(this, 'nv.picker', new nukeviet.Picker(this, options));
            }
        });
    };
})(jQuery);
