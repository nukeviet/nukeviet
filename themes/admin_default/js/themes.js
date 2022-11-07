/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

// Config logo
if (typeof(LANG) == 'undefined') {
    var LANG = {};
}
var MODULE_URL = script_name + "?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable;

$(document).ready(function() {
    // Copy blocks
    $("select[name=theme1]").change(function() {
        var theme1 = $(this).val();
        var theme2 = $("select[name=theme2]").val();
        if (theme2 != 0 && theme1 != 0 && theme1 != theme2) {
            $("#loadposition").html('<img src="' + nv_base_siteurl + 'assets/images/load_bar.gif" alt="" />' + LANG.autoinstall_package_processing);
            $("#loadposition").load(MODULE_URL + "=loadposition&theme2=" + theme2 + "&theme1=" + theme1);
        } else {
            $("#loadposition").html("");
        }
    });
    $("select[name=theme2]").change(function() {
        var theme2 = $(this).val();
        var theme1 = $("select[name=theme1]").val();
        if (theme2 != 0 && theme1 != 0 && theme1 != theme2) {
            $("#loadposition").html('<img src="' + nv_base_siteurl + 'assets/images/load_bar.gif" alt="" />' + LANG.autoinstall_package_processing);
            $("#loadposition").load(MODULE_URL + "=loadposition&theme2=" + theme2 + "&theme1=" + theme1);
        } else {
            $("#loadposition").html("");
        }
    });
    $("input[name=continue]").click(function() {
        var theme1 = $("select[name=theme1]").val();
        var theme2 = $("select[name=theme2]").val();
        var positionlist = [];
        $('input[name="position[]"]:checked').each(function() {
            positionlist.push($(this).val());
        });
        if (positionlist.length < 1) {
            alert(LANG.xcopyblock_no_position);
            return false;
        } else {
            $("#loadposition").html('<img src="' + nv_base_siteurl + 'assets/images/load_bar.gif" alt="" />' + LANG.autoinstall_package_processing);
            $.ajax({
                type: "POST",
                url: MODULE_URL + "=xcopyprocess",
                data: "position=" + positionlist + "&theme1=" + theme1 + "&theme2=" + theme2 + "&checkss=" + $("input[name=checkss]").val(),
                success: function(data) {
                    $("#loadposition").html(data);
                }
            });
        }
    });
    $('[data-toggle="checkallpos"]').click(function(e) {
        e.preventDefault();
        var target = $(this).data('target');
        var checked = ($(target).length == $(target + ':checked').length ? false : true);
        $(target).prop("checked", checked);
    });

    // Package theme module
    $("input[name=continue_ptm]").click(function() {
        var themename = $("select[name=themename]").val();
        module_file = '';
        $("input[name='module_file[]']:checked").each(function() {
            module_file = module_file + ',' + $(this).val();
        });
        if (themename != 0 && module_file != '') {
            $("#message").html('<img src="' + nv_base_siteurl + 'assets/images/load_bar.gif" alt="" />' + LANG.autoinstall_package_processing);
            $("#message").fadeIn();
            $("input[name=continue_ptm]").attr("disabled", "disabled");
            $("#step1").slideUp();
            $.ajax({
                type: "POST",
                url: script_name + "?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=" + nv_module_name,
                data: "themename=" + themename + "&module_file=" + module_file + "&" + nv_fc_variable + "=package_theme_module&checkss=" + $("input[name=checkss]").val(),
                success: function(data) {
                    $("input[name=continue_ptm]").removeAttr("disabled");
                    $("#message").html(data);
                }
            });
        } else {
            alert(LANG.package_noselect_module_theme);
            return false;
        }
    });

    // Main theme
    $("a.activate").click(function() {
        var theme = $(this).data("title");
        $.ajax({
            type: "POST",
            url: MODULE_URL + "=activatetheme",
            data: "theme=" + theme + "&checkss=" + $(this).data("checkss"),
            success: function(data) {
                if (data != "OK_" + theme) {
                    alert(data);
                }
                window.location = script_name + "?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=" + nv_module_name;
            }
        });
    });
    $("a.delete").click(function() {
        var theme = $(this).data("title");
        if (confirm(LANG.theme_delete_confirm + theme + " ?")) {
            $.ajax({
                type: "POST",
                url: MODULE_URL + "=deletetheme",
                data: "theme=" + theme + "&checkss=" + $(this).data("checkss"),
                success: function(data) {
                    alert(data);
                    window.location = script_name + "?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=" + nv_module_name;
                }
            });
        }
    });
    $('[data-toggle="viewthemedetail"]').click(function(e) {
        e.preventDefault();
        var target = $(this).data('target');
        modalShow($(target).attr('title'), $(target).html(), function(e) {
            var btn = $(e).find('.preview-link-btn');
            if (btn.is(':visible')) {
                var btnid = 'btnpreviewtheme-' + (new Date().getTime());
                btn.attr('id', btnid);
                var clipboard = new ClipboardJS('#' + btnid);
                clipboard.on('success', function(e) {
                    $(e.trigger).tooltip('show');
                });
            }
            btn.mouseleave(function() {
                $(this).tooltip('destroy');
            });
        });
    });
    $(document).delegate('.selectedfocus', 'focus', function(e) {
        $(this).select();
    });
    $(document).delegate('[data-toggle="previewtheme"]', 'click', function(e) {
        e.preventDefault();
        var $this = $(this);
        var $ctn = $this.parent().parent().parent();
        if ($this.find('i').is(':visible')) {
            return false;
        }
        $this.find('i').removeClass('hidden');
        $.ajax({
            type: "POST",
            url: MODULE_URL + "=main",
            data: "togglepreviewtheme=1&theme=" + $this.data('value'),
            dataType: 'JSON',
            success: function(data) {
                if (data.status == 'SUCCESS') {
                    $this.find('span').html(data.spantext);
                    if (data.mode == 'enable') {
                        $('.preview-label', $ctn).show();
                        $('.preview-link', $ctn).removeClass('hidden');
                        $('.preview-link', $ctn).find('[type="text"]').val(data.link);
                        var btn = $('.preview-link', $ctn).find('.btn');
                        btn.attr('data-clipboard-text', data.link);
                        var btnid = 'btnpreviewtheme-' + (new Date().getTime());
                        btn.attr('id', btnid);
                        var clipboard = new ClipboardJS('#' + btnid);
                        clipboard.on('success', function(e) {
                            $(e.trigger).tooltip('show');
                        });
                    } else {
                        $('.preview-label', $ctn).hide();
                        $('.preview-link', $ctn).addClass('hidden');
                    }
                }
                $this.find('i').addClass('hidden');
            }
        });
        $('#sitemodal').on('hidden.bs.modal', function(e) {
            window.location.href = window.location.href.replace(/#(.*)/, "");
        });

    });

    if ($('#blocklist').length) {
        var blocklist = $('#blocklist');
        // Nếu chọn module của block
        $('[name=module]', blocklist).on('change', function() {
            var module = $(this).val();
            window.location.href = MODULE_URL + "=blocks&module=" + module;
        });
        // Nếu chọn function của block
        $('[name=function]', blocklist).on('change', function() {
            var module = $('[name=module]', blocklist).val(),
                func = $(this).val();
            window.location = MODULE_URL + "=blocks&module=" + module + "&func=" + func;
        });
        // Chọn tất cả/bỏ chọn tất cả
        $('[type=checkbox].checkall', blocklist).on('change', function() {
            $('[type=checkbox].checkall, [type=checkbox].checkitem', blocklist).prop('checked', $(this).prop('checked'))
        });
        $('[type=checkbox].checkitem', blocklist).on('change', function() {
            $('[type=checkbox].checkall', blocklist).prop('checked', !$('[type=checkbox].checkitem:not(:checked)', blocklist).length)
        });
        // Thay đổi thứ tự block
        $('.order, .order_func', blocklist).on('change', function() {
            var order = $(this).val(),
                bid = $(this).parents('.item').data('id');
            $.ajax({
                type: "POST",
                url: MODULE_URL + ($(this).is('.order') ? '=blocks_change_order_group' : '=blocks_change_order'),
                data: ($(this).is('.order_func') ? 'func_id=' + func_id + '&' : '') + 'order=' + order + '&bid=' + bid + '&checkss=' + blockcheckss,
                success: function(data) {
                    window.location.href = window.location.href
                }
            });
        });
        // Bật modal thay đổi vị trí block
        $('.change_pos_block', blocklist).on('click', function() {
            var item = $(this).parents('.item');
            $('.modal', item).modal('show')
        });
        // Thay đổi vị trí block
        $('[name=listpos]', blocklist).on('change', function() {
            var pos = $(this).val(),
                bid = $(this).parents('.item').data('id'),
                conf = func_id ? confirm(LANG.block_change_pos_warning + ' ' + bid + '. ' + LANG.block_change_pos_warning2) : true;
            if (conf) {
                $.ajax({
                    type: "POST",
                    url: MODULE_URL + "=blocks_change_pos",
                    data: "bid=" + bid + "&pos=" + pos + "&checkss=" + blockcheckss,
                    success: function(data) {
                        window.location.href = window.location.href
                    }
                });
            } else {
                $(this).val($(this).data('default'));
                $(this).parents('.modal').modal('hide')
            }
        });
        // Nút hiển thị danh sách vị trí hiển thị của block
        $('.viewlist', blocklist).on('click', function() {
            $(this).hide().parents('.item').find('.funclist').show()
        });
        // Bật/tắt block
        $('.act', blocklist).on('change', function() {
            var that = $(this),
                item = that.parents('.item'),
                bid = item.data('id'),
                checkss = item.data('checkss');
            that.prop('disabled', true);
            $.post(MODULE_URL + "=block_change_show", "bid=" + bid + "&checkss=" + checkss, function() {
                setTimeout(function() {
                    that.prop('disabled', false)
                }, 2000)
            })
        });
        // Thêm/sửa block
        $('.block_content', blocklist).on('click', function() {
            if ($(this).is('.add')) {
                nv_open_browse(MODULE_URL + "=block_content&selectthemes=" + selectthemes + "&blockredirect=" + blockredirect, "ChangeBlock", 800, 500, "resizable=no,scrollbars=yes,toolbar=no,location=no,status=no");
            } else {
                var bid = parseInt($(this).parents('.item').data("id"));
                nv_open_browse(MODULE_URL + "=block_content&selectthemes=" + selectthemes + "&bid=" + bid + "&blockredirect=" + blockredirect, "ChangeBlock", 800, 500, "resizable=no,scrollbars=yes,toolbar=no,location=no,status=no");
            }
        });
        // Xóa block
        $('.delete_block', blocklist).on('click', function() {
            var bid = parseInt($(this).parents('.item').data('id'));
            if (bid > 0 && confirm(LANG.block_delete_per_confirm)) {
                $.post(MODULE_URL + "=blocks_del", "bid=" + bid + "&checkss=" + blockcheckss, function(theResponse) {
                    window.location.href = window.location.href
                });
            }
        });
        $('.bl_action', blocklist).on('click', function(e) {
            e.preventDefault();
            var action = $('[name=action]', blocklist).val();
            var list = [];
            $('[name=idlist]:checked', blocklist).each(function() {
                list.push($(this).val())
            });
            if (list.length < 1) {
                alert(LANG.block_error_noblock);
                return false
            }
            // Chọn thiết bị hiển thị của nhiều block
            if (action == 'blocks_show_device') {
                $('#modal_show_device').modal('toggle')
            }
            // Xóa nhiều block
            else if (action == 'delete_group') {
                if (confirm(LANG.block_delete_confirm)) {
                    $.ajax({
                        type: "POST",
                        url: MODULE_URL + "=blocks_del_group",
                        data: "list=" + list + "&checkss=" + blockcheckss,
                        success: function(data) {
                            alert(data);
                            window.location = MODULE_URL + "=blocks";
                        }
                    })
                }
            }
            // Bật nhiều block
            else if (action == 'bls_act') {
                $('[name=action],.bl_action', blocklist).prop('disabled', true);
                $.post(MODULE_URL + "=block_change_show", "multi=1&list=" + list + "&checkss=" + blockcheckss, function() {
                    $('[name=idlist]:checked', blocklist).each(function() {
                        var item = $(this).parents('.item');
                        $('[name=act]', item).val('1')
                    });
                    setTimeout(function() {
                        $('[name=action],.bl_action', blocklist).prop('disabled', false)
                    }, 2000)
                })
            }
            // Tắt nhiều block
            else if (action == 'bls_deact') {
                $('[name=action],.bl_action', blocklist).prop('disabled', true);
                $.post(MODULE_URL + "=block_change_show", "multi=0&list=" + list + "&checkss=" + blockcheckss, function() {
                    $('[name=idlist]:checked', blocklist).each(function() {
                        var item = $(this).parents('.item');
                        $('[name=act]', item).val('0')
                    });
                    setTimeout(function() {
                        $('[name=action],.bl_action', blocklist).prop('disabled', false)
                    }, 2000)
                })
            }
        });
        // Đặt lại thứ tự block
        $('.block_weight', blocklist).on('click', function() {
            if (confirm(LANG.block_weight_confirm)) {
                $.post(MODULE_URL + "=blocks_reset_order", "checkss=" + blockcheckss, function(theResponse) {
                    window.location.href = window.location.href;
                })
            }
        });
        // Form Thay đổi thiết bị hiển thị
        $('#modal_show_device .submit', blocklist).on('click', function() {
            var list = [],
                active_device = [];
            $('[name=idlist]:checked', blocklist).each(function() {
                list.push($(this).val())
            });

            $('[name=active_device]:checked', blocklist).each(function() {
                active_device.push($(this).val());
            });

            $.ajax({
                type: "POST",
                url: MODULE_URL + "=blocks_change_active",
                data: "list=" + list + "&active_device=" + active_device + "&selectthemes=" + selectthemes + "&checkss=" + blockcheckss,
                success: function(data) {
                    alert(data);
                    $('#modal_show_device', blocklist).modal('hide');
                }
            });
        });
    }
});
