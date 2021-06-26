/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

(function($) {
    $.fn.tshift = function() {
        var start = 0;
        var checkboxes = $("#" + this.attr('id') + " :checkbox");
        checkboxes.on("click", function(event) {
            if (this.checked) {
                if (event.shiftKey) {
                    end = checkboxes.index(this);
                    if (end < start) {
                        end = start;
                        start = checkboxes.index(this);
                    }
                    checkboxes.each(function(index) {
                        if (index >= start && index < end) {
                            this.checked = true;
                        }
                    });
                }
                start = checkboxes.index(this);
            }
        });
        return this;
    };
})(jQuery);

function nv_checkForm() {
    var op_name = $("#op_name").val();
    var type_name = document.getElementById('type_name');
    var ext_name = document.getElementById('ext_name');

    if (op_name == 'optimize') {
        type_name.disabled = true;
        ext_name.disabled = true;
    } else {
        type_name.disabled = false;
        ext_name.disabled = false;
    }
}

function nv_chsubmit(oForm, cbName) {
    var op_name = $("#op_name").val();
    if (op_name == 'optimize') {
        var tabs = "";
        for (var i = 0; i < oForm[cbName].length; i++) {
            if (oForm[cbName][i].checked) {
                tabs += (tabs != "") ? "," : "";
                tabs += oForm[cbName][i].value;
            }
        }

        var subm_form = document.getElementById('subm_form');
        subm_form.disabled = true;

        $.post(oForm.action + '&nocache=' + new Date().getTime(), nv_fc_variable + '=' + op_name + '&tables=' + tabs, function(res) {
            alert(res);
            nv_show_dbtables();
            return;
        });
    } else {
        oForm.submit();
    }
    return;
}

function nv_show_dbtables() {
    $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=main&nocache=' + new Date().getTime(), 'show_tabs=1', function(res) {
        $("#show_tables").html(res);
    });
}

function nv_show_highlight(tp) {
    $.post(window.location.href + '&nocache=' + new Date().getTime(), 'show_highlight=' + tp, function(res) {
        $("#my_highlight").html(res);
    });
    return false;
}

function nv_delete_sampledata(sname, checkss) {
    if (confirm(nv_is_del_confirm[0])) {
        $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=sampledata&nocache=' + new Date().getTime(), 'delete=' + checkss + '&sname=' + encodeURIComponent(sname), function(res) {
            window.location.reload(true);
        });
    }
    return false;
}

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
                $('[type="submit"] .lo', $this).hide();
                $('[name="delifexists"]', $this).val('1');
            }
            if (res.reload) {
                setTimeout(function() {
                    window.location.reload(true);
                }, 5000);
            }
        }
    }, 'json').fail(function(e) {
        $('<p class="text-danger">' + $('#sampledataarea').data('errsys') + '</p>').insertAfter('#spdresulttop');
    });
}

$(function() {
    $('#sampledataarea form').submit(function(e) {
        e.preventDefault();
        var $this = $(this);
        var sname = encodeURIComponent($('[name="sample_name"]', $this).val());
        var delifexists = parseInt($('[name="delifexists"]', $this).val());
        $('[type="submit"]', $this).prop('disabled', true);
        $('[name="sample_name"]', $this).prop('disabled', true);
        $('[type="submit"] .lo', $this).show();
        $('<p class="text-info">' + $('#sampledataarea').data('init') + '</p>').insertAfter('#spdresulttop');
        $('#spdresult').show();

        var url = script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=sampledata&startwrite=1';
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
});
