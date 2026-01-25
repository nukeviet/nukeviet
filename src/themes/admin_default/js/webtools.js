/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

var nv_loading = '<div class="text-center"><em class="fa fa-spin fa-spinner fa-2x m-bottom wt-icon-loading"></em></div>';

function start_tooltip() {
    $('[data-toggle="tooltip"]').tooltip();
}

$(document).ready(function() {
    $("body").delegate("#sysUpdRefresh", "click", function() {
        $("#sysUpd").html(nv_loading).load("index.php?" + nv_name_variable + "=webtools&" + nv_fc_variable + "=checkupdate&i=sysUpdRef&num=" + nv_randomPassword(10), function() {
            start_tooltip();
        });
    });
    $("body").delegate("#extUpdRefresh", "click", function() {
        $("#extUpd").html(nv_loading).load("index.php?" + nv_name_variable + "=webtools&" + nv_fc_variable + "=checkupdate&i=extUpdRef&num=" + nv_randomPassword(10), function() {
            start_tooltip();
        });
    });
    $("body").delegate(".ninfo", "click", function() {
        $(".ninfo").each(function() {
            $(this).show()
        });
        $(".wttooltip").each(function() {
            $(this).hide()
        });
        $(this).hide().next(".wttooltip").show();
        return false
    });
    $("body").delegate(".wttooltip", "click", function() {
        $(this).hide().prev(".ninfo").show();
    });

    $('#clearsystem-form').on('submit', function(e) {
        e.preventDefault();
        var that = $(this),
            url = that.attr('action'),
            data = that.serialize(),
            checked_num = $('[name^=deltype]:checked', that).length;
        if (checked_num) {
            $('#pload').show();
            $('#presult, #pnoresult').hide();
            $('input', that).prop('disabled', true);
            $.ajax({
                type: "POST",
                url: url,
                data: data,
                cache: !1,
                success: function(response) {
                    $('input', that).prop('disabled', false);
                    $('[type=checkbox]:checked', that).prop('checked', false);
                    if (response) {
                        $('#presult .contents').html(response);
                        $('#pload, #pnoresult').hide();
                        $('#presult').slideDown();
                    } else {
                        $('#pload, #presult').hide();
                        $('#pnoresult').show();
                    }
                }
            })
        }
    });
    $('#errorfile').on('change', function() {
        var url = $(this).data('url'),
        efile = $(this).val();
        $.ajax({
            type: "POST",
            url: url,
            data: 'errorfile=' + efile,
            cache: !1,
            dataType: "json",
            success: function(response) {
                $('#errorlist').html(response.errorlist);
                $('#error-content .error_file_name').text(response.errorfilename);
                $('#error-content code').html(response.errorfilecontent);
                hljs.debugMode();
                hljs.highlightAll();
            }
        })
    });
    $('#display-mode').on('change', function() {
        var url = $(this).data('url'),
            val = $(this).val();
        $.ajax({
            type: "POST",
            url: url,
            data: 'changemode=1&mode=' + val,
            cache: !1,
            success: function(response) {
                if (val == 'tabular') {
                    $('#errorlist').show();
                    $('#error-content').hide()
                } else {
                    $('#error-content').show();
                    $('#errorlist').hide()
                }
            }
        })
    });
    if ($('#error-content').length) {
        hljs.debugMode();
        hljs.highlightAll();
    }
});
