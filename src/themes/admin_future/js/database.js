/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

$(function() {
    // Js xử lý xuất dữ liệu mẫu
    function ajaxWriteSampleData(url, data) {
        $.post(url + '&nocache=' + new Date().getTime(), data, function(res) {
            $('<p class="text-' + (res.lev == 1 ? 'success' : (res.lev == 2 ? 'warning' : 'danger')) + '">' + res.message + '</p>').insertAfter('#spdresulttop');
            if (res.next) {
                setTimeout(function() {
                    ajaxWriteSampleData(url, res.nextdata);
                }, 400);
            } else {
                if (!res.finish) {
                    var $this = $('#sampledataarea');
                    $('[type="submit"]', $this).prop('disabled', false);
                    $('[name="sample_name"]', $this).prop('disabled', false);
                    $('[name="delifexists"]', $this).val('1');

                    let icon = $('[type="submit"] i', $this);
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                }
                if (res.reload) {
                    setTimeout(function() {
                        location.reload();
                    }, 5000);
                }
            }
        }, 'json').fail(function(e) {
            $('<p class="text-danger">' + $('#sampledataarea').data('errsys') + '</p>').insertAfter('#spdresulttop');
        });
    }

    $('#sampledataarea form').submit(function(e) {
        e.preventDefault();
        var $this = $(this);
        var sname = encodeURIComponent($('[name="sample_name"]', $this).val());
        var delifexists = parseInt($('[name="delifexists"]', $this).val());

        var btn = $('[type="submit"]', $this);
        var icon = $('i', btn);
        if (icon.is('.fa-spinner')) {
            return;
        }

        btn.prop('disabled', true);
        $('[name="sample_name"]', $this).prop('disabled', true);

        icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
        $('<p class="text-info">' + $('#sampledataarea').data('init') + '</p>').insertAfter('#spdresulttop');
        $('#spdresult').removeClass('d-none');

        var url = script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + nv_func_name + '&startwrite=1';
        var data = {
            sample_name: sname,
            delifexists: delifexists
        };

        setTimeout(function() {
            ajaxWriteSampleData(url, data);
        }, 400);
    });

    $('#sampledataarea [name="sample_name"]').keydown(function() {
        $('[name="delifexists"]', $(this.form)).val('0');
    });

    // Xóa dữ liệu mẫu
    $('[data-toggle="sampDel"]').on('click', function(e) {
        e.preventDefault();
        let btn = $(this);
        let icon = $('i', btn);
        if (icon.is('.fa-spinner')) {
            return;
        }
        nvConfirm(nv_is_del_confirm[0], () => {
            icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + nv_func_name + '&nocache=' + new Date().getTime(),
                data: {
                    delete: btn.data('checkss'),
                    sname: btn.data('sname')
                },
                success: function() {
                    location.reload();
                },
                error: function(xhr, text, err) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    nvToast(text, 'error');
                    console.log(xhr, text, err);
                }
            });
        });
    });

    // Xóa file backup DB
    $('[data-toggle="delBackup"]').on('click', function(e) {
        e.preventDefault();
        let btn = $(this);
        let icon = $('i', btn);
        if (icon.is('.fa-spinner')) {
            return;
        }
        nvConfirm(nv_is_del_confirm[0], () => {
            icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
            $.ajax({
                type: 'POST',
                url: btn.data('url') + '&nocache=' + new Date().getTime(),
                dataType: 'json',
                success: function(data) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    if (data.error) {
                        nvToast(data.message, 'error');
                        return;
                    }
                    location.reload();
                },
                error: function(xhr, text, err) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    nvToast(text, 'error');
                    console.log(xhr, text, err);
                }
            });
        });
    });

    // Ajax tải danh sách các bảng dữ liệu - tăng tốc hiển thị trang vì bảng dữ liệu có thể lâu
    let eleDbTbls = $('#show_db_tables');
    if (eleDbTbls.length == 1) {
        // Cố định header bảng
        function stickyTable() {
            $('.table-db-sticky').each(function() {
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
        let timerstickyTable;
        $(window).on('resize', function() {
            clearTimeout(timerstickyTable);
            timerstickyTable = setTimeout(() => {
                stickyTable();
            }, 210);
        });

        $.ajax({
            type: 'POST',
            url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&nocache=' + new Date().getTime(),
            dataType: 'json',
            data: {
                show_tabs: 1,
                checkss: $('body').data('checksess')
            },
            success: function(data) {
                if (data.error) {
                    nvToast(data.message, 'error');
                    return;
                }
                eleDbTbls.html(data.html);
                stickyTable();
            },
            error: function(xhr, text, err) {
                nvToast(text, 'error');
                console.log(xhr, text, err);
            }
        });

        eleDbTbls.on('submit', '[data-toggle="formDbTbls"]', function(e) {
            let form = $(this);
            let btn = $('[data-toggle="actionDbTbls"]', form);
            let icon = $('i', btn);
            if (icon.is('.fa-spinner')) {
                return;
            }

            let op = $('[data-toggle="acOp"]', form).val();
            if (op == 'optimize') {
                e.preventDefault();

                let listid = [];
                $('[data-toggle="checkSingle"]:checked').each(function() {
                    listid.push($(this).val());
                });
                listid = listid.join(',');

                icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
                $.ajax({
                    type: 'POST',
                    url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=optimize&nocache=' + new Date().getTime(),
                    data: {
                        tables: listid,
                        checkss: $('[name="checkss"]', form).val()
                    },
                    success: function(data) {
                        icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                        nvAlert({
                            html: true,
                            message: data
                        }, () => {
                            location.reload();
                        });
                    },
                    error: function(xhr, text, err) {
                        icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                        nvToast(text, 'error');
                        console.log(xhr, text, err);
                    }
                });

                return;
            }
        });

        eleDbTbls.on('change', '[data-toggle="acOp"]', function() {
            let bl = $(this).val() == 'optimize';
            $('[data-toggle="acType"]', eleDbTbls).prop('disabled', bl);
            $('[data-toggle="acExt"]', eleDbTbls).prop('disabled', bl);
        });
    }
});
