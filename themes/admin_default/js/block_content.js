/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

function checkallmodfirst() {
    $(this).one("click", checkallmodsecond);
    $("input.checkmodule").prop("checked", true);
    $("input[name='func_id[]']:checkbox").each(function() {
        $("input[name='func_id[]']:visible").prop("checked", true);
    });
}

function checkallmodsecond() {
    $(this).one("click", checkallmodfirst);
    $("input.checkmodule").prop("checked", false);
    $("input[name='func_id[]']:checkbox").each(function() {
        $("input[name='func_id[]']:visible").prop("checked", false);
    });
}

function file_name_change() {
    var file_name = $("select[name=file_name]").val();
    var module_type = $("select[name=module_type]").val();

    var blok_file_name = "";
    if (file_name != "") {
        var arr_file = file_name.split("|");
        if (parseInt(arr_file[1]) == 1) {
            blok_file_name = arr_file[0];
        }
    }

    if (file_name.substring(0, 7) == "global.") {
        $(".funclist").css({
            "display": ""
        });
        $("#labelmoduletype1").css({
            "display": ""
        });
    } else {
        $("#labelmoduletype1").css({
            "display": "none"
        });
        $(".funclist").css({
            "display": "none"
        });
        if (module_type == "theme") {
            if ("undefined" != typeof arr_file) {
                var arr = arr_file[2].split(".");
                for (var i = 0; i < arr.length; i++) {
                    $("#idmodule_" + arr[i]).css({
                        "display": "block"
                    });
                }
            }
        } else {
            $("#idmodule_" + module_type).css({
                "display": "block"
            });
        }

        var $radios = $("input:radio[name=all_func]");
        $radios.filter("[value=0]").prop("checked", true);
        $("#shows_all_func").show();
    }

    if (blok_file_name != "") {
        $("#block_config").show();
        $("#block_config").html(htmlload);
        $.get(script_name + "?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=" + nv_module_name + '&' + nv_lang_variable + "=" + nv_lang_data + "&" + nv_fc_variable + "=block_config&bid=" + bid + "&module=" + module_type + "&selectthemes=" + selectthemes + "&file_name=" + blok_file_name + "&nocache=" + new Date().getTime(), function(theResponse) {
            if (theResponse.length > 10) {
                theResponse = theResponse.replace("<head/><tr><td", "<tr><td"); //fix for Centmin Mod 1.2.3-eva2000.07
                $("#block_config").html(theResponse);
            } else {
                $("#block_config").hide();
            }
        });
    } else {
        $("#block_config").hide();
    }
}

$(function() {
    $("select[name=file_name]").load(script_name + "?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=" + nv_module_name + '&' + nv_lang_variable + "=" + nv_lang_data + "&" + nv_fc_variable + "=loadblocks&selectthemes=" + selectthemes + "&module=" + bid_module + "&bid=" + bid + "&nocache=" + new Date().getTime(), function() {
        file_name_change();
    });

    $("#exp_time").datepicker({
        showOn: "both",
        dateFormat: "dd/mm/yy",
        changeMonth: true,
        changeYear: true,
        showOtherMonths: true,
        buttonImage: "",
        buttonImageOnly: true,
        buttonText: ""
    });

    $("#exp_time_btn").click(function() {
        $("#exp_time").focus();
    });

    $("select[name=module_type]").change(function() {
        var module_type = $("select[name=module_type]").val();
        $("select[name=file_name]").html("");
        if (module_type != "") {
            $("#block_config").html("");
            $("#block_config").hide();
            $("select[name=file_name]").load(script_name + "?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=" + nv_module_name + "&" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_fc_variable + "=loadblocks&bid=" + bid + "&module=" + module_type + "&nocache=" + new Date().getTime());
        }
    });

    $("select[name=file_name]").change(function() {
        file_name_change();
    });

    $("input[name=all_func]").click(function() {
        var module_type = $("select[name=module_type]").val();
        var af = $(this).val();
        if (af == "0" && module_type != "global") {
            $("#shows_all_func").show();
        } else if (module_type == "global" && af == 0) {
            $("#shows_all_func").show();
        } else if (af == 1) {
            $("#shows_all_func").hide();
        }
    });

    $("input[name=leavegroup]").click(function() {
        var lv = $("input[name='leavegroup']:checked").val();
        if (lv == "1") {
            var $radios = $("input:radio[name=all_func]");
            $radios.filter("[value=0]").prop("checked", true);
            $("#shows_all_func").show();
        }
    });

    $('[name="checkallmod"]').one("click", checkallmodfirst);

    $("input[name='func_id[]']:checkbox").change(function() {
        var numfuc = $("#" + $(this).parent().parent().parent().attr("id") + " input[name='func_id[]']:checkbox").length;
        var fuccheck = $("#" + $(this).parent().parent().parent().attr("id") + " input[name='func_id[]']:checkbox:checked").length;
        if (fuccheck != numfuc) {
            $("#" + $(this).parent().parent().parent().attr("id") + " .checkmodule").prop("checked", false);
        } else if (numfuc == fuccheck) {
            $("#" + $(this).parent().parent().parent().attr("id") + " .checkmodule").prop("checked", true);
        }
    });

    $("input.checkmodule").change(function() {
        $("#idmodule_" + $(this).attr('value') + " input[name='func_id[]']:checkbox").prop("checked", $(this).prop('checked'));
    });

    $("input[name=confirm]").click(function() {
        var leavegroup = $("input[name=leavegroup]").is(":checked") ? 1 : 0;
        var all_func = $("input[name='all_func']:checked").val();
        if (all_func == 0) {
            var funcid = [];
            $("input[name='func_id[]']:checked").each(function() {
                funcid.push($(this).val());
            });
            if (funcid.length < 1) {
                alert(lang_block_no_func);
                return false;
            }
        }
    });
});
