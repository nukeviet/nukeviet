/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC ( contact@vinades.vn )
 * @Copyright ( C ) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3 / 25 / 2010 18 : 6
 */

var total = 0;

function nv_check_accept_number(form, num, errmsg) {
    opts = form["option[]"];
    for (var e = total = 0; e < opts.length; e++)
        if (opts[e].checked && (total += 1), total > num) return alert(errmsg), !1
}

function nv_sendvoting(form, id, num, checkss, errmsg, captcha) {
    var vals = "0";
    num = parseInt(num);
    if(num==0)url=errmsg;
    captcha = parseInt(captcha);
    if (1 == num) {
        opts = form.option;
        for (var b = 0; b < opts.length; b++) opts[b].checked && (vals = opts[b].value)
    } else if (1 < num)
        for (opts = form["option[]"], b = 0; b < opts.length; b++) opts[b].checked && (vals = vals + "," + opts[b].value);

    if ("0" == vals && 0 < num) {
        alert(errmsg);
    } else if (captcha == 0 || "0" == vals) { //alert(url);
        nv_sendvoting_submit(id, checkss, vals,'',url);
    } else {
        $('#voting-modal-' + id).data('id', id).data('checkss', checkss).data('vals', vals);
        modalShowByObj('#voting-modal-' + id, "recaptchareset");
        //window.location.href = nv_base_siteurl + "index.php?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=main";
    }
    return !1
}

function nv_sendvoting_submit(id, checkss, vals, capt,url) {
    $.ajax({
        type: "POST",
        cache: !1,
        url: nv_base_siteurl + "index.php?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=voting&" + nv_fc_variable + "=main&vid=" + id + "&checkss=" + checkss + "&lid=" + vals + (typeof capt != '' ? '&captcha=' + capt : ''),
        data: "nv_ajax_voting=1",
        dataType: "html",
        success: function(res) {
            if (res.match(/^ERROR\|/g)) {
                change_captcha('.rsec');
                alert(res.substring(6));
            } else {
                modalShow("", res);
                if(url!='') {
                	setTimeout(function(){location.reload(); }, 3000);
                }

            }
        }
    });
}

function nv_sendvoting_captcha(btn, id, msg,url) {
    var ctn = $('#voting-modal-' + id);
    var capt = "";
    if (nv_is_recaptcha) {
        capt = $('[name="g-recaptcha-response"]', $(btn).parent()).val();
    } else {
        capt = $('[name="captcha"]', $(btn).parent()).val();
    }
    if (capt == "") {
        alert(msg);
    } else {
        nv_sendvoting_submit(ctn.data('id'), ctn.data('checkss'), ctn.data('vals'), capt,url);
    }
}