/**
 * NUKEVIET Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

var nv_loading = '<div class="text-center"><i class="fas fa-spin fa-spinner fa-2x wt-icon-loading"></i></div>';

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
    $("body").delegate(".ext-info-title", "click", function() {
        $(".ext-info-title").each(function() {
            $(this).removeClass('d-none');
        });
        $(".ext-info-content").each(function() {
            $(this).addClass('d-none');
        });
        $(this).addClass('d-none').next(".ext-info-content").removeClass('d-none');
        return false
    });
    $("body").delegate(".ext-info-content", "click", function() {
        $(this).addClass('d-none').prev(".ext-info-title").removeClass('d-none');
    });
});
