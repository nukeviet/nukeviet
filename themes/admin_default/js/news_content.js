/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC ( contact@vinades.vn )
 * @Copyright ( C ) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 9 - 8 - 2013 15 : 40
 */

function split(val) {
	return val.split(/,\s*/);
}

function extractLast(term) {
	return split(term).pop();
}

$(document).ready(function() {
    $("#titlelength").html($("#idtitle").val().length);
    $("#idtitle").bind("keyup paste", function() {
    	$("#titlelength").html($(this).val().length);
    });
    
    $("#titlesitelength").html($("#idtitlesite").val().length);
    $("#idtitlesite").bind("keyup paste", function() {
    	$("#titlesitelength").html($(this).val().length);
    });
    
    $("#descriptionlength").html($("#description").val().length);
    $("#description").bind("keyup paste", function() {
    	$("#descriptionlength").html($(this).val().length);
    });

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
		buttonImage : nv_base_siteurl + "assets/images/calendar.gif",
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

	$("#keywords-search").bind("keydown", function(event) {
		if (event.keyCode === $.ui.keyCode.TAB && $(this).data("ui-autocomplete").menu.active) {
			event.preventDefault();
		}

        if(event.keyCode==13){
            var keywords_add= $("#keywords-search").val();
            keywords_add = trim( keywords_add );
            if( keywords_add != '' ){
                nv_add_element( 'keywords', keywords_add, keywords_add );
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
			if(event.keyCode!=13){
	            nv_add_element( 'keywords', ui.item.value, ui.item.value );
	            $(this).val('');
	           }
            return false;
		}
	});

    $("#keywords-search").blur(function() {
		// add placeholder to get the comma-and-space at the end
        var keywords_add= $("#keywords-search").val();
        keywords_add = trim( keywords_add );
        if( keywords_add != '' ){
            nv_add_element( 'keywords', keywords_add, keywords_add );
            $(this).val('');
        }
        return false;
	});
    $("#keywords-search").bind("keyup", function(event) {
		var keywords_add= $("#keywords-search").val();
        if(keywords_add.search(',') > 0 )
        {
            keywords_add = keywords_add.split(",");
            for (i = 0; i < keywords_add.length; i++) {
                var str_keyword = trim( keywords_add[i] );
                if( str_keyword != '' ){
                    nv_add_element( 'keywords', str_keyword, str_keyword );
                }
            }
            $(this).val('');
        }
        return false;
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
	
	$("#topicid").select2({
		language: "vi",
		ajax: {
	    url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=content&get_topic_json=1',
	    	dataType: 'json',
	    	delay: 250,
	    	data: function (params) {
	      		return {
	      			q: params.term, // search term
	      			page: params.page
	      		};
	      	},
	    	processResults: function (data, params) {
	    		params.page = params.page || 1;
	    		return {
	    			results: data,
	    			pagination: {
	    				more: (params.page * 30) < data.total_count
	    			}
	    		};
	    	},
		cache: true
		},
		escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
		minimumInputLength: 3,
		templateResult: formatRepo, // omitted for brevity, see the source of this page
		templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
	});
    
    // Control content adv tab
    $('#adv-form').on('hidden.bs.collapse', function() {
        $('#adv-form-arrow').removeClass('fa-angle-double-up').addClass('fa-angle-double-down');
        $.cookie(nv_module_name + '_advtabcontent', 'HIDE', {expires: 7});
    });
    $('#adv-form').on('shown.bs.collapse', function() {
        $('#adv-form-arrow').removeClass('fa-angle-double-down').addClass('fa-angle-double-up');
        $.cookie(nv_module_name + '_advtabcontent', 'SHOW', {expires: 7});
    });
    if ($.cookie(nv_module_name + '_advtabcontent') == 'SHOW') {
        $('#adv-form').collapse('show');
    } else {
        $('#adv-form').collapse('hide');
    }
    
    $('input[name="open_source"]').change(function() {
    	if ($(this).is(':checked')) {
    		$('#content_bodytext_required').addClass('hidden');
    	} else {
    		$('#content_bodytext_required').removeClass('hidden');
    	}
	});
});

function formatRepo (repo) {
	if (repo.loading) return repo.text;
	return repo.title;
}

function formatRepoSelection (repo) {
	return repo.title || repo.text;
}

function nv_add_element( idElment, key, value ){
   var html = "<span title=\"" + value + "\" class=\"uiToken removable\" ondblclick=\"$(this).remove();\">" + value + "<input type=\"hidden\" value=\"" + key + "\" name=\"" + idElment + "[]\" autocomplete=\"off\"><a onclick=\"$(this).parent().remove();\" href=\"javascript:void(0);\" class=\"remove uiCloseButton uiCloseButtonSmall\"></a></span>";
    $("#" + idElment).append( html );
	return false;
}

var timer_check_takeover = '';
function nv_timer_check_takeover(id) {
	clearTimeout(timer_check_takeover);
    $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=content&nocache=' + new Date().getTime(), 'id='+id+'&check_edit=1', function(res) {
		res = res.split("_");
		if (res[0] != 'OK') {// thông báo bị chiếm quyền sửa
			alert(res[1]);
			$('.submit-post').remove();
		} else {
			timer_check_takeover = setTimeout(function() {
				nv_timer_check_takeover(id);
			}, 30000);
		}
	});
	return false;
}