/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC ( contact@vinades.vn )
 * @Copyright ( C ) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3 / 25 / 2010 18 : 6
 */
var total = 0;

function nv_check_accept_number(a, d, c) {
	a = a["option[]"];
	for (var e = total = 0; e < a.length; e++) if (a[e].checked && (total += 1), total > d) return alert(c), !1
}

function nv_sendvoting(a, d, c, e, g) {
	var f = "0";
	c = parseInt(c);
	if (1 == c) {
		a = a.option;
		for (var b = 0; b < a.length; b++) a[b].checked && (f = a[b].value)
	} else if (1 < c) for (a = a["option[]"], b = 0; b < a.length; b++) a[b].checked && (f = f + "," + a[b].value);
	"0" == f && 0 < c ? alert(g) : $.ajax({
		type: "POST",
		cache: !1,
		url: nv_base_siteurl + "index.php?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=voting&" + nv_fc_variable + "=main&vid=" + d + "&checkss=" + e + "&lid=" + f,
		data: "nv_ajax_voting=1",
		dataType: "html",
		success: function(a) {
			modalShow("", a)
		}
	});
	return !1
};