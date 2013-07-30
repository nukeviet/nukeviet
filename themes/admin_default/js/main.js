function ver_menu_click() {
	if ($('#ver_menu').is(':visible')) {
		$('#ver_menu').hide({
			direction : "horizontal"
		}, 500);
		$('#left_menu').css("width", "0px").css("left", "0px");
		$('#middle').css("margin-left", "0px");
		$('#cs_menu').removeClass("small").addClass("lage");
	} else {
		$('#middle').css("margin-left", "200px");
		$('#left_menu').css("width", "200px").css("left", "-200px");
		$('#ver_menu').show({
			direction : "horizontal"
		}, 500);
		$('#cs_menu').removeClass("lage").addClass("small");
	}
}