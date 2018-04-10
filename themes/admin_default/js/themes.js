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
                data: "position=" + positionlist + "&theme1=" + theme1 + "&theme2=" + theme2,
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
                data: "themename=" + themename + "&module_file=" + module_file + "&" + nv_fc_variable + "=package_theme_module",
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
        var theme = $(this).attr("title");
        $.ajax({
            type: "POST",
            url: MODULE_URL + "=activatetheme",
            data: "theme=" + theme,
            success: function(data) {
                if (data != "OK_" + theme) {
                    alert(data);
                }
                window.location = script_name + "?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=" + nv_module_name;
            }
        });
    });
    $("a.delete").click(function() {
        var theme = $(this).attr("title");
        if (confirm(LANG.theme_delete_confirm + theme + " ?")) {
            $.ajax({
                type: "POST",
                url: MODULE_URL + "=deletetheme",
                data: "theme=" + theme,
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
                var clipboard = new Clipboard('#' + btnid);
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
                        var clipboard = new Clipboard('#' + btnid);
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
        $('#sitemodal').on('hidden.bs.modal', function (e) {
            window.location.href = window.location.href.replace(/#(.*)/, "");
        });

    });

    // Manager block
    $("a.block_content").click(function() {
        var bid = parseInt($(this).attr("title"));
        nv_open_browse(MODULE_URL + "=block_content&selectthemes=" + selectthemes + "&bid=" + bid + "&blockredirect=" + blockredirect, "ChangeBlock", 800, 500, "resizable=no,scrollbars=yes,toolbar=no,location=no,status=no");
    });
    $("select.order").change(function() {
        $("select.order").attr({
            "disabled": ""
        });
        var order = $(this).val();
        var bid = $(this).attr("title");
        $.ajax({
            type: "POST",
            url: MODULE_URL + "=blocks_change_order_group",
            data: "order=" + order + "&bid=" + bid,
            success: function(data) {
                window.location = MODULE_URL + "=blocks";
            }
        });
    });
    $("select[name=module]").change(function() {
        var module = $(this).val();
        window.location = MODULE_URL + "=blocks_func&module=" + module;
    });
    $("a.delete_block").click(function() {
        var bid = parseInt($(this).attr("title"));
        if (bid > 0 && confirm(LANG.block_delete_per_confirm)) {
            $.post(MODULE_URL + "=blocks_del", "bid=" + bid, function(theResponse) {
                alert(theResponse);
                window.location = MODULE_URL + "=blocks";
            });
        }
    });
    $("a.block_weight").click(function() {
        if (confirm(LANG.block_weight_confirm)) {
            $.post(MODULE_URL + "=blocks_reset_order", "checkss=" + blockcheckss, function(theResponse) {
                alert(theResponse);
                window.location = MODULE_URL + "=blocks";
            });
        }
    });
    $("a.delete_group").click(function() {
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
                    window.location = MODULE_URL + "=blocks";
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
    $("select[name=listpos]").change(function() {
        var pos = $(this).val();
        var bid = $(this).attr("title");
        $.ajax({
            type: "POST",
            url: MODULE_URL + "=blocks_change_pos",
            data: "bid=" + bid + "&pos=" + pos,
            success: function(data) {
                alert(data);
                window.location = MODULE_URL + "=blocks";
            }
        });
    });

    // Block funcs
    $("a.block_content_fucs").click(function() {
        var bid = parseInt($(this).attr("title"));
        nv_open_browse(MODULE_URL + "=block_content&bid=" + bid + "&blockredirect=" + blockredirect, "ChangeBlock", 800, 500, "resizable=no,scrollbars=yes,toolbar=no,location=no,status=no");
    });
    $("select[name=function]").change(function() {
        var module = $("select[name=module]").val();
        var func = $(this).val();
        window.location = MODULE_URL + "=blocks_func&module=" + module + "&func=" + func;
    });
    $("select.order").change(function() {
        $("select.order").attr({
            "disabled": ""
        });
        var order = $(this).val();
        var bid = $(this).attr("title");
        $.ajax({
            type: "POST",
            url: MODULE_URL + "=blocks_change_order",
            data: "func_id=" + func_id + "&order=" + order + "&bid=" + bid,
            success: function(data) {
                window.location = MODULE_URL + "=blocks_func&func=" + func_id + "&module=" + selectedmodule;
            }
        });
    });
    $("a.delete_block_fucs").click(function() {
        var bid = parseInt($(this).attr("title"));
        if (bid > 0 && confirm(LANG.block_delete_per_confirm)) {
            $.post(MODULE_URL + "=blocks_del", "bid=" + bid, function(theResponse) {
                alert(theResponse);
                window.location = MODULE_URL + "=blocks_func&func=" + func_id;
            });
        }
    });
    $("a.delete_group_fucs").click(function() {
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
    $("select[name=listpos_funcs]").change(function() {
        var pos = $(this).val();
        var bid = $(this).attr("title");
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
                $this.prop('disabled', false);
                $("input[name=idlist]:checkbox").each(function() {
                    $(this).prop("checked", false);
                });
            }
        });
    });
});
