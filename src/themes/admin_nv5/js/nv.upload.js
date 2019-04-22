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
        ctnFilterSort: "#nv-filemanager-ctn-filter-sort",
        ctnFilterUser: "#nv-filemanager-ctn-filter-user",
        ctnFilterType: "#nv-filemanager-ctn-filter-type",
        loader: "#nv-filemanager-loader",
        dropzoneCtn: "#filemanager-dropzone-ctn",
        dropzoneArea: "#filemanager-dropzone-area",
        formFilter: "#nv-filemanager-form-filter",
        btnToggleFormFilter: "#nv-filemanager-btn-toggle-form-filter",
        filesContainer: "#nv-filemanager-files-container"
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

    // Các thiết lập Icon cố định
    var ICON = [];
    ICON.select = 'icon far fa-check-square';
    ICON.download = 'icon fas fa-download';
    ICON.preview = 'icon far fa-eye';
    ICON.create = 'icon far fa-file';
    ICON.recreatethumb = 'icon fas fa-retweet';
    ICON.move = 'icon fas fa-arrows-alt';
    ICON.rename = 'icon fas fa-pencil-alt';
    ICON.filedelete = 'icon far fa-trash-alt';
    ICON.filecrop = 'icon fas fa-crop-alt';
    ICON.filerotate = 'icon fas fa-redo';
    ICON.addlogo = 'icon far fa-file-image';
    ICON.spin = 'icon fas fa-spinner';

    this.ICON = ICON;

    this.perload = 0;
    this.firstData = {};
    this.dataFilesDefault = {
        path: '',
        type: 'file',
        imgfile: 'order',
        author: '',
    };
}

/*
 * Init upload: Javascript xử lý sau khi đã load đủ HTML
 */
NVCoreFileBrowser.prototype.init = function(data) {
    var self = this;
    var cfg = self.cfg;
    var ICON = self.ICON;

    KEYPR.init(cfg);
    RRT.init();
    NVCMENU.init();
    NVLDATA.init();

    // Thay đổi chế độ xem dạng lưới hay danh sách
    $(cfg.btnChangeViewMode).on('click', function(e) {
        e.preventDefault();
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
        setTimeout(function() {
            self.hideLoader();
            updatePerfectScrollbar();
        }, 1000);
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
            e.stopPropagation();
            e.preventDefault();
            couterDragIn = 0;
            $(cfg.dropzoneCtn).hide();
            $(cfg.dropzoneCtn).removeClass('drag-hover');
        }
    });

    $(document).on('dragenter', function(e) {
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

    self.showLoader();

    // Load cây thư mục
    var urlFolder = data.baseurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=folderlist&path=' + data.path + '&currentpath=' + data.currentpath + '&random=' + self.strRand(10);
    $(cfg.folderElement).load(urlFolder, function() {
        self.perload++;
        self.folderHandler();
        self.checkInitCompleted();

        // Xác định tên thư mục hiện tại cho di động khi load xong cây thư mục
        var activeFolder = $(cfg.folderElement).find('li.active:last');
        if (activeFolder.length) {
            self.setCurrentMobileFolderName(activeFolder.find('a:first')[0]);
        }
    });

    self.firstData = data;

    // Load các file của thư mục
    var urlFiles = data.baseurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=imglist&path=' + data.currentpath + '&type=' + data.type + '&imgfile=' + data.imgfile + '&random=' + self.strRand(10);
    $(cfg.filesContainer).load(urlFiles, function() {
        self.perload++;
        self.listFilesHandler();
        self.checkInitCompleted();
    });
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
            var fileExt = $("input[name=selFile]").val().slice(-3);
            var CKEditorFuncNum = $("input[name=CKEditorFuncNum]").val();
            var area = $("input[name=area]").val();
            var html = "";

            // Menu chọn file
            if ((CKEditorFuncNum > 0 || area != "") && !isMultiple) {
                html += '<a class="dropdown-item" id="select"><i class="' + ICON.select + '"></i>' + LANG.select + '</a>';
            }

            // Nếu không chọn nhiều file thì cho phép xem chi tiết và tải xuống
            if (!isMultiple) {
                html += '<a class="dropdown-item" id="download" href="#"><i class="' + ICON.download + '"></i>' + LANG.download + '</a>';
                html += '<a class="dropdown-item" id="filepreview" href="#"><i class="' + ICON.preview + '"></i>' + LANG.preview + '</a>';
            }

            // Các thao tác xử lý nếu đây là ảnh
            if ($.inArray(fileExt, array_images) !== -1) {
                if ($("span#create_file").attr("title") == "1" && !isMultiple) {
                    html += '<a class="dropdown-item" id="fileaddlogo" href="#"><i class="' + ICON.addlogo + '"></i>' + LANG.addlogo + '</a>';
                    html += '<a class="dropdown-item" id="create" href="#"><i class="' + ICON.create + '"></i>' + LANG.upload_createimage + '</a>';
                    html += '<a class="dropdown-item" id="cropfile" href="#"><i class="' + ICON.filecrop + '"></i>' + LANG.crop + '</a>';
                    html += '<a class="dropdown-item" id="rotatefile" href="#"><i class="' + ICON.filerotate + '"></i>' + LANG.rotate + '</a>';
                }
            }

            // Cho phép di chuyển file
            if (1) {
                html += '<a class="dropdown-item" id="move" href="#"><i class="' + ICON.move + '"></i>' + LANG.move + '</a>';
            }

            // Cho phép đổi tên file
            if (1 && !isMultiple) {
                html += '<a class="dropdown-item" id="rename" href="#"><i class="' + ICON.rename + '"></i>' + LANG.rename + '</a>';
            }

            // Cho phép xóa file
            if (1) {
                html += '<a class="dropdown-item" id="filedelete" href="#"><i class="' + ICON.filedelete + '"></i>' + LANG.upload_delfile + '</a>';
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
        if ($(this).parent().is('.active')) {
            return false;
        }
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
}

/*
 * Load danh sách các file
 */
NVCoreFileBrowser.prototype.getListFiles = function(callback) {
    var self = this;
    var cfg = self.cfg;
    var cfgf = self.cfgFolderData;

    // FIXME xử lý thêm chỗ này
    //var imgtype = $("select[name=imgtype]").val();
    //var selFile = $("input[name=selFile]").val();
    //var author = $("select[name=author]").val() == 1 ? "&author" : "";

    var imgtype = $(cfg.ctnFilterType).data('value');
    var selFile = '';
    var author = $(cfg.ctnFilterUser).data('value');
    var order = $(cfg.ctnFilterSort).data('value');
    var path = $(cfgf.folder).data('value');
    var urlFiles = self.firstData.baseurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=imglist&path=' + path + '&type=' + imgtype + '&imgfile=' + selFile + '&author=' + author + '&order=' + order + '&random=' + self.strRand(10);

    $(cfg.filesContainer).load(urlFiles, function() {
        self.hideLoader();
        self.listFilesHandler();
        updatePerfectScrollbar();
    });

    if (callback) {
        self.checkInitCompleted();
    }
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
    $('.file', $(cfg.filesContainer)).bind("mouseup", function(e) {
        e.preventDefault();
        self.fileMouseup(this, e);
    });

    // Khi mở menu chuột phải thì không xử lý gì.
    $('.file', $(cfg.filesContainer)).bind("contextmenu", function(e) {
        e.preventDefault();
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
    }
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
        $("input[name=selFile]").val('');

        if ($('.file-selected').length) {
            fileName = new Array();
            $.each($('.file-selected'), function() {
                fileName.push($(this).attr("title"));
            });
            fileName = fileName.join('|');

            $("input[name=selFile]").val(fileName);
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
 * Xử lý thao tác bàn phím trong khu vực danh sách các file
 */
var KEYPR = {
    isCtrl: false,
    isShift: false,
    shiftOffset: 0,
    allowKey: [112, 113, 114, 115, 116, 117, 118, 119, 120, 121, 122, 123],
    isSelectable: false,
    isFileSelectable: false,
    init: function(cfg) {
        $('body').keyup(function(e) {
            if (!$(e.target).is('.dynamic') && $.inArray(e.keyCode, KEYPR.allowKey) == -1) {
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
            if (!$(e.target).is('.dynamic') && $.inArray(e.keyCode, KEYPR.allowKey) == -1) {
                e.preventDefault();
            } else {
                return;
            }

            // Ctrl key press
            if (e.keyCode == 17 /* Ctrl */ ) {
                KEYPR.isCtrl = true;
            } else if (e.keyCode == 27 /* ESC */ ) {
                // Unselect all file
                $(".file-selected").removeClass("file-selected");
                LFILE.setSelFile();

                // Hide contextmenu
                NVCMENU.hide();

                // Reset shift offset
                KEYPR.shiftOffset = 0;
            } else if (e.keyCode == 65 /* A */ && e.ctrlKey === true) {
                // Select all file
                $(".file", $(cfg.filesContainer)).addClass("file-selected");
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

        // Unselect file when click on wrap area
        $(cfg.filesContainer).click(function(e) {
            if (KEYPR.isSelectable == false) {
                if (!$(e.target).closest('.file').length) {
                    $(".file-selected").removeClass("file-selected");
                }
            }

            KEYPR.isSelectable = false;
        });
    }
};

/* Rorate Handle */
var RRT = {
    direction: 0,
    currentDirection: 0,
    arrayDirection: [0, 90, 180, 270],
    timer: null,
    timeOut: 20,
    trigger: function() {
        $('#rorateContent img').rotate(RRT.direction);
    },
    setVal: function() {
        $('[name="rorateDirection"]').val(RRT.direction);
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
    init: function() {
        $('[name="rorateDirection"]').keyup(function() {
            var direction = $(this).val();

            if (isNaN(direction)) {
                direction = direction.slice(0, direction.length - 1);
            }

            RRT.setDirection(direction);
            RRT.setVal();
            RRT.trigger();
        });

        $('#rorateLeft').mousedown(function() {
            RRT.timer = setInterval("RRT.decrease()", RRT.timeOut);
        });

        $('#rorateLeft').bind("mouseup mouseleave", function() {
            clearInterval(RRT.timer);
        });

        $('#rorateRight').mousedown(function() {
            RRT.timer = setInterval("RRT.increase()", RRT.timeOut);
        });

        $('#rorateRight').bind("mouseup mouseleave", function() {
            clearInterval(RRT.timer);
        });

        $('#rorate90Anticlockwise').click(function() {
            RRT.currentDirection--;

            if (RRT.currentDirection < 0) {
                RRT.currentDirection = 3;
            }

            RRT.setDirection(RRT.arrayDirection[RRT.currentDirection]);
            RRT.setVal();
            RRT.trigger();
        });

        $('#rorate90Clockwise').click(function() {
            RRT.currentDirection++;

            if (RRT.currentDirection > 3) {
                RRT.currentDirection = 0;
            }

            RRT.setDirection(RRT.arrayDirection[RRT.currentDirection]);
            RRT.setVal();
            RRT.trigger();
        });

        $('#rorateimageOK').click(function() {
            var roratePath = $('[name="roratePath"]').val();
            var rorateFile = $('[name="rorateFile"]').val();
            var rorateDirection = $('[name="rorateDirection"]').val();

            $(this).attr("disabled", "disabled");

            $.ajax({
                type: "POST",
                url: nv_module_url + "rotateimg&num=" + nv_randomNum(10),
                data: "path=" + roratePath + "&file=" + rorateFile + "&direction=" + rorateDirection,
                success: function(g) {
                    $('#rorateimageOK').removeAttr("disabled");
                    var h = g.split("#");

                    if (h[0] == "ERROR") {
                        alert(h[1]);
                    } else {
                        $("div#rorateimage").dialog("close");
                        LFILE.reload(roratePath, rorateFile);
                    }
                }
            });
        });
    }
};

var NVCMENU = {
    menu: null,
    bindings: {
        select: function() {
            insertvaluetofield();
        },
        download: function() {
            download();
        },
        filepreview: function() {
            preview();
        },
        fileaddlogo: function() {
            addlogo();
        },
        create: function() {
            create();
        },
        move: function() {
            move();
        },
        rename: function() {
            filerename();
        },
        filedelete: function() {
            filedelete();
        },
        cropfile: function() {
            cropfile();
        },
        rotatefile: function() {
            rotatefile();
        },
        renamefolder: function() {
            renamefolder()
        },
        createfolder: function() {
            createfolder()
        },
        recreatethumb: function() {
            recreatethumb()
        },
        deletefolder: function() {
            deletefolder()
        }
    },
    init: function() {
        NVCMENU.menu = $('<div id="nvContextMenu" class="dropdown-menu"></div>').appendTo('body').bind('click', function(e) {
            e.stopPropagation();
        });
        NVCMENU.menu.bind('contextmenu', function(e) {
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
                $('#' + id, NVCMENU.menu).bind('click', function(e) {
                    NVCMENU.hide();
                    func();
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
            var maxTop = $('body').height() - menuHeight - 5;

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
                'width': menuWidth + 'px'
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
            $(document).trigger("nv.upload.ready");
        }
    });

    // Load Jquery UI
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
        var url = self.options.adminBaseUrl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&popup=1';

        this.$element.html(self.options.templateLoader);
        this.$element.load(url, function() {
            self.init();
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
            type: self.options.type,
            imgfile: self.options.imgfile
        };

        // Xử lý các thành phần
        (new NVCoreFileBrowser()).init(data);

        /*
         * Build thêm thanh cuộn
         * Lưu ý: Phần này là giao diện mặc định, các giao diện khác có thể không có
         * do đó nếu copy sang các giao diện khác nên xóa đi
         */
        $('.nv-scroller', self.$element).each(function(k, v) {
            nvScrollbar.push(new PerfectScrollbar(v, {
                wheelPropagation: $(this).data('wheel') ? true : false
            }));
        });
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
