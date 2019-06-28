/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

var NVCoreFileBrowser = function() {
    // Các thiết lập về ID
    this.cfg = {
        container: "#nv-filemanager",
        btnFolderMobileToggle: "#nv-filemanager-folder-btn-toggle",
        folderElement: "#nv-filemanager-folder",
        childFolderBtnToggle: "#nv-filemanager-folder a span.toggle",
        folderOpenFileBtn: "#nv-filemanager-folder a",
        btnChangeViewMode: "#nv-filemanager-btn-change-viewmode",
        btnReload: "#nv-filemanager-btn-reload",
        btnSearch: ".nv-filemanager-btn-search",
        btnFilterSort: ".nv-filemanager-btn-filter-sort",
        btnFilterUser: ".nv-filemanager-btn-filter-user",
        btnFilterType: ".nv-filemanager-btn-filter-type",
        btnUpload: "#nv-filemanager-btn-upload-local",
        btnUploadDropdown: "#nv-filemanager-btn-upload-dropdown",
        ctnFilterSort: "#nv-filemanager-ctn-filter-sort",
        ctnFilterUser: "#nv-filemanager-ctn-filter-user",
        ctnFilterType: "#nv-filemanager-ctn-filter-type",
        loader: "#nv-filemanager-loader",
        dropzoneCtn: "#filemanager-dropzone-ctn",
        dropzoneArea: "#filemanager-dropzone-area",
        formFilter: "#nv-filemanager-form-filter",
        btnToggleFormFilter: "#nv-filemanager-btn-toggle-form-filter",
        filesContainer: "#nv-filemanager-files-container",
        filesScroller: "#nv-filemanager-files-scroller",
        filesNav: "#nv-filemanager-files-nav",
        filesToolBar: "#nv-filemanager-tool-bar",
        linkDownload: "#nv-filemanager-download-link",

        formSearch: '#nv-filemanager-form-search',
        formCreatFolder: '#nv-filemanager-form-newfolder',
        formRenameFolder: '#nv-filemanager-form-renamefolder',
        formRecreatThumb: '#nv-filemanager-form-recreatthumb',
        formPreview: '#nv-filemanager-form-previewfile',
        formRenameFile: '#nv-filemanager-form-renamefile',
        formMoveFile: '#nv-filemanager-form-movefile',
        formRotateFile: '#nv-filemanager-form-rotatefile',
        formCrop: '#nv-filemanager-form-cropfile',
        formCreatImage: '#nv-filemanager-form-createimage',
        formAddLogo: '#nv-filemanager-form-addlogo',
        formRemoteUpload: '#nv-filemanager-form-remoteupload',

        ctnUploadParent: '#nv-filemanager-upload-parent',
        ctnUploadQueue: '#nv-filemanager-upload-queue',
        ctnUploadScroller: '#nv-filemanager-upload-queue-scroller',

        modalAlert: '#nv-filemanager-alert-modal',
    };

    this.cfgFolderData = {
        path: '#fmFCPath',
        folder: '#fmFCFolder',
        allowedView: '#fmFCAllowedViewFiles',
        allowedCreatDir: '#fmFCAllowedCreatDir',
        allowedReThumb: '#fmFCAllowedReThumb',
        allowedRenameDir: '#fmFCAllowedRenameDir',
        allowedDeleteDir: '#fmFCAllowedDeleteDir',
        allowedUpload: '#fmFCAllowedUpload',
        allowedCreatFile: '#fmFCAllowedCreatFile',
        allowedRenameFile: '#fmFCAllowedRenameFile',
        allowedDeleteFile: '#fmFCAllowedDeleteFile',
        allowedMoveFile: '#fmFCAllowedMoveFile',
        allowedCropFile: '#fmFCAllowedCropFile',
        allowedRorateFile: '#fmFCAllowedRorateFile'
    };

    this.cfgMain = {
        fileURL: '#fmMainCurrentFileURL',
        file: '#fmMainCurrentFile',
        area: '#fmMainArea',
        currentFileURL: '#fmMainAlt',
        logo: '#fmMainLogo',
        editorFunc: '#fmCKEditorFuncNum',
        logoConfig: '#fmMainLogoConfig'
    };

    // Các thiết lập Icon cố định
    var ICON = [];
    ICON.select = 'icon far fa-check-square';
    ICON.download = 'icon fas fa-download';
    ICON.preview = 'icon far fa-eye';
    ICON.create = 'icon far fa-copy';
    ICON.recreatethumb = 'icon fas fa-retweet';
    ICON.move = 'icon fas fa-arrows-alt';
    ICON.rename = 'icon fas fa-pencil-alt';
    ICON.filedelete = 'icon far fa-trash-alt';
    ICON.filecrop = 'icon fas fa-crop-alt';
    ICON.filerotate = 'icon fas fa-redo';
    ICON.addlogo = 'icon far fa-file-image';
    ICON.spin = 'icon fas fa-spinner';
    ICON.access = 'icon fas fa-mouse-pointer';

    this.ICON = ICON;

    this.debug = 0;
    this.perload = 0;
    this.timerRecreatThumb = 0;
    this.firstData = {};
    this.dataFilesDefault = {
        path: '',
        currentpath: '',
        type: 'file',
        area: '',
        alt: '',
        imgfile: '',
        author: '',
    };

    this.uploader = null;
    this.uploadRendered = false;
    this.uploadStarted = false;
}

/*
 * Init upload: Javascript xử lý sau khi đã load đủ HTML
 */
NVCoreFileBrowser.prototype.init = function(data) {
    var self = this;
    var cfg = self.cfg;
    var cfgf = self.cfgFolderData;
    var cfgm = self.cfgMain;
    var ICON = self.ICON;

    self.firstData = data;

    KEYPR.init();
    RRT.init();
    NVCMENU.init();
    NVLDATA.init();

    // Thay đổi chế độ xem dạng lưới hay danh sách
    $(cfg.btnChangeViewMode).on('click', function(e) {
        e.preventDefault();
        $(this).data('auto', false);
        if ($(cfg.container).is('.view-detail')) {
            $(cfg.container).addClass('view-gird');
            $(cfg.container).removeClass('view-detail');
        } else {
            $(cfg.container).addClass('view-detail');
            $(cfg.container).removeClass('view-gird');
        }
        setTimeout(function() {
            updatePerfectScrollbar();
        }, 251);
    });

    // Load lại file
    $(cfg.btnReload).on('click', function(e) {
        e.preventDefault();
        self.showLoader();
        // Load cây thư mục, callback = true thì load lại các file nữa
        self.getListFolders(true, true);
    });

    // Xem theo ngày tháng
    $(cfg.btnFilterSort).on('click', function(e) {
        e.preventDefault();

        $(cfg.ctnFilterSort).data('value', $(this).data('value'));
        $(cfg.ctnFilterSort).find('.text').html($(this).html());

        self.showLoader();
        self.getListFiles();
    });

    // Xem theo người đăng
    $(cfg.btnFilterUser).on('click', function(e) {
        e.preventDefault();

        $(cfg.ctnFilterUser).data('value', $(this).data('value'));
        $(cfg.ctnFilterUser).find('.text').html($(this).html());

        self.showLoader();
        self.getListFiles();
    });

    // Xem theo loại file
    $(cfg.btnFilterType).on('click', function(e) {
        e.preventDefault();

        $(cfg.ctnFilterType).data('value', $(this).data('value'));
        $(cfg.ctnFilterType).find('.text').html($(this).html());

        self.showLoader();
        self.getListFiles();
    });

    // Đóng mở form lọc ở mobile
    $(cfg.btnToggleFormFilter).on('click', function(e) {
        e.preventDefault();
        $(cfg.container).toggleClass('open-form-filter');
    });

    // Thiết lập các event này duy nhất một lần
    if (typeof window.fileManagerLoaded == "undefined") {
        // Xử lý khi kéo thả file vào
        var isFireFox = navigator.userAgent.indexOf('Firefox') > -1;
        var dragInCurrentTarget = null;
        /*
         * FifeFox lỗi không thể đếm kết thúc drag kiểu target
         * Dùng phương thức couter
         */
        var couterDragIn = 0;

        $(document).on('dragend', function(e) {
            dragInCurrentTarget = null;
            couterDragIn = 0;
        });

        $(document).on('dragleave', function(e) {
            couterDragIn--;
            if ((dragInCurrentTarget == e.target && !isFireFox) || (isFireFox && couterDragIn <= 0)) {
                (self.debug && console.log('dragleave'));
                e.stopPropagation();
                e.preventDefault();
                couterDragIn = 0;
                $(cfg.dropzoneCtn).hide();
                $(cfg.dropzoneCtn).removeClass('drag-hover');
            }
        });

        $(document).on('dragenter', function(e) {
            (self.debug && console.log('dragenter'));
            dragInCurrentTarget = e.target;
            e.stopPropagation();
            e.preventDefault();
            couterDragIn++;
            $(cfg.dropzoneCtn).show();
        });

        $(cfg.dropzoneArea).on('dragleave', function(e) {
            e.preventDefault();
            $(cfg.dropzoneCtn).removeClass('drag-hover');
        });

        $(cfg.dropzoneArea).on('dragenter', function(e) {
            e.preventDefault();
            $(cfg.dropzoneCtn).addClass('drag-hover');
        });

        $(document).on('drop', function(e) {
            dragInCurrentTarget = null;
            couterDragIn = 0;
        });

        /*
         * Xử lý khi thay đổi màn hình
         */
        $(window).on('resize', function() {
            $(cfg.folderElement).removeAttr('style');
        });

        /*
         * Tự động lấy file Alt khi điền URL remote upload
         */
        if (nv_auto_alt) {
            $('[name="uploadremoteFile"]', $(cfg.formRemoteUpload)).on('keyup', function() {
                var imageUrl = $(this).val();
                fileAlt = self.getImgAlt(imageUrl);
                $('[name="uploadremoteFileAlt"]', $(cfg.formRemoteUpload)).val(fileAlt);
            });
        }

        /*
         * Xử lý khi thao tác tại form công cụ ảnh
         */
        $('[name="newWidth"],[name="newHeight"]', $(cfg.formCreatImage)).on('keyup', function() {
            var type = $(this).attr("name"),
                value = $(this).val(),
                orgW = $('[name="newWidth"]', $(cfg.formCreatImage)).data('orgw'),
                orgH = $('[name="newHeight"]', $(cfg.formCreatImage)).data('orgh'),
                maxSize = self.getImageDisplaySize(orgW, orgH, nv_max_width, nv_max_height);

            if (!is_numeric(value) || value < 0) {
                $('[name="newWidth"]', $(cfg.formCreatImage)).val("");
                $('[name="newHeight"]', $(cfg.formCreatImage)).val("");
                return false;
            }
            if (type == "newWidth") {
                if (value > maxSize[0]) {
                    value = maxSize[0];
                }
                $('[name="newWidth"]', $(cfg.formCreatImage)).val(value);
                $('[name="newHeight"]', $(cfg.formCreatImage)).val(parseInt(orgH * value / orgW));
            } else {
                if (value > maxSize[1]) {
                    value = maxSize[1];
                }
                $('[name="newWidth"]', $(cfg.formCreatImage)).val(parseInt(value * orgW / orgH));
                $('[name="newHeight"]', $(cfg.formCreatImage)).val(value);
            }
        });
        $('[name="prView"]', $(cfg.formCreatImage)).on('click', function() {
            self.checkNewImageSize();
        });

        /*
         * Xử lý khi mở form tìm kiếm lên
         */
        $(cfg.formSearch).on('show.bs.modal', function(e) {
            var modalEle = $(e.currentTarget);
            $('[name="q"]', modalEle).val('');

            // Build cây thư mục
            $('[name="searchPath"]', modalEle).html('');
            $('a.view_dir', $(cfg.folderElement)).each(function() {
                var folder = $(this).data('folder');
                $('[name="searchPath"]', modalEle).append('<option value="' + folder + '"' + ($(cfgf.folder).data('value') == folder ? ' selected="selected"' : '') + '>' + folder + '</option>');
            });
        });
        $(cfg.formSearch).on('shown.bs.modal', function(e) {
            var modalEle = $(e.currentTarget);
            $('[name="q"]', modalEle).focus();
            self.fix2Modal(modalEle);
        });

        /*
         * Xử lý khi mở form tạo thư mục mới lên
         */
        $(cfg.formCreatFolder).on('shown.bs.modal', function(e) {
            var modalEle = $(e.currentTarget);
            $('[name="foldername"]', modalEle).val('').focus();
            self.fix2Modal(modalEle);
        });

        /*
         * Xử lý khi mở form đổi tên thư mục lên
         */
        $(cfg.formRenameFolder).on('shown.bs.modal', function(e) {
            var modalEle = $(e.currentTarget);
            $('[name="foldername"]', modalEle).focus();
            self.fix2Modal(modalEle);
        });

        /*
         * Xử lý khi mở, đóng form tạo lại ảnh thumb lên
         */
        $(cfg.formRecreatThumb).on('shown.bs.modal', function(e) {
            var modalEle = $(e.currentTarget);
            self.fix2Modal(modalEle);
        });
        $(cfg.formRecreatThumb).on('hide.bs.modal', function(e) {
            var modalEle = $(e.currentTarget);
            // Huỷ tiến trình tạo lại nếu đang chạy
            if (self.timerRecreatThumb) {
                clearTimeout(self.timerRecreatThumb);
            }
        });

        /*
         * Xử lý khi mở, đóng form xem chi tiết
         */
        $(cfg.formPreview).on('shown.bs.modal', function(e) {
            var modalEle = $(e.currentTarget);
            self.fix2Modal(modalEle);
        });
        $(cfg.formPreview).on('hide.bs.modal', function(e) {
            $('#FileRelativePathBtn').tooltip('dispose');
            $('#FileAbsolutePathBtn').tooltip('dispose');
        });

        /*
         * Xử lý khi mở form xoay ảnh lên
         */
        $(cfg.formRotateFile).on('shown.bs.modal', function(e) {
            var modalEle = $(e.currentTarget);
            var selFile = $(cfgm.file).data('value');
            var fdata = $('[data-file="' + selFile + '"]', $(cfg.filesContainer)).data('fdata').split("|");
            var path = (fdata[7] == "") ? $(cfgf.folder).data('value') : fdata[7];

            self.fix2Modal(modalEle);

            var ctnWidth = $('[data-toggle="name"]', modalEle).width();
            var size = self.getImageDisplaySize(fdata[0], fdata[1], ctnWidth, ctnWidth, true);
            var contentMargin = parseInt((Math.sqrt(size[0] * size[0] + size[1] * size[1]) - size[1]) / 2);

            $('[data-toggle="img"]', modalEle).css({
                'width': size[0],
                'height': size[1],
                'margin-top': contentMargin,
                'margin-bottom': contentMargin + 10,
                'margin-left': 'auto',
                'margin-right': 'auto'
            }).html('<img src="' + nv_base_siteurl + path + "/" + selFile + '?' + fdata[8] + '"  width="' + size[0] + '" height="' + size[1] + '">');
        });

        /*
         * Xử lý khi mở form cắt ảnh lên
         */
        $(cfg.formCrop).on('shown.bs.modal', function(e) {
            var modalEle = $(e.currentTarget);
            var selFile = $(cfgm.file).data('value');
            var fdata = $('[data-file="' + selFile + '"]', $(cfg.filesContainer)).data('fdata').split("|");
            var path = (fdata[7] == "") ? $(cfgf.folder).data('value') : fdata[7];

            self.fix2Modal(modalEle);

            fdata[0] = parseInt(fdata[0]);
            fdata[1] = parseInt(fdata[1]);

            var ctnWidth = $('[data-toggle="getw"]', modalEle).width();
            var size = self.getImageDisplaySize(fdata[0], fdata[1], ctnWidth, ctnWidth);

            $('[data-toggle="img"]', modalEle).css({
                'width': size[0],
                'height': size[1],
                'margin-bottom': 10,
                'margin-left': 'auto',
                'margin-right': 'auto'
            }).html('<img class="crop-image" src="' + nv_base_siteurl + path + "/" + selFile + '?' + fdata[8] + '"  width="' + size[0] + '" height="' + size[1] + '">');

            // Hiển thị thông báo khi ảnh quá nhỏ
            if (fdata[0] < 10 || fdata[1] < 10 || (fdata[0] < 16 && fdata[1] < 16)) {
                $('[data-toggle="note"]', modalEle).removeClass('d-none').html(LANG.crop_error_small);
            } else {
                $('[data-toggle="note"]', modalEle).addClass('d-none').html('');
            }

            // Init cropper
            $('img.crop-image', modalEle).cropper({
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
                    $('[name="x"]', modalEle).val(parseInt(Math.floor(e.x)));
                    $('[name="y"]', modalEle).val(parseInt(Math.floor(e.y)));
                    $('[name="w"]', modalEle).val(parseInt(Math.floor(e.width)));
                    $('[name="h"]', modalEle).val(parseInt(Math.floor(e.height)));
                }
            });
        });

        /*
         * Xử lý khi mở tạo ảnh mới lên
         */
        $(cfg.formCreatImage).on('shown.bs.modal', function(e) {
            var modalEle = $(e.currentTarget);
            var selFile = $(cfgm.file).data('value');
            var fdata = $('[data-file="' + selFile + '"]', $(cfg.filesContainer)).data('fdata').split("|");
            var path = (fdata[7] == "") ? $(cfgf.folder).data('value') : fdata[7];

            self.fix2Modal(modalEle);

            fdata[0] = parseInt(fdata[0]);
            fdata[1] = parseInt(fdata[1]);

            var ctnWidth = $('[data-toggle="imgname"]', modalEle).width();
            var size = self.getImageDisplaySize(fdata[0], fdata[1], ctnWidth, ctnWidth);
            $('[data-toggle="img"]', $(cfg.formCreatImage)).css({
                'width': (size[0] + 'px'),
                'height': size[1] + 'px'
            });
        });

        /*
         * Xử lý khi mở form thêm logo lên
         */
        $(cfg.formAddLogo).on('shown.bs.modal', function(e) {
            var modalEle = $(e.currentTarget);
            var selFile = $(cfgm.file).data('value');
            var fdata = $('[data-file="' + selFile + '"]', $(cfg.filesContainer)).data('fdata').split("|");
            var path = (fdata[7] == "") ? $(cfgf.folder).data('value') : fdata[7];

            self.fix2Modal(modalEle);

            fdata[0] = parseInt(fdata[0]);
            fdata[1] = parseInt(fdata[1]);

            var ctnWidth = $('[data-toggle="btns"]', modalEle).width();
            var size = self.getImageDisplaySize(fdata[0], fdata[1], ctnWidth, ctnWidth);
            var logo = $(cfgm.logo).data('value');
            var logoConfig = $(cfgm.logoConfig).data('value').split('|');

            $('[data-toggle="img"]', modalEle).css({
                'width': size[0],
                'height': size[1],
                'margin-left': 'auto',
                'margin-right': 'auto'
            }).html('<img src="' + nv_base_siteurl + path + "/" + selFile + '?' + fdata[8] + '"  width="' + size[0] + '" height="' + size[1] + '">');

            // Hiển thị thông báo khi ảnh quá nhỏ
            if (fdata[0] < 10 || fdata[1] < 10 || (fdata[0] < 16 && fdata[1] < 16)) {
                $('[data-toggle="note"]', modalEle).removeClass('d-none').html(LANG.addlogo_error_small);
            } else if (logo == '') {
                $('[data-toggle="note"]', modalEle).removeClass('d-none').html(LANG.notlogo);
            } else {
                $('[data-toggle="note"]', modalEle).addClass('d-none').html('');

                // Set logo size
                var markW, markH;

                if (fdata[0] <= 150) {
                    markW = Math.ceil(fdata[0] * parseFloat(logoConfig[2]) / 100);
                } else if (fdata[0] < 350) {
                    markW = Math.ceil(fdata[0] * parseFloat(logoConfig[3]) / 100);
                } else {
                    if (Math.ceil(fdata[0] * parseFloat(logoConfig[4]) / 100) > logoConfig[0]) {
                        markW = logoConfig[0];
                    } else {
                        markW = Math.ceil(fdata[0] * parseFloat(logoConfig[4]) / 100);
                    }
                }

                markH = Math.ceil(markW * logoConfig[1] / logoConfig[0]);

                if (markH > fdata[1]) {
                    markH = fdata[1];
                    markW = Math.ceil(markH * logoConfig[0] / logoConfig[1]);
                }

                // Init cropper
                $('[data-toggle="img"] img', modalEle).cropper({
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
                        $('[name="x"]', modalEle).val(parseInt(Math.floor(e.x)));
                        $('[name="y"]', modalEle).val(parseInt(Math.floor(e.y)));
                        $('[name="w"]', modalEle).val(parseInt(Math.floor(e.width)));
                        $('[name="h"]', modalEle).val(parseInt(Math.floor(e.height)));
                    },
                    built: function(e) {
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
                            'opacity' : 1,
                            'background-image' : 'url(' + logo + ')',
                            'background-size' : '100%',
                            'background-color' : 'transparent'
                        });
                    }
                });
            }
        });

        /*
         * Xử lý khi mở form di chuyển file lên
         */
        $(cfg.formMoveFile).on('shown.bs.modal', function(e) {
            var modalEle = $(e.currentTarget);
            self.fix2Modal(modalEle);
        });

        /*
         * Xử lý khi mở form đổi tên file lên
         */
        $(cfg.formRenameFile).on('shown.bs.modal', function(e) {
            var modalEle = $(e.currentTarget);
            self.fix2Modal(modalEle);
        });

        /*
         * Xử lý khi mở form upload từ internet lên
         */
        $(cfg.formRemoteUpload).on('shown.bs.modal', function(e) {
            var modalEle = $(e.currentTarget);
            self.fix2Modal(modalEle);

            $('[name="uploadremoteFile"]', modalEle).val('').focus();
            $('[name="uploadremoteFileAlt"]', modalEle).val('');
            $('[name="uploadremoteFileOK"]', modalEle).prop('disabled', false);

            var logo = $(cfgm.logo).data('value');
            var path = $(cfgf.folder).data('value');
            var folder = $('[data-folder="' + path + '"]', $(cfg.folderElement));

            if (logo == '' || folder.length < 1) {
                $('[data-toggle="autoLogoArea"]', modalEle).addClass('d-none');
                $('[name="auto_logo"]', modalEle).prop('checked', false);
            } else {
                $('[data-toggle="autoLogoArea"]', modalEle).removeClass('d-none');
                if (folder.is('.auto_logo')) {
                    $('[name="auto_logo"]', modalEle).prop('checked', true);
                } else {
                    $('[name="auto_logo"]', modalEle).prop('checked', false);
                }
            }
        });

        /*
         * Xử lý khi mở thông báo lên
         */
        $(cfg.modalAlert).on('shown.bs.modal', function(e) {
            var modalEle = $(e.currentTarget);
            self.fix2Modal(modalEle);
        });

        /*
         * Xử lý khi submit các form
         */
        $('form', $(cfg.formSearch)).on('submit', function(e) {
            e.preventDefault();
            self.submitSearch(this);
        });
        $('form', $(cfg.formCreatFolder)).on('submit', function(e) {
            e.preventDefault();
            self.submitCreatFolder(this);
        });
        $('form', $(cfg.formRenameFolder)).on('submit', function(e) {
            e.preventDefault();
            self.submitRenameFolder(this);
        });
        $('form', $(cfg.formRecreatThumb)).on('submit', function(e) {
            e.preventDefault();
            self.submitRecreatThumb(this);
        });
        $('form', $(cfg.formRenameFile)).on('submit', function(e) {
            e.preventDefault();
            self.submitRenameFile(this);
        });
        $('form', $(cfg.formMoveFile)).on('submit', function(e) {
            e.preventDefault();
            self.submitMoveFile(this);
        });
        $('form', $(cfg.formRotateFile)).on('submit', function(e) {
            e.preventDefault();
            self.submitRotateFile(this);
        });
        $('form', $(cfg.formCrop)).on('submit', function(e) {
            e.preventDefault();
            self.submitCrop(this);
        });
        $('form', $(cfg.formCreatImage)).on('submit', function(e) {
            e.preventDefault();
            self.submitCreatImage(this);
        });
        $('form', $(cfg.formAddLogo)).on('submit', function(e) {
            e.preventDefault();
            self.submitAddLogo(this);
        });
        $('form', $(cfg.formRemoteUpload)).on('submit', function(e) {
            e.preventDefault();
            self.submitRemoteUpload(this);
        });

        // Xử lý tại form xem chi tiết file
        $.widget.bridge('uitooltip', $.ui.tooltip);
        var clipboard1 = new ClipboardJS('#FileRelativePathBtn');
        var clipboard2 = new ClipboardJS('#FileAbsolutePathBtn');
        clipboard1.on('success', function(e) {
            $(e.trigger).tooltip('show');
        });
        clipboard2.on('success', function(e) {
            $(e.trigger).tooltip('show');
        });
        $("#FileRelativePathBtn").on('mouseout', function() {
            $(this).tooltip('dispose');
        });
        $("#FileAbsolutePathBtn").on('mouseout', function() {
            $(this).tooltip('dispose');
        });
        $("#FileRelativePath").on('focus', function() {
            $(this).select();
        });
        $("#FileAbsolutePath").on('focus', function() {
            $(this).select();
        });
    }

    // Load cây thư mục, callback = true thì sau đó sẽ load luôn các file
    self.showLoader();
    self.getListFolders(true, false, data);
}

/*
 * Mở cây thư mục
 */
NVCoreFileBrowser.prototype.openFolder = function() {
    var self = this;
    var pr = $(self.cfg.btnFolderMobileToggle).parent();
    $(self.cfg.folderElement).slideDown(200, function() {
        pr.addClass('open');
    });
}

/*
 * Đóng cây thư mục
 */
NVCoreFileBrowser.prototype.closeFolder = function() {
    var self = this;
    var pr = $(self.cfg.btnFolderMobileToggle).parent();
    $(self.cfg.folderElement).slideUp(200, function() {
        pr.removeClass('open');
    });
}

/*
 * Đặt tên thư mục ở dạng mobile
 */
NVCoreFileBrowser.prototype.setCurrentMobileFolderName = function(folder) {
    var self = this;
    var arr = [$(folder).text()];
    while (1) {
        var isBreak = true;
        var prt = $(folder).parent().parent();
        if (prt.is('ul')) {
            folder = prt.prev();
            if (folder.length == 1 && folder.is('a')) {
                isBreak = false;
                arr.push(folder.text());
            }
        }
        if (isBreak) {
            break;
        }
    }
    var text = [];
    for (i = arr.length; i > 0; i--) {
        text.push(arr[i - 1]);
    }
    text = text.join('/');
    $(self.cfg.btnFolderMobileToggle).find('span').html(text);
}

/*
 * Hiển thị icon load
 */
NVCoreFileBrowser.prototype.showLoader = function() {
    var self = this;
    $(self.cfg.container).addClass('loading');
}

/*
 * Ẩn icon load
 */
NVCoreFileBrowser.prototype.hideLoader = function() {
    var self = this;
    $(self.cfg.container).removeClass('loading');
}

/*
 * Xử lý khi ấn chuột vào file.
 * Bao gồm cả chuột trái, chuột giữa, chuột phải
 */
NVCoreFileBrowser.prototype.fileMouseup = function(file, e) {
    var self = this;
    var cfg = self.cfg;
    var cfgf = self.cfgFolderData;
    var cfgm = self.cfgMain;
    var ICON = self.ICON;

    // Khong xu ly neu jquery UI selectable dang kich hoat
    if (KEYPR.isFileSelectable == false) {
        // Set shift offset
        if (e.which != 3 && !KEYPR.isShift) {
            // Reset shift offset
            KEYPR.shiftOffset = 0;

            $.each($('.file', $(cfg.filesContainer)), function(k, v) {
                if (v == file) {
                    KEYPR.shiftOffset = k;
                    return false;
                }
            });
        }

        // e.which: 1: Left Mouse, 2: Center Mouse, 3: Right Mouse
        if (KEYPR.isCtrl) {
            if ($(file).is('.file-selected') && e.which != 3) {
                $(file).removeClass('file-selected');
            } else {
                $(file).addClass('file-selected');
            }
        } else if (KEYPR.isShift && e.which != 3) {
            var clickOffset = -1;
            $('.file', $(cfg.filesContainer)).removeClass('file-selected');

            $.each($('.file', $(cfg.filesContainer)), function(k, v) {
                if (v == file) {
                    clickOffset = k;
                }

                if ((clickOffset == -1 && k >= KEYPR.shiftOffset) || (clickOffset != -1 && k <= KEYPR.shiftOffset) || v == file) {
                    if (!$(v).is('.file-selected')) {
                        $(v).addClass('file-selected');
                    }
                }
            });
        } else {
            if (e.which != 3 || (e.which == 3 && !$(file).is('.file-selected'))) {
                $('.file-selected', $(cfg.filesContainer)).removeClass('file-selected');
                $(file).addClass('file-selected');
            }
        }

        LFILE.setSelFile();

        /*
         * Mở menu khi ấn chuột phải
         */
        if (e.which == 3) {
            var isMultiple = $('.file-selected', $(cfg.filesContainer)).length === 1 ? false : true;
            var fileExt = $(cfgm.file).data('value').slice(-3);
            var CKEditorFuncNum = $(cfgm.editorFunc).data('value');
            var area = $(cfgm.area).data('value');
            var html = "";

            // Menu chọn file
            if ((CKEditorFuncNum > 0 || area != "") && !isMultiple) {
                html += '<a class="dropdown-item" href="#" data-toggle="selectfile"><i class="' + ICON.select + '"></i>' + LANG.select + '</a>';
            }

            // Nếu không chọn nhiều file thì cho phép xem chi tiết và tải xuống
            if (!isMultiple) {
                html += '<a class="dropdown-item" href="#" data-toggle="download"><i class="' + ICON.download + '"></i>' + LANG.download + '</a>';
                html += '<a class="dropdown-item" href="#" data-toggle="preview"><i class="' + ICON.preview + '"></i>' + LANG.preview + '</a>';
            }

            // Các thao tác xử lý nếu đây là ảnh
            if ($.inArray(fileExt, array_images) !== -1) {
                if ($(cfgf.allowedCreatFile).data('value') == "1" && !isMultiple) {
                    html += '<a class="dropdown-item" data-toggle="addlogo" href="#"><i class="' + ICON.addlogo + '"></i>' + LANG.addlogo + '</a>';
                    html += '<a class="dropdown-item" data-toggle="create" href="#"><i class="' + ICON.create + '"></i>' + LANG.upload_createimage + '</a>';
                    html += '<a class="dropdown-item" data-toggle="crop" href="#"><i class="' + ICON.filecrop + '"></i>' + LANG.crop + '</a>';
                    html += '<a class="dropdown-item" data-toggle="rotate" href="#"><i class="' + ICON.filerotate + '"></i>' + LANG.rotate + '</a>';
                }
            }

            // Cho phép di chuyển file
            if ($(cfgf.allowedMoveFile).data('value') == "1") {
                html += '<a class="dropdown-item" data-toggle="move" href="#"><i class="' + ICON.move + '"></i>' + LANG.move + '</a>';
            }

            // Cho phép đổi tên file
            if ($(cfgf.allowedRenameFile).data('value') == "1" && !isMultiple) {
                html += '<a class="dropdown-item" data-toggle="renamefile" href="#"><i class="' + ICON.rename + '"></i>' + LANG.rename + '</a>';
            }

            // Cho phép xóa file
            if ($(cfgf.allowedDeleteFile).data('value') == "1") {
                html += '<a class="dropdown-item" data-toggle="deletefile" href="#"><i class="' + ICON.filedelete + '"></i>' + LANG.upload_delfile + '</a>';
            }

            $("div#contextMenu").html(html);
            NVCMENU.show(e);
        }

    }

    KEYPR.isFileSelectable = false;
}


/*
 * Khi đang kéo thả để chọn file
 */
NVCoreFileBrowser.prototype.fileSelecting = function(e, ui) {
    var self = this;
    var cfg = self.cfg;

    if (e.ctrlKey) {
        // Giữ CTRL để chọn thêm file hoặc bỏ file đã chọn
        if ($(ui.selecting).is('.file-selected')) {
            $(ui.selecting).addClass('file-unselected-temp');
        } else {
            $(ui.selecting).addClass('file-selected-temp');
        }
    } else if (e.shiftKey) {
        // Giữ SHIFT để thêm file (không bỏ file đã chọn)
        $(ui.selecting).addClass('file-selected-temp');
    } else {
        // Mặc định thì bỏ những file đã chọn trước đó và chọn file mới
        $(ui.selecting).removeClass('file-unselected-temp').addClass('file-selected-temp');
        $('.file:not(.file-selected-temp)', $(cfg.filesContainer)).addClass('file-unselected-temp');
    }
}

/*
 * Khi kết thúc kéo thả chọn file
 */
NVCoreFileBrowser.prototype.fileSelectStop = function(e, ui) {
    var self = this;
    var cfg = self.cfg;

    $(cfg.filesContainer).find('.ui-selected').removeClass('ui-selected');
    $('.file-selected-temp', $(cfg.filesContainer)).addClass('file-selected').removeClass('file-selected-temp');
    $('.file-unselected-temp', $(cfg.filesContainer)).removeClass('file-selected file-unselected-temp');
    LFILE.setSelFile();
}

/*
 * Khi thôi chọn file
 */
NVCoreFileBrowser.prototype.fileUnselect = function(e, ui) {
    $(ui.unselecting).removeClass('file-unselected-temp file-selected-temp');
}

/*
 * Xử lý sau khi tải nội dung cây thư mục
 */
NVCoreFileBrowser.prototype.folderHandler = function() {
    var self = this;
    var cfg = self.cfg;
    var cfgf = self.cfgFolderData;

    // Khi click vào tên thư mục => Đóng mở thư mục ở chế độ mobile
    $(cfg.btnFolderMobileToggle).on('click', function() {
        var pr = $(this).parent();
        if (pr.hasClass('open')) {
            self.closeFolder();
        } else {
            self.openFolder();
        }
    });

    // Đóng mở thư mục con
    $(cfg.childFolderBtnToggle).on('click', function(e) {
        e.stopPropagation();
        e.preventDefault();
        var prtLink = $(this).parent();
        var prtLinkCtn = prtLink.parent();
        var subMenu = prtLink.next();
        if (prtLinkCtn.hasClass('open')) {
            subMenu.slideUp(200, function() {
                prtLinkCtn.removeClass('open');
                updatePerfectScrollbar();
            });
        } else {
            subMenu.slideDown(200, function() {
                prtLinkCtn.addClass('open');
                updatePerfectScrollbar();
            });
        }
    });

    /*
     * Mở file trong thư mục
     * Xử lý khi ấn chuột trái vào thư mục
     */
    $(cfg.folderOpenFileBtn).on('click', function(e) {
        e.preventDefault();
        // Bỏ comment thì không ấn được khi đã mở thư mục tuy nhiên không cần thiết phải ràng buộc như vậy
        //if ($(this).parent().is('.active')) {
        //    return false;
        //}
        self.setCurrentMobileFolderName(this);
        $(cfg.folderElement).find('.active').removeClass('active');
        $(this).parent().addClass('active');
        if ($.isSm()) {
            self.closeFolder();
        }

        var $this = $(this);

        $(cfgf.folder).data('value', $this.data('folder'));
        $(cfgf.allowedView).data('value', $this.is(".view_dir") ? "1" : "0");
        $(cfgf.allowedCreatDir).data('value', $this.is(".create_dir") ? "1" : "0");
        $(cfgf.allowedReThumb).data('value', $this.is(".recreatethumb") ? "1" : "0");
        $(cfgf.allowedRenameDir).data('value', $this.is(".rename_dir") ? "1" : "0");
        $(cfgf.allowedDeleteDir).data('value', $this.is(".delete_dir") ? "1" : "0");
        $(cfgf.allowedUpload).data('value', $this.is(".upload_file") ? "1" : "0");
        $(cfgf.allowedCreatFile).data('value', $this.is(".create_file") ? "1" : "0");
        $(cfgf.allowedRenameFile).data('value', $this.is(".rename_file") ? "1" : "0");
        $(cfgf.allowedDeleteFile).data('value', $this.is(".delete_file") ? "1" : "0");
        $(cfgf.allowedMoveFile).data('value', $this.is(".move_file") ? "1" : "0");
        $(cfgf.allowedCropFile).data('value', $this.is(".crop_file") ? "1" : "0");
        $(cfgf.allowedRorateFile).data('value', $this.is(".rotate_file") ? "1" : "0");

        if ($this.is(".view_dir")) {
            self.showLoader();
            self.getListFiles();
        } else {
            $(cfg.filesContainer).html('');
            updatePerfectScrollbar();
        }
    });

    // Mở menu của thư mục khi ấn chuột phải
    $(cfg.folderOpenFileBtn).on('contextmenu', function(e) {
        e.preventDefault();
        self.folderMenu(this, e);
    });
}

/*
 * Load cây thư mục
 */
NVCoreFileBrowser.prototype.getListFolders = function(callback, reload, data) {
    var self = this;
    var cfg = self.cfg;
    var cfgf = self.cfgFolderData;
    var cfgm = self.cfgMain;

    if (callback) {
        self.perload = 0;
    }

    var path, currentPath = '';
    if (typeof data == 'undefined') {
        path = $(cfgf.path).data('value');
        currentPath = $(cfgf.folder).data('value');
    } else {
        path = data.path;
        currentPath = data.currentpath;
    }

    if (typeof data != "undefined" && typeof data.imgfile != "undefined" && data.imgfile != "") {
        $(cfgm.file).data("value", data.imgfile);
    }

    var urlFolder = self.firstData.baseurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=upload&' + nv_fc_variable + '=folderlist&path=' + path + '&currentpath=' + currentPath + (reload ? '&dirListRefresh' : '') + '&random=' + self.strRand(10);
    $(cfg.folderElement).load(urlFolder, function() {
        self.perload++;
        if (callback) {
            self.checkInitCompleted();
            self.getListFiles(callback, reload);
        } else {
            self.hideLoader();
            updatePerfectScrollbar();
        }

        self.folderHandler();

        // Xác định tên thư mục hiện tại cho di động khi load xong cây thư mục
        var activeFolder = $(cfg.folderElement).find('li.active:last');
        if (activeFolder.length) {
            self.setCurrentMobileFolderName(activeFolder.find('a:first')[0]);
        }
    });
}

/*
 * Load danh sách các file
 */
NVCoreFileBrowser.prototype.getListFiles = function(callback, reload, geturl) {
    var self = this;
    var cfg = self.cfg;
    var cfgf = self.cfgFolderData;
    var cfgm = self.cfgMain;
    var urlFiles;

    $(cfg.filesNav).html('').addClass('d-none');
    $(cfg.btnUpload).prop('disabled', true);
    $(cfg.btnUploadDropdown).prop('disabled', true);

    if (geturl) {
        urlFiles = geturl + '&random=' + self.strRand(10);
    } else {
        var imgtype = $(cfg.ctnFilterType).data('value');
        var selFile = $(cfgm.file).data('value');
        var author = $(cfg.ctnFilterUser).data('value');
        var order = $(cfg.ctnFilterSort).data('value');
        var path = $(cfgf.folder).data('value');
        var q = '';
        var folder = $('[data-folder="' + path + '"]', $(cfg.folderElement));
        if (folder.length) {
            q = folder.data('q');
            if (typeof q == 'undefined') {
                q = '';
            }
            folder.data('q', '');
        }

        urlFiles = self.firstData.baseurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=upload&' + nv_fc_variable + '=imglist&path=' + path + '&q=' + rawurlencode(q) + '&type=' + imgtype + '&imgfile=' + selFile + '&author=' + author + '&order=' + order + (reload ? '&refresh' : '') + '&random=' + self.strRand(10);
    }

    $.ajax({
        url: urlFiles,
        dataType: 'json',
        method: 'GET',
        cache: false
    }).done(function(data) {
        $(cfg.filesContainer).html(data.body);
        if (data.nav != '') {
            $(cfg.filesNav).html(data.nav).find('ul.pagination').addClass('justify-content-center');
            $(cfg.filesNav).removeClass('d-none');
        }
        self.perload++;
        if (callback) {
            self.checkInitCompleted();
        } else {
            self.hideLoader();
            updatePerfectScrollbar();
        }

        self.listFilesHandler();
    }).fail(function() {
        alert('Sys Error!!!');
    });
}

/*
 * Xử lý sau khi tải danh sách các file
 */
NVCoreFileBrowser.prototype.listFilesHandler = function() {
    var self = this;
    var cfg = self.cfg;

    /*
     * Kéo thả chuột để chọn file
     */
    $(cfg.filesContainer).selectable({
        filter: '.file',
        delay: 90,
        start: function(e, ui) {
            //NVCMENU.hide();
            // Thiết đặt true nhằm hạn chế ấn phím trong khi chọn
            KEYPR.isSelectable = true;
            KEYPR.isFileSelectable = true;
        },
        selecting: function(e, ui) {
            self.fileSelecting(e, ui);
        },
        stop: function(e, ui) {
            self.fileSelectStop(e, ui);
            setTimeout(function() {
                // Kết thúc chọn thì lại có thể ấn phím
                KEYPR.isSelectable = false;
                KEYPR.isFileSelectable = false;
            }, 50);
        },
        unselecting: function(e, ui) {
            self.fileUnselect(e, ui);
        },
    });

    // Xử lý khi ấn chuột trái, chuột giữa và chuột phải vào file
    $('.file', $(cfg.filesContainer)).on("mouseup", function(e) {
        e.preventDefault();
        self.fileMouseup(this, e);
    });

    // Xử lý khi click đôi vào file
    $('.file', $(cfg.filesContainer)).on("dblclick", function(e) {
        e.preventDefault();
        self.handleMenuSelect();
    });

    // Khi mở menu chuột phải thì không xử lý gì.
    $('.file', $(cfg.filesContainer)).on("contextmenu", function(e) {
        e.preventDefault();
    });

    // Xử lý khi click phân trang
    $('a', $(cfg.filesNav)).on('click', function(e) {
        e.preventDefault();
        if ($(this).is(':not([href="#"])')) {
            self.showLoader();
            self.getListFiles(false, false, $(this).attr('href'));
        }
    });

    // Tự động thiết lập chế độ xem lưới hay danh sách tùy loại file nào nhiều hơn
    if ($(cfg.btnChangeViewMode).data('auto')) {
        var numFiles = $('[data-img="false"]', $(cfg.filesContainer)).length;
        var numImage = $('[data-img="true"]', $(cfg.filesContainer)).length;

        if (numImage > numFiles) {
            $(cfg.container).removeClass('view-detail');
            $(cfg.container).addClass('view-gird');
        } else if (numFiles > 0) {
            $(cfg.container).removeClass('view-gird');
            $(cfg.container).addClass('view-detail');
        }
    }

    // Cuộn đến thành phần được đánh dấu
    var fileSelected = $('.file-selected:first', $(cfg.filesContainer));
    if (fileSelected.length) {
        $(cfg.filesScroller)[0].scrollTop = $(cfg.filesScroller).scrollTop() - $(cfg.filesScroller).offset().top + fileSelected.offset().top;
    }

    // Thiết lập chức năng upload
    self.uploadReset();
}

/*
 * Hiển thị menu chuột phải của thư mục
 */
NVCoreFileBrowser.prototype.folderMenu = function(folder, event) {
    var self = this;
    var ICON = self.ICON;

    if (!$(folder).is('.menu')) {
        return false;
    }
    var html = "";

    if ($(folder).is(".view_dir") && !$(folder).parent().is(".active")) {
        // Xem danh sách các file
        html += '<a href="#" class="dropdown-item" data-toggle="accessfolder" data-folder="' + $(folder).data('folder') + '"><i class="' + ICON.access + '"></i>' + LANG.gotofolder + '</a>'
    }
    if ($(folder).is(".create_dir")) {
        // Tạo thư mục con
        html += '<a href="#" class="dropdown-item" data-toggle="newfolder" data-folder="' + $(folder).data('folder') + '"><i class="' + ICON.create + '"></i>' + LANG.createfolder + '</a>'
    }
    if ($(folder).is(".recreatethumb")) {
        // Tạo lại ảnh thumb
        html += '<a href="#" class="dropdown-item" data-toggle="recreatethumb" data-folder="' + $(folder).data('folder') + '"><i class="' + ICON.recreatethumb + '"></i>' + LANG.recreatethumb + '</a>'
    }
    if ($(folder).is(".rename_dir")) {
        // Đổi tên thư mục
        html += '<a href="#" class="dropdown-item" data-toggle="renamefolder" data-folder="' + $(folder).data('folder') + '"><i class="' + ICON.rename + '"></i>' + LANG.renamefolder + '</a>'
    }
    if ($(folder).is(".delete_dir")) {
        // Xóa thư mục
        html += '<a href="#" class="dropdown-item" data-toggle="deletefolder" data-folder="' + $(folder).data('folder') + '"><i class="' + ICON.filedelete + '"></i>' + LANG.deletefolder + '</a>'
    }

    $("div#contextMenu").html(html);
    NVCMENU.show(event);
}

/*
 * Xử lý khi chuột phải thư mục chọn truy cập
 */
NVCoreFileBrowser.prototype.handleMenuAccessFolder = function (element) {
    var self = this;
    var cfg = self.cfg;
    var folder = $(element).data('folder');
    $('[data-folder="' + folder + '"]', $(cfg.folderElement)).trigger('click');
}

/*
 * Xử lý khi chuột phải thư mục chọn tạo thư mục mới
 */
NVCoreFileBrowser.prototype.handleMenuNewFolder = function (element) {
    var self = this;
    var cfg = self.cfg;
    var folder = $(element).data('folder');
    $('form', $(cfg.formCreatFolder)).data('folder', folder);
    $(cfg.formCreatFolder).modal('show');
}

/*
 * Xử lý khi chuột phải thư mục chọn xóa thư mục
 */
NVCoreFileBrowser.prototype.handleMenuDeleteFolder = function (element) {
    var self = this;
    var cfg = self.cfg;
    var cfgf = self.cfgFolderData;
    var folder = $(element).data('folder');

    if (confirm(LANG.delete_folder)) {
        $.ajax({
            type: "POST",
            url: nv_module_url + 'delfolder&random=' + self.strRand(10),
            data: {
                path: folder
            },
            success: function(b) {
                b = b.split("_");
                if (b[0] == "ERROR") {
                    alert(b[1]);
                    return false;
                }
                arrFolder = folder.split("/");
                parentFolder = '';
                for (i = 0; i < arrFolder.length - 1; i++) {
                    if (parentFolder != '') {
                        parentFolder += '/';
                    }
                    parentFolder += arrFolder[i];
                }
                $(cfg.formCreatFolder).modal('hide');
                self.showLoader();
                self.getListFolders(true, false, {
                    path: $(cfgf.path).data('value'),
                    currentpath: parentFolder
                });
            }
        });
    }
}

/*
 * Xử lý khi chuột phải thư mục chọn đổi tên thư mục
 */
NVCoreFileBrowser.prototype.handleMenuRenameFolder = function (element) {
    var self = this;
    var cfg = self.cfg;
    var folder = $(element).data('folder'), folderName;
    folderName = folder.split("/");
    folderName = folderName[folderName.length - 1];
    $('form', $(cfg.formRenameFolder)).data('folder', folder);
    $('[name="foldername"]', $(cfg.formRenameFolder)).val(folderName);
    $(cfg.formRenameFolder).modal('show');
}

/*
 * Xử lý khi chuột phải thư mục chọn tạo lại ảnh thumb
 */
NVCoreFileBrowser.prototype.handleMenuReThumb = function (element) {
    var self = this;
    var cfg = self.cfg;
    var folder = $(element).data('folder');
    $('form', $(cfg.formRecreatThumb)).data('folder', folder);
    $('form', $(cfg.formRecreatThumb)).data('busy', false);
    $('[data-toggle="welcome"]', $(cfg.formRecreatThumb)).removeClass('d-none');
    $('[data-toggle="load"]', $(cfg.formRecreatThumb)).addClass('d-none');
    $('[data-toggle="resultwrap"]', $(cfg.formRecreatThumb)).addClass('d-none');
    $('[data-toggle="resultall"]', $(cfg.formRecreatThumb)).addClass('d-none');
    $('[type="submit"]', $(cfg.formRecreatThumb)).prop('disabled', false);
    $(cfg.formRecreatThumb).modal('show');
}

/*
 * Xử lý khi chuột phải file, chọn download
 */
NVCoreFileBrowser.prototype.handleMenuDownload = function (element) {
    var self = this;
    var cfg = self.cfg;
    var cfgm = self.cfgMain;
    var cfgf = self.cfgFolderData;

    var file = $(cfgm.file).data('value');
    var fileItem = $('[data-file="' + file + '"]', $(cfg.filesContainer));
    if (fileItem.length) {
        var fdata = fileItem.data('fdata').split("|"), filepath, urldown;
        if (fdata[7] == "") {
            filepath = $(cfgf.folder).data('value');
        } else {
            filepath = fdata[7];
        }

        urldown = nv_module_url + 'dlimg&path=' + filepath + '&img=' + file;
        $(cfg.linkDownload).attr('href', urldown);
        $(cfg.linkDownload)[0].click();
    }
}

/*
 * Xử lý khi chuột phải file, chọn xem chi tiết
 */
NVCoreFileBrowser.prototype.handleMenuPreview = function (element) {
    var self = this;
    var cfg = self.cfg;
    var cfgm = self.cfgMain;
    var cfgf = self.cfgFolderData;

    var file = $(cfgm.file).data('value');
    var fileItem = $('[data-file="' + file + '"]', $(cfg.filesContainer));
    if (fileItem.length) {
        var fdata = fileItem.data('fdata').split("|"), filepath, fsizeinfo;
        if (fdata[7] == "") {
            filepath = $(cfgf.folder).data('value');
        } else {
            filepath = fdata[7];
        }
        fsizeinfo = LANG.upload_size + ": ";
        if (fdata[3] == "image") {
            fsizeinfo += fdata[0] + " x " + fdata[1] + " pixels (" + fdata[4] + ")";
            $('[data-toggle="thumb"]', $(cfg.formPreview)).html('<a href="' + nv_base_siteurl + filepath + "/" + file + '?' + fdata[8] + '" target="_blank"><img class="img-thumbnail" src="' + nv_base_siteurl + filepath + "/" + file + '?' + fdata[8] + '"></a>').show();
        } else {
            fsizeinfo += fdata[4];
            $('[data-toggle="thumb"]', $(cfg.formPreview)).html("").hide();
        }

        $('[data-toggle="alt"]', $(cfg.formPreview)).html(fileItem.data('alt'));
        $('[data-toggle="name"]', $(cfg.formPreview)).html(file);
        $('[data-toggle="size"]', $(cfg.formPreview)).html(fsizeinfo);
        $('[data-toggle="mtime"]', $(cfg.formPreview)).html(LANG.pubdate + ": " + fdata[6]);

        $('#FileRelativePath').val(nv_base_siteurl + filepath + "/" + file);
        $('#FileAbsolutePath').val(nv_my_domain + nv_base_siteurl + filepath + "/" + file);

        $(cfg.formPreview).modal('show');
    }
}

/*
 * Xử lý khi chuột phải file, chọn xem đổi tên file
 */
NVCoreFileBrowser.prototype.handleMenuRenameFile = function (element) {
    var self = this;
    var cfg = self.cfg;
    var cfgm = self.cfgMain;
    var cfgf = self.cfgFolderData;

    var file = $(cfgm.file).data('value');
    var fileItem = $('[data-file="' + file + '"]', $(cfg.filesContainer));

    $('[name="name"]', $(cfg.formRenameFile)).val(file.replace(/^(.+)\.([a-zA-Z0-9]+)$/, "$1"));
    $('[data-toggle="ext"]', $(cfg.formRenameFile)).html('.' + file.replace(/^(.+)\.([a-zA-Z0-9]+)$/, "$2"));
    $('[name="alt"]', $(cfg.formRenameFile)).val(fileItem.data('alt'));
    $('[data-toggle="orgfile"]', $(cfg.formRenameFile)).html(file);
    $('[type="submit"]', $(cfg.formRenameFile)).prop('disabled', false);

    $(cfg.formRenameFile).modal('show');
}

/*
 * Xử lý khi chuột phải file, chọn di chuyển
 */
NVCoreFileBrowser.prototype.handleMenuMove = function (element) {
    var self = this;
    var cfg = self.cfg;
    var cfgm = self.cfgMain;
    var cfgf = self.cfgFolderData;

    $('[name="mirrorFile"]', $(cfg.formMoveFile)).prop('checked', false);
    $('[name="goNewPath"]', $(cfg.formMoveFile)).prop('checked', false);

    // Build cây thư mục
    $('[name="newPath"]', $(cfg.formMoveFile)).html('');
    $('a.create_file', $(cfg.folderElement)).each(function() {
        var folder = $(this).data('folder');
        $('[name="newPath"]', $(cfg.formMoveFile)).append('<option value="' + folder + '"' + ($(cfgf.folder).data('value') == folder ? ' selected="selected"' : '') + '>' + folder + '</option>');
    });

    $('[type="submit"]', $(cfg.formMoveFile)).prop('disabled', false);

    $(cfg.formMoveFile).modal('show');
}

/*
 * Xử lý khi chuột phải file, chọn xóa file
 */
NVCoreFileBrowser.prototype.handleMenuDeleteFile = function (element) {
    var self = this;
    var cfg = self.cfg;
    var cfgm = self.cfgMain;
    var cfgf = self.cfgFolderData;

    var selFile = $(cfgm.file).data('value'), path, confirmMessage;

    // Kiem tra xoa nhieu file hay xoa 1 file
    if (selFile.indexOf('|') == -1) {
        var selFileData = $('[data-file="' + selFile + '"]', $(cfg.filesContainer)).data('fdata').split("|");
        path = (selFileData[7] == "") ? $(cfgf.folder).data('value') : selFileData[7];
        confirmMessage = LANG.upload_delimg_confirm + " " + selFile + " ?";
    } else {
        selFile = selFile.split('|');
        var selFileData = $('[data-file="' + selFile[0] + '"]', $(cfg.filesContainer)).data('fdata').split("|");
        path = (selFileData[7] == "") ? $(cfgf.folder).data('value') : selFileData[7];
        confirmMessage = LANG.upload_delimgs_confirm.replace('%s', selFile.length) + "?";
        selFile = selFile.join('|');
    }

    if (confirm(confirmMessage)) {
        self.showLoader();
        $.ajax({
            type: 'POST',
            url: nv_module_url + 'delimg&num=' + self.strRand(10),
            data: {
                path: path,
                file: selFile
            },
            success: function(e) {
                e = e.split('#');
                if (e[0] != 'OK') {
                    self.hideLoader();
                    alert(e[1]);
                    return false;
                }
                self.getListFiles();
            }
        });
    }
}

/*
 * Xử lý khi chuột phải file, chọn xoay ảnh
 */
NVCoreFileBrowser.prototype.handleMenuRotate = function (element) {
    var self = this;
    var cfg = self.cfg;
    var cfgm = self.cfgMain;
    var cfgf = self.cfgFolderData;

    var selFile = $(cfgm.file).data('value');

    $('[name="rorateDirection"]', $(cfg.formRotateFile)).val('0');
    $('[data-toggle="name"]', $(cfg.formRotateFile)).html(selFile);
    $('[type="submit"]', $(cfg.formRotateFile)).prop('disabled', false);

    // Reset lại giá trị xoay ảnh
    RRT.direction = 0;
    RRT.currentDirection = 0;

    $(cfg.formRotateFile).modal('show');
}

/*
 * Xử lý khi chuột phải file, chọn cắt ảnh
 */
NVCoreFileBrowser.prototype.handleMenuCrop = function (element) {
    var self = this;
    var cfg = self.cfg;
    var cfgm = self.cfgMain;
    var cfgf = self.cfgFolderData;

    $('[name="keeporg"]', $(cfg.formCrop)).prop('checked', false);
    $('[type="submit"]', $(cfg.formCrop)).prop('disabled', false);

    $(cfg.formCrop).modal('show');
}

/*
 * Xử lý khi chuột phải file, chọn công cụ ảnh (tạo ảnh mới)
 */
NVCoreFileBrowser.prototype.handleMenuCreatImage = function (element) {
    var self = this;
    var cfg = self.cfg;
    var cfgm = self.cfgMain;
    var cfgf = self.cfgFolderData;

    var file = $(cfgm.file).data('value');
    var fileItem = $('[data-file="' + file + '"]', $(cfg.filesContainer));
    var fdata = fileItem.data('fdata').split("|"), filepath;
    if (fdata[7] == "") {
        filepath = $(cfgf.folder).data('value');
    } else {
        filepath = fdata[7];
    }
    var SizeMax = self.getImageDisplaySize(fdata[0], fdata[1], nv_max_width, nv_max_height);
    var SizeMin = self.getImageDisplaySize(fdata[0], fdata[1], nv_min_width, nv_min_height);

    $('[data-toggle="limit"]', $(cfg.formCreatImage)).html("Max: " + SizeMax[0] + " x " + SizeMax[1] + ", Min: " + SizeMin[0] + " x " + SizeMin[1] + " (pixels)");
    $('[data-toggle="img"]', $(cfg.formCreatImage)).attr('src', nv_base_siteurl + filepath + "/" + file + "?" + fdata[8]);
    $('[data-toggle="orgsize"]', $(cfg.formCreatImage)).html(LANG.origSize + ": " + fdata[0] + " x " + fdata[1] + " pixels");
    $('[data-toggle="imgname"]', $(cfg.formCreatImage)).html(file);
    $('[data-toggle="error"]', $(cfg.formCreatImage)).html('').addClass('d-none');
    $('[name="newWidth"]', $(cfg.formCreatImage)).val('').data('orgw', fdata[0]);
    $('[name="newHeight"]', $(cfg.formCreatImage)).val('').data('orgh', fdata[1]);
    $('[type="submit"]', $(cfg.formCreatImage)).prop('disabled', false);

    $(cfg.formCreatImage).modal('show');
}

/*
 * Xử lý khi chuột phải file, chọn thêm logo
 */
NVCoreFileBrowser.prototype.handleMenuAddLogo = function (element) {
    var self = this;
    var cfg = self.cfg;
    var cfgm = self.cfgMain;
    var cfgf = self.cfgFolderData;

    var file = $(cfgm.file).data('value');
    var fileItem = $('[data-file="' + file + '"]', $(cfg.filesContainer));
    var fdata = fileItem.data('fdata').split("|"), filepath;
    if (fdata[7] == "") {
        filepath = $(cfgf.folder).data('value');
    } else {
        filepath = fdata[7];
    }

    var logo = $(cfgm.logo).data('value');
    var logoConfig = $(cfgm.logoConfig).data('value').split('|');

    $(cfg.formAddLogo).modal('show');
}

/*
 * Xử lý khi chuột phải file, chọn chọn file
 */
NVCoreFileBrowser.prototype.handleMenuSelect = function (element) {
    var self = this;
    var cfg = self.cfg;
    var cfgm = self.cfgMain;
    var cfgf = self.cfgFolderData;

    var file = $(cfgm.file).data('value');
    var fileItem = $('[data-file="' + file + '"]', $(cfg.filesContainer));
    var fdata = fileItem.data('fdata').split("|"), filepath;
    if (fdata[7] == "") {
        filepath = $(cfgf.folder).data('value');
    } else {
        filepath = fdata[7];
    }
    var fullPath = nv_base_siteurl + filepath + '/' + file;

    if (self.firstData.area != '') {
        if ($(self.firstData.area).length) {
            if (self.firstData.restype == 'folderpath') {
                $(self.firstData.area).val(nv_base_siteurl + filepath);
            } else {
                $(self.firstData.area).val(fullPath);
            }
        }
    } else {
        //
    }

    $('#mdNVFileManagerPopup').modal('hide');
}

/*
 * Submit form tìm kiếm file
 */
NVCoreFileBrowser.prototype.submitSearch = function (e) {
    var self = this;
    var cfg = self.cfg;
    var cfgf = self.cfgFolderData;

    var form = $(e);
    var searchFolder = $('[name="searchPath"]', form).val();
    var searchQ = $('[name="q"]', form).val();

    // Tương tự nhấp vào tên thư mục
    var folder = $('[data-folder="' + searchFolder + '"]', $(cfg.folderElement));
    if (folder.length) {
        folder.data('q', searchQ);
        folder.parent().removeClass('active');
        folder.trigger('click');
    }
    $(cfg.formSearch).modal('hide');
}

/*
 * Submit form tạo thư mục
 */
NVCoreFileBrowser.prototype.submitCreatFolder = function (e) {
    var self = this;
    var cfg = self.cfg;
    var cfgf = self.cfgFolderData;

    var form = $(e);
    if (form.data('busy')) {
        return false;
    }

    var foldername = $('[name="foldername"]', form).val();
    var path = form.data('folder');
    if (foldername == "" || !nv_namecheck.test(foldername)) {
        alert(LANG.name_folder_error);
        $('[name="foldername"]', form).focus();
        return false;
    }
    form.data('busy', true);
    $.ajax({
        type: 'POST',
        url: nv_module_url + 'createfolder&random=' + self.strRand(10),
        data: {
            path: path,
            newname: foldername
        },
        cache: false,
        success: function(d) {
            form.data('busy', false);
            var e = d.split("_");
            if (e[0] == "ERROR") {
                alert(e[1]);
                return false;
            }
            $(cfg.formCreatFolder).modal('hide');
            self.showLoader();
            self.getListFolders(true, false, {
                path: $(cfgf.path).data('value'),
                currentpath: d
            });
        }
    });
}

/*
 * Submit form đổi tên thư mục
 */
NVCoreFileBrowser.prototype.submitRenameFolder = function (e) {
    var self = this;
    var cfg = self.cfg;
    var cfgf = self.cfgFolderData;

    var form = $(e);
    if (form.data('busy')) {
        return false;
    }

    var foldername = $('[name="foldername"]', form).val();
    var path = form.data('folder');
    // Kiểm tra thư mục đúng chuẩn
    if (foldername == "" || !nv_namecheck.test(foldername)) {
        alert(LANG.rename_nonamefolder);
        $('[name="foldername"]', form).focus();
        return false;
    }
    // Kiểm tra trùng
    var samepath = $('[data-folder="' + path + '"]', $(cfg.folderElement));
    var isExists = false;
    if (samepath.length) {
        samepath = samepath.attr('class').split(' ');
        samepath = samepath[samepath.length - 1];
        $("a." + samepath, $(cfg.folderElement)).each(function() {
            var _folderName = $(this).data('folder').split('/');
            _folderName = _folderName[_folderName.length - 1];
            if (_folderName == foldername) {
                isExists = true;
            }
        });
    }
    if (isExists) {
        alert(LANG.folder_exists);
        $('[name="foldername"]', form).focus();
        return false;
    }
    form.data('busy', true);
    $.ajax({
        type: 'POST',
        url: nv_module_url + 'renamefolder&random=' + self.strRand(10),
        data: {
            path: path,
            newname: foldername
        },
        cache: false,
        success: function(d) {
            form.data('busy', false);
            var e = d.split("_");
            if (e[0] == "ERROR") {
                alert(e[1]);
                return false;
            }
            $(cfg.formRenameFolder).modal('hide');
            self.showLoader();
            self.getListFolders(true, false, {
                path: $(cfgf.path).data('value'),
                currentpath: d
            });
        }
    });
}

/*
 * Submit form tạo lại ảnh thumb
 */
NVCoreFileBrowser.prototype.submitRecreatThumb = function (e) {
    var self = this;
    var cfg = self.cfg;
    var cfgf = self.cfgFolderData;
    var form = $(e);
    if (form.data('busy') || $('[type="submit"]', form).is(':disabled')) {
        return false;
    }
    var path = form.data('folder');

    form.data('busy', true);
    $('[type="submit"]', form).prop('disabled', true);
    $('[data-toggle="welcome"]', form).addClass('d-none');
    $('[data-toggle="load"]', form).removeClass('d-none');

    self.timerRecreatThumb = setTimeout(function() {
        self.runRecreatThumb(path, -1);
    }, 500);
}

/*
 * Submit form đổi tên file
 */
NVCoreFileBrowser.prototype.submitRenameFile = function (e) {
    var self = this;
    var cfg = self.cfg;
    var cfgm = self.cfgMain;
    var cfgf = self.cfgFolderData;
    var form = $(e);
    if (form.data('busy') || $('[type="submit"]', form).is(':disabled')) {
        return false;
    }

    var file = $(cfgm.file).data('value');
    var fileItem = $('[data-file="' + file + '"]', $(cfg.filesContainer));

    var newname = $.trim($('[name="name"]', form).val());
    var newalt = $.trim($('[name="alt"]', form).val());
    var checkname = file.match(/^(.+)\.([a-zA-Z0-9]+)$/);

    // Báo lỗi không nhập tên file
    if (newname == '') {
        alert(LANG.rename_noname);
        $('[name="name"]', form).focus();
        return false;
    }
    // Không thay đổi gì thì kết thúc
    if (newname == checkname[1] && fileItem.data('alt') == newalt) {
        $(cfg.formRenameFile).modal('hide');
        return true;
    }

    var fdata = fileItem.data('fdata').split("|"), filepath;
    if (fdata[7] == "") {
        filepath = $(cfgf.folder).data('value');
    } else {
        filepath = fdata[7];
    }

    $('[type="submit"]', form).prop('disabled', true);

    $.ajax({
        type: 'POST',
        url: nv_module_url + 'renameimg&num=' + self.strRand(10),
        data: {
            path: filepath,
            file: file,
            newname: newname,
            newalt: newalt
        },
        success: function(g) {
            var h = g.split("_");

            $('[type="submit"]', form).prop('disabled', false);

            if (h[0] == "ERROR") {
                alert(h[1]);
                return false;
            }

            self.showLoader();
            self.getListFiles();
            $(cfg.formRenameFile).modal('hide');
        }
    });
}

/*
 * Submit form di chuyển file
 */
NVCoreFileBrowser.prototype.submitMoveFile = function (e) {
    var self = this;
    var cfg = self.cfg;
    var cfgm = self.cfgMain;
    var cfgf = self.cfgFolderData;
    var form = $(e);
    if (form.data('busy') || $('[type="submit"]', form).is(':disabled')) {
        return false;
    }

    var selfile = $(cfgm.file).data('value');
    var currentPath = $(cfgf.folder).data('value');
    var newPath = $('[name="newPath"]', form).val();
    var mirrorFile = ($('[name="mirrorFile"]', form).is(':checked') ? 1 : 0);
    var goNewPath = ($('[name="goNewPath"]', form).is(':checked') ? 1 : 0);

    if (currentPath == newPath) {
        $(cfg.formMoveFile).modal('hide');
        return true;
    }

    $('[type="submit"]', form).prop('disabled', true);

    $.ajax({
        type: "POST",
        url: nv_module_url + 'moveimg&num=' + self.strRand(10),
        data: {
            path: currentPath,
            newpath: newPath,
            file: selfile,
            mirror: mirrorFile
        },
        success: function(e) {
            $('[type="submit"]', form).prop('disabled', false);

            var e = e.split("#");
            if (e[0] == "ERROR") {
                alert(e[1]);
                return false;
            }

            var imgfile = e[1];

            $(cfg.formMoveFile).modal('hide');
            $(cfgm.file).data('value', imgfile);

            self.showLoader();
            if (goNewPath) {
                $('[data-folder="' + newPath + '"]', $(cfg.folderElement)).trigger('click');
            } else {
                self.getListFiles();
            }
        }
    });
}

/*
 * Submit form xoay ảnh
 */
NVCoreFileBrowser.prototype.submitRotateFile = function (e) {
    var self = this;
    var cfg = self.cfg;
    var cfgm = self.cfgMain;
    var cfgf = self.cfgFolderData;
    var form = $(e);
    if (form.data('busy') || $('[type="submit"]', form).is(':disabled')) {
        return false;
    }

    var file = $(cfgm.file).data('value');
    var fileItem = $('[data-file="' + file + '"]', $(cfg.filesContainer));
    var fdata = fileItem.data('fdata').split("|"), filepath;
    if (fdata[7] == "") {
        filepath = $(cfgf.folder).data('value');
    } else {
        filepath = fdata[7];
    }
    var rorateDirection = $('[name="rorateDirection"]', form).val();

    $('[type="submit"]', form).prop('disabled', true);

    $.ajax({
        type: 'POST',
        url: nv_module_url + 'rotateimg&num=' + self.strRand(10),
        data: {
            path: filepath,
            file: file,
            direction: rorateDirection
        },
        success: function(g) {
            $('[type="submit"]', form).prop('disabled', false);
            var h = g.split("#");

            if (h[0] == "ERROR") {
                alert(h[1]);
                return false;
            }

            self.showLoader();
            self.getListFiles();
            $(cfg.formRotateFile).modal('hide');
        }
    });
}

/*
 * Submit form cắt ảnh
 */
NVCoreFileBrowser.prototype.submitCrop = function (e) {
    var self = this;
    var cfg = self.cfg;
    var cfgm = self.cfgMain;
    var cfgf = self.cfgFolderData;
    var form = $(e);
    if (form.data('busy') || $('[type="submit"]', form).is(':disabled')) {
        return false;
    }

    var file = $(cfgm.file).data('value');
    var fileItem = $('[data-file="' + file + '"]', $(cfg.filesContainer));
    var fdata = fileItem.data('fdata').split("|"), filepath;
    if (fdata[7] == "") {
        filepath = $(cfgf.folder).data('value');
    } else {
        filepath = fdata[7];
    }

    $('[type="submit"]', form).prop('disabled', true);

    $.ajax({
        type: 'POST',
        url: nv_module_url + 'cropimg&random=' + self.strRand(10),
        data: {
            path: filepath,
            file: file,
            x: $('[name="x"]', form).val(),
            y: $('[name="y"]', form).val(),
            w: $('[name="w"]', form).val(),
            h: $('[name="h"]', form).val(),
            k: ($('[name="keeporg"]', form).is(':checked') ? 1 : 0)
        },
        success: function(e) {
            $('[type="submit"]', form).prop('disabled', false);
            e = e.split('#');

            if (e[0] == 'ERROR') {
                alert(e[1]);
                return false;
            }

            $(cfgm.file).data('value', e[1]);
            self.showLoader();
            self.getListFiles();
            $(cfg.formCrop).modal('hide');
        }
    });
}

/*
 * Submit form tạo ảnh mới
 */
NVCoreFileBrowser.prototype.submitCreatImage = function (e) {
    var self = this;
    var cfg = self.cfg;
    var cfgm = self.cfgMain;
    var cfgf = self.cfgFolderData;
    var form = $(e);
    if (form.data('busy') || $('[type="submit"]', form).is(':disabled')) {
        return false;
    }

    var file = $(cfgm.file).data('value');
    var fileItem = $('[data-file="' + file + '"]', $(cfg.filesContainer));
    var fdata = fileItem.data('fdata').split("|"), filepath;
    if (fdata[7] == "") {
        filepath = $(cfgf.folder).data('value');
    } else {
        filepath = fdata[7];
    }

    if (self.checkNewImageSize() !== true) {
        return false;
    }

    $('[type="submit"]', form).prop('disabled', true);

    $.ajax({
        type: 'POST',
        url: nv_module_url + 'createimg&random=' + self.strRand(10),
        data: {
            path: filepath,
            img: file,
            width: $('[name="newWidth"]', form).val(),
            height: $('[name="newHeight"]', form).val()
        },
        success: function(h) {
            $('[type="submit"]', form).prop('disabled', false);
            var j = h.split("_");
            if (j[0] == "ERROR") {
                alert(j[1]);
                return false;
            }

            $(cfgm.file).data('value', h);
            self.showLoader();
            self.getListFiles();
            $(cfg.formCreatImage).modal('hide');
        }
    });
}

/*
 * Submit form thêm logo
 */
NVCoreFileBrowser.prototype.submitAddLogo = function (e) {
    var self = this;
    var cfg = self.cfg;
    var cfgm = self.cfgMain;
    var cfgf = self.cfgFolderData;
    var form = $(e);
    if (form.data('busy') || $('[type="submit"]', form).is(':disabled')) {
        return false;
    }

    var file = $(cfgm.file).data('value');
    var fileItem = $('[data-file="' + file + '"]', $(cfg.filesContainer));
    var fdata = fileItem.data('fdata').split("|"), filepath;
    if (fdata[7] == "") {
        filepath = $(cfgf.folder).data('value');
    } else {
        filepath = fdata[7];
    }

    $('[type="submit"]', form).prop('disabled', true);

    $.ajax({
        type: 'POST',
        url: nv_module_url + 'addlogo&random=' + self.strRand(10),
        data: {
            path: filepath,
            file: file,
            x: $('[name="x"]', form).val(),
            y: $('[name="y"]', form).val(),
            w: $('[name="w"]', form).val(),
            h: $('[name="h"]', form).val()
        },
        success: function(e) {
            $('[type="submit"]', form).prop('disabled', false);
            e = e.split('#');

            if (e[0] == 'ERROR') {
                alert(e[1]);
                return false;
            }

            self.showLoader();
            self.getListFiles();
            $(cfg.formAddLogo).modal('hide');
        }
    });
}

/*
 * Submit form upload file từ internet
 */
NVCoreFileBrowser.prototype.submitRemoteUpload = function (e) {
    var self = this;
    var cfg = self.cfg;
    var cfgm = self.cfgMain;
    var cfgf = self.cfgFolderData;
    var form = $(e);
    if (form.data('busy') || $('[type="submit"]', form).is(':disabled')) {
        return false;
    }

    var fileUrl = $('[name="uploadremoteFile"]', form).val();
    var currUrl = $(cfgm.fileURL).data('value');
    var folderPath = $(cfgf.folder).data('value');
    var check = fileUrl + " " + folderPath;
    var fileAlt = $('[name="uploadremoteFileAlt"]', form).val();
    var auto_logo = ($('[name="auto_logo"]', form).is(':checked') ? 1 : 0);

    if (/^(https?|ftp):\/\//i.test(fileUrl) === false) {
        fileUrl = 'http://' + fileUrl;
    }
    $(cfgm.fileURL).data('value', fileUrl);

    if (/^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(fileUrl) && currUrl != check && ((nv_alt_require && fileAlt != '') || !nv_alt_require)) {
        $('[type="submit"]', form).prop('disabled', true);
        $('[data-toggle="loader"]', form).removeClass('d-none');

        $.ajax({
            type: "POST",
            url: nv_module_url + "upload&random=" + self.strRand(10),
            data: {
                path: folderPath,
                fileurl: fileUrl,
                filealt: fileAlt,
                autologo: auto_logo
            },
            success: function(k) {
                $('[type="submit"]', form).prop('disabled', false);
                $('[data-toggle="loader"]', form).addClass('d-none');

                var l = k.split("_");
                if (l[0] == "ERROR") {
                    alert(l[1]);
                    return false;
                }

                $(cfgm.fileURL).data('value', check);
                $(cfgm.file).data('value', k);

                self.showLoader();
                self.getListFiles();
                $(cfg.formRemoteUpload).modal('hide');
            }
        });
    } else if (nv_alt_require && fileAlt == '' && fileUrl != '') {
        alert(LANG.upload_alt_note);
    } else {
        alert(nv_url);
    }
}

/*
 * Tiến trình chạy tạo lại ảnh thumb
 */
NVCoreFileBrowser.prototype.runRecreatThumb = function (path, idfile) {
    var self = this;
    var cfg = self.cfg;
    var form = $('form', $(cfg.formRecreatThumb));

    clearTimeout(self.timerRecreatThumb);
    $.ajax({
        type : 'POST',
        url : nv_module_url + 'recreatethumb&random=' + self.strRand(10),
        data : {
            path: path,
            idf: idfile
        },
        success : function(d) {
            var e = d.split("_");
            if (e[0] == "ERROR") {
                alert(e[1]);
                return;
            }
            if (e[0] == "OK") {
                self.timerRecreatThumb = setTimeout(function() {
                    var per = (parseInt(e[1]) / parseInt(e[2])) * 100;
                    var progressbar = $('.progress-bar', form);

                    $('[data-toggle="creatcurrent"]', form).html(e[1]);
                    $('[data-toggle="creattotal"]', form).html(e[2]);

                    progressbar.css({width: per + '%'});
                    progressbar.attr('aria-valuenow', per);
                    progressbar.html(Math.ceil(per) + '%');

                    $('[data-toggle="load"]', form).addClass('d-none');
                    $('[data-toggle="resultwrap"]', form).removeClass('d-none');
                    self.runRecreatThumb(path, e[1]);
                }, 1000);
            } else if (e[0] == "COMPLETE") {
                $('[data-toggle="load"]', form).addClass('d-none');
                $('[data-toggle="resultnum"]', form).html(e[1]);
                $('[data-toggle="resultwrap"]', form).addClass('d-none');
                $('[data-toggle="resultall"]', form).removeClass('d-none');
                setTimeout(function() {
                    $(cfg.formRecreatThumb).modal('hide');
                }, 3000);
            }
        }
    });
}

// Tạo chuỗi ngẫu nhiên
NVCoreFileBrowser.prototype.strRand = function (a) {
    for (var b = "", d = 0; d < a; d++) {
        b += "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890".charAt(Math.floor(Math.random() * 62));
    }
    return b;
}

// Kiểm tra ẩn icon load
NVCoreFileBrowser.prototype.checkInitCompleted = function () {
    if (this.perload > 1) {
        this.hideLoader();
        updatePerfectScrollbar();
    }
}

/*
 * Thiết lập nút Upload
 */
NVCoreFileBrowser.prototype.uploadInit = function() {
    var self = this;
    var cfg = self.cfg;
    var cfgm = self.cfgMain;
    var cfgf = self.cfgFolderData;

    if (self.uploader) {
        return true;
    }

    // Cấm các nút upload
    $(cfg.btnUpload).prop('disabled', true);
    $(cfg.btnUploadDropdown).prop('disabled', true);

    // Kiểm tra quyền upload vào thư mục hiện tại
    var isUploadAllow = $(cfgf.allowedUpload).data('value');
    if (!isUploadAllow) {
        $(cfg.btnUpload).prop('title', LANG.notupload);
        $(cfg.btnUploadDropdown).prop('title', LANG.notupload);
        return true;
    }

    // Cho phép nút upload
    $(cfg.btnUpload).prop('disabled', false);
    $(cfg.btnUploadDropdown).prop('disabled', false);

    var folderPath = $(cfgf.folder).data('value');

    // Thiết lập trình Upload
    self.uploader = new plupload.Uploader({
        runtimes: 'html5,flash,silverlight,html4',
        browse_button: $(cfg.btnUpload)[0],
        url: nv_module_url + "upload&path=" + folderPath + "&random=" + self.strRand(10),
        flash_swf_url: nv_base_siteurl + 'assets/js/plupload/Moxie.swf',
        silverlight_xap_url: nv_base_siteurl + 'assets/js/plupload/Moxie.xap',
        drop_element: $(cfg.container)[0],
        file_data_name: 'upload',
        multipart: true,
        filters : {
           max_file_size : nv_max_size_bytes,
           mime_types: []
        },
        chunk_size: nv_chunk_size,
        resize: false,
        init: {
            // Event on init uploader
            PostInit: function() {
                // Không làm gì
                (self.debug && console.log("Plupload: Event init"));
            },

            // Event on add file (Add to queue or first add)
            FilesAdded: function(up, files) {
                (self.debug && console.log("Plupload: Event fileadded"));

                // Thiết lập container của trình upload
                if (!self.uploadRendered) {
                    self.uploadRenderUI();
                }

                self.uploadRefreshList();
                $(cfg.dropzoneCtn).removeClass('drag-hover').hide();
            },

            // Event on trigger a file upload status
            UploadProgress: function(up, file) {
                (self.debug && console.log("Plupload: Event Upload Progress"));
                $('#' + file.id + ' [data-toggle="filestatus"]').html(file.percent + '%');
                self.handleStatus(file, false);
                self.updateTotalProgress();
            },

            // Event on one file finish uploaded (Maybe success or error)
            FileUploaded: function(up, file, response) {
                (self.debug && console.log("Plupload: Event file uploaded"));
                (self.debug && console.log(response));
                response = response.response;
                self.handleStatus(file, response);
            },

            // Event on start upload or finish upload
            StateChanged: function() {
                (self.debug && console.log("Plupload: Event state changed " + self.uploader.state));

                if (self.uploader.state === plupload.STARTED) {
                    // Bắt đầu upload
                    if (!self.uploadStarted) {
                        self.uploadStarted = true;

                        // Ẩn các nút
                        $('[data-toggle="addfile"]', $(cfg.ctnUploadQueue)).addClass('d-none');
                        $('[data-toggle="start"]', $(cfg.ctnUploadQueue)).addClass('d-none');
                        $('[data-toggle="cancel"]', $(cfg.ctnUploadQueue)).addClass('d-none');

                        // Hiển thị nút tạm dừng, tiếp tục
                        if (parseFloat(nv_chunk_size) <= 0) {
                            $('[data-toggle="stop"]', $(cfg.ctnUploadQueue)).removeClass('d-none');
                        }

                        self.updateTotalProgress();
                    }
                } else if (self.uploader.state != 8) {
                    // 8 is Queueable.DESTROYED state
                    self.uploadRefreshList();
                }
            },

            // Event on a file is uploading
            UploadFile: function(up, file) {
                // Not thing to do
            },

            // Event on remove a file
            FilesRemoved: function() {
                (self.debug && console.log("Plupload: Event file removed"));
                var scrollTop = $(cfg.ctnUploadScroller).scrollTop();
                self.uploadRefreshList();
                $(cfg.ctnUploadScroller).scrollTop(scrollTop);
            },

            // Event on all files are uploaded
            UploadComplete: function(up, files) {
                (self.debug && console.log("Plupload: Event upload completed"));

                // Ẩn nút tiếp tục, tạm dừng
                $('[data-toggle="stop"]', $(cfg.ctnUploadQueue)).addClass('d-none');
                $('[data-toggle="continue"]', $(cfg.ctnUploadQueue)).addClass('d-none');

                if (self.uploader.total.failed > 0) {
                    // Nếu có file nào đó bị thất bại thì hiển thị nút kết thúc
                    $('[data-toggle="finish"]', $(cfg.ctnUploadQueue)).removeClass('d-none');
                } else {
                    // Trực tiếp kết thúc
                    $('[data-toggle="finishloader"]', $(cfg.ctnUploadQueue)).removeClass('d-none');
                    setTimeout(function() {
                        self.uploadFinish();
                    }, 1000);
                }
            },

            // Event on error
            Error: function(up, err) {
                (self.debug && console.log("Plupload: Event error"));
                self.showMsgError("Error #" + err.message + ": <br>" + err.file.name);

                if (err.code === plupload.INIT_ERROR) {
                    setTimeout(function() {
                        self.uploadDestroy();
                    }, 1000);
                }
            },

            // Get image alt before upload
            BeforeUpload: function(up, file) {
                (self.debug && console.log("Plupload: Event before upload"));
                var filealt = '';
                var autologo = ($('[name="auto_logo"]', $(cfg.ctnUploadQueue)).is(':checked') ? 1 : 0);

                if ($('#' + file.id + ' [data-toggle="fileAltInput"]').length) {
                    filealt = $('#' + file.id + ' [data-toggle="fileAltInput"]').val();
                }

                self.uploader.settings.multipart_params = {
                    "filealt": filealt,
                    "autologo": autologo
                };
                // Xác định resize ảnh (bug plupload 2.3.1) => Tạm thời để lại code phòng khi lỗi, vài phiên bản nũa nếu không lỗi sẽ xóa code này
                /*
                if (nv_resize != false) {
                    if (typeof file.clientResize != "undefined" && file.clientResize) {
                        self.uploader.settings.resize = nv_resize;
                    } else {
                        self.uploader.settings.resize = {};
                    }
                }
                */
            },

            // Upload xong một BLOB
            ChunkUploaded: function(up, file, res) {
                (self.debug && console.log("Plupload: Event chunk uploaded"));
                /**
                 * Hiện tại Plupload không có chức năng dừng upload chunk và chuyển sang file khác
                 * Do đó tạm thời khi lỗi một BLOG phải chờ upload xong cả file để kiểm tra lỗi
                 */
                if (res.response != null && res.response != '') {
                    //self.handleStatus(file, res.response);
                }
            }
        }
    });

    self.uploader.init();
}

/*
 * Reset lại dữ liệu PLUpload
 * - Destroy
 * - Tạo lại
 */
NVCoreFileBrowser.prototype.uploadReset = function() {
    var self = this;
    var cfg = self.cfg;
    var cfgm = self.cfgMain;
    var cfgf = self.cfgFolderData;

    // Destroy current uploader
    if (self.uploader) {
        self.uploadDestroy();
    }

    // Tạo lại nút upload
    setTimeout(function() {
        self.uploadInit();
    }, 50);
}

/*
 * Hủy dữ liệu upload
 */
NVCoreFileBrowser.prototype.uploadDestroy = function() {
    var self = this;
    var cfg = self.cfg;
    var cfgm = self.cfgMain;
    var cfgf = self.cfgFolderData;

    // Destroy current uploader
    self.uploader.destroy();
    self.uploadStarted = false;

    // Clear uploader variable
    self.uploader = null;
    self.uploadRendered = false;

    // Clear upload container
    $(cfg.ctnUploadQueue).remove();

    // Hiển thị lại danh sách file và công cụ
    $(cfg.filesToolBar).removeClass('d-none');
    $(cfg.filesScroller).removeClass('d-none');
    $(cfg.filesNav).removeClass('d-none');
}

/*
 * Thiết lập container của trình upload
 */
NVCoreFileBrowser.prototype.uploadRenderUI = function() {
    var self = this;
    var cfg = self.cfg;
    var cfgm = self.cfgMain;
    var cfgf = self.cfgFolderData;

    var logo = $(cfgm.logo).data('value');
    var path = $(cfgf.folder).data('value');
    var folder = $('[data-folder="' + path + '"]', $(cfg.folderElement));

    // Ẩn các thành phần hiển thị danh sách file và nút công cụ
    $(cfg.filesToolBar).addClass('d-none');
    $(cfg.filesScroller).addClass('d-none');
    $(cfg.filesNav).addClass('d-none');

    var html = '\
    <div class="fm-upload-queue" id="nv-filemanager-upload-queue">\
        <div class="queue-tools">\
            <div class="tool-btns">\
                <button data-toggle="addfile" type="button" class="btn mr-1 btn-primary mb-2">' + LANG.upload_add_files + '</button>\
                <button data-toggle="start" type="button" class="btn mr-1 btn-primary mb-2">' + LANG.upload_file + '</button>\
                <button data-toggle="cancel" type="button" class="btn mr-1 btn-secondary mb-2">' + LANG.upload_cancel + '</button>\
                <button data-toggle="stop" type="button" class="btn mr-1 btn-secondary mb-2 d-none">' + LANG.upload_stop + '</button>\
                <button data-toggle="continue" type="button" class="btn mr-1 btn-secondary mb-2 d-none">' + LANG.upload_continue + '</button>\
                <button data-toggle="finishloader" type="button" class="btn mr-1 btn-success mb-2 d-none"><i class="fas fa-spinner fa-pulse"></i></button>\
                <button data-toggle="finish" type="button" class="btn mr-1 btn-secondary mb-2 d-none">' + LANG.upload_finish + '</button>\
            </div>\
            <div class="tool-sizes">\
                <div data-toggle="totalSize" class="mb-2"></div>\
            </div>\
            <div class="tool-progress">\
                <div class="progress mb-2">\
                    <div data-toggle="progress" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="progress-bar bg-primary progress-bar-striped progress-bar-animated">0%</div>\
                </div>\
            </div>\
        </div>\
        <div class="queue-opts' + ((logo == '' || folder.length < 1) ? ' d-none' : '') + '">\
            <label class="custom-control custom-checkbox custom-control-inline mb-2">\
                <input class="custom-control-input" type="checkbox" name="auto_logo" value="1"' + ((logo != '' && folder.length && folder.is('.auto_logo'))  ? ' checked="checked"' : '') + '><span class="custom-control-label custom-control-color text-truncate">Chèn logo vào tập tin tải lên (nếu là ảnh)</span>\
            </label>\
        </div>\
        <div class="queue-head">\
            <div class="queue-col-name">' + LANG.file_name + '</div>\
            <div class="queue-col-alt">' + LANG.altimage + '</div>\
            <div class="queue-col-size">' + LANG.upload_size + '</div>\
            <div class="queue-col-status">' + LANG.upload_status + '</div>\
            <div class="queue-col-tool"></div>\
        </div>\
        <div class="queue-files nv-scroller" id="nv-filemanager-upload-queue-scroller">\
            <div class="queue-files-items" data-toggle="ctnitems">\
            </div>\
        </div>\
    </div>';

    $(cfg.ctnUploadParent).append(html);

    self.uploadRendered = true;

    // Ấn vào nút hủy upload
    $('[data-toggle="cancel"]', $(cfg.ctnUploadQueue)).on('click', function(e) {
        e.preventDefault();
        self.uploadReset();
    });

    // Ấn vào nút bắt đầu upload
    $('[data-toggle="start"]', $(cfg.ctnUploadQueue)).on('click', function(e) {
        e.preventDefault();
        var allow_start = true;
        if (nv_alt_require) {
            $('[data-toggle="fileAltInput"]', $(cfg.ctnUploadQueue)).each(function() {
                if ($(this).val() == '') {
                    allow_start = false;
                    return false;
                }
            });

            if (allow_start == false) {
                self.showMsgError(LANG.upload_alt_note);
            }
        }

        if (allow_start) {
            self.uploader.start();
        }
    });

    // Ấn vào nút thêm file
    $('[data-toggle="addfile"]', $(cfg.ctnUploadQueue)).on('click', function(e) {
        e.preventDefault();
        $('.moxie-shim.moxie-shim-' + self.uploader.runtime, $(cfg.container)).find('input').trigger('click');
    });

    // Ấn vào nút dừng
    $('[data-toggle="stop"]', $(cfg.ctnUploadQueue)).on('click', function(e) {
        e.preventDefault();
        $('[data-toggle="stop"]', $(cfg.ctnUploadQueue)).addClass('d-none');
        $('[data-toggle="continue"]', $(cfg.ctnUploadQueue)).removeClass('d-none');
        self.uploader.stop();
    });

    // Ấn vào nút tiếp tục
    $('[data-toggle="continue"]', $(cfg.ctnUploadQueue)).on('click', function(e) {
        e.preventDefault();
        $('[data-toggle="stop"]', $(cfg.ctnUploadQueue)).removeClass('d-none');
        $('[data-toggle="continue"]', $(cfg.ctnUploadQueue)).addClass('d-none');
        self.uploader.start();
    });

    // Ấn vào nút kết thúc (nút hày hiển thị khi có file tải lên lỗi)
    $('[data-toggle="finish"]', $(cfg.ctnUploadQueue)).on('click', function(e) {
        e.preventDefault();
        self.uploadFinish();
    });
}

/*
 * Build lại danh sách các file upload
 */
NVCoreFileBrowser.prototype.uploadRefreshList = function () {
    var self = this;
    var cfg = self.cfg;
    var cfgm = self.cfgMain;
    var cfgf = self.cfgFolderData;

    $('[data-toggle="ctnitems"]', $(cfg.ctnUploadQueue)).html('');

    $.each(self.uploader.files, function(i, file) {
        var fileAlt = NVLDATA.getValue(file.id);
        if (nv_auto_alt && fileAlt == '') {
            fileAlt = self.getImgAlt(file.name);
            NVLDATA.setValue(file.id, fileAlt);
        }
        var html = '\
        <div class="queue-files-item inFileManagerModal" id="' + file.id + '">\
            <div class="queue-col-name">' + file.name + '</div>\
            <div class="queue-col-alt">\
                <input data-toggle="fileAltInput" class="form-control form-control-xs" type="text" value="' + fileAlt + '" onkeyup="NVLDATA.setValue(\'' + file.id + '\', this.value);">\
            </div>\
            <div class="queue-col-size">' + plupload.formatSize(file.size) + '</div>\
            <div class="queue-col-status" data-toggle="filestatus">' + file.percent + '%</div>\
            <div class="queue-col-tool" data-toggle="fileaction"></div>\
        </div>';
        $('[data-toggle="ctnitems"]', $(cfg.ctnUploadQueue)).append(html);

        self.handleStatus(file, false);

        // Xử lý xóa file
        $('#' + file.id + ' .file-delete').click(function(e) {
            e.preventDefault();
            $('#' + file.id).remove();
            self.uploader.removeFile(file);
        });
    });

    $('[data-toggle="totalSize"]', $(cfg.ctnUploadQueue)).html(plupload.formatSize(self.uploader.total.size));

    // Tạo thanh cuộn
    var scroller = $(cfg.ctnUploadScroller).data('scroller');
    if (!scroller) {
        scroller = new PerfectScrollbar($(cfg.ctnUploadScroller)[0], {
            wheelPropagation: false
        });
        $(cfg.ctnUploadScroller).data('scroller', scroller);
    } else {
        scroller.update();
    }

    // Cuộn đến cuối list file
    $(cfg.ctnUploadScroller)[0].scrollTop = $(cfg.ctnUploadScroller)[0].scrollHeight;

    // Cập nhật tình trạng upload tổng
    self.updateTotalProgress();

    // Cấm/cho phép nút upload
    if (self.uploader.files.length) {
        $('[data-toggle="start"]', $(cfg.ctnUploadQueue)).prop('disabled', false);
    } else {
        $('[data-toggle="start"]', $(cfg.ctnUploadQueue)).prop('disabled', true);
    }
}

/*
 * Xử lý hiển thị trạng thái file upload
 */
NVCoreFileBrowser.prototype.handleStatus = function (file, response) {
    var self = this;
    var actionClass;

    if (response != false) {
        check = response.split('_');

        if (check[0] == 'ERROR') {
            file.status = plupload.FAILED;
            file.hint = check[1];
            self.uploader.total.uploaded--;
            self.uploader.total.failed++;
        } else {
            file.name = response;
        }

        $.each(self.uploader.files, function(i, f) {
            if (f.id == file.id) {
                self.uploader.files[i].status = file.status;
                self.uploader.files[i].hint = file.hint;
                self.uploader.files[i].name = file.name;
            }
        });
    }

    if (file.status == plupload.DONE) {
        actionClass = 'text-success fas fa-check-circle';
    } else if (file.status == plupload.FAILED) {
        actionClass = 'text-danger fas fa-exclamation-triangle';
    } else if (file.status == plupload.QUEUED) {
        actionClass = 'cursor-pointer text-primary far fa-times-circle file-delete';
    } else if (file.status == plupload.UPLOADING) {
        actionClass = 'text-info fas fa-spinner fa-pulse';
    } else {
        // Nothing to do
    }

    var actionHTML = '<i class="' + actionClass + '"></i>';
    if ($('#' + file.id + ' [data-toggle="fileaction"]').html() != actionHTML) {
        $('#' + file.id + ' [data-toggle="fileaction"]').html(actionHTML);
    }

    if (file.hint) {
        $('#' + file.id).attr('title', file.hint);
    }
}

/*
 * Cập nhật tổng tiến trình upload
 */
NVCoreFileBrowser.prototype.updateTotalProgress = function () {
    var self = this;
    var cfg = self.cfg;
    var cfgm = self.cfgMain;
    var cfgf = self.cfgFolderData;

    var progress = $('[data-toggle="progress"]', $(cfg.ctnUploadQueue));

    progress.attr('aria-valuenow', self.uploader.total.percent);
    progress.css({
        width: self.uploader.total.percent + '%',
    }).html(self.uploader.total.percent + '%');
}

/*
 * Hiển thị thông báo lỗi
 */
NVCoreFileBrowser.prototype.showMsgError = function (message) {
    var self = this;
    var cfg = self.cfg;

    $('[data-toggle="content"]', $(cfg.modalAlert)).html(message);
    $('[data-toggle="icon"]', $(cfg.modalAlert)).removeClass('fas far fa-info-circle fa-check');
    $('[data-toggle="icon"]', $(cfg.modalAlert)).addClass('far fa-times-circle');
    $(cfg.modalAlert).removeClass('modal-full-color-primary modal-full-color-success');
    $(cfg.modalAlert).addClass('modal-full-color-danger');
    $(cfg.modalAlert).modal('show');
}

/*
 * Hiển thị thông báo thành công
 */
NVCoreFileBrowser.prototype.showMsgSuccess = function (message) {
    var self = this;
    var cfg = self.cfg;

    $('[data-toggle="content"]', $(cfg.modalAlert)).html(message);
    $('[data-toggle="icon"]', $(cfg.modalAlert)).removeClass('fas far fa-times-circle fa-info-circle');
    $('[data-toggle="icon"]', $(cfg.modalAlert)).addClass('fas fa-check');
    $(cfg.modalAlert).removeClass('modal-full-color-danger modal-full-color-primary');
    $(cfg.modalAlert).addClass('modal-full-color-success');
    $(cfg.modalAlert).modal('show');
}

/*
 * Hiển thị thông báo thường
 */
NVCoreFileBrowser.prototype.showMsgInfo = function (message) {
    var self = this;
    var cfg = self.cfg;

    $('[data-toggle="content"]', $(cfg.modalAlert)).html(message);
    $('[data-toggle="icon"]', $(cfg.modalAlert)).removeClass('fas far fa-times-circle fa-check');
    $('[data-toggle="icon"]', $(cfg.modalAlert)).addClass('fas fa-info-circle');
    $(cfg.modalAlert).removeClass('modal-full-color-danger modal-full-color-success');
    $(cfg.modalAlert).addClass('modal-full-color-primary');
    $(cfg.modalAlert).modal('show');
}

/*
 * Xử lý kết thúc một lượt upload
 * - Xóa các thành phần upload
 * - Hiển thị lại các thành phần file
 * - Tải lại folder
 * - Thiết lập lại upload
 */
NVCoreFileBrowser.prototype.uploadFinish = function () {
    var self = this;
    var cfg = self.cfg;
    var cfgf = self.cfgFolderData;
    var cfgm = self.cfgMain;
    var selFile;

    if (self.uploader.total.uploaded > 0) {
        selFile = new Array();
        $.each(self.uploader.files, function(k, v) {
            if (v.status == plupload.DONE) {
                selFile.push(v.name);
            }
        });
        selFile = selFile.join('|');
    } else {
        selFile = '';
    }

    $(cfgm.file).data('value', selFile);
    self.uploadDestroy();
    $('[data-folder="' + $(cfgf.folder).data('value') + '"]', $(cfg.folderElement)).trigger('click');
}

/*
 * Lấy chiều rộng và chiều cao của ảnh
 * hiển thị phù hợp với vùng chứa.
 * Thông số pitago cho phép chỉnh ảnh nhỏ lại
 * đảo bảo đường chéo lớn nhất bằng chiều rộng vùng chứa
 */
NVCoreFileBrowser.prototype.getImageDisplaySize = function (imgW, imgH, ctnW, ctnH, pitago) {
    var ratioImg = imgW / imgH;
    var ratioCtn = ctnW / ctnH;
    if (ratioImg > ratioCtn) {
        // Trường hợp ảnh có khả năng vượt quá chiều rộng vùng chứa
        if (imgW > ctnW) {
            imgW = ctnW;
            imgH = imgW / ratioImg;
        }
    } else {
        // Trường hợp ảnh có khả năng vượt quá chiều cao vùng chứa
        if (imgH > ctnH) {
            imgH = ctnH;
            imgW = imgH * ratioImg;
        }
    }
    var size = [imgW, imgH], sizePi = [0, 0], ratio;
    if (pitago && Math.sqrt((imgW * imgW) + (imgH * imgH)) > ctnW) {
        ratio = size[0] / size[1];
        sizePi[1] = Math.sqrt((ctnW * ctnW) / ((ratio * ratio) + 1));
        sizePi[0] = sizePi[1] * ratio;
        return [parseInt(sizePi[0]), parseInt(sizePi[1])];
    }
    return [parseInt(size[0]), parseInt(size[1])];
}

/*
 * Kiểm tra kích thước ảnh mới hợp lệ
 */
NVCoreFileBrowser.prototype.checkNewImageSize = function () {
    var self = this;
    var cfg = self.cfg;

    var orgW = $('[name="newWidth"]', $(cfg.formCreatImage)).data('orgw');
    var orgH = $('[name="newHeight"]', $(cfg.formCreatImage)).data('orgh');
    var w = $('[name="newWidth"]', $(cfg.formCreatImage)).val();
    var h = $('[name="newHeight"]', $(cfg.formCreatImage)).val();
    var maxSize = self.getImageDisplaySize(orgW, orgH, nv_max_width, nv_max_height);
    var minSize = self.getImageDisplaySize(orgW, orgH, nv_min_width, nv_min_height);
    var errorInfo = [];

    if (w == "" || !is_numeric(w)) {
        errorInfo = [LANG.errorEmptyX, "newWidth"];
    } else if (w > maxSize[0]) {
        errorInfo = [LANG.errorMaxX, "newWidth"];
    } else if (w < minSize[0]) {
        errorInfo = [LANG.errorMinX, "newWidth"];
    } else if (h == "" || !is_numeric(h)) {
        errorInfo = [LANG.errorEmptyY, "newHeight"];
    } else if (h > maxSize[1]) {
        errorInfo = [LANG.errorMaxY, "newHeight"];
    } else if (h < minSize[1]) {
        errorInfo = [LANG.errorMinY, "newHeight"];
    }

    if (typeof errorInfo[0] != "undefined") {
        $('[data-toggle="error"]', $(cfg.formCreatImage)).html(errorInfo[0]).removeClass('d-none');
        $('[name="' + errorInfo[1] + '"]', $(cfg.formCreatImage)).select();
        return false;
    }

    $('[data-toggle="error"]', $(cfg.formCreatImage)).html('').addClass('d-none');
    var ctnWidth = $('[data-toggle="imgname"]', $(cfg.formCreatImage)).width();
    var size = self.getImageDisplaySize(w, h, ctnWidth, ctnWidth);
    $('[data-toggle="img"]', $(cfg.formCreatImage)).css({
        'width': (size[0] + 'px'),
        'height': size[1] + 'px'
    });

    return true;
}

/*
 * Xử lý khi có nhiều modal mở lên
 */
NVCoreFileBrowser.prototype.fix2Modal = function (modalEle) {
    var numBackFrop = $('.modal-backdrop').length;
    if (numBackFrop != 2) {
        return true;
    }
    modalEle.css({
        "z-index": 1070
    });
    $('.modal-backdrop:last').css({
        "z-index": 1060
    });
}

/*
 * Lấy Image Alt từ url
 */
NVCoreFileBrowser.prototype.getImgAlt = function (fileAlt) {
    var lastChar = fileAlt.charAt(fileAlt.length - 1);

    if (lastChar === '/' || lastChar === '\\') {
        fileAlt = fileAlt.slice(0, -1);
    }

    fileAlt = decodeURIComponent(this.decode(fileAlt.replace(/^.*[\/\\]/g, '')));
    fileAlt = fileAlt.split('.');

    if (fileAlt.length > 1) {
        fileAlt[fileAlt.length - 1] = '';
    }

    fileAlt = fileAlt.join(' ');
    fileAlt = fileAlt.split('_');
    fileAlt = fileAlt.join(' ');
    fileAlt = fileAlt.split('-');
    fileAlt = fileAlt.join(' ');
    return trim(fileAlt);
}

/*
 * htmlspecialchars_decode
 */
NVCoreFileBrowser.prototype.decode = function (string, quote_style) {
    //       discuss at: http://phpjs.org/functions/htmlspecialchars_decode/
    //      original by: Mirek Slugen
    //      improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    //      bugfixed by: Mateusz "loonquawl" Zalega
    //      bugfixed by: Onno Marsman
    //      bugfixed by: Brett Zamir (http://brett-zamir.me)
    //      bugfixed by: Brett Zamir (http://brett-zamir.me)
    //         input by: ReverseSyntax
    //         input by: Slawomir Kaniecki
    //         input by: Scott Cariss
    //         input by: Francois
    //         input by: Ratheous
    //         input by: Mailfaker (http://www.weedem.fr/)
    //       revised by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // reimplemented by: Brett Zamir (http://brett-zamir.me)
    //        example 1: htmlspecialchars_decode("<p>this -&gt; &quot;</p>", 'ENT_NOQUOTES');
    //        returns 1: '<p>this -> &quot;</p>'
    //        example 2: htmlspecialchars_decode("&amp;quot;");
    //        returns 2: '&quot;'

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
        // Allow for a single string or an array of string flags
        quote_style = [].concat(quote_style);
        for (i = 0; i < quote_style.length; i++) {
            // Resolve string input to bitwise e.g. 'PATHINFO_EXTENSION' becomes 4
            if (OPTS[quote_style[i]] === 0) {
                noquotes = true;
            } else if (OPTS[quote_style[i]]) {
                optTemp = optTemp | OPTS[quote_style[i]];
            }
        }
        quote_style = optTemp;
    }
    if (quote_style & OPTS.ENT_HTML_QUOTE_SINGLE) {
        string = string.replace(/&#0*39;/g, "'"); // PHP doesn't currently escape if more than one 0, but it should
        // string = string.replace(/&apos;|&#x0*27;/g, "'"); // This would also be useful here, but not a part of PHP
    }
    if (!noquotes) {
        string = string.replace(/&quot;/g, '"');
    }
    // Put this in last place to avoid escape being double-decoded
    string = string.replace(/&amp;/g, '&');

    return string;
}

var NVLDATA = {
    support: false,
    init: function() {
        if (typeof(Storage) !== "undefined") {
            NVLDATA.support = true;
        }
    },
    getValue: function(key) {
        if (!NVLDATA.support) {
            return '';
        }

        if (typeof(sessionStorage[key]) !== "undefined" && sessionStorage[key]) {
            return sessionStorage[key];
        }

        return '';
    },
    setValue: function(key, val) {
        sessionStorage[key] = val;
    }
};

/*
 * Xử lý thao tác
 * - Load lại danh sách file
 * - Chọn file
 */
var LFILE = {
    reload: function(path, file) {
        var imgtype = $("select[name=imgtype]").val();
        var author = $("select[name=author]").val() == 1 ? "&author" : "";
        var order = $("select[name=order]").val();

        // Reset shift offset
        KEYPR.shiftOffset = 0;

        $("#imglist").html(nv_loading_data).load(nv_module_url + "imglist&path=" + path + "&type=" + imgtype + "&imgfile=" + file + author + "&order=" + order + "&num=" + nv_randomNum(10), function() {
            LFILE.setViewMode();
        });
    },
    setSelFile: function() {
        var cfgm = window.fileManager.cfgMain;
        $(cfgm.file).data('value', '');
        if ($('.file-selected').length) {
            fileName = new Array();
            $.each($('.file-selected'), function() {
                fileName.push($(this).data("file"));
            });
            fileName = fileName.join('|');
            $(cfgm.file).data('value', fileName);
        }
    },
    setViewMode: function() {
        var numFiles = $('[data-img="false"]').length;
        var numImage = $('[data-img="true"]').length;
        var autoMode = $(".viewmode em").data('auto');

        if (autoMode) {
            if (numImage > numFiles) {
                $('#imglist').removeClass('view-detail');
            } else if (numFiles > 0) {
                $('#imglist').addClass('view-detail');
            }
        }

        LFILE.setViewIcon();
    },
    setViewIcon: function() {
        if ($('#imglist').is('.view-detail')) {
            $('.viewmode em').removeClass('fa-hourglass-o fa-spin fa-list').addClass('fa-file-image-o').attr('title', $('.viewmode em').data('langthumb'));
        } else {
            $('.viewmode em').removeClass('fa-hourglass-o fa-spin fa-file-image-o').addClass('fa-list').attr('title', $('.viewmode em').data('langdetail'));
        }
    }
};

/*
 * Xử lý thao tác bàn phím đối với trang khi có quản lý file
 */
var KEYPR = {
    isCtrl: false,
    isShift: false,
    shiftOffset: 0,
    allowKey: [112, 113, 114, 115, 116, 117, 118, 119, 120, 121, 122, 123],
    isSelectable: false,
    isFileSelectable: false,
    initYet: false,
    init: function() {
        if (KEYPR.initYet) {
            return;
        }
        KEYPR.initYet = true;
        $('body').keyup(function(e) {
            if (!$(window.fileManager.cfg.container).is(':visible')) {
                // Khi container không hiển thị thì không xử lý
                return;
            }
            if (!$(e.target).parents('.inFileManagerModal').length && $.inArray(e.keyCode, KEYPR.allowKey) == -1 && !$(e.target).is('.inFileManagerModal')) {
                e.preventDefault();
            } else {
                return;
            }

            // Ctrl key unpress
            if (e.keyCode == 17) {
                KEYPR.isCtrl = false;
            } else if (e.keyCode == 16) {
                KEYPR.isShift = false;
            }
        });

        $('body').keydown(function(e) {
            if (!$(window.fileManager.cfg.container).is(':visible')) {
                // Khi container không hiển thị thì không xử lý
                return;
            }
            if (!$(e.target).parents('.inFileManagerModal').length && $.inArray(e.keyCode, KEYPR.allowKey) == -1 && !$(e.target).is('.inFileManagerModal')) {
                e.preventDefault();
            } else {
                return;
            }

            // Ctrl key press
            if (e.keyCode == 17 /* Ctrl */ ) {
                KEYPR.isCtrl = true;
            } else if (e.keyCode == 27 /* ESC */ ) {
                // Bỏ chọn các file
                $(".file-selected").removeClass("file-selected");
                LFILE.setSelFile();

                // Ẩn menu chuột phải
                NVCMENU.hide();

                // Thiết lập lại vị trí bắt đầu chọn SHIFT
                KEYPR.shiftOffset = 0;
            } else if (e.keyCode == 65 /* A */ && e.ctrlKey === true) {
                // Select all file
                $(".file", $(window.fileManager.cfg.filesContainer)).addClass("file-selected");
                LFILE.setSelFile();

                // Hide contextmenu
                NVCMENU.hide();
            } else if (e.keyCode == 16 /* Shift */ ) {
                KEYPR.isShift = true;
            } else if (e.keyCode == 46 /* Del */ ) {
                // Delete file
                if ($('.file-selected').length && $("span#delete_file").attr("title") == '1') {
                    filedelete();
                }
            } else if (e.keyCode == 88 /* X */ ) {
                // Move file
                if ($('.file-selected').length && $("span#move_file").attr("title") == '1') {
                    move();
                }
            }
        });

        /*
         * Hủy chọn các file khi ấn vào các phần khác ngoại trừ:
         * - Chính các file
         * - Click vào modal mở lên
         */
        $(document).on('click', function(e) {
            if (KEYPR.isSelectable == false) {
                if (!$(e.target).closest('.file').length && !$(e.target).parents('.inFileManagerModal').length && !$(e.target).is('.inFileManagerModal')) {
                    $(".file-selected").removeClass("file-selected");
                    LFILE.setSelFile();
                }
            }

            KEYPR.isSelectable = false;
        });
    }
};

/* Xử lý chức năng xoay ảnh */
var RRT = {
    direction: 0,
    currentDirection: 0,
    arrayDirection: [0, 90, 180, 270],
    timer: null,
    timeOut: 20,
    trigger: function() {
        var cfg = window.fileManager.cfg;
        $('[data-toggle="img"] img', $(cfg.formRotateFile)).rotate(RRT.direction);
    },
    setVal: function() {
        var cfg = window.fileManager.cfg;
        $('[name="rorateDirection"]', $(cfg.formRotateFile)).val(RRT.direction);
    },
    setDirection: function(direction) {
        if (direction == '') {
            RRT.direction = 0;
        } else {
            direction = parseInt(direction);

            if (direction >= 360) {
                direction = 359;
            } else if (direction < 0) {
                direction = 0;
            }

            RRT.direction = direction;
        }
    },
    increase: function() {
        var direction = RRT.direction;
        direction++;

        if (direction == 360) {
            direction = 0;
        }

        RRT.setDirection(direction);
        RRT.setVal();
        RRT.trigger();
    },
    decrease: function() {
        var direction = RRT.direction;
        direction--;

        if (direction == -1) {
            direction = 359;
        }

        RRT.setDirection(direction);
        RRT.setVal();
        RRT.trigger();
    },
    initYet: false,
    init: function() {
        if (RRT.initYet) {
            return;
        }
        RRT.initYet = true;
        var cfg = window.fileManager.cfg;

        $('[name="rorateDirection"]', $(cfg.formRotateFile)).keyup(function() {
            var direction = $(this).val();

            if (isNaN(direction)) {
                direction = direction.slice(0, direction.length - 1);
            }

            RRT.setDirection(direction);
            RRT.setVal();
            RRT.trigger();
        });

        $('[data-toggle="rleft"]', $(cfg.formRotateFile)).on('mousedown', function() {
            RRT.timer = setInterval(function() {
                RRT.decrease();
            }, RRT.timeOut);
        });

        $('[data-toggle="rleft"]', $(cfg.formRotateFile)).on("mouseup mouseleave", function() {
            clearInterval(RRT.timer);
        });

        $('[data-toggle="rright"]', $(cfg.formRotateFile)).on('mousedown', function() {
            RRT.timer = setInterval(function() {
                RRT.increase();
            }, RRT.timeOut);
        });

        $('[data-toggle="rright"]', $(cfg.formRotateFile)).on("mouseup mouseleave", function() {
            clearInterval(RRT.timer);
        });

        $('[data-toggle="rleft90"]', $(cfg.formRotateFile)).click(function() {
            RRT.currentDirection--;

            if (RRT.currentDirection < 0) {
                RRT.currentDirection = 3;
            }

            RRT.setDirection(RRT.arrayDirection[RRT.currentDirection]);
            RRT.setVal();
            RRT.trigger();
        });

        $('[data-toggle="rright90"]', $(cfg.formRotateFile)).click(function() {
            RRT.currentDirection++;

            if (RRT.currentDirection > 3) {
                RRT.currentDirection = 0;
            }

            RRT.setDirection(RRT.arrayDirection[RRT.currentDirection]);
            RRT.setVal();
            RRT.trigger();
        });
    }
};

var NVCMENU = {
    menu: null,
    bindings: {
        accessfolder: 'handleMenuAccessFolder',
        selectfile: 'handleMenuSelect',
        download: 'handleMenuDownload',
        preview: 'handleMenuPreview',
        addlogo: 'handleMenuAddLogo',
        create: 'handleMenuCreatImage',
        move: 'handleMenuMove',
        renamefile: 'handleMenuRenameFile',
        renamefolder: 'handleMenuRenameFolder',
        deletefile: 'handleMenuDeleteFile',
        deletefolder: 'handleMenuDeleteFolder',
        crop: 'handleMenuCrop',
        rotate: 'handleMenuRotate',
        newfolder: 'handleMenuNewFolder',
        recreatethumb: 'handleMenuReThumb'
    },
    initYet: false,
    init: function() {
        if (NVCMENU.initYet) {
            return;
        }
        NVCMENU.initYet = true;
        NVCMENU.menu = $('<div id="nvContextMenu" class="dropdown-menu"></div>').appendTo('body').on('click', function(e) {
            e.stopPropagation();
        });
        NVCMENU.menu.on('contextmenu', function(e) {
            e.preventDefault();
        });
        $(document).delegate('*', 'click', function(e) {
            if (e.which != 3) {
                NVCMENU.hide();
            }
        });
    },
    show: function(e) {
        e.preventDefault();

        if ($('#contextMenu').html() != '') {
            var content = $('#contextMenu').html();
            NVCMENU.menu.html(content);

            $.each(NVCMENU.bindings, function(id, func) {
                $('[data-toggle="' + id + '"]', NVCMENU.menu).on('click', function(e) {
                    e.preventDefault();
                    NVCMENU.hide();
                    window.fileManager[func](this);
                });
            });

            // Xác định lại vị trí cái menu cho phù hợp
            var menuLeft = e.pageX + 1;
            var menuTop = e.pageY + 1;

            var itemHeight = 34;
            var menuWidth = 185;
            // Số item + khoảng cách trên dưới + viền trên dưới
            var menuHeight = (NVCMENU.menu.find('>a').length * itemHeight) + 7 + 7 + 1 + 1;

            var maxLeft = $('body').width() - menuWidth - 5;
            var maxTop = ($(window).scrollTop() + $('body').height()) - menuHeight - 5;

            if (menuLeft > maxLeft) {
                menuLeft = maxLeft;
            }
            if (menuTop > maxTop) {
                menuTop = maxTop;
            }

            NVCMENU.menu.css({
                'left': '0px',
                'top': '0px',
                'will-change' : 'transform',
                'position': 'absolute',
                'transform': 'translate3d(' + menuLeft + 'px, ' + menuTop + 'px, 0px)',
                'width': menuWidth + 'px',
                'z-index': 1000001
            }).show();
        }
        return false;
    },
    hide: function() {
        NVCMENU.menu.hide();
    }
};

$(document).ready(function() {
    var numberScriptLoaded = 0;
    var numberCssLoad = 0;

    $(document).on("nv.upload.resourceloaded", function(event, type) {
        if (type == 1) {
            numberScriptLoaded++;
        } else {
            numberCssLoad++;
        }
        if (numberScriptLoaded >= 6 && numberCssLoad >= 2) {
            $.fn.tooltip = $.fn.bstooltip; // Trả lại Tooltip của Boostrap vì conflic với Jquery UI
            $(document).trigger("nv.upload.ready");
        }
    });

    // Load Jquery UI
    $.fn.bstooltip = $.fn.tooltip;
    if (typeof $.ui == "undefined") {
        loadJS(nv_base_siteurl + "assets/js/jquery-ui/jquery-ui.min.js");
        loadCSS(nv_base_siteurl + "assets/js/jquery-ui/jquery-ui.min.css");
    } else {
        $(document).trigger("nv.upload.resourceloaded", 1);
        $(document).trigger("nv.upload.resourceloaded", 0);
    }
    // Load Jquery Cropper
    if (typeof $.fn.cropper == "undefined") {
        loadJS(nv_base_siteurl + "assets/js/cropper/cropper.min.js");
        loadCSS(nv_base_siteurl + "assets/js/cropper/cropper.min.css");
    } else {
        $(document).trigger("nv.upload.resourceloaded", 1);
        $(document).trigger("nv.upload.resourceloaded", 0);
    }
    // Load Jquery Rotate
    if (typeof $.fn.rotate == "undefined") {
        loadJS(nv_base_siteurl + "assets/js/jquery/jQueryRotate.js");
    } else {
        $(document).trigger("nv.upload.resourceloaded", 1);
    }
    // Load Jquery clipboard
    if (typeof ClipboardJS == "undefined") {
        loadJS(nv_base_siteurl + "assets/js/clipboard/clipboard.min.js");
    } else {
        $(document).trigger("nv.upload.resourceloaded", 1);
    }
    // Load Plpuload
    if (typeof plupload == "undefined") {
        loadJS(nv_base_siteurl + "assets/js/plupload/plupload.full.min.js", nv_base_siteurl + "assets/js/language/plupload-" + nv_lang_interface + ".js");
    } else {
        $(document).trigger("nv.upload.resourceloaded", 1);
        $(document).trigger("nv.upload.resourceloaded", 1);
    }

    // Load các file JS
    function loadJS(url, urlnext) {
        $.ajax({
            url: url,
            dataType: "script",
            success: function() {
                $(document).trigger("nv.upload.resourceloaded", 1);
                if (typeof urlnext != "undefined") {
                    loadJS(urlnext);
                }
            },
            cache: true
        });
    }

    // Load các file CSS
    function loadCSS(url) {
        var head = document.getElementsByTagName("head")[0]
        var link = document.createElement('link');
        link.rel  = 'stylesheet';
        link.href = url;
        head.appendChild(link);
        link.onload = function() {
            $(document).trigger("nv.upload.resourceloaded", 0);
        };
    }
});

/*
 * Xử lý trình quản lý file ở cấp độ toàn trang
 */
+function($) {
    'use strict';

    var NVStaticUpload = function(element, options) {
        var self = this;

        this.$element = $(element);
        this.options = options;

        self.loadMainContainer();
    }

    NVStaticUpload.VERSION  = '5.0.00';

    NVStaticUpload.DEFAULTS = {
        modal 			: false,
        adminBaseUrl	: "",
        templateLoader	: '<div class="card card-filemanager card-border-color card-border-color-primary loading"><div class="filemanager-loader"><div><i class="fas fa-spinner fa-pulse"></i></div></div></div>',
        path: '',
        currentpath: '',
        type: '',
        imgfile: ''
    };

    /*
     * Tải mẫu
     */
    NVStaticUpload.prototype.loadMainContainer = function() {
        var self = this;
        var url = self.options.adminBaseUrl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=upload&nocache=' + self.strRand(10);

        self.$element.html(self.options.templateLoader);
        $.ajax({
            method: "GET",
            url: url,
            data: {popup: 1},
            dataType: "json",
            cache: false
        }).done(function(data) {
            self.$element.html(data.container);
            $('body:first').append(data.modals);
            self.init();
        }).fail(function() {
            alert("Ajax request Error, please reload your browser!!!");
        });
    }

    /*
     * Thiết lập Upload lên mẫu đã tải
     */
    NVStaticUpload.prototype.init = function() {
        var self = this;
        var data = {
            baseurl: self.options.adminBaseUrl,
            path: self.options.path,
            currentpath: self.options.currentpath,
            type: self.options.type
        };

        // Xử lý các thành phần
        window.fileManager = new NVCoreFileBrowser();
        window.fileManager.init(data);
        window.fileManagerLoaded = true;

        /*
         * Build thêm thanh cuộn
         */
        $('.nv-scroller', self.$element).each(function(k, v) {
            nvScrollbar.push(new PerfectScrollbar(v, {
                wheelPropagation: $(this).data('wheel') ? true : false
            }));
        });
    }

    NVStaticUpload.prototype.strRand = function (a) {
        for (var b = "", d = 0; d < a; d++) {
            b += "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890".charAt(Math.floor(Math.random() * 62));
        }
        return b;
    }

    function Plugin(option) {
        return this.each(function() {
            var $this   = $(this);
            var options = $.extend({}, NVStaticUpload.DEFAULTS, $this.data(), typeof option == 'object' && option);
            var data    = $this.data('nv.upload');

            if (!data && option == 'destroy') {
                return true;
            }
            if (!data) {
                $this.data('nv.upload', (data = new NVStaticUpload(this, options)));
            }
            if (typeof option == 'string') {
                data[option]();
            }
        });
    }

    var old = $.fn.nvstaticupload;

    $.fn.nvstaticupload = Plugin;
    $.fn.nvstaticupload.Constructor = NVStaticUpload;

    // NVSTATICUPLOAD NO CONFLICT
    // =================
    $.fn.nvstaticupload.noConflict = function() {
        $.fn.nvstaticupload = old;
        return this;
    }
}(jQuery);

/*
 * Xử lý trình quản lý file ở các nút duyệt
 */
+function($) {
    'use strict';

    var NVBrowseFile = function(element, options) {
        var self = this;

        this.$elements = $(element);
        this.options = options;

        /*
         * Thiết lập mở modal khi ấn vào nút nhấn
         */
        $(element).on('click', function() {
            $(self.options.templateContainerID).data('btn', this);
            $(self.options.templateContainerID).modal('show');
        });
    }

    NVBrowseFile.VERSION  = '5.0.00';

    NVBrowseFile.DEFAULTS = {
        adminBaseUrl	: "",
        templateLoader	: '<div class="card card-filemanager card-border-color card-border-color-primary loading"><div class="filemanager-loader"><div><i class="fas fa-spinner fa-pulse"></i></div></div></div>',
        path: 'uploads', // Thư mục upload gốc
        currentpath: 'uploads', // Thư mục upload hiện tại (thư mục con hoặc là thư mục gốc)
        type: 'file', // file|image|flash
        area: '', // Đối tượng trả về đường dẫn => Build ra currentfile
        alt: '', // Đối tượng trả về ALT image
        templateContainer: '<div id="mdNVFileManagerPopup" tabindex="-1" role="dialog" class="modal" data-backdrop="static"><div class="modal-dialog full-width modal-filemanager"><div class="modal-content"><div class="modal-header"><button type="button" data-dismiss="modal" aria-hidden="true" class="close"><span class="fas fa-times"></span></button></div><div class="modal-body"></div></div></div></div>',
        templateContainerID: '#mdNVFileManagerPopup',
        restype: 'filepath' // filepath|folderpath
    };

    /*
     * Thiết lập Upload lên mẫu đã tải
     */
    NVBrowseFile.prototype.init = function() {
        var self = this;
        var data = {
            baseurl: self.options.adminBaseUrl,
            path: self.options.path,
            currentpath: self.options.currentpath,
            type: self.options.type,
            restype: self.options.restype,
            area: self.options.area,
            alt: self.options.alt,
            imgfile: '' // File đang chọn
        };

        if (data.area != '' && $(data.area).length == 1) {
            data.imgfile = $(data.area).val();
        }

        // Xử lý các thành phần
        window.fileManager = new NVCoreFileBrowser();
        window.fileManager.init(data);
        window.fileManagerLoaded = true;

        /*
         * Build thêm thanh cuộn
         */
        $('.nv-scroller', self.$element).each(function(k, v) {
            nvScrollbar.push(new PerfectScrollbar(v, {
                wheelPropagation: $(this).data('wheel') ? true : false
            }));
        });
    }

    NVBrowseFile.prototype.strRand = function (a) {
        for (var b = "", d = 0; d < a; d++) {
            b += "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890".charAt(Math.floor(Math.random() * 62));
        }
        return b;
    }

    function Plugin(option) {
        /*
         * Build modal để dùng chung
         */
        if (!$(NVBrowseFile.DEFAULTS.templateContainerID).length) {
            $('body:first').append(NVBrowseFile.DEFAULTS.templateContainer);

            /*
             * Thiết lập trình quản lý file lên khi mở xong modal
             */
            $(NVBrowseFile.DEFAULTS.templateContainerID).on('shown.bs.modal', function(e) {
                var modalEle = $(e.currentTarget);
                var btn = $(modalEle.data('btn'));
                var uploadApi = btn.data('nv.upload');
                var url = uploadApi.options.adminBaseUrl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=upload&nocache=' + uploadApi.strRand(10);

                $('.modal-body', modalEle).html(uploadApi.options.templateLoader);
                $.ajax({
                    method: "GET",
                    url: url,
                    data: {
                        popup: 1,
                        alt: uploadApi.options.alt,
                        area: uploadApi.options.area,
                        type: uploadApi.options.type,
                        imgfile: uploadApi.options.imgfile,
                    },
                    dataType: "json",
                    cache: false
                }).done(function(data) {
                    $('.modal-body', modalEle).html(data.container);
                    if (typeof window.fileManager == "undefined") {
                        $('body:first').append(data.modals);
                    }
                    uploadApi.init();
                }).fail(function() {
                    alert("Ajax request Error, please reload your browser!!!");
                });
            });

            /*
             * Hủy dữ liệu quản lý file khi đóng modal
             */
            $(NVBrowseFile.DEFAULTS.templateContainerID).on('hidden.bs.modal', function(e) {
                var modalEle = $(e.currentTarget);
                $('.modal-body', modalEle).html('');
            });
        }

        return this.each(function() {
            var $this   = $(this);
            var options = $.extend({}, NVBrowseFile.DEFAULTS, $this.data(), typeof option == 'object' && option);
            var data    = $this.data('nv.upload');

            if (!data && option == 'destroy') {
                return true;
            }
            if (!data) {
                $this.data('nv.upload', (data = new NVBrowseFile(this, options)));
            }
            if (typeof option == 'string') {
                data[option]();
            }
        });
    }

    var old = $.fn.nvBrowseFile;

    $.fn.nvBrowseFile = Plugin;
    $.fn.nvBrowseFile.Constructor = NVBrowseFile;

    // nvBrowseFile NO CONFLICT
    // =================
    $.fn.nvBrowseFile.noConflict = function() {
        $.fn.nvBrowseFile = old;
        return this;
    }
}(jQuery);
