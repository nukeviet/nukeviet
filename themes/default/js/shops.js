/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 04/18/2017 09:47
 */

function sendrating(id, point, newscheckss)
{
	if (point == 1 || point == 2 || point == 3 || point == 4 || point == 5) {
		$.post(nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=rating&nocache=' + new Date().getTime(), 'id=' + id + '&checkss=' + newscheckss + '&point=' + point, function(res) {
			$("#stringrating").html(res);
		});
	}
}

function remove_text() {
	document.getElementById('to_date').value = "";
	document.getElementById('from_date').value = "";
}

function nv_del_content(id, checkss, base_adminurl) {
	if (confirm(nv_is_del_confirm[0])) {
		$.post(base_adminurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_content&nocache=' + new Date().getTime(), 'id=' + id + '&checkss=' + checkss, function(res) {
			var r_split = res.split("_");
			if (r_split[0] == 'OK') {
				window.location.href = strHref;
			} else if (r_split[0] == 'ERR') {
				alert(r_split[1]);
			} else {
				alert(nv_is_del_confirm[2]);
			}
		});
	}
	return false;
}

/*javascript user*/
function cartorder(a_ob, popup, url) {
	var id = $(a_ob).attr("id");
	if( popup == '0' )
	{
		$.ajax({
			type : "GET",
			url : nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=setcart' + '&id=' + id + "&nocache=" + new Date().getTime(),
			data : '',
			success : function(data) {
				var s = data.split('_');
				var strText = s[1];
				if (strText != null) {
					var intIndexOfMatch = strText.indexOf('#@#');
					while (intIndexOfMatch != -1) {
						strText = strText.replace('#@#', '_');
						intIndexOfMatch = strText.indexOf('#@#');
					}
					alert_msg(strText);
					linkloadcart = nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=loadcart';
					$("#cart_" + nv_module_name).load(linkloadcart);
					//window.location.href = nv_base_siteurl + "index.php?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=cart";
				}
			}
		});
	}
	else
	{
	        $('#idmodals').removeData('bs.modal');
	     	$.post(url + '&popup=1', function(res) {
			$('#idmodals .modal-body').html( res ); 
			$('#idmodals').modal('show');
		});
	}
}

/**/
function cartorder_detail(a_ob, popup, buy_now) {
	var num = $('#pnum').val();
	var id = $(a_ob).attr("data-id");
	var group = '';
	var label = '';

	var i = 0;
	$('.itemsgroup').each(function(){
		if( $('input[name="groupid['+$(this).data('groupid')+']"]:checked' ).length == 0 )
		{
			i++;
    		if( i == 1 ){
    			label = label + $(this).data('header');
    		}
    		else{
    			label = label + ', ' + $(this).data('header');
    		}
		}
	});
	if( label != '' ){
		$('#group_error').css( 'display', 'block' );
		$('#group_error').html( detail_error_group + ' <strong>' + label + '</strong>' );
		return false;
	}

	i = 0;
    $('.groupid').each(function(){
    	if( $(this).is(':checked') )
    	{
    		i++;
    		if( i == 1 )
    		{
    			group = group + $(this).val();
    		}
    		else
    		{
    			group = group + ',' + $(this).val();
    		}
    	}
	});

	$.ajax({
		type : "POST",
		url : nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=setcart' + '&id=' + id + "&group=" + group + "&nocache=" + new Date().getTime(),
		data : 'num=' + num,
		success : function(data) {
			var s = data.split('_');
			var strText = s[1];
			if (strText != null) {
				var intIndexOfMatch = strText.indexOf('#@#');
				while (intIndexOfMatch != -1) {
					strText = strText.replace('#@#', '_');
					intIndexOfMatch = strText.indexOf('#@#');
				}
				alert_msg(strText);
				linkloadcart = nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=loadcart';
				$("#cart_" + nv_module_name).load(linkloadcart);
				if( buy_now )
				{
					parent.location = nv_base_siteurl + "index.php?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=cart";
				}
				else if( popup )
				{
					parent.location = parent.location;
				}
			}
		}
	});
}

function alert_msg(msg) {
	$('#msgshow').html(msg);
	$('#msgshow').show('slide').delay(3000).hide('slow');
}

function checknum() {
	var price1 = $('#price1').val();
	var price2 = $('#price2').val();
	if (price2 == '') {
		price2 = 0;
	}
	if (price2 < price1) {
		document.getElementById('price2').value = '';
	}
	if (isNaN(price1)) {
		alert(isnumber);
		document.getElementById('submit').disabled = true;
	} else if (price2 != 0 && isNaN(price2)) {
		alert(isnumber);
		document.getElementById('submit').disabled = true;
	}
}

function cleartxtinput(id, txt_default) {
	$("#" + id).focusin(function() {
		var txt = $(this).val();
		if (txt_default == txt) {
			$(this).val('');
		}
	});
	$("#" + id).focusout(function() {
		var txt = $(this).val();
		if (txt == '') {
			$(this).val(txt_default);
		}
	});
}

function onsubmitsearch(module) {
	var keyword = $('#keyword').val();
	var price1 = $('#price1').val();
	if (price1 == null)
		price1 = '';
	var price2 = $('#price2').val();
	if (price2 == null)
		price2 = '';
	var typemoney = $('#typemoney').val();
	if (typemoney == null)
		typemoney = '';
	var cataid = $('#cata').val();
	if (keyword == '' && price1 == '' && price2 == '' && cataid == 0 ) {
		return false;
	} else {
		window.location.href = nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + module + '&' + nv_fc_variable + '=search_result&keyword=' + rawurlencode(keyword) + '&price1=' + price1 + '&price2=' + price2 + '&typemoney=' + typemoney + '&cata=' + cataid;
	}
	return false;
}

function onsubmitsearch1() {
	var keyword = $('#keyword1').val();
	var price1 = $('#price11').val();
	if (price1 == null)
		price1 = '';
	var price2 = $('#price21').val();
	if (price2 == null)
		price2 = '';
	var typemoney = $('#typemoney1').val();
	if (typemoney == null)
		typemoney = '';
	var cataid = $('#cata1').val();
	if (keyword == '' && price1 == '' && price2 == '' && cataid == 0 ) {
		return false;
	} else {
		window.location.href = nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=search_result&keyword=' + rawurlencode(keyword) + '&price1=' + price1 + '&price2=' + price2 + '&typemoney=' + typemoney + '&cata=' + cataid;
	}
	return false;
}

function nv_chang_price() {
	var newsort = $("#sort").val();
	$.post(nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=main&nocache=' + new Date().getTime(), 'changesprice=1&sort=' + newsort, function(res) {
		if (res != 'OK') {
			alert(res);
		} else {
			window.location.href = window.location.href;
		}
	});
}

function nv_chang_viewtype() {
	var viewtype = $("#viewtype").val();
	$.post(nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=main&nocache=' + new Date().getTime(), 'changeviewtype=1&viewtype=' + viewtype, function(res) {
		if (res != 'OK') {
			alert(res);
		} else {
			window.location.href = window.location.href;
		}
	});
}

function nv_compare(a) {
	nv_settimeout_disable("compare_" + a, 5E3);
	$.post(nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=compare&nocache=' + new Date().getTime(), 'compare=1&id=' + a, function(res) {
		res = res.split("[NV]");
		if (res[0] != 'OK') {
			$("#compare_" + res[2]).removeAttr("checked");
			alert(res[1]);
		}
	});
}

function nv_compare_click() {
	$.post(nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=compare&nocache=' + new Date().getTime(), 'compareresult=1', function(res) {
		if (res != 'OK') {
			alert(res);
		} else {
			window.location.href = nv_base_siteurl + "index.php?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=compare";
		}
	});
	return;
}

function nv_compare_del( id, all ) {
	if (confirm(lang_del_confirm)) {
		$.post(nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=compare&nocache=' + new Date().getTime(), 'compare_del=1&id=' + id + '&all=' + all, function(res) {
			if (res == 'OK') {
				window.location.href = window.location.href;
			}
		});
	}
	return;
}

function wishlist(id, object) {
	$.ajax({
		type : "GET",
		url : nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=wishlist_update' + '&id=' + id + "&ac=add&nocache=" + new Date().getTime(),
		data : '',
		success : function(data) {
			var s = data.split('_');
			if( s[0] == 'OK' )
			{
				$(object).addClass('disabled');
				$('#wishlist_num_id').text( s[1] );
			}
			alert_msg(s[2]);
		}
	});
}

function wishlist_del_item(id) {
	if (confirm(lang_del_confirm)) {
		$.ajax({
			type : "GET",
			url : nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=wishlist_update' + '&id=' + id + "&ac=del&nocache=" + new Date().getTime(),
			data : '',
			success : function(data) {
				var s = data.split('_');
				if( s[0] == 'OK' )
				{
					$('#wishlist_num_id').text( s[1] );
					$('#item_' + id).remove();
					if( s[1] == '0' )
					{
						window.location.href = window.location.href;
					}
				}
				alert_msg(s[2]);
			}
		});
	}
}

function payment_point( order_id, checkss, lang_confirm )
{
	if (confirm(lang_confirm)) {
		$.ajax({
			type : "GET",
			url : nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=point' + '&paypoint=1&checkss='+checkss+'&order_id=' + order_id + "&nocache=" + new Date().getTime(),
			data : '',
			success : function(data) {
				var s = data.split('_');
				if( s[0] == 'OK' )
				{
					alert( s[1] );
					window.location.href = window.location.href;
				}
				else
				{
					alert( s[1] );
				}
			}
		});
	}
}

function check_price( id_pro, pro_unit )
{
	var data = [];
    $('.groupid:checked').each(function(){
    	var value = $(this).val();
    	if( value != '' ){
    		data.push( value );
    	}
	});

	if( data.length > 0 ){
		$.ajax({
			method: "POST",
			url : nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=detail&nocache=' + new Date().getTime(),
			data: 'check_quantity=1&id_pro='+id_pro+'&pro_unit='+pro_unit+'&listid=' + data,
			success : function(res) {
				var s = res.split('_');
				if( s[0] == 'OK' ){
					$('#product_number').html( s[2] );
					$('#pnum, .btn-order').attr('disabled', false);
					$('#product_number').html( s[2] ).removeClass( 'text-danger' );
					$('#pnum').attr('max', s[1]);
				}
				else{
					$('#pnum, .btn-order').attr('disabled', true);
					$('#product_number').html( s[2] ).addClass( 'text-danger' );
				}
			}
		});
	}
}

function fix_image_content(){
	var news = $('.tab-content'), newsW, w, h;
	if( news.length ){
		var newsW = news.innerWidth();
		$.each($('img', news), function(){
			if( typeof $(this).data('width') == "undefined" ){
				w = $(this).innerWidth();
				h = $(this).innerHeight();
				$(this).data('width', w);
				$(this).data('height', h);
			}else{
				w = $(this).data('width');
				h = $(this).data('height');
			}

			if( w > newsW ){
				$(this).prop('width', newsW);
				$(this).prop('height', h * newsW / w);
			}
		});
	}
}

$(window).on('load', function(){
	fix_image_content();
});

$(window).on("resize", function() {
	fix_image_content();
});
