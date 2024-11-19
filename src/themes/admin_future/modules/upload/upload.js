'use strict';

/**
 * NukeViet có cơ chế kiểm soát tệp js local load nhiều lần
 * nên không cần quan tâm xử lý cơ chế tệp này được gọi nhiều lần
 * {* Lưu ý: Tệp này được gọi bằng smarty *}
 */
document.addEventListener('DOMContentLoaded', () => {
    let cssNum = 0, jsNum = 0, ready = false;
    let amountCss = 3, amountJs = 9;

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
    // Tải select2
    if (typeof $.fn.select2 == "undefined") {
        loadScript(nv_base_siteurl + "assets/js/select2/select2.min.js", nv_base_siteurl + "assets/js/select2/i18n/" + nv_lang_interface + ".js");
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

const nukeviet = window.nukeviet || {};

/**
 * Class xử lý trình quản lý tệp tin
 */
nukeviet.Picker = class {
    html_modal = `{$HTML_POPUP}`;
    html_container = `{$HTML_CONTENT}`;
    html_dialog = `{$HTML_DIALOG}`;
    html_modal_backdrop = `<div class="fmm-backdrop fade"></div>`;
    html_dialog_backdrop = `<div class="fmd-backdrop fade"></div>`;

    // Hàm khởi tạo
    constructor(element, options) {
        this.$element = $(element);
        this.settings = $.extend({
            show: 'button', // button|inline
            path: '', // Thư mục được tải lên dạng uploads/module/...
            currentpath: '', // Active thư mục này
            type: 'file', // image|file
            imgfile: '', // Select tệp này
        }, options);

        this.fmm = null;
        this.fms = null;
        this.fmd = null;
        this.fs = null;
        this.ts = null;
        this.qs = null;
        this.bodyEndPadding = 0;
        this.bodyOverflow = '';
        this.bodyDigEndPadding = 0;
        this.bodyDigOverflow = '';

        this.refresh = false;
        this.page = 1;

        this.id = this.ranid();
        this.fmdId = 'fmd' + this.id;
        this.fmsId = 'fms' + this.id;
        this.html_dialog = this.html_dialog.replace(/\[prefix\]/g, this.fmdId);
        this.html_container = this.html_container.replace(/\[prefix\]/g, this.fmsId);

        this.up = null;

        this.constant = {
            auto_alt: {$UPLOAD_AUTO_ALT},
            alt_require: {$UPLOAD_ALT_REQUIRE}
        }

        this.init();
    }

    // Dựng trình quản lý tệp tin
    init() {
        let cfg = this.settings;

        const self = this;
        if (cfg.show == 'inline') {
            self.fms = $(self.html_container);
            self.fmd = $(self.html_dialog);
            self.$element.replaceWith(self.fms);
            self.fms.after(self.fmd);
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
        const self = this;

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
        self.qs = new PerfectScrollbar($('[data-toggle="queue-scroller"]', self.fms)[0], {
            wheelPropagation: false
        });

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
                const icon = $('i', fq);
                fq.data('q', '');
                fq.attr('title', fq.data('label-search'));
                fq.attr('aria-label', fq.data('label-search'));
                icon.removeClass(icon.data('icon-clear')).addClass(icon.data('icon-search'));

                self.page = 1;
                self.fetchFile();
                return;
            }

            self.showDialog('search');
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

        // Handler lỗi trong các dialog
        self.handlerDialogError();

        // Kiểu danh sách hoặc lưới
        $('[data-toggle="list-grid"]', self.fms).on('click', function(e) {
            e.preventDefault();
            self.switchView($(this).data('view') == 'list' ? 'grid' : 'list');
            self.fs.update();
        });

        // Tooltip trong container
        $('[data-bs-toggle="tooltip"]', self.fms).each(function() {
            new bootstrap.Tooltip(this);
        });

        // Tự xác định alt khi nhập url upload remote
        self.fmd.filter('[data-dialog="upload-remote"]').on('keyup', '[name="fileurl"]', function() {
            if (!self.constant.auto_alt) {
                return;
            }
            const dig = $(this).closest('.fmd');
            $('[name="filealt"]', dig).val(self.getAlt($(this).val()));
        });

        // Xử lý sự kiện khi click vào khu vực file
        $('[data-toggle="file-scroller"]', self.fms).on('click', function(e) {
            self.handlerFileClick(e);
        });

        // Lấy nội dung
        self.fetchAll();
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
    }

    // Đóng trình quản lý tệp tin dạng popup
    hideModal() {
        const self = this;

        if (self.fmm) {
            self.fmm.removeClass('show');
            self.fmm.removeAttr('aria-modal');
            self.fmm.attr('aria-hidden', 'true');

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
        const self = this;

        // Tạo HTML cho modal và lắng nghe các sự kiện
        self.fmm = $(self.html_modal);
        self.fmd = $(self.html_dialog);
        $('body').append(self.fmm);
        $('body').append(self.fmd);

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
        self.backdrop = $(self.html_modal_backdrop);
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
        const self = this;

        self.showLoader();

        let pr = {
            checkss: $('body').data('checksess'),
            show_file: file ? 1 : 0,
            show_folder: tree ? 1 : 0,
            path: self.settings.path,
            currentpath: self.settings.currentpath,
            type: self.settings.type,
            imgfile: self.settings.imgfile,
            type: $('[data-toggle="filter-type"]', self.fms).data('type'),
            author: $('[data-toggle="filter-author"]', self.fms).data('author'),
            order: $('[data-toggle="filter-order"]', self.fms).data('order'),
            page: self.page
        };
        const activeDir = $('[data-toggle="tree-scroller"] .active', self.fms);
        if (activeDir.length) {
            pr.currentpath = activeDir.data('dir');
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
            url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=main&nocache=' + new Date().getTime(),
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
                    self.ts.update();
                    self.initUploader();
                }
                if (file) {
                    $('[data-toggle="file-scroller"]', self.fms).html(respon.files);
                    $('[data-toggle="pagination"]', self.fms).html(respon.pagination);

                    self.switchView(respon.view);
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
    handlerDialogError() {
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
    showDialog(name) {
        const self = this;
        const dig = self.fmd.filter('[data-dialog="' + name + '"]');
        if (dig.length != 1 || dig.is('.show')) {
            return;
        }

        // Đình chỉ thanh cuộn của body
        const body = document.body;
        self.bodyDigEndPadding = body.style.paddingRight;
        self.bodyDigOverflow = body.style.overflow;

        body.style.paddingRight = self.scrollbarWidth() + 'px';
        body.style.overflow = 'hidden';
        $('body').addClass('fmd-open');

        // Tạo hiệu ứng của nền mỗi lần mở modal
        self.backdropDig = $(self.html_dialog_backdrop);
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
            self.showDialogCallback(name, dig);
        }, 1);
        setTimeout(() => {
            self.shownDialogCallback(name, dig);
        }, 301);
    }

    // Xử lý khi Dialog bắt đầu mở lên
    showDialogCallback(name, dialog) {
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
    }

    // Xử lý sau khi Dialog được mở lên
    shownDialogCallback(name, dialog) {
        if (name == 'search') {
            $('[name="q"]', dialog).focus();
            return;
        }

        if (name == 'upload-remote') {
            $('[name="fileurl"]', dialog).focus();
            return;
        }
    }

    // Đóng các dialog
    hideDialog(dig) {
        const self = this;

        dig.removeClass('show');
        dig.removeAttr('aria-modal');
        dig.attr('aria-hidden', 'true');

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
        }, 300);
    }

    // Xử lý sau khi Dialog đóng lại
    hideDialogCallback(dialog) {
        $('.is-invalid', dialog).removeClass('is-invalid');
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
            if (self.constant.alt_require && trim($('[name="filealt"]', dig).val()) === '') {
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
                success: function(respon) {
                    $('input, textarea, select, button', $(form)).prop('disabled', false);
                    const res = respon.split('_');
                    if (res[0] == 'ERROR') {
                        nvToast(res[1], 'error');
                        return;
                    }
                    self.hideDialog(dig);
                    self.page = 1;
                    self.fetchFile();
                    // FIXME chỗ này chưa xong
                },
                error: function(xhr, text, err) {
                    $('input, textarea, select, button', $(form)).prop('disabled', false);
                    nvToast(err, 'error');
                    console.log(xhr, text, err);
                }
            });
            return;
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

    // Trả về mảng các file được chọn, nếu không có tệp trả về rỗng
    getSelectedFile() {

    }

    // Chọn tệp
    setSelectedFile() {

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

    // Xử lý event khi click chuột trái vào tệp tin
    handlerFileClick(event) {

        //console.log(event.ctrlKey);

        const self = this;
        let file = [];
        if ($(event.target).is('[data-toggle="file"]')) {
            file = $(event.target);
        } else {
            const ff = $(event.target).closest('[data-toggle="file"]');
            if (ff.length == 1) {
                file = ff;
            }
        }

        $('[data-toggle="file"]', self.fms).removeClass('selected');

        if (file.length == 1) {
            file.addClass('selected');
            return;
        }
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
