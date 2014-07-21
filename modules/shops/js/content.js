/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC ( contact@vinades.vn )
 * @Copyright ( C ) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 9 - 8 - 2013 15 : 40
 */

$(document).ready(function() {
	$("#publ_date,#exp_date").datepicker({
		showOn : "both",
		dateFormat : "dd/mm/yy",
		changeMonth : true,
		changeYear : true,
		showOtherMonths : true,
		buttonImage : nv_siteroot + "images/calendar.gif",
		buttonImageOnly : true
	});

	$("#keywords-search").bind("keydown", function(event) {
		if (event.keyCode === $.ui.keyCode.TAB && $(this).data("ui-autocomplete").menu.active) {
			event.preventDefault();
		}

		if (event.keyCode == 13) {
			var keywords_add = $("#keywords-search").val();
			keywords_add = trim(keywords_add);
			if (keywords_add != '') {
				nv_add_element('keywords', keywords_add, keywords_add);
				$(this).val('');
			}
			return false;
		}

	}).autocomplete({
		source : function(request, response) {
			$.getJSON(script_name + "?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=tagsajax", {
				term : extractLast(request.term)
			}, response);
		},
		search : function() {
			// custom minLength
			var term = extractLast(this.value);
			if (term.length < 2) {
				return false;
			}
		},
		focus : function() {
			//no action
		},
		select : function(event, ui) {
			// add placeholder to get the comma-and-space at the end
			if (event.keyCode != 13) {
				nv_add_element('keywords', ui.item.value, ui.item.value);
				$(this).val('');
			}
			return false;
		}
	});

	$("#keywords-search").blur(function() {
		// add placeholder to get the comma-and-space at the end
		var keywords_add = $("#keywords-search").val();
		keywords_add = trim(keywords_add);
		if (keywords_add != '') {
			nv_add_element('keywords', keywords_add, keywords_add);
			$(this).val('');
		}
		return false;
	});
	$("#keywords-search").bind("keyup", function(event) {
		var keywords_add = $("#keywords-search").val();
		if (keywords_add.search(',') > 0) {
			keywords_add = keywords_add.split(",");
			for ( i = 0; i < keywords_add.length; i++) {
				var str_keyword = trim(keywords_add[i]);
				if (str_keyword != '') {
					nv_add_element('keywords', str_keyword, str_keyword);
				}
			}
			$(this).val('');
		}
		return false;
	});

});

function FormatNumber(str) {

	var strTemp = GetNumber(str);
	if (strTemp.length <= 3)
		return strTemp;
	strResult = "";
	for (var i = 0; i < strTemp.length; i++)
		strTemp = strTemp.replace(",", "");
	var m = strTemp.lastIndexOf(".");
	if (m == -1) {
		for (var i = strTemp.length; i >= 0; i--) {
			if (strResult.length > 0 && (strTemp.length - i - 1) % 3 == 0)
				strResult = "," + strResult;
			strResult = strTemp.substring(i, i + 1) + strResult;
		}
	} else {
		var strphannguyen = strTemp.substring(0, strTemp.lastIndexOf("."));
		var strphanthapphan = strTemp.substring(strTemp.lastIndexOf("."), strTemp.length);
		var tam = 0;
		for (var i = strphannguyen.length; i >= 0; i--) {

			if (strResult.length > 0 && tam == 4) {
				strResult = "," + strResult;
				tam = 1;
			}

			strResult = strphannguyen.substring(i, i + 1) + strResult;
			tam = tam + 1;
		}
		strResult = strResult + strphanthapphan;
	}
	return strResult;
}

function GetNumber(str) {
	var count = 0;
	for (var i = 0; i < str.length; i++) {
		var temp = str.substring(i, i + 1);
		if (!(temp == "," || temp == "." || (temp >= 0 && temp <= 9))) {
			alert("{LANG.inputnumber}");
			return str.substring(0, i);
		}
		if (temp == " ")
			return str.substring(0, i);
		if (temp == ".") {
			if (count > 0)
				return str.substring(0, ipubl_date);
			count++;
		}
	}
	return str;
}

function IsNumberInt(str) {
	for (var i = 0; i < str.length; i++) {
		var temp = str.substring(i, i + 1);
		if (!(temp == "." || (temp >= 0 && temp <= 9))) {
			alert("{LANG.inputnumber}");
			return str.substring(0, i);
		}
		if (temp == ",") {
			alert("{LANG.thaythedaucham}");
			return str.substring(0, i);
		}
	}
	return str;
}

function split(val) {
	return val.split(/,\s*/);
}

function extractLast(term) {
	return split(term).pop();
}

function nv_add_element(idElment, key, value) {
	var html = "<span title=\"" + value + "\" class=\"uiToken removable\">" + value + "<input type=\"hidden\" value=\"" + key + "\" name=\"" + idElment + "[]\" autocomplete=\"off\"><a onclick=\"$(this).parent().remove();\" href=\"javascript:void(0);\" class=\"remove uiCloseButton uiCloseButtonSmall\"></a></span>";
	$("#" + idElment).append(html);
	return false;
}