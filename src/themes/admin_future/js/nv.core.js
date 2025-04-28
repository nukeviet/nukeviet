/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

'use strict';

function locationReplace(url) {
    var uri = window.location.href.substring(window.location.protocol.length + window.location.hostname.length + 2);
    if (url != uri && history.pushState) {
        history.pushState(null, null, url)
    }
}

function formXSSsanitize(form) {
    $(form).find("input, textarea").not(":submit, :reset, :image, :file, :disabled").not('[data-sanitize-ignore]').each(function() {
        $(this).val(DOMPurify.sanitize($(this).val(), {ALLOWED_TAGS: nv_whitelisted_tags, ADD_ATTR: nv_whitelisted_attr}));
    });
}

$(function() {
    // Hàm lưu config tùy chỉnh của giao diện
    function storeThemeConfig(configName, configValue, callbackSuccess, callbackError) {
        if (typeof callbackSuccess == 'undefined') {
            callbackSuccess = (data) => {
                if (data.error) {
                    nvToast(data.message, 'danger');
                }
            };
        }
        if (typeof callbackError == 'undefined') {
            callbackError = (xhr, text, error) => {
                console.log(xhr, text, error);
                nvToast(text, 'danger');
            };
        }
        $.ajax({
            type: 'POST',
            url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=siteinfo&nocache=' + new Date().getTime(),
            data: {
                store_theme_config: $('body').data('checksess'),
                config_name: configName,
                config_value: configValue
            },
            dataType: 'json',
            error: callbackError,
            success: callbackSuccess
        });
    }

    // Đếm ngược phiên đăng nhập của quản trị
    if ($('#countdown').length) {
        var countdown = $('#countdown'),
            distance = parseInt(countdown.data('duration')),
            countdownObj = setInterval(function() {
                distance = distance - 1000;

                var hours = Math.floor(distance / (1000 * 60 * 60)),
                    minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60)),
                    seconds = Math.floor((distance % (1000 * 60)) / 1000);
                if (minutes < 10) {
                    minutes = '0' + minutes
                };
                if (seconds < 10) {
                    seconds = '0' + seconds
                };
                countdown.text(hours + ':' + minutes + ':' + seconds)

                if (distance <= 0) {
                    clearInterval(countdownObj);
                    window.location.reload()
                }
            }, 1000);
    };

    // Quản trị thoát
    $('[data-toggle="admin-logout"]').on('click', function(e) {
        e.preventDefault();
        nv_admin_logout();
    });

    // Đóng mở thanh menu phải nếu nó có
    var rBar = $('#right-sidebar');
    if (rBar.length) {
        $('[data-toggle="right-sidebar"]').on('click', function(e) {
            e.preventDefault();
            $('body').toggleClass('open-right-sidebar');
        });
        $(document).on('click', function(e) {
            if ($(e.target).is('[data-toggle="right-sidebar"]') || $(e.target).closest('[data-toggle="right-sidebar"]').length) {
                return;
            }
            if ($(e.target).is('.right-sidebar') || $(e.target).closest('.right-sidebar').length) {
                return;
            }
            if ($(e.target).is('#site-toasts') || $(e.target).closest('#site-toasts').length) {
                return;
            }
            if ($('body').is('.open-right-sidebar')) {
                $('body').removeClass('open-right-sidebar');
            }
        });
        new PerfectScrollbar($('.right-sidebar-inner', rBar)[0], {
            wheelPropagation: false
        });
    }

    // Đóng mở thanh breadcrumb
    $('[data-toggle="breadcrumb"]').on('click', function(e) {
        e.preventDefault();
        $('body').toggleClass('open-breadcrumb');
    });

    // Menu các module admin
    var menuSys = $('#menu-sys'), psMsys;
    $('[data-bs-toggle="dropdown"]', menuSys).on('show.bs.dropdown', function() {
        if (psMsys) {
            return;
        }
        psMsys = new PerfectScrollbar($('.menu-sys-inner', menuSys)[0], {
            wheelPropagation: false
        });
    });

    // Xử lý đổi ngôn ngữ
    $('[name="gsitelanginterface"]').on('change', function() {
        $.ajax({
            url: script_name + '?langinterface=' + $(this).val() + '&' + nv_lang_variable +  '=' + nv_lang_data,
            type: 'POST',
            cache: false,
            success: function() {
                location.reload();
            },
            error: function(xhr, text, err) {
                nvToast(err, 'error');
                console.log(xhr, text, err);
            }
        });
    });
    $('[name="gsitelangdata"]').on('change', function() {
        window.location = script_name + '?langinterface=' + nv_lang_interface + '&' + nv_lang_variable +  '=' + $(this).val();
    });

    /**
     * Điều khiển menu trái
     */
    var lBar = $('#left-sidebar'), nvLBarSubsScroller = {}, nvLBarScroller, lBarTips = [];
    var nvLBarScroll = $('.left-sidebar-scroll', lBar);

    // Menu trái thu gọn hay không?
    function isCollapsibleLeftSidebar() {
        return $('body').is('.collapsed-left-sidebar');
    }

    // Xóa các thanh cuộn trong menu phụ
    function destroyLBarSubsScroller() {
        $.each(nvLBarSubsScroller, function(k) {
            nvLBarSubsScroller[k].destroy();
        });
        nvLBarSubsScroller = {};
    }

    // Cập nhật thanh cuộn chính của menu trái
    function updateLeftSidebarScrollbar() {
        if (!$.isSm()) {
            nvLBarScroller.update();
        }
    }

    // Cập nhật thanh cuộn menu con
    function updateLBarSubsScroller() {
        $.each(nvLBarSubsScroller, function(k) {
            nvLBarSubsScroller[k].update();
        });
    }

    // Xóa tooltip ở menu thu gọn
    function setLbarTip() {
        if (lBarTips.length > 0) {
            return;
        }
        $('.icon', lBar).each(function(k) {
            lBarTips[k] = new bootstrap.Tooltip(this);
        });
    }

    // Set tooltip ở menu thu gọn
    function removeLbarTip() {
        if (lBarTips.length <= 0) {
            return;
        }
        for (var i = 0; i < lBarTips.length; i++) {
            lBarTips[i].dispose();
        }
        lBarTips = [];
    }

    // Điều khiển mở menu cấp 2,3 ở dạng thu gọn
    function openLeftSidebarSub(menu) {
        var li = $(menu).parent(), // Li item
            subMenu = $(menu).next(),
            speed = 200,
            isLev1 = menu.parents().eq(1).hasClass('sidebar-elements'), // Xác định có phải menu cấp 1 không
            menuOpened = li.siblings('.open'); // Các menu cùng cấp khác đang mở

        // Đóng các menu cùng cấp đang mở
        if (menuOpened) {
            closeLeftSidebarSub($('> ul', menuOpened), menu);
        }

        if (!$.isSm() && isCollapsibleLeftSidebar() && isLev1) {
            // Mở menu dạng thu gọn
            destroyLBarSubsScroller();
            li.addClass('open');
            subMenu.addClass('visible');

            var scroller = li.find('.nv-left-sidebar-scroller');
            scroller.each(function(k, v) {
                nvLBarSubsScroller[k] = new PerfectScrollbar(v, {
                    wheelPropagation: false
                });
            });
        } else {
            // Mở menu dạng đầy đủ
            subMenu.slideDown({
                duration: speed,
                complete: function() {
                    li.addClass("open");
                    $(this).removeAttr("style");
                    updateLeftSidebarScrollbar();
                    updateLBarSubsScroller();
                }
            });
        }
    }

    // Điều khiển đóng menu cấp 2,3 ở dạng thu gọn
    function closeLeftSidebarSub(subMenu, menu) {
        var li = $(subMenu).parent(),
            subMenuOpened = $("li.open", li), // Các menu con đang mở
            notInMenu = !menu.closest(lBar).length,
            speed = 200,
            isLev1 = menu.parents().eq(1).hasClass("sidebar-elements"); // Xác định có phải menu cấp 1 không

        if (!$.isSm() && isCollapsibleLeftSidebar() && (isLev1 || notInMenu)) {
            // Đóng menu dạng thu gọn
            li.removeClass("open");
            subMenu.removeClass("visible");
            subMenuOpened.removeClass("open").removeAttr("style");
            updateLBarSubsScroller();
        } else {
            // Đóng menu dạng đầy đủ
            subMenu.slideUp({
                duration: speed,
                complete: function() {
                    li.removeClass("open");
                    $(this).removeAttr("style");
                    subMenuOpened.removeClass("open").removeAttr("style");
                    updateLeftSidebarScrollbar();
                    updateLBarSubsScroller();
                }
            });
        }
    }

    // Thanh cuộn menu trái nếu màn hình lớn
    if (!$.isSm() && lBar.length) {
        nvLBarScroller = new PerfectScrollbar(nvLBarScroll[0], {
            wheelPropagation: false
        });
    }

    // Tip menu trái ở chế độ thu gọn
    if (isCollapsibleLeftSidebar() && !$.isSm()) {
        setLbarTip();
    }

    var lBarTimer;
    $(window).resize(function() {
        if (lBarTimer) {
            clearTimeout(lBarTimer);
        }
        if (!lBar.length) {
            return;
        }
        lBarTimer = setTimeout(() => {
            if (isCollapsibleLeftSidebar() && !$.isSm()) {
                setLbarTip();
            } else {
                removeLbarTip();
            }
            if ($.isSm()) {
                if (nvLBarScroller) {
                    nvLBarScroller.destroy();
                }
                return;
            }
            if (nvLBarScroll.hasClass('ps')) {
                nvLBarScroller.update();
            } else {
                nvLBarScroller = new PerfectScrollbar(nvLBarScroll[0], {
                    wheelPropagation: false
                });
            }
        }, 100);
    });

    // Click vào nút mở rộng menu con ở menu trái ở chế độ đầy đủ
    $('span.toggle', lBar).on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();

        var menu = $(this).parent();
        var li = menu.parent();
        var subMenu = menu.next();

        if (li.hasClass('open')) {
            closeLeftSidebarSub(subMenu, menu);
        } else {
            openLeftSidebarSub(menu);
        }
    });

    // Click vào link menu trái > xử lý ở chế độ thu gọn. Chế độ đầy đủ xem như link thường
    $('.sidebar-elements li a', lBar).on('click', function(e) {
        var menu = $(this);
        var li = menu.parent();
        var subMenu = menu.next();
        if ((isCollapsibleLeftSidebar() && menu.parent().parent().is('.sidebar-elements') && li.is('.parent')) || menu.attr('href') == '#') {
            e.preventDefault();
            if (subMenu.length && subMenu.hasClass('visible')) {
                closeLeftSidebarSub(subMenu, menu);
            } else {
                openLeftSidebarSub(menu);
            }
        }
    });

    // Xử lý đóng menu trái cấp 2 ở chế độ thu gọn
    $(document).on('click', function(e) {
        if (!$(e.target).closest(lBar).length && !$.isSm()) {
            closeLeftSidebarSub($('ul.visible', lBar), $(e.currentTarget));
        }
    });

    // Mở rộng/thu gọn menu trái
    $('[data-toggle="left-sidebar"]').on('click', function(e) {
        e.preventDefault();
        var collapsed = $('body').is('.collapsed-left-sidebar');
        if (collapsed) {
            // Mở rộng
            $('ul.sub-menu.visible', lBar).removeClass('visible');
            // Xóa bỏ các thanh cuộn ở menu con
            destroyLBarSubsScroller();
            removeLbarTip();
        } else {
            // Thu gọn
            setLbarTip();
        }
        $('body').toggleClass('collapsed-left-sidebar');
        // Cập nhật lại chiều rộng các stickyTableHeaders
        setTimeout(() => {
            $(window).trigger('resize.stickyTableHeaders');
        }, 250);
        storeThemeConfig('collapsed_left_sidebar', collapsed ? 0 : 1);
    });

    // Đóng mở thanh menu trái ở dạng mobile
    $('[data-toggle="left-sidebar-sm"]', lBar).on('click', function(e) {
        e.preventDefault();
        $('body').toggleClass('left-sidebar-open-sm');
        $('.left-sidebar-spacer', lBar).slideToggle(300, function() {
            $(this).removeAttr('style').toggleClass('open');
        });
    });

    // Chỉnh chế độ màu
    var mColor = $('#site-color-mode');
    $('a', mColor).on('click', function(e) {
        e.preventDefault();
        var $this = $(this);
        if ($this.is('.active') || mColor.data('busy')) {
            return;
        }
        var icon = $('i', $this);
        icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
        mColor.data('busy', 1);

        storeThemeConfig('color_mode', $this.data('mode'), () => {
            mColor.data('busy', 0);
            $('a', mColor).removeClass('active');
            $this.addClass('active');
            icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));

            $('html').data('theme', $this.data('mode')).attr('data-theme', $this.data('mode'));
            nvSetThemeMode($this.data('mode'));
        }, (xhr, text, err) => {
            console.log(xhr, text, err);
            icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
            mColor.data('busy', 0);
            nvToast(text, 'danger');
        });
    });

    // Chỉnh hướng lrt, rtl
    $('[name="g_themedir"]').on('change', function() {
        var dir = $(this).val();
        var ctn = $('#site-text-direction');
        var $this = $(this).next();
        var icon = $('i', $this);
        if (ctn.data('busy') || icon.is('.fa-spinner')) {
            return;
        }

        $('[name="g_themedir"]').prop('disabled', true);
        icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
        ctn.data('busy', 1);

        storeThemeConfig('dir', dir, () => {
            location.reload();
        }, (xhr, text, err) => {
            console.log(xhr, text, err);
            nvToast(text, 'danger');
            $('[name="g_themedir"][value="' + $('html').attr('dir') + '"]').prop('checked', true);
            $('[name="g_themedir"]').prop('disabled', false);
            icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
            ctn.data('busy', 0);
        });
    });

    // Xử lý khi click trong điều kiện alertbox đang mở
    $(document).on('click', function(e) {
        if (!$('body').is('.alert-open') || $(e.target).is('.alert-box-content') || $(e.target).closest('.alert-box-content').length) {
            return;
        }
        const al = document.getElementsByClassName('alert-box')[0];
        if (al.classList.contains('modal-static')) {
            return;
        }
        al.classList.add('modal-static');
        setTimeout(() => {
            al.classList.remove('modal-static');
        }, 150);
    });

    // Xử lý hết phiên đăng nhập của admin
    var adTimer, adInterval, adOffcanvas = $('#admin-session-timeout'), sysNoti = $('#main-notifications');

    const timeoutsessrun = () => {
        clearInterval(adTimer);
        var Timeout = 60;
        $('[data-toggle="sec"]', adOffcanvas).text(Timeout);
        adOffcanvas.addClass('show');
        var msBegin = new Date().getTime();

        // Dừng ajax thông báo
        if (sysNoti.length) {
            sysNoti.data('enable', false);
        }

        adInterval = setInterval(() => {
            var msCurrent = new Date().getTime();
            var ms = Timeout - Math.round((msCurrent - msBegin) / 1000);
            if (ms >= 0) {
                $('[data-toggle="sec"]', adOffcanvas).text(ms);
                return;
            }

            clearInterval(adInterval);
            adOffcanvas.removeClass('show');
            $.getJSON(nv_base_siteurl + "index.php", {
                second: "time_login",
                nocache: (new Date).getTime()
            }).done(function(json) {
                if (json.showtimeoutsess == 1) {
                    $.get(nv_base_siteurl + "index.php?second=admin_logout&js=1&system=1&nocache=" + (new Date).getTime(), function() {
                        window.location.reload();
                    });
                } else {
                    // Chạy lại
                    if (sysNoti.length) {
                        sysNoti.data('enable', false);
                    }
                    adTimer = setTimeout(() => {
                        timeoutsessrun();
                    }, json.check_pass_time);
                }
            });
        }, 1000);
    }

    const timeoutsesscancel = () => {
        clearInterval(adInterval);
        $.ajax({
            url: nv_base_siteurl + 'index.php?second=statimg',
            cache: false
        }).done(function() {
            adOffcanvas.removeClass('show');

            // Chạy lại ajax thông báo
            if (sysNoti.length) {
                sysNoti.data('enable', true);
            }
            adTimer = setTimeout(() => {
                timeoutsessrun();
            }, nv_check_pass_mstime);
        });
    }

    adTimer = setTimeout(() => {
        timeoutsessrun();
    }, nv_check_pass_mstime);
    $('[data-toggle="cancel"]', adOffcanvas).on('click', function(e) {
        e.preventDefault();
        timeoutsesscancel();
    });

    // Add rel="noopener noreferrer nofollow" to all external links
    $('a[href^="http"]').not('a[href*="' + location.hostname + '"]').not('[rel*=dofollow]').attr({
        target: "_blank",
        rel: "noopener noreferrer nofollow"
    });

    // Prevent empty link click
    $('a[href="#"]').on('click', function(e) {
        e.preventDefault();
    });

    // Change Localtion
    $("a[data-location]").on("click", function() {
        locationReplace($(this).data("location"));
    });

    // XSSsanitize
    $('body').on('click', '[type=submit]:not([name],.ck-button-save)', function(e) {
        var form = $(this).parents('form');
        if (XSSsanitize && !$('[name=submit]', form).length) {
            // Khi không xử lý XSS thì trình submit mặc định sẽ thực hiện
            e.preventDefault();

            // Đưa CKEditor 5 trình soạn thảo vào textarea trước khi submit
            $(form).find("textarea").each(function() {
                if (this.dataset.editorname && window.nveditor && window.nveditor[this.dataset.editorname]) {
                    $(this).val(window.nveditor[this.dataset.editorname].getData());
                }
            });

            formXSSsanitize(form);
            $(form).submit();
        }
    });

    // checkAll
    $('body').on('click', '[data-toggle=checkAll]', function() {
        let ns = $(this).data('type');
        let sltorS = '[data-toggle=checkSingle]';
        let sltorA = '[data-toggle=checkAll]';
        if (ns) {
            sltorS += '[data-type="' + ns + '"]';
            sltorA += '[data-type="' + ns + '"]';
        }
        sltorS += ':not(:disabled)';
        $(sltorA).prop('checked', $(this).is(':checked'));
        $(sltorA).prop('indeterminate', false);
        $(sltorS).prop('checked', $(this).is(':checked'));
    });

    // checkSingle
    $('body').on('click', '[data-toggle=checkSingle]', function() {
        let ns = $(this).data('type');
        let sltorS = '[data-toggle=checkSingle]';
        let sltorA = '[data-toggle=checkAll]';
        if (ns) {
            sltorS += '[data-type="' + ns + '"]';
            sltorA += '[data-type="' + ns + '"]';
        }
        sltorS += ':not(:disabled)';
        $(sltorA).prop('checked', ($(sltorS + ':checked').length >= $(sltorS).length));
        $(sltorA).prop('indeterminate', ($(sltorS + ':checked').length > 0 && $(sltorS + ':checked').length < $(sltorS).length));
    });

    /**
     * Đoạn xử lý các nút mở trình quản lý file
     */
    let initPicker = false;
    function showPicker(btn) {
        let options = {};
        options.path = btn.data('path') ? btn.data('path') : '';
        options.currentpath = btn.data('currentpath') ? btn.data('currentpath') : options.path;
        options.type = btn.data('type') ? btn.data('type') : 'file';
        if (btn.data('alt')) {
            options.alt = btn.data('alt');
        }
        if (btn.data('target')) {
            options.area = btn.data('target');
            options.imgfile = $('#' + btn.data('target')).val();
        }
        const picker = nukeviet.Picker.getOrCreateInstance(btn[0], options);
        if (options.imgfile && options.imgfile != '') {
            // Dùng để pick lần thứ 2 trở đi
            picker.setOption('imgfile', options.imgfile);
        }
        picker.show();
    }

    function loadPicker() {
        let script = document.createElement('script');
        script.src = script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&langinterface=' + nv_lang_interface + '&' + nv_name_variable + '=upload&' + nv_fc_variable + '=js&t=' + nv_cache_timestamp;
        script.async = true;
        document.body.appendChild(script);
        initPicker = true;
    }

    // Tải trước uploader nếu có nút selectfile
    if ($('[data-toggle=selectfile]').length && (typeof nukeviet == 'undefined' || !nukeviet.Picker)) {
        loadPicker();
    }

    // Nút chọn file/ảnh
    $('body').on('click', '[data-toggle=selectfile]', function(e) {
        e.preventDefault();
        const btn = $(this);

        // Load thư viện nếu chưa có
        if (!initPicker && (typeof nukeviet == 'undefined' || !nukeviet.Picker)) {
            loadPicker();
        }
        // Xử lý trong trường hợp uploader chưa được tải sẵn (các DOM động)
        if (!window.nvPickerReady) {
            // Chỉ register event 1 lần duy nhất chờ do picker được tải và tự show
            if (!btn.data('init-picker')) {
                btn.data('init-picker', true);
                document.addEventListener('nv.picker.ready', () => {
                    showPicker(btn);
                });
            }
            return;
        }

        // Show trực tiếp picker
        showPicker(btn);
    });

    // Ajax submit
    // Condition: The returned result must be in JSON format with the following elements:
    // status ('OK/error', required), mess (Error content), input (input name),
    // redirect (redirect URL if status is OK), refresh (Reload page if status is OK)
    const formAj = $('.ajax-submit');
    if (formAj.length > 0) {
        $('select', formAj).on('change keyup', function() {
            $(this).removeClass('is-invalid is-valid');
            if ($(this).parent().is('.input-group')) {
                $(this).parent().removeClass('is-invalid is-valid');
            }
        });
        $('[type="text"], [type="password"], [type="number"], [type="email"], textarea', formAj).on('change keyup', function(e) {
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

    $('body').on('submit', '.ajax-submit', function(e) {
        e.preventDefault();

        if ($('.is-invalid:visible', this).length > 0) {
            let ipt = $('.is-invalid:visible:first', this);
            if (ipt.is('.input-group')) {
                ipt = $('input:first', ipt);
            }
            ipt.focus();
            return;
        }

        $('.is-invalid', this).removeClass('is-invalid');
        $('.is-valid', this).removeClass('is-valid');

        if (typeof(CKEDITOR) !== 'undefined') {
            for (let instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
                CKEDITOR.instances[instance].setReadOnly(true)
            }
        }

        var that = $(this),
            data = that.serialize(),
            callback = that.data('callback');
        $('input, textarea, select, button', that).prop('disabled', true);
        $.ajax({
            url: that.attr('action'),
            type: 'POST',
            data: data,
            cache: false,
            dataType: "json",
            success: function(a) {
                if (a.status == 'NO' || a.status == 'no' || a.status == 'error') {
                    $('input, textarea, select, button', that).prop('disabled', false);
                    if (a.tab) {
                        bootstrap.Tab.getOrCreateInstance(document.getElementById(a.tab)).show();
                    }
                    if (a.input) {
                        let eleCtn = null;
                        if (a.input_parent) {
                            // Trường hơp nhiều input cùng tên có chỉ định ra thẻ cha của nó
                            eleCtn = $(a.input_parent, that);
                        } else {
                            eleCtn = that;
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
                    let cb;
                    if ('function' === typeof callback) {
                        cb = callback(a);
                    } else if ('string' == typeof callback && "function" === typeof window[callback]) {
                        cb = window[callback](a);
                    }
                    if (cb === 0 || cb === false) {
                        return;
                    }
                    let timeout = 0;
                    if (a.mess) {
                        nvToast(a.mess, a.warning ? 'warning' : 'success');
                        timeout = a.timeout ? a.timeout : 2000;
                    }
                    if (a.redirect) {
                        setTimeout(() => {
                            window.location.href = a.redirect;
                        }, timeout);
                    } else if (a.refresh) {
                        setTimeout(() => {
                            window.location.reload();
                        }, timeout);
                    } else {
                        setTimeout(() => {
                            $('input, textarea, select, button', that).prop('disabled', false);
                            if (typeof(CKEDITOR) !== 'undefined') {
                                for (let instance in CKEDITOR.instances) {
                                    CKEDITOR.instances[instance].setReadOnly(false);
                                }
                            }
                        }, 1000);
                    }
                }
            },
            error: function(xhr, text, err) {
                $('input, textarea, select, button', that).prop('disabled', false);
                nvToast(text, 'error');
                console.log(xhr, text, err);
            }
        });
    });

    // Chỉ cho gõ ký tự dạng số ở input có class number
    $('body').on('input', '.number', function() {
        $(this).val($(this).val().replace(/[^0-9]/gi, ''))
    });

    // Chỉ cho gõ các ký tự [a-zA-Z0-9_] ở input có class alphanumeric
    $('body').on('input', '.alphanumeric', function() {
        $(this).val($(this).val().replace(/[^a-zA-Z0-9\_]/gi, ''))
    });

    // Không cho xuống dòng
    $('body').on('input', '.nonewline', function() {
        var val = $(this).val().replace(/\n$/gi, '');
        $(this).val(val.replace(/\s*\n\s*/gi, ' '))
    });

    // uncheck khi click vào radio nếu radio đang ở trạng thái checked
    $('body').on("click mousedown", 'input[type=radio].uncheckRadio, .uncheckRadio input[type=radio]', function() {
        var c;
        return function(i) {
            c = "click" == i.type ? !c || (this.checked = !1) : this.checked
        }
    }());

    // Cố định header bảng
    function stickyTable() {
        $('.table-sticky').each(function() {
            let ctn = $(this).parent(), bkp = '', test = ctn.attr('class').match(/table\-responsive\-*(sm|md|lg|xl|xxl)*/);
            if (test !== null) {
                bkp = test[1] || 'all';
            }
            let allowed;
            if (bkp == '') {
                allowed = true;
            } else {
                switch(bkp) {
                    case 'sm': allowed = !$.isXs(); break;
                    case 'md': allowed = !$.isSm(); break;
                    case 'lg': allowed = !$.isMd(); break;
                    case 'xl': allowed = !$.isLg(); break;
                    default: allowed = false;
                }
            }
            if (allowed) {
                $(this).stickyTableHeaders({
                    fixedOffset: $('header'),
                    cacheHeaderHeight: true
                });
            } else {
                $(this).stickyTableHeaders('destroy');
            }
        });
    }
    stickyTable();
    let timerstickyTable;
    $(window).on('resize', function() {
        clearTimeout(timerstickyTable);
        timerstickyTable = setTimeout(() => {
            stickyTable();
        }, 210);
    });

    // Tooltip
    ([...document.querySelectorAll('[data-bs-toggle="tooltip"]')].map(tipEle => new bootstrap.Tooltip(tipEle)));

    // Popover
    ([...document.querySelectorAll('[data-bs-toggle="popover"]')].map(popEle => new bootstrap.Popover(popEle)));

    // Default toasts
    ([...document.querySelectorAll('.toast')].map(toastEl => new bootstrap.Toast(toastEl)));

    // Default Scrollbar
    ([...document.querySelectorAll('[data-nv-toggle="scroll"]')].map(scrollEl => new PerfectScrollbar(scrollEl, {
        wheelPropagation: true
    })));

    if (
        !window.location.hostname.endsWith('.local') &&
        window.location.hostname !== 'localhost' &&
        !/^127\.0\.\d+\.\d+$/.test(window.location.hostname)
    ) {
        $("img.imgstatnkv").attr("src", "//static.nukeviet.vn/img.jpg");
    }
});

$(window).on('load', function() {
    // Xử lý thanh breadcrumb
    var brcb = $('#breadcrumb');
    if (brcb.length) {
        let gocl = $('#go-clients');
        let ctn = brcb.parent(), spacer = 8, timer;

        function brcbProcess() {
            let brcbW = ctn.width() - gocl.width() - spacer;
            let brcbWE = 40, stacks = [];
            $('ol.breadcrumb>li.breadcrumb-item', brcb).removeClass('over');
            $('ol.breadcrumb>li.breadcrumb-dropdown', brcb).addClass('d-none');
            $('ol.breadcrumb>li.breadcrumb-item', brcb).each(function() {
                brcbWE += $(this).innerWidth();
                if (brcbWE > brcbW) {
                    $(this).addClass('over');
                    stacks.push($(this).html());
                }
            });
            let popover = bootstrap.Popover.getOrCreateInstance($('[data-toggle="popover"]', brcb)[0]);
            if (stacks.length) {
                $('ol.breadcrumb>li.breadcrumb-dropdown', brcb).removeClass('d-none');
                let html = '<div class="list-group"><div class="list-group-item list-group-item-action">' + stacks.join('</div><div class="list-group-item list-group-item-action">') + '</div></div>';
                popover.setContent({
                    '.popover-body': html
                });
            } else {
                popover.hide();
            }
        }
        brcbProcess();
        $(window).on('resize', function() {
            if (timer) {
                clearTimeout(timer);
            }
            timer = setTimeout(() => {
                brcbProcess();
            }, 50);
        });

        $('[data-toggle="popover"]', brcb).on('click', function(e) {
            e.preventDefault();
        });
    }
});

/*
 * Kiểm tra loại màn hình
 */
+
function(e) {
    e.isBreakpoint = function(t) {
        var i, a, o;
        switch (t) {
            case 'xs':
                o = 'd-none d-sm-block';
                break;
            case 'sm':
                o = 'd-none d-md-block';
                break;
            case 'md':
                o = 'd-none d-lg-block';
                break;
            case 'lg':
                o = 'd-none d-xl-block';
                break;
            case 'xl':
                o = 'd-none d-xxl-block'
                break;
            case 'xxl':
                o = 'd-none'
        }
        return a = (i = e('<div/>', {
            class: o
        }).appendTo('body')).is(':hidden'), i.remove(), a
    };
    e.extend(e, {
        isXs: function() {
            return e.isBreakpoint('xs')
        },
        isSm: function() {
            return e.isBreakpoint('sm')
        },
        isMd: function() {
            return e.isBreakpoint('md')
        },
        isLg: function() {
            return e.isBreakpoint('lg')
        },
        isXl: function() {
            return e.isBreakpoint('xl')
        },
        isXxl: function() {
            return e.isBreakpoint('xxl')
        }
    });
}(jQuery);
