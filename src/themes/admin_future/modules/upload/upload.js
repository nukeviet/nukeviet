'use strict';

/**
 * NukeViet có cơ chế kiểm soát tệp js local load nhiều lần
 * nên không cần quan tâm xử lý cơ chế tệp này được gọi nhiều lần
 */
document.addEventListener('DOMContentLoaded', () => {
    let cssNum = 0, jsNum = 0, ready = false;
    let amountCss = 2, amountJs = 6;

    // Tải jquery UI
    if (typeof $.ui == "undefined") {
        loadScript(nv_base_siteurl + "assets/js/jquery-ui/jquery-ui.min.js");
        loadCSS(nv_base_siteurl + "assets/js/jquery-ui/jquery-ui.min.css");
    } else {
        jsNum++;
        cssNum++;
    }
    // Tải Jquery Cropper
    if (typeof $.fn.cropper == "undefined") {
        loadScript(nv_base_siteurl + "assets/js/cropper/cropper.min.js");
        loadCSS(nv_base_siteurl + "assets/js/cropper/cropper.min.css");
    } else {
        jsNum++;
        cssNum++;
    }
    // Tải Jquery Rotate
    if (typeof $.fn.rotate == "undefined") {
        loadScript(nv_base_siteurl + "assets/js/jquery/jQueryRotate.js");
    } else {
        jsNum++;
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

    // Xuất ra event sẵn sàng
    function fireReady() {
        if (cssNum < amountCss || jsNum < amountJs || ready) {
            return;
        }
        ready = true;
        // Event cho js thuần
        document.dispatchEvent(new Event('nv.upload.ready'));

        // Event cho Jquery
        $(document).trigger("nv.upload.ready");
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
});

/*
 * Xử lý trình quản lý file ở các nút duyệt file
 */
(($) => {
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

    NVBrowseFile.VERSION = '5.0.00';

    NVBrowseFile.DEFAULTS = {
        adminBaseUrl: "",
        templateLoader: '<div class="card card-filemanager card-border-color card-border-color-primary loading"><div class="filemanager-loader"><div><i class="fas fa-spinner fa-pulse"></i></div></div></div>',
        path: 'uploads', // Thư mục upload gốc
        currentpath: 'uploads', // Thư mục upload hiện tại (thư mục con hoặc là thư mục gốc)
        type: 'file', // file|image|flash
        area: '', // Đối tượng trả về đường dẫn => Build ra currentfile
        alt: '', // Đối tượng trả về ALT image
        templateContainer: '<div id="mdNVFileManagerPopup" tabindex="-1" role="dialog" class="modal" data-backdrop="static"><div class="modal-dialog full-width modal-filemanager"><div class="modal-content"><div class="modal-header"><button type="button" data-dismiss="modal" aria-hidden="true" class="close"><span class="fas fa-times"></span></button></div><div class="modal-body"></div></div></div></div>',
        templateContainerID: '#mdNVFileManagerPopup',
        restype: 'filepath', // filepath|folderpath
        onPicked: null // Hàm xử lý khi chọn ảnh xong
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
            imgfile: '', // File đang chọn
            templateContainerID: NVBrowseFile.DEFAULTS.templateContainerID,
            onPicked: self.options.onPicked // Event khi chọn ảnh xong
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

    NVBrowseFile.prototype.strRand = function(a) {
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

                // Fix multi modal
                if ($('.modal-backdrop.show').length) {
                    $('body').addClass('modal-open');
                }
            });
        }

        return this.each(function() {
            var $this = $(this);
            var options = $.extend({}, NVBrowseFile.DEFAULTS, $this.data(), typeof option == 'object' && option);
            var data = $this.data('nv.upload');

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
})(jQuery);
