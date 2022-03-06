/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC ( contact@vinades.vn )
 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 04/18/2017 09:47
 */

$(document).ready(function() {
	$("#publ_date,#exp_date").datepicker({
		dateFormat : "dd/mm/yy",
		changeMonth : true,
		changeYear : true,
		showOtherMonths : true,
		showOn : 'focus'
	});
	$('#publ_date-btn').click(function(){
		$("#publ_date").datepicker('show');
	});
	$('#exp_date-btn').click(function(){
		$("#exp_date").datepicker('show');
	});

	$("#gift_from, #gift_to").datepicker({
		dateFormat : "dd/mm/yy",
		changeMonth : true,
		changeYear : true,
		showOtherMonths : true,
		showOn : 'focus'
	});
	$('#to-btn').click(function(){
		$("#to").datepicker('show');
	});
	$('#from-btn').click(function(){
		$("#from").datepicker('show');
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