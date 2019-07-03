/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC ( contact@vinades.vn )
 * @Copyright ( C ) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 1 - 31 - 2010 5 : 12
 */

// Config logo
if (typeof(LANG) == 'undefined') {
    var LANG = {};
}
var MODULE_URL = script_name + "?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable;

$(document).ready(function() {
    // Sao chép block
    function copyLoadThemePos() {
        var theme1 = $("select[name=theme1]").val();
        var theme2 = $("select[name=theme2]").val();

        $("#loadposition").html('');

        if (theme2 != 0 && theme1 != 0 && theme1 != theme2) {
            $('[data-toggle="themeloader"]').removeClass('d-none');
            $("#loadposition").load(MODULE_URL + "=loadposition&theme2=" + theme2 + "&theme1=" + theme1, function() {
                $('[data-toggle="themeloader"]').addClass('d-none');
            });
        } else {
            $('[data-toggle="themeloader"]').addClass('d-none');
        }
    }

    $("select[name=theme1]").change(function() {
        copyLoadThemePos()
    });

    $("select[name=theme2]").change(function() {
        copyLoadThemePos()
    });

    $("button[name=continue]").click(function() {
        var theme1 = $("select[name=theme1]").val();
        var theme2 = $("select[name=theme2]").val();
        var positionlist = [];
        $('input[name="position[]"]:checked').each(function() {
            positionlist.push($(this).val());
        });
        if (positionlist.length < 1) {
            $.gritter.add({
                title: LANG.error,
                text: LANG.xcopyblock_no_position,
                class_name: "color danger"
            });
            return false;
        } else {
            $('[data-toggle="themeloader"]').removeClass('d-none');
            $('[data-toggle="resarea"]').html('').addClass('d-none');
            $("button[name=continue]").prop('disabled', true);

            $.ajax({
                type: "POST",
                url: MODULE_URL + "=xcopyprocess",
                data: "position=" + positionlist + "&theme1=" + theme1 + "&theme2=" + theme2,
                success: function(data) {
                    $('[data-toggle="resarea"]').html(data).removeClass('d-none');
                    $('[data-toggle="themeloader"]').addClass('d-none');
                    $("button[name=continue]").prop('disabled', false);
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

    // Đóng gói giao diện theo module
    $("[name=continue_ptm]").click(function() {
        var btn = $(this);
        var themename = $("select[name=themename]").val();
        module_file = '';
        $("input[name='module_file[]']:checked").each(function() {
            module_file = module_file + ',' + $(this).val();
        });
        if (themename != 0 && module_file != '') {
            btn.prop('disabled', true);
            $('[data-toggle="reshtml"]').html('').addClass('d-none');
            $('[data-toggle="resload"]').removeClass('d-none');
            $('[data-toggle="resarea"]').removeClass('d-none');

            $.ajax({
                type: "POST",
                url: script_name + "?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=" + nv_module_name,
                data: "themename=" + themename + "&module_file=" + module_file + "&" + nv_fc_variable + "=package_theme_module&checksess=" + btn.data('checksess'),
                success: function(data) {
                    btn.prop('disabled', false);
                    $('[data-toggle="resload"]').addClass('d-none');
                    $('[data-toggle="reshtml"]').html(data).removeClass('d-none');
                }
            });
        } else {
            $.gritter.add({
                title: LANG.error,
                text: LANG.package_noselect_module_theme,
                class_name: "color danger"
            });
            return false;
        }
    });

    /*
     * Quản lý giao diện trang chính
     * Kích hoạt
     */
    $("a.activate-theme").click(function(e) {
        e.preventDefault();
        var theme = $(this).data("theme");
        $.ajax({
            type: "POST",
            url: MODULE_URL + "=activatetheme",
            data: "theme=" + theme,
            success: function(data) {
                if (data != "OK_" + theme) {
                    alert(data);
                }
                location.reload();
            }
        });
    });

    /*
     * Quản lý giao diện trang chính
     * Xóa thiết lập
     */
    $("a.delete-theme-setting").click(function(e) {
        e.preventDefault();
        var theme = $(this).data("theme");
        if (confirm(LANG.theme_delete_confirm + theme + " ?")) {
            $.ajax({
                type: "POST",
                url: MODULE_URL + "=deletetheme",
                data: "theme=" + theme,
                success: function(data) {
                    alert(data);
                    location.reload();
                }
            });
        }
    });

    $(document).delegate('.selectedfocus', 'focus', function(e) {
        $(this).select();
    });

    $('.modal-theme-detail').on('shown.bs.modal', function(e) {
        var $this = $(this);
        var btn = $this.find('.preview-link-btn');
        if (btn.is(':visible')) {
            var btnid = 'btnpreviewtheme-' + (new Date().getTime());
            btn.attr('id', btnid);
            var clipboard = new ClipboardJS('#' + btnid);
            clipboard.on('success', function(e) {
                $(e.trigger).tooltip('show');
            });
        }
        btn.mouseleave(function() {
            $(this).tooltip('dispose');
        });
    });

    $(document).delegate('[data-toggle="previewtheme"]', 'click', function(e) {
        e.preventDefault();
        var $this = $(this);
        var $ctn = $this.parent().parent().parent();
        if ($this.find('i').is(':visible')) {
            return false;
        }
        $this.find('i').removeClass('d-none');
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
                        $('.preview-link', $ctn).removeClass('d-none');
                        $('.preview-link', $ctn).find('[type="text"]').val(data.link);
                        var btn = $('.preview-link', $ctn).find('.btn');
                        var btnid = 'btnpreviewtheme-' + (new Date().getTime());
                        btn.attr('id', btnid);
                        var clipboard = new ClipboardJS('#' + btnid);
                        clipboard.on('success', function(e) {
                            $(e.trigger).tooltip('show');
                        });
                    } else {
                        $('.preview-label', $ctn).hide();
                        $('.preview-link', $ctn).addClass('d-none');
                    }
                }
                $this.find('i').addClass('d-none');
            }
        });

        $('.modal-theme-detail').on('hidden.bs.modal', function(e) {
            location.reload();
        });
    });

    // Quản lý block
    $("a.block_content,button.block_content").click(function(e) {
        e.preventDefault();
        var bid = parseInt($(this).data("bid"));
        nv_open_browse(MODULE_URL + "=block_content&selectthemes=" + selectthemes + "&bid=" + bid + "&blockredirect=" + blockredirect, "ChangeBlock", 800, 500, "resizable=no,scrollbars=yes,toolbar=no,location=no,status=no");
    });

    $("select.blockChangeOrder").change(function() {
        var order = $(this).val();
        var bid = $(this).data("bid");
        $("select.blockChangeOrder").prop('disabled', true);
        $.ajax({
            type: "POST",
            url: MODULE_URL + "=blocks_change_order_group",
            data: "order=" + order + "&bid=" + bid,
            success: function(data) {
                location.reload();
            }
        });
    });

    $("select[name=BlockFilterModule]").change(function() {
        var module = $(this).val();
        window.location = MODULE_URL + "=blocks_func&module=" + module;
    });

    $("a.delete_block").click(function(e) {
        e.preventDefault();
        var bid = parseInt($(this).data("bid"));
        if (bid > 0 && confirm(LANG.block_delete_per_confirm)) {
            $.post(MODULE_URL + "=blocks_del", "bid=" + bid, function(theResponse) {
                alert(theResponse);
                location.reload();
            });
        }
    });

    /*
     * Quản lý block
     * Cập nhật lại vị trí các block
     */
    $("button.block_weight").click(function(e) {
        e.preventDefault();
        if (confirm(LANG.block_weight_confirm)) {
            $.post(MODULE_URL + "=blocks_reset_order", "checkss=" + blockcheckss, function(theResponse) {
                alert(theResponse);
                location.reload();
            });
        }
    });

    $("button.delete_group").click(function(e) {
        e.preventDefault();
        var list = [];
        $("input[name=idlist]:checked").each(function() {
            list.push($(this).val());
        });
        if (list.length < 1) {
            alert(LANG.block_error_noblock);
            return false;
        }
        if (confirm(LANG.block_delete_confirm)) {
            $.ajax({
                type: "POST",
                url: MODULE_URL + "=blocks_del_group",
                data: "list=" + list,
                success: function(data) {
                    alert(data);
                    location.reload();
                }
            });
        }
        return false;
    });

    $("#checkall").click(function() {
        $("input[name=idlist]:checkbox").each(function() {
            $(this).prop("checked", true);
        });
    });

    $("#uncheckall").click(function() {
        $("input[name=idlist]:checkbox").each(function() {
            $(this).prop("checked", false);
        });
    });

    $("[name=blockListPos]").change(function() {
        var pos = $(this).val();
        var bid = $(this).data("bid");
        $.ajax({
            type: "POST",
            url: MODULE_URL + "=blocks_change_pos",
            data: "bid=" + bid + "&pos=" + pos,
            success: function(data) {
                alert(data);
                location.reload();
            }
        });
    });

    /*
     * Block theo func
     * Sửa block
     */
    $("a.block_content_fucs,button.block_content_fucs").click(function(e) {
        e.preventDefault();
        var bid = parseInt($(this).data("bid"));
        nv_open_browse(MODULE_URL + "=block_content&bid=" + bid + "&blockredirect=" + blockredirect, "ChangeBlock", 800, 500, "resizable=no,scrollbars=yes,toolbar=no,location=no,status=no");
    });

    $("select[name=BlockFuncFilterFunction]").change(function() {
        var module = $("select[name=BlockFilterModule]").val();
        var func = $(this).val();
        window.location = MODULE_URL + "=blocks_func&module=" + module + "&func=" + func;
    });

    $('select.blockFuncChangeOrder').change(function() {
        var order = $(this).val();
        var bid = $(this).data("bid");
        $('select.blockFuncChangeOrder').prop('disabled', true);
        $.ajax({
            type: "POST",
            url: MODULE_URL + "=blocks_change_order",
            data: "func_id=" + func_id + "&order=" + order + "&bid=" + bid,
            success: function(data) {
                window.location = MODULE_URL + "=blocks_func&func=" + func_id + "&module=" + selectedmodule;
            }
        });
    });

    /*
     * Block theo func
     * Xóa block
     */
    $("a.delete_block_fucs").click(function(e) {
        e.preventDefault();
        var bid = parseInt($(this).data("bid"));
        if (bid > 0 && confirm(LANG.block_delete_per_confirm)) {
            $.post(MODULE_URL + "=blocks_del", "bid=" + bid, function(theResponse) {
                alert(theResponse);
                window.location = MODULE_URL + "=blocks_func&func=" + func_id;
            });
        }
    });

    /*
     * Block theo func
     * Xóa nhiều block
     */
    $("button.delete_group_fucs").click(function(e) {
        e.preventDefault();
        var list = [];
        $("input[name=idlist]:checked").each(function() {
            list.push($(this).val());
        });
        if (list.length < 1) {
            alert(LANG.block_error_noblock);
            return false;
        }
        if (confirm(LANG.block_delete_confirm)) {
            $.ajax({
                type: "POST",
                url: MODULE_URL + "=blocks_del_group",
                data: "list=" + list,
                success: function(data) {
                    alert(data);
                    window.location = MODULE_URL + "=blocks_func&func=" + func_id;
                }
            });
        }
        return false;
    });

    /*
     * Block theo func:
     * Đổi vị trí
     */
    $("select[name=listpos_funcs]").change(function() {
        var pos = $(this).val();
        var bid = $(this).data("bid");
        if (confirm(LANG.block_change_pos_warning + " " + bid + " " + LANG.block_change_pos_warning2)) {
            $.ajax({
                type: "POST",
                url: MODULE_URL + "=blocks_change_pos",
                data: "bid=" + bid + "&pos=" + pos,
                success: function(data) {
                    alert(data);
                    window.location = MODULE_URL + "=blocks_func&func=" + func_id;
                }
            });
        }
    });

    // Config blocks show device
    $('body').delegate('.blocks_show_device', 'click', function(e) {
        var list = [];
        $("input[name=idlist]:checked").each(function() {
            list.push($(this).val());
        });
        if (list.length < 1) {
            alert(LANG.block_error_noblock);
            return false;
        }
        e.preventDefault();
        $('#modal_show_device').data('title', $(this).data('title')).modal('toggle');
    });

    $('#modal_show_device').on('show.bs.modal', function(e) {
        var list = [];
        $("input[name=idlist]:checked").each(function() {
            list.push($(this).val());
        });

        $("input[name=active_device]").prop('checked', false);

        if (list.length > 1) {
            $("input[name=active_device]:first").prop('checked', true);
        } else {
            var bid = list[0];
            var bl = $("input[name=idlist][value='" + bid + "']");
            if (bl.length) {
                var activedevice = bl.data('activedevice').toString().split(',');
                for (var i = 0, j = activedevice.length; i < j; i++) {
                    var device = parseInt(activedevice[i]);
                    if (device >= 1 && device <= 4) {
                        $("#active_device_" + device).prop('checked', true);
                    }
                }
            }
        }
    });

    $('#modal_show_device .submit').click(function() {
        var $this = $(this);
        $this.prop('disabled', true);

        var list = [];
        $("input[name=idlist]:checked").each(function() {
            list.push($(this).val());
        });

        var active_device = [];
        $("input[name=active_device]:checked").each(function() {
            active_device.push($(this).val());
        });

        $.ajax({
            type: "POST",
            url: MODULE_URL + "=blocks_change_active",
            data: "list=" + list + "&active_device=" + active_device,
            success: function(data) {
                alert(data);
                $('#modal_show_device').modal('hide');
                location.reload();
            }
        });
    });
});
