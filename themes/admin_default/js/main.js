function ver_menu_click() {
	if ($('#ver_menu').is(':visible')) {
		$('#ver_menu').hide({
			direction : "horizontal"
		}, 500);
		$('#left_menu').css("width", "0px").css("left", "0px");
		$('#middle').css("margin-left", "0px");
		$('#cs_menu').removeClass("small").addClass("lage");
		nv_setCookie('ver_menu_show', '0', 86400000);
	} else {
		$('#middle').css("margin-left", "200px");
		$('#left_menu').css("width", "200px").css("left", "-200px");
		$('#ver_menu').show({
			direction : "horizontal"
		}, 500);
		$('#cs_menu').removeClass("lage").addClass("small");
		nv_setCookie('ver_menu_show', '1', 86400000);
	}
}

function ver_menu_show() {
	var showmenu = ( nv_getCookie('ver_menu_show') ) ? ( nv_getCookie('ver_menu_show')) : '1';
	if (showmenu == '0') {
		$('#ver_menu').hide();
		$('#left_menu').css("width", "0").css("left", "0");
		$('#middle').css("margin-left", "0");
		$('#cs_menu').removeClass("small").addClass("lage");
	}
}