/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

$(function() {
    // Hiển thị chi tiết ứng dụng
    let mdEd = $('#mdExtDetail');
    if (mdEd.length) {
        $('.ex-detail').on('click', function(e) {
            e.preventDefault();
            let btn = $(this);
            let icon = $('i', btn);
            if (icon.is('.fa-spinner')) {
                return;
            }
            $('#mdExtDetailLabel').html(btn.data('title'));
            icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
            $.ajax({
                type: 'GET',
                url: btn.data('url') + '&popup=1&nocache=' + new Date().getTime(),
                success: function(res) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    $('.modal-body', mdEd).html(res);
                    $('[data-bs-toggle="tooltip"]', mdEd).each(function() {
                        new bootstrap.Tooltip(this);
                    });
                    bootstrap.Modal.getOrCreateInstance(mdEd[0]).show();
                },
                error: function(xhr, text, err) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    nvToast(err, 'error');
                    console.log(xhr, text, err);
                }
            });
        });
    }

    // Tải về file cài đặt ứng dụng
    function downloadExt() {
        let ctn = $('[data-toggle="checkExtCtnDownload"]');
        let icon = $('i', ctn);
        let indicator = $('[data-toggle="checkExtIndicate"]');

        ctn.removeClass('d-none');
        setTimeout(() => {
            $.ajax({
                type: 'POST',
                url: script_name,
                data: nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=download&data=' + ctn.data('jsonencode'),
                success: function(e) {
                    e = e.split('|');
                    if (e[0] == 'OK') {
                        icon.removeClass('fa-spinner fa-spin-pulse').addClass('fa-circle-check');
                        ctn.addClass('text-success');
                        indicator.removeClass('text-bg-primary').addClass('text-bg-success');
                        $('span', ctn).html(ctn.data('lang-ok'));
                        setTimeout(() => {
                            window.location = script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=extensions&' + nv_fc_variable + '=upload&uploaded=1';
                        }, 3000);
                        return;
                    }

                    icon.removeClass('fa-spinner fa-spin-pulse').addClass('fa-circle-xmark');
                    ctn.addClass('text-danger');
                    indicator.removeClass('text-bg-primary').addClass('text-bg-danger');
                    $('span', ctn).html(e[1]);
                },
                error: function(xhr, text, err) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass('fa-circle-xmark');
                    ctn.addClass('text-danger');
                    indicator.removeClass('text-bg-primary').addClass('text-bg-danger');
                    nvToast(err, 'error');
                    console.log(xhr, text, err);
                }
            });
        }, 500);
    }

    // Xác nhận cài ứng dụng
    $('[data-toggle="checkExtConfirm"]').on('click', function(e) {
        e.preventDefault();
        $('[data-toggle="checkExtWarning"]').addClass('d-none');
        $('[data-toggle="checkExtIndicate"]').removeClass('text-bg-warning').addClass('text-bg-primary');
        downloadExt();
    });

    // Tự động tải về
    let autoDownExt = $('[data-toggle="checkExtAutoDownload"]');
    if (autoDownExt.length) {
        downloadExt();
    }

    // Kiểm tra gói ứng dụng trước khi tải lên
    function checkext(myArray, myValue) {
        var type = eval(myArray).join().indexOf(myValue) >= 0;
        return type;
    }
    $('#formSubmitExt').on('submit', function(e) {
        let form = $(this);
        let zipfile = $("input[name=extfile]").val();
        if (zipfile == "") {
            e.preventDefault();
            nvToast(form.data('error-choose'), 'error');
            return;
        }

        let filezip = zipfile.slice(-3);
        let filegzip = zipfile.slice(-2);
        let allowext = new Array("zip", "gz");

        if (!checkext(allowext, filezip) && !checkext(allowext, filegzip)) {
            e.preventDefault();
            nvToast(form.data('error-type'), 'error');
        }
    });

    // Xóa ứng dụng ra khỏi hệ thống
    $('[data-toggle="deleteExtension"]').on('click', function(e) {
        e.preventDefault();
        let btn = $(this);
        let icon = $('i', btn);
        if (icon.is('.fa-spinner')) {
            return;
        }
        nvConfirm(btn.data('confirm'), () => {
            icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
            $.ajax({
                type: 'POST',
                url: btn.attr('href') + '&nocache=' + new Date().getTime(),
                dataType: 'json',
                cache: false,
                success: function(respon) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    if (!respon.success) {
                        nvToast(respon.text, 'error');
                        return;
                    }
                    nvToast(respon.text, 'success');
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                },
                error: function(xhr, text, err) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    nvToast(err, 'error');
                    console.log(xhr, text, err);
                }
            });
        });
    });

    function handlerAutoRedirect() {
        let ele = $('[data-toggle="upExtSuccessAutolink"]', $('#filelist'));
        if (ele.length) {
            setTimeout(() => {
                window.location = ele.attr('href');
            }, 3000);
        }
    }

    // Giải nén gói ứng dụng đã upload
    $('#upload-ext-status a').on('click', function(e) {
        e.preventDefault();
        $('#filelist').html($('#filelist-loader').html());
        $.ajax({
            type: 'GET',
            url: $('#filelist').data('link') + '&nocache=' + new Date().getTime(),
            success: function(res) {
                $('#filelist').html(res);
                handlerAutoRedirect();
            },
            error: function(xhr, text, err) {
                nvToast(err, 'error');
                console.log(xhr, text, err);
            }
        });
    });

    // Giải nén gói ứng dụng đã upload
    $('body').on('click', '[data-toggle="upExtDismissWarning"]', function(e) {
        e.preventDefault();
        let btn = $(this);
        $('#filelist').html($('#filelist-loader').html());
        $.ajax({
            type: 'GET',
            url: btn.data('link') + '&nocache=' + new Date().getTime(),
            success: function(res) {
                $('#filelist').html(res);
                handlerAutoRedirect();
            },
            error: function(xhr, text, err) {
                nvToast(err, 'error');
                console.log(xhr, text, err);
            }
        });
    });

    // Xử lý cập nhật ứng dụng
    let getUpCtn = $('#getUpd');
    if (getUpCtn.length) {
        getUpCtn.html($('#getUpdLoader').html()).removeClass('d-none');
        setTimeout(() => {
            $.ajax({
                type: 'GET',
                url: getUpCtn.data('url') + '&nocache=' + new Date().getTime(),
                success: function(res) {
                    getUpCtn.html(res);
                    let downCtn = $('#upd-getfile', getUpCtn);
                    if (downCtn.length) {
                        setTimeout(() => {
                            $.ajax({
                                type: 'GET',
                                url: downCtn.data('link') + '&nocache=' + new Date().getTime(),
                                success: function(res) {
                                    downCtn.html(res);
                                },
                                error: function(xhr, text, err) {
                                    nvToast(err, 'error');
                                    console.log(xhr, text, err);
                                }
                            });
                        }, 200);
                    }
                },
                error: function(xhr, text, err) {
                    nvToast(err, 'error');
                    console.log(xhr, text, err);
                }
            });
        }, 500);

        // Giải nén gói cập nhật
        $(document).on('click', '[data-toggle="updateExtUnzip"] a', function(e) {
            e.preventDefault();
            getUpCtn.html($('#getUpdLoader').html()).removeClass('d-none');
            $.ajax({
                type: 'GET',
                url: $(this).attr('href') + '&nocache=' + new Date().getTime(),
                success: function(res) {
                    getUpCtn.html(res);
                    let ele = $('[data-toggle="upExtSuccessAutolink"]', getUpCtn);
                    if (ele.length) {
                        setTimeout(() => {
                            window.location = ele.attr('href');
                        }, 3000);
                    }
                },
                error: function(xhr, text, err) {
                    nvToast(err, 'error');
                    console.log(xhr, text, err);
                }
            });
        });
    }
});
