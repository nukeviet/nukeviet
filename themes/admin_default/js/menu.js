/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

$(function() {
    // Lấy HTML cho modal thêm/sửa khối menu
    $('#menu-block .add-menu-block, #menu-block .edit-menu-block').on('click', function() {
        $.ajax({
            type: "GET",
            url: $(this).data('url'),
            cache: !1
        }).done(function(a) {
            $('#menu-block-modal').html(a);
            $('#menu-block-modal .modal').modal({
                backdrop: 'static',
                keyboard: false
            }).modal('show')
        })
    });

    // Thêm/sửa khối menu
    $('#menu-block-modal').on('submit', 'form', function(e) {
        e.preventDefault();
        $.ajax({
            type: 'POST',
            cache: !1,
            url: $(this).attr('action'),
            data: $(this).serialize(),
            dataType: 'json',
            success: function(e) {
                if ('error' == e.status) {
                    alert(e.mess)
                } else if ('OK' == e.status) {
                    location.reload()
                }
            }
        })
    });

    // Xóa khối menu
    $('#menu-block .delete-menu-block').on('click', function() {
        if (confirm(nv_is_del_confirm[0])) {
            $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=blocks&nocache=' + new Date().getTime(), 'del=1&id=' + $(this).data('id'), function() {
                location.reload()
            });
        }
    });

    // Thay đổi khối menu
    $('#tools .change-mid').on('change', function() {
        var mid = $(this).val();
        window.location.href = script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&mid=' + mid
    });

    // Thay đổi thứ tự menu
    $('#menulist .chang_weight').on('change', function() {
        var id = $(this).parents('.item').data('id'),
            mid = $('#menulist').data('mid'),
            parentid = $('#menulist').data('parentid'),
            new_weight = $(this).val();
        $.ajax({
            type: "POST",
            url: $('#menulist').attr('action'),
            cache: !1,
            data: 'action=chang_weight&mid=' + mid + '&parentid=' + parentid + '&id=' + id + '&new_weight=' + new_weight
        }).done(function(a) {
            location.reload()
        })
    });

    // Xóa menu
    $('#menulist .item_delete').on('click', function() {
        var item = $(this).parents('.item'),
            id = item.data('id'),
            mid = $('#menulist').data('mid'),
            parentid = $('#menulist').data('parentid'),
            num = parseInt(item.data('num')),
            conf = num ? confirm(cat + num + caton) : confirm(nv_is_del_confirm[0]);
        if (conf) {
            $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&nocache=' + new Date().getTime(), 'action=delete&id=' + id + '&parentid=' + parentid + '&mid=' + mid, function() {
                location.reload()
            });
        }
    });

    // Xóa nhiều menu
    $('#menulist .multi-delete').on('click', function() {
        var mid = $('#menulist').data('mid'),
            parentid = $('#menulist').data('parentid'),
            list = [];
        $('#menulist [name^=idcheck]:checked').each(function() {
            list.push($(this).val())
        });
        if (!list.length) {
            alert($(this).data('error'));
            return !1
        }

        if (confirm(nv_is_del_confirm[0])) {
            $.ajax({
                type: "POST",
                url: $('#menulist').attr('action'),
                cache: !1,
                data: 'action=delete&mid=' + mid + '&parentid=' + parentid + '&idcheck=' + list
            }).done(function(a) {
                location.reload()
            })
        }
    });

    // Thay đổi trạng thái của menu
    $('#menulist .change-active').on('click', function() {
        var id = $(this).parents('.item').data('id'),
            that = $(this);
        that.prop('disabled', true);
        $.ajax({
            type: "POST",
            url: $('#menulist').attr('action'),
            cache: !1,
            data: 'action=change_active&id=' + id
        }).done(function() {
            setTimeout(function() {
                that.prop('disabled', false)
            }, 1000);
        })
    });

    // Reload lại menu
    $('#menulist .menu_reload').on('click', function() {
        if (confirm($('#menulist').data('reload-confirm'))) {
            $.post($('#menulist').attr('action'), 'reload=1&mid=' + $('#menulist').data('mid') + '&id=' + $(this).parents('.item').data('id'), function(res) {
                location.reload()
            });
        }
    });

    $('#tools .add-menu, #menulist .edit-menu').on('click', function() {
        $.ajax({
            type: "GET",
            url: $(this).data('url'),
            cache: !1
        }).done(function(a) {
            $('#edit').html(a);
            $('#edit .modal').modal({
                backdrop: 'static',
                keyboard: false
            }).modal('show')
        })
    });

    // Button chọn hình/icon
    $('#edit').on('click', '.selectimg', function(e) {
        e.preventDefault();
        var area = $(this).data('area'),
            path = $(this).data('path');
        nv_open_browse(script_name + "?" + nv_name_variable + "=upload&popup=1&area=" + area + "&path=" + path + "&type=image&currentpath=" + path, "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
    });

    // Event khi thay đổi module
    $('#edit').on('change', '[name=module_name]', function() {
        var mod_field = $(this).parents('.field'),
            val = $(this).val(),
            subMod = $('#edit [name=func]');
        if (subMod.length) {
            subMod.parents('.field').remove()
        }
        if (val != '') {
            $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&nocache=' + new Date().getTime(), 'action=link_module&module=' + val, function(res) {
                if (res != '') {
                    mod_field.after(res);
                    $('#edit [name=func]').select2()
                }
            });
        }
    });

    // Khi click vào button lấy title theo module/op
    $('#edit').on('click', '.get-title', function() {
        var obj = $('#edit'),
            module = $('[name=module_name]', obj).val(),
            opobj = $('[name=func]', obj),
            op;
        if (opobj.length) {
            op = opobj.val();
            if (op != '') {
                $('[name=title]', obj).val(trim(strip_tags($('[name=func] option[value="' + op + '"]', obj).text())));
                return !1
            }
        }
        if (module != '') {
            $('[name=title]', obj).val(trim(strip_tags($('[name=module_name] option[value=' + module + ']', obj).text())))
            return !1
        }
        $('[name=title]', obj).val('')
    });

    // Khi click vào nút lấy link theo module/op
    $('#edit').on('click', '.get-link', function() {
        var obj = $('#edit'),
            module = $('[name=module_name]', obj).val(),
            opobj = $('[name=func]', obj),
            op;
        if (opobj.length) {
            op = opobj.val();
            if (op != '') {
                $('[name=link]', obj).val(nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + module + "&" + nv_fc_variable + "=" + op);
                return !1
            }
        }
        if (module != '') {
            $('[name=link]', obj).val(nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + module);
            return !1
        }
        $('[name=link]', obj).val('')
    });

    // Khi thay đổi khối menu
    $('#edit').on('change', '[name=item_menu]', function() {
        $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&nocache=' + new Date().getTime(), 'action=link_menu&mid=' + $(this).val() + '&parentid=' + $(this).data('parentid'), function(res) {
            $('#edit [name=parentid]').html(res).select2();
        });
    });

    // Form thêm/sửa menu
    $('#edit').on('submit', 'form', function(e) {
        e.preventDefault();
        var data = $(this).serialize();
        $.ajax({
            type: 'POST',
            cache: !1,
            url: $(this).attr('action'),
            data: data,
            dataType: 'json',
            success: function(e) {
                if ('error' == e.status) {
                    alert(e.mess)
                } else if ('OK' == e.status) {
                    location.reload()
                }
            }
        })
    })
});
