/**
 * NUKEVIET Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

function formatStringAsUriComponent(s) {
    // replace html with whitespace
    s = s.replace(/<\/?[^>]*>/gm, " ");

    // remove entities
    s = s.replace(/&[\w]+;/g, "");

    // remove 'punctuation'
    s = s.replace(/[\.\,\"\'\?\!\;\:\#\$\%\&\(\)\*\+\-\/\<\>\=\@\[\]\\^\_\{\}\|\~]/g, "");

    // replace multiple whitespace with single whitespace
    s = s.replace(/\s{2,}/g, " ");

    // trim whitespace at start and end of title
    return s.replace(/^\s+|\s+$/g, "");
}

$(document).ready(function() {
    // RPC ping
    $("#rpc .col3").click(function() {
        var a = $(this).attr("title");
        a != "" && alert(a);
        return !1
    });
});
