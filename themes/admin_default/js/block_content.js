/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

var form,
    bid,
    bindDatePicker = function(element) {
        $(element).removeAttr('id').removeClass('hasDatepicker').datepicker({
            dateFormat: "dd/mm/yy",
            changeMonth: true,
            changeYear: true,
            showOtherMonths: true
        })
    },
    file_name_change = function() {
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
            $(".funclist").css('display', '');
            $("#labelmoduletype1").css('display', '');
        } else {
            $("#labelmoduletype1").css('display', 'none');
            $(".funclist").css('display', 'none');
            if (module_type == "theme") {
                if ("undefined" != typeof arr_file) {
                    var arr = arr_file[2].split(".");
                    for (var i = 0; i < arr.length; i++) {
                        $("#idmodule_" + arr[i]).css('display', '');
                    }
                }
            } else {
                $("#idmodule_" + module_type).css('display', '');
            }

            var $radios = $("input:radio[name=all_func]");
            $radios.filter("[value=0]").prop("checked", true);
            $("#shows_all_func").show();
        }

        if (blok_file_name != "") {
            $("#block_config").show();
            $("#block_config").html('<div class="text-center"><img src="' + form.data('load-image') + '"/></div>');
            $.get(script_name + "?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=" + nv_module_name + '&' + nv_fc_variable + "=block_config&bid=" + bid + "&module=" + module_type + "&selectthemes=" + form.data('selectthemes') + "&file_name=" + blok_file_name + "&nocache=" + new Date().getTime(), function(theResponse) {
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
    };

$(function() {
    form = $('#block-content-form');
    bid = $('[name=bid]', form).val();
    file_name_change();

    $('#block-content-form [name=module_type]').on('change', function() {
        var form = $('#block-content-form'),
            module_type = $(this).val(),
            block_id = $('[name=bid]', form);
        $('[name=file_name]', form).html('');
        if (module_type != '') {
            $('#block_config').html('').hide();
            $('[name=file_name]', form).load(script_name + "?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=block_content&bid=" + bid + "&loadBlocks=" + module_type + "&nocache=" + new Date().getTime());
        }
    });

    $("#block-content-form [name=file_name]").on('change', function() {
        file_name_change();
    });

    $("input[name=all_func]").on('click', function() {
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

    $("input[name=leavegroup]").on('click', function() {
        var lv = $("input[name='leavegroup']:checked").val();
        if (lv == "1") {
            var $radios = $("input:radio[name=all_func]");
            $radios.filter("[value=0]").prop("checked", true);
            $("#shows_all_func").show();
        }
    });

    $('[name=checkallmod]').on('click', function(e) {
        e.preventDefault();
        var obj = $('#shows_all_func'),
            notcheck = $('[type=checkbox]:not(:checked)', obj).length;
        $('[type=checkbox]', obj).prop('checked', notcheck)
    });

    $('[name^=func_id]').on('change', function() {
        var item = $(this).parents('.funclist'),
            notcheck = $('[name^=func_id]:not(:checked)', item).length;
        $('.checkmodule', item).prop('checked', !notcheck)
    });

    $('.checkmodule').on('change', function() {
        var item = $(this).parents('.funclist');
        $('[name^=func_id]', item).prop('checked', $(this).prop('checked'))
    });

    $('#block-content-form').on('submit', function(e) {
        e.preventDefault();
        if (typeof CKEDITOR != "undefined") {
            for (var instanceName in CKEDITOR.instances) {
                $('#' + instanceName).val(CKEDITOR.instances[instanceName].getData());
            }
        }
        var that = $(this),
            url = that.attr('action'),
            data = that.serialize();
        $.ajax({
            type: "POST",
            cache: !1,
            url: url,
            data: data,
            dataType: 'JSON',
            success: function(data) {
                alert(data.mess);
                if (data.status == 'OK') {
                    if (data.redirect != '') {
                        window.opener.location.href = data.redirect
                    } else {
                        window.opener.location.href = window.opener.location.href
                    }
                    window.opener.focus();
                    window.close();
                }
            }
        })
    });

    $('#block-content-form [name=dtime_type]').on('change', function(e) {
        var val = $(this).val(),
            form = $('#block-content-form'),
            url = form.attr('action'),
            bid = $('[name=bid]', form).val();
        $.ajax({
            type: "POST",
            url: url,
            data: {
                'get_dtime_details': val,
                'bid': bid
            },
            success: function(data) {
                $('#dtime_details').html(data)
            }
        })
    });

    form.on('click', '.add_dtime', function() {
        var item = $(this).parents('.dtime_details'),
            new_item = item.clone();
        new_item.find('[type=text]').val('');
        new_item.find('option:selected').prop('selected', false);
        $('.date', new_item).each(function() {
            bindDatePicker(this);
        });
        item.after(new_item)
    });

    form.on('click', '.del_dtime', function() {
        var items = $(this).parents('.dtime'),
            count = $('.dtime_details', items).length,
            item = $(this).parents('.dtime_details');
        if (count > 1) {
            item.remove()
        } else {
            item.find('[type=text]').val('');
            item.find('option:selected').prop('selected', false);
        }
    });

});
