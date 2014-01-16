/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC ( contact@vinades.vn )
 * @Copyright ( C ) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3 / 25 / 2010 18 : 6
 */

var total = 0;

function nv_check_accept_number(form, acceptcm, msg) {
	var fa = form['option[]'];
	total = 0;
	for (var i = 0; i < fa.length; i++) {
		if (fa[i].checked) {
			total = total + 1;
		}
		if (total > acceptcm) {
			alert(msg);
			return false;
		}
	}
}

function nv_sendvoting(form, vid, acceptcm, checkss, msg) {
	var lid = '0';
	acceptcm = parseInt(acceptcm);
	if (acceptcm == 1) {
		var fa = form.option;
		for (var i = 0; i < fa.length; i++) {
			if (fa[i].checked) {
				lid = fa[i].value;
			}
		}
	} else if (acceptcm > 1) {
		var fa = form['option[]'];
		for (var i = 0; i < fa.length; i++) {
			if (fa[i].checked) {
				lid = lid + ',' + fa[i].value;
			}
		}
	}
	if (lid == '0' && acceptcm > 0) {
		alert(msg);
	} else {
		Shadowbox.open({
			content : '<iframe src="' + nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=voting&' + nv_fc_variable + '=main&vid=' + vid + '&checkss=' + checkss + '&lid=' + lid + '" border="0" frameborder="0" style="width:670px;height:400px"></iframe>',
			player : 'html',
			height : 400,
			width : 670
		});
	}
	return false;
}