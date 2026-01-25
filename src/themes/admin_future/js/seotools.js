/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

$(function() {
    // Lưu cấu hình Dữ liệu có cấu trúc, cả form và từng giá trị
    $('#strdata').on('submit', function(e) {
        e.preventDefault();
        var url = $(this).attr('action'),
            data = $(this).serialize();
        $.ajax({
            type: 'POST',
            cache: !1,
            url: url,
            data: data,
            dataType: "json",
            success: function() {},
            error: function(xhr, text, err) {
                console.log(xhr, text, err);
                nvToast(err, 'error');
            }
        })
    });
    $('#strdata .autosubmit').on('change', function() {
        var that = $(this),
            form = that.closest('form'),
            url = form.attr('action'),
            name = that.attr('name'),
            checkss = $('[name=checkss]', form).val(),
            val = that.is(':checked') ? 1 : 0;
        that.prop('disabled', true);
        $.ajax({
            type: 'POST',
            cache: !1,
            url: url,
            data: {
                'name': name,
                'val': val,
                'checkss': checkss
            },
            dataType: "json",
            success: function(result) {
                that.prop('disabled', false);
                if ('error' == result.status) {
                    nvToast(result.mess, 'error');
                    that.prop('checked', !val);
                }
            },
            error: function(xhr, text, err) {
                console.log(xhr, text, err);
                nvToast(err, 'error');
                that.prop('disabled', false);
                that.prop('checked', !val);
            }
        })
    });

    // Lấy dữ liệu mẫu json thông tin doanh nghiệp
    $('[data-toggle=sample_data]').on('click', function(e) {
        e.preventDefault();
        let btn = $(this);
        let icon = $('i', btn);
        if (icon.is('.fa-spinner')) {
            return;
        }
        var url = $(this).data('url'),
            form = $(this).closest('form');
        icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
        $.ajax({
            type: 'POST',
            cache: !1,
            url: url,
            data: 'sample_data=1',
            success: function(result) {
                icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                $('[name=jsondata]', form).val(result);
            },
            error: function(xhr, text, err) {
                icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                console.log(xhr, text, err);
                nvToast(err, 'error');
            }
        });
    });

    // Xóa dữ liệu mẫu thông tin doanh nghiệp
    $('[data-toggle=lbinf_delete]').on('click', function(e) {
        e.preventDefault();
        let btn = $(this);
        let icon = $('i', btn);
        if (icon.is('.fa-spinner')) {
            return;
        }

        var url = $(this).data('url');
        nvConfirm($(this).data('confirm'), () => {
            icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
            $.ajax({
                type: 'POST',
                cache: !1,
                url: url,
                data: 'lbinf_delete=1',
                success: function(result) {
                    window.location.href = result
                },
                error: function(xhr, text, err) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    console.log(xhr, text, err);
                    nvToast(err, 'error');
                }
            });
        });
    });

    // Click mở modal tải lên logo
    let mdLogo = $('#mdUploadLogo');
    $('#organization_logo').on('click', function(e) {
        e.preventDefault();
        bootstrap.Modal.getOrCreateInstance(mdLogo[0]).show();
    });
    mdLogo.on('shown.bs.modal', function() {
        UAV.init();
    });
    mdLogo.on('hide.bs.modal', function() {
        location.reload();
    });
    let mdLogoRes = $('#organlogo-uploaded');
    if (mdLogoRes.length) {
        try {
            let jso = JSON.parse(trim(mdLogoRes.html()));
            if (jso.success) {
                parent.location.reload();
                return;
            }
            window.parent.postMessage(jso.error, location.origin);
        } catch (error) {
            window.parent.postMessage(error, location.origin);
        }
    }
    if (mdLogo.length) {
        window.addEventListener("message", function(event) {
            if (event.origin !== location.origin) {
                return;
            }
            nvToast(event.data, 'error');
        });
    }

    // Xóa biểu trưng tổ chức
    $('#organization_logo_del').on('click', function(e) {
        e.preventDefault();
        let btn = $(this);
        let icon = $('i', btn);
        if (icon.is('.fa-spinner')) {
            return;
        }
        var url = $(this).closest('form').attr('action');
        icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
        $.ajax({
            type: 'POST',
            cache: !1,
            url: url,
            data: 'logodel=1',
            dataType: "json",
            success: function() {
                location.reload();
            },
            error: function(xhr, text, err) {
                icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                console.log(xhr, text, err);
                nvToast(err, 'error');
            }
        });
    });

    // Ẩn báo lỗi khi bật tắt link-tags
    $('[name^=opensearch_link]').on('change', function() {
        let ctn = $(this).closest('ul');
        let it = $('[data-sarea="' + $(this).attr('value') + '"]', ctn);
        $('.is-invalid', it).removeClass('is-invalid');
    });

    // Thêm thuộc tính linktags
    $('[data-toggle="addLinkTagsAttr"]').on('click', function() {
        let tbl = $(this).closest('table');
        let ctn = $('tbody', tbl);
        $('<tr>' + $('.sample', ctn).html() + '</tr>').insertBefore($('.sample', ctn));
    });

    // Submit form thêm thẻ link
    $('[data-toggle="formLinkTags"]').on('submit', function(e) {
        e.preventDefault();
        let form = $(this);
        let relVal = $('.rel-val', form).val();
        relVal = trim(relVal);
        $('.rel-val', form).val(relVal);
        if ('' == relVal) {
            nvToast(form.data('error'), 'error');
            $('.rel-val', form).focus();
            return;
        }
        let data = $(form).serialize();
        $('input,button', form).prop('disabled', true);
        $.ajax({
            type: $(form).prop("method"),
            cache: !1,
            url: $(form).prop("action"),
            data: data,
            dataType: "json",
            success: function(e) {
                if ("error" == e.status) {
                    nvToast(e.mess, 'error');
                    $('input,button', form).prop('disabled', false);
                    return;
                }
                location.reload();
            },
            error: function(xhr, text, err) {
                $('input,button', form).prop('disabled', false);
                console.log(xhr, text, err);
                nvToast(err, 'error');
            }
        });
    });

    // Xóa thẻ link
    $('[data-toggle="delLinkTagsAttr"]').on('click', function(e) {
        e.preventDefault();
        let btn = $(this);
        let icon = $('i', btn);
        if (icon.is('.fa-spinner')) {
            return;
        }
        let form = btn.closest('form');
        nvConfirm(btn.data('message'), () => {
            icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
            $.ajax({
                type: $(form).prop("method"),
                cache: !1,
                url: $(form).prop("action"),
                data: {'key':$('[name=key]',form).val(), 'del':1, 'checkss': $('[name=checkss]',form).val()},
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

    // Thêm dòng meta-tag
    $('#metatags-manage').on('click', '.add-meta-tag', function() {
        var item = $(this).closest('.item'),
            newitem = item.clone();
        $('[name^=metaGroupsName] option:selected', newitem).prop('selected', false);
        $('[name^=metaGroupsValue], [name^=metaContents]', newitem).val('');
        $('.metaGroupsValue-opt', newitem).text('');
        item.after(newitem);
    });
    // Xóa dòng meta-tag
    $('#metatags-manage').on('click', '.del-meta-tag', function() {
        var items = $(this).closest('.items'),
            item = $(this).closest('.item');
        if ($('.item', items).length > 1) {
            item.remove();
        } else {
            $('[name^=metaGroupsName] option:selected', item).prop('selected', false);
            $('[name^=metaGroupsValue], [name^=metaContents]', item).val('');
            $('.metaGroupsValue-opt', item).text('');
        }
    });
    // Chọn mục nhóm meta từ danh sách đổ xuống
    $('#metatags-manage').on('click', '.groupvalue', function(e) {
        e.preventDefault();
        var item = $(this).closest('.item');
        $('[name^=metaGroupsValue]', item).val($(this).text());
    });
    // Chọn mục nội dung meta từ danh sách đổ xuống
    $('#metatags-manage').on('click', '.metacontent', function(e) {
        e.preventDefault();
        var item = $(this).closest('.item'),
            val = $('[name^=metaContents]', item).val() + $(this).text();
        $('[name^=metaContents]', item).val(val);
    });
    // Lọc tên của metatag
    $('[name^=metaGroupsValue]').on('input', function() {
        $(this).val($(this).val().replace(/[^a-zA-Z0-9-_.:]+/g, ''));
    });
    // Các meta dựng sẵn
    $('#metatags-manage').on('show.bs.dropdown', '.metaGroupsValue-dropdown', function() {
        var item = $(this).closest('.item'),
            metaGroupsName = $('[name^=metaGroupsName]', item).val(),
            id = (metaGroupsName == 'name') ? 'meta-name-list' : (metaGroupsName == 'property' ? 'meta-property-list' : 'meta-http-equiv-list');
        $('.metaGroupsValue-opt', this).html($('#' + id).html());
    });

    // Thêm dòng robots
    $('#robots-manage').on('click', '[data-toggle="robot_line_add"]', function() {
        var item = $(this).closest('.item'),
            newitem = item.clone();
        $('[name^=optionother] option:selected', newitem).prop('selected', false);
        $('[name^=fileother]', newitem).val('');
        item.after(newitem);
    });

    // Xóa dòng robots
    $('#robots-manage').on('click', '[data-toggle="robot_line_delete"]', function() {
        var items = $(this).closest('.items'),
            item = $(this).closest('.item');
        if ($('.item', items).length > 1) {
            item.remove();
        } else {
            $('[name^=optionother] option:selected', item).prop('selected', false);
            $('[name^=fileother]', item).val('');
        }
    });
});
