/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC ( contact@vinades.vn )
 * @Copyright ( C ) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 1 - 31 - 2010 5 : 12
 */

$(document).ready(function(){
	$('#departmentid').change(function(){
		window.location.href = script_name + "?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=supporter&departmentid=" + $(this).val();
	});
});

function mark_as_unread() {
	$.ajax({
		type: "POST",
		url: window.location.href,
		cache: !1,
		data: "&mark=unread",
		dataType: "json"
	}).done(function(a) {
		"error" == a.status ? alert(a.mess) : window.location.href = a.mess
	});
	return !1
}
function multimark(a, b) {
	"unread" != b && (b = "read");
	$.ajax({
		type: "POST",
		url: window.location.href,
		cache: !1,
		data: "&mark=" + b + "&" + $(a).serialize(),
		dataType: "json"
	}).done(function(a) {
		"error" == a.status ? alert(a.mess) : window.location.href = "" != a.mess ? a.mess : window.location.href
	});
	return !1
}

function nv_chang_status(a) {
	nv_settimeout_disable("change_status_" + a, 5E3);
	var b = $("#change_status_" + a).val();
	$.post(script_name + "?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=change_status&nocache=" + (new Date).getTime(), "id=" + a + "&new_status=" + b, function(a) {
		"OK" != a && (alert(nv_is_change_act_confirm[2]), window.location.href = strHref)
	})
}

function nv_change_default(a, b) {
	var c = $("[data-is-default]").attr("data-is-default"),
		d = $("[data-not-default]").attr("data-not-default");
	if ($("em", b).is("." + c)) return !1;
	$.post(script_name + "?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=change_default&nocache=" + (new Date).getTime(), "id=" + a, function(a) {
		"OK" == a && ($("." + c).removeClass(c).addClass(d), $("em", b).removeClass(d).addClass(c))
	});
	return !1
}

function nv_del_department(a) {
	confirm(nv_is_del_confirm[0]) && $.post(script_name + "?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=del_department&nocache=" + (new Date).getTime(), "id=" + a, function(a) {
		"OK" == a ? window.location.href = strHref : alert(nv_is_del_confirm[2])
	});
	return !1
}
function nv_del_submit(a, b) {
	var c = 0;
	if (a[b].length) for (var d = 0; d < a[b].length; d++) {
		if (1 == a[b][d].checked) {
			c = 1;
			break
		}
	} else 1 == a[b].checked && (c = 1);
	c && confirm(nv_is_del_confirm[0]) && a.submit();
	return !1
}

function nv_delall_submit() {
	confirm(nv_is_del_confirm[0]) && (window.location.href = script_name + "?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=del&t=3");
	return !1
}
function nv_del_mess(a) {
	confirm(nv_is_del_confirm[0]) && (window.location.href = script_name + "?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=del&t=1&id=" + a);
	return !1
}

function nv_chang_weight(a) {
	nv_settimeout_disable("change_weight_" + a, 5E3);
	var b = $("#change_weight_" + a).val();
	$.post(script_name + "?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=change_weight&nocache=" + (new Date).getTime(), "id=" + a + "&new_weight=" + b, function(a) {
		"OK" != a.split("_")[0] ? alert(nv_is_change_act_confirm[2]) : window.location.href = window.location.href
	})
}

function get_alias(a) {
	var b = strip_tags(document.getElementById("idfull_name").value);
	"" != b && $.post(script_name + "?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=alias&nocache=" + (new Date).getTime(), "title=" + encodeURIComponent(b) + "&id=" + a, function(a) {
		"" != a ? document.getElementById("idalias").value = a : document.getElementById("idalias").value = ""
	});
	return !1
};

function nv_open_file( $_this )
{
	var area = $_this.data('area');
	var path = $_this.data('path');
	var currentpath = $_this.data('currentpath');
	var type = $_this.data('type');
	nv_open_browse( script_name + '?' + nv_name_variable + "=upload&popup=1&area=" + area + "&path=" + path + "&currentpath="+currentpath+"&type=" + type, "NVImg", 850, 500, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
	return false;
}