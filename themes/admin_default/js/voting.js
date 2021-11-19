/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

function nv_del_content(vid, checkss) {
    if (confirm(nv_is_del_confirm[0])) {
        $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del&nocache=' + new Date().getTime(), 'vid=' + vid + '&checkss=' + checkss, function(res) {
            var r_split = res.split("_");
            if (r_split[0] == 'OK') {
                window.location.href = script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=main';
            } else if (r_split[0] == 'ERR') {
                alert(r_split[1]);
            } else {
                alert(nv_is_del_confirm[2]);
            }
        });
    }
    return false;
}

function nv_vote_add_item(mess) {
    items++;
    var newitem = '<tr>';
    newitem += '	<td class="text-right">' + mess + ' ' + items + '</td>';
    newitem += '	<td><input class="form-control" type="text" value="" name="answervotenews[]" maxlength="245"></td>';
    newitem += '	<td><input class="form-control" type="text" value="" name="urlvotenews[]"></td>';
    newitem += '	</tr>';
    $("#items").append(newitem);
}

$(document).ready(function() {
    $('[data-toggle="viewresult"]').click(function(e) {
        e.preventDefault();
        var poptitle = $(this).data('title');
        $.ajax({
            type: "POST",
            cache: !1,
            url: nv_base_siteurl + "index.php?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=voting&" + nv_fc_variable + "=main&vid=" + $(this).data('vid') + "&checkss=" + $(this).data('checkss') + "&lid=0",
            data: "nv_ajax_voting=1",
            dataType: "html",
            success: function(res) {
                if (res.match(/^ERROR\|/g)) {
                    alert(res.substring(6));
                } else {
                    modalShow(poptitle, res);
                }
            }
        });
    });
});
