/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

$(function() {
    // Select 2
    if ($('.select2').length) {
        $('.select2').select2({
            language: nv_lang_interface,
            dir: $('html').attr('dir'),
            width: '100%'
        });
    }

    // Xử lý riêng form dọn dẹp hệ thống
    $('#clearsystem-form').on('submit', function(e) {
        e.preventDefault();
        var that = $(this),
            url = that.attr('action'),
            data = that.serialize(),
            checked_num = $('[name^=deltype]:checked', that).length;

        if (checked_num < 1) {
            nvToast(nv_please_check, 'info');
            return;
        }

        $('#pload').removeClass('d-none');
        $('#presult, #pnoresult').addClass('d-none');
        $('input,button', that).prop('disabled', true);

        $.ajax({
            type: "POST",
            url: url,
            data: data,
            cache: !1,
            dataType: 'json',
            success: function(response) {
                $('input,button', that).prop('disabled', false);
                $('#pload').addClass('d-none');

                if (response.status == 'error') {
                    nvToast(response.mess, 'error');
                    return;
                }

                if (response.data.length < 1) {
                    $('#presult').addClass('d-none');
                    $('#pnoresult').removeClass('d-none');
                    return;
                }

                $('#pnoresult').addClass('d-none');
                $('.dynamic-item', $('#presult')).remove();

                let html = '';
                response.data.forEach(function(item) {
                    html += '<li class="list-group-item text-break dynamic-item">' + item + '</li>';
                });
                $('#presult').append(html).removeClass('d-none');
            },
            error: function(xhr, text, err) {
                console.log(xhr, text, err);
                nvToast(err, 'error');
                $('input,button', that).prop('disabled', false);
                $('#pload').addClass('d-none');
            }
        });
    });

    // Xử lý load chậm trang kiểm tra phiên bản
    let upCtn = $('#updIf');
    if (upCtn.length) {
        // Kiểm tra phiên bản các ứng dụng
        function checkUpdateExt(reload) {
            $.ajax({
                type: 'GET',
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + nv_func_name + '&i=' + (reload ? 'extUpdRef' : 'extUpd') + '&nocache=' + new Date().getTime(),
                success: function(res) {
                    $('#extUpd').html(res);
                    $('[data-bs-toggle="tooltip"]', $('#extUpd')).each(function() {
                        new bootstrap.Tooltip(this);
                    });
                },
                error: function(xhr, text, err) {
                    nvToast(err, 'error');
                    console.log(xhr, text, err);
                }
            });
        }

        // Kiểm tra phiên bản hệ thống
        function checkUpdateSys(reload) {
            $.ajax({
                type: 'GET',
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + nv_func_name + '&i=' + (reload ? 'sysUpdRef' : 'sysUpd') + '&nocache=' + new Date().getTime(),
                success: function(res) {
                    $('#sysUpd').html(res);
                    $('#extUpd').html($('#upLoader').html()).removeClass('d-none');
                    setTimeout(() => {
                        checkUpdateExt();
                    }, 200);
                },
                error: function(xhr, text, err) {
                    nvToast(text, 'error');
                    console.log(xhr, text, err);
                }
            });
        }

        $('#sysUpd').html($('#upLoader').html()).removeClass('d-none');
        setTimeout(() => {
            checkUpdateSys();
        }, 500);

        // Cập nhật lại kiểm tra phiên bản hệ thống
        $("body").on("click", "#sysUpdRefresh", function(e) {
            e.preventDefault();
            $('#sysUpd').html($('#upLoader').html()).removeClass('d-none');
            checkUpdateSys(true);
        });

        // Cập nhật lại kiểm tra phiên bản ứng dụng
        $("body").on("click", "#extUpdRefresh", function(e) {
            e.preventDefault();
            $('#extUpd').html($('#upLoader').html()).removeClass('d-none');
            checkUpdateExt(true);
        });

        // Xem chi tiết thông tin check update ứng dụng
        $('body').on('click', '[data-toggle="viewUpExtInfo"]', function(e) {
            e.preventDefault();
            let ctn = $(this).closest('tr');
            let ocElement = $('#offcanvasUpExtDetail');
            $('.offcanvas-body', ocElement).html($('[data-toggle="viewUpExtInfoBody"]', ctn).html());
            $('.offcanvas-title', ocElement).text($(this).data('title'));
            let oc = bootstrap.Offcanvas.getOrCreateInstance(ocElement[0]);
            oc.show();
        });
    }

    // Xử lý load chậm trang tải và kiểm tra gói cập nhật
    let getupCtn = $('#getUpd');
    if (getupCtn.length) {
        getupCtn.html($('#getUpdLoader').html()).removeClass('d-none');
        setTimeout(() => {
            $.ajax({
                type: 'GET',
                url: getupCtn.data('url') + '&nocache=' + new Date().getTime(),
                success: function(res) {
                    getupCtn.html(res);
                },
                error: function(xhr, text, err) {
                    nvToast(text, 'error');
                    console.log(xhr, text, err);
                }
            });
        }, 500);

        // Xử lý các link thông qua ajax
        getupCtn.on('click', 'a', function(e) {
            if ($(this).closest('.alert-danger').length || $(this).is('[data-toggle="autolink"]')) {
                return;
            }
            e.preventDefault();
            getupCtn.html($('#getUpdLoader').html()).removeClass('d-none');
            $.ajax({
                type: 'GET',
                url: $(this).attr('href'),
                success: function(res) {
                    getupCtn.html(res);
                    if ($('[data-bs-toggle="tooltip"]', getupCtn).length) {
                        $('[data-bs-toggle="tooltip"]', getupCtn).each(function() {
                            new bootstrap.Tooltip(this);
                        });
                    }
                    if ($('[data-toggle="autolink"]', getupCtn).length) {
                        setTimeout(() => {
                            window.location = $('[data-toggle="autolink"]', getupCtn).attr('href');
                        }, 5000);
                    }
                },
                error: function(xhr, text, err) {
                    nvToast(text, 'error');
                    console.log(xhr, text, err);
                }
            });
        });
    }

    /**
     * Xử lý trang xem log lỗi
     */
    $('#errorfile').on('change', function() {
        var url = $(this).data('url'),
        efile = $(this).val();
        $.ajax({
            type: "POST",
            url: url,
            data: 'errorfile=' + efile,
            cache: !1,
            dataType: "json",
            success: function(response) {
                $('#errorlist').html(response.errorlist);
                $('#error-content .error_file_name').text(response.errorfilename);
                $('#error-content code').html(response.errorfilecontent);
                hljs.debugMode();
                hljs.highlightAll();
            },
            error: function(xhr, text, err) {
                nvToast(text, 'error');
                console.log(xhr, text, err);
            }
        })
    });

    $('#display-mode').on('change', function() {
        var url = $(this).data('url'),
            val = $(this).val();
        $.ajax({
            type: "POST",
            url: url,
            data: 'changemode=1&mode=' + val,
            cache: !1,
            success: function() {
                if (val == 'tabular') {
                    $('#errorlist').removeClass('d-none');
                    $('#error-content').addClass('d-none');
                } else {
                    $('#error-content').removeClass('d-none');
                    $('#errorlist').addClass('d-none');
                }
            },
            error: function(xhr, text, err) {
                nvToast(text, 'error');
                console.log(xhr, text, err);
            }
        })
    });

    if ($('#error-content').length) {
        hljs.debugMode();
        hljs.highlightAll();
    }
});
