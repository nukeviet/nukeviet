/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC ( contact@vinades.vn )
 * @Copyright ( C ) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 9 - 8 - 2013 15 : 40
 */

function create_keywords() {
	var content = strip_tags(document.getElementById('keywords').value);
	if (content != '') {
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=keywords&nocache=' + new Date().getTime(), 'content=' + encodeURIComponent(content), function(res) {
			if (res != "n/a") {
				document.getElementById('keywords').value = res;
			} else {
				document.getElementById('keywords').value = '';
			}
		});
	}
	return false;
}

function split(val) {
	return val.split(/,\s*/);
}

function extractLast(term) {
	return split(term).pop();
}


$("#titlelength").html($("#idtitle").val().length);
$("#idtitle").bind("keyup paste", function() {
	$("#titlelength").html($(this).val().length);
});

$("#descriptionlength").html($("#description").val().length);
$("#description").bind("keyup paste", function() {
	$("#descriptionlength").html($(this).val().length);
});

$(document).ready(function() {
	$("input[name='catids[]']").click(function() {
		var catid = $("input:radio[name=catid]:checked").val();
		var radios_catid = $("input:radio[name=catid]");
		var catids = [];
		$("input[name='catids[]']").each(function() {
			if ($(this).prop('checked')) {
				$("#catright_" + $(this).val()).show();
				catids.push($(this).val());
			} else {
				$("#catright_" + $(this).val()).hide();
				if ($(this).val() == catid) {
					radios_catid.filter("[value=" + catid + "]").prop("checked", false);
				}
			}
		});

		if (catids.length > 1) {
			for ( i = 0; i < catids.length; i++) {
				$("#catright_" + catids[i]).show();
			};
			catid = parseInt($("input:radio[name=catid]:checked").val() + "");
			if (!catid) {
				radios_catid.filter("[value=" + catids[0] + "]").prop("checked", true);
			}
		}
	});
	$("#publ_date,#exp_date").datepicker({
		showOn : "both",
		dateFormat : "dd/mm/yy",
		changeMonth : true,
		changeYear : true,
		showOtherMonths : true,
		buttonImage : nv_siteroot + "images/calendar.gif",
		buttonImageOnly : true
	});

	var cachetopic = {};
	$("#AjaxTopicText").autocomplete({
		minLength : 2,
		delay : 500,
		source : function(request, response) {
			var term = request.term;
			if ( term in cachetopic) {
				response(cachetopic[term]);
				return;
			}
			$.getJSON(script_name + "?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=topicajax", request, function(data, status, xhr) {
				cachetopic[term] = data;
				response(data);
			});
		}
	});

	var cachesource = {};
	$("#AjaxSourceText").autocomplete({
		minLength : 2,
		delay : 500,
		source : function(request, response) {
			var term = request.term;
			if ( term in cachesource) {
				response(cachesource[term]);
				return;
			}
			$.getJSON(script_name + "?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=sourceajax", request, function(data, status, xhr) {
				cachesource[term] = data;
				response(data);
			});
		}
	});

	$("#keywords").bind("keydown", function(event) {
		if (event.keyCode === $.ui.keyCode.TAB && $(this).data("ui-autocomplete").menu.active) {
			event.preventDefault();
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
			// prevent value inserted on focus
			return false;
		},
		select : function(event, ui) {
			var terms = split(this.value);
			// remove the current input
			terms.pop();
			// add the selected item
			terms.push(ui.item.value);
			// add placeholder to get the comma-and-space at the end
			terms.push("");
			this.value = terms.join(", ");
			return false;
		}
	});

	// hide message_body after the first one
	$(".message_list .message_body:gt(1)").hide();

	// hide message li after the 5th
	$(".message_list li:gt(5)").hide();

	// toggle message_body
	$(".message_head").click(function() {
		$(this).next(".message_body").slideToggle(500);
		return false;
	});

	// collapse all messages
	$(".collpase_all_message").click(function() {
		$(".message_body").slideUp(500);
		return false;
	});

	// Show all messages
	$(".show_all_message").click(function() {
		$(".message_body").slideDown(1000);
		return false;
	});
});
