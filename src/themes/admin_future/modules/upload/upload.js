'use strict';

/**
 * NukeViet có cơ chế kiểm soát tệp js local load nhiều lần
 * nên không cần quan tâm xử lý cơ chế tệp này được gọi nhiều lần
 * {* Lưu ý: Tệp này được gọi bằng smarty *}
 */
document.addEventListener('DOMContentLoaded', () => {
    let cssNum = 0, jsNum = 0, ready = false;
    let amountCss = 3, amountJs = 7;

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
    // Tải PerfectScrollbar
    if (typeof PerfectScrollbar == "undefined") {
        loadScript(nv_base_siteurl + "assets/js/perfect-scrollbar/min.js");
        loadCSS(nv_base_siteurl + "assets/js/perfect-scrollbar/style.css");
    } else {
        jsNum++;
        cssNum++;
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

const nukeviet = window.nukeviet || {};

/**
 * Class xử lý trình quản lý tệp tin
 */
nukeviet.Picker = class {
    html_modal = `{$HTML_POPUP}`;
    html_container = `{$HTML_CONTENT}`;
    html_backdrop = `<div class="fmm-backdrop fade"></div>`;

    // Hàm khởi tạo
    constructor(element, options) {
        this.$element = $(element);
        this.settings = $.extend({
            show: 'button', // button|inline
            path: '', // Thư mục được tải lên dạng uploads/module/...
            currentpath: '', // Active thư mục này
            type: '', // image|file
            imgfile: '', // Select tệp này
        }, options);

        this.fmm = null;
        this.fms = null;
        this.fs = null;
        this.ts = null;
        this.bodyEndPadding = 0;
        this.bodyOverflow = '';

        this.init();
    }

    // Dựng trình quản lý tệp tin
    init() {
        let cfg = this.settings;
        let self = this;
        if (cfg.show == 'inline') {
            self.fms = $(self.html_container);
            self.$element.replaceWith(self.fms);
            self.initContainer();
            return;
        }

        this.$element.on('click', function(e) {
            e.preventDefault();
            self.showModal();
        });
    }

    // Xử lý các sự kiện sau khi dựng được container
    initContainer() {
        let self = this;

        // Xử lý khi đổ thư mục con ra
        $(self.fms).on('show.bs.collapse', '[data-toggle="collapseTree"]', function(e) {
            e.stopPropagation();
            let btn = $('[aria-controls="' + $(e.target).attr('id') + '"]', self.fms);
            let icon = $('[data-toggle="tree-icon"]', btn);
            icon.removeClass(icon.data('icon')).addClass('fa-folder-open');
        });
        $(self.fms).on('shown.bs.collapse', '[data-toggle="collapseTree"]', function(e) {
            e.stopPropagation();
            self.ts.update();
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
            self.ts.update();
        });

        // Tạo các thanh cuộn
        self.fs = new PerfectScrollbar($('[data-toggle="file-scroller"]', self.fms)[0], {
            wheelPropagation: false
        });
        self.ts = new PerfectScrollbar($('[data-toggle="tree-scroller"]', self.fms)[0], {
            wheelPropagation: false
        });

        // Lấy nội dung
        self.fetchAll();
    }

    // Đóng trình quản lý tệp tin dạng popup
    hideModal() {
        let self = this;

        if (self.fmm) {
            self.fmm.removeClass('show');
            self.fmm.removeAttr('aria-modal');
            self.fmm.attr('aria-hidden', 'true');

            setTimeout(() => {
                self.fmm[0].style.display = 'none';
                self.fmm.remove();
                self.fmm = null;
                self.fms = null;
                self.fs = null;
                self.ts = null;
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
        }, 300);
    }

    // Mở modal lên để chuẩn bị xây dựng trình quản lý tệp tin
    showModal() {
        let self = this;

        // Tạo HTML cho modal và lắng nghe các sự kiện
        self.fmm = $(self.html_modal);
        $('body').append(self.fmm);

        self.fmm.on('click', '[data-dismiss="fmm"]', function() {
            self.hideModal();
        });

        // Đình chỉ thanh cuộn của body
        const body = document.body;
        self.bodyEndPadding = body.style.paddingRight;
        self.bodyOverflow = body.style.overflow;

        body.style.paddingRight = self.scrollbarWidth() + 'px';
        body.style.overflow = 'hidden';

        // Tạo hiệu ứng của nền mỗi lần mở modal
        self.backdrop = $(self.html_backdrop);
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
            self.fms = $(self.html_container);
            $('[data-toggle="fmm-body"]', self.fmm).html(self.fms);
            self.initContainer();
        }, 310);
    }

    fetchTree() {
        this.fetch(true, false);
    }

    fetchFile() {
        this.fetch(false, true);
    }

    fetchAll() {
        this.fetch(true, true);
    }

    fetch(tree, file) {
        let self = this;
        $.ajax({
            type: 'POST',
            url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=main&nocache=' + new Date().getTime(),
            data: {
                checkss: $('body').data('checksess'),
                show_file: file ? 1 : 0,
                show_folder: tree ? 1 : 0,
                path: self.settings.path,
                currentpath: self.settings.currentpath,
                type: self.settings.type,
                imgfile: self.settings.imgfile,
            },
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
                    self.ts.update();
                }
                if (file) {
                    $('[data-toggle="file-scroller"]', self.fms).html(respon.files);
                    $('[data-toggle="pagination"]', self.fms).html(respon.pagination);
                    self.fs.update();
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
};

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
