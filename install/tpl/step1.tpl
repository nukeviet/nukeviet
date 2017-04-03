<!-- BEGIN: step -->
<script type="text/javascript">
	function nv_checklang() {
		url = $("#lang").val();
		if (url == 'none') {
			alert('{LANG.select_language}');
			return false;
		} else if (url == 'other') {
			top.location.href = 'http://translate.nukeviet.vn/en/translate/download/';
		} else {
			top.location.href = url;
		}
	}
</script>
<p>{LANG.select_lang_des}</p>
<form action="#" id="checklang">
    <p>
        <select class="select" id="lang" onchange="return nv_checklang()">
            <option value="none">{LANG.choose_lang}</option>
            <!-- BEGIN: languagelist -->
            <option value="{BASE_SITEURL}install/index.php?{LANG_VARIABLE}={LANGTYPE}&amp;step=1"{SELECTED}>{LANGNAME}</option>
            <!-- END: languagelist -->
            <option value="other">Other Language</option>
        </select>
    </p>
    <ul class="control_t fr">
        <li><span class="next_step" id="next_step"><a href="{BASE_SITEURL}install/index.php?{LANG_VARIABLE}={CURRENTLANG}&amp;step=2">{LANG.next_step}</a></span></li>
    </ul>
</form>
<!-- BEGIN: check_supports_rewrite -->
<script type="text/javascript">
	$("#next_step").hide();
	var supports_rewrite = '';
	$.ajax({
		url : '{BASE_SITEURL}install/check.rewrite',
		type : 'GET',
		success : function(theResponse) {
			if (theResponse == "mod_rewrite works") {
				supports_rewrite = '{SUPPORTS_REWRITE}';
			}
			nv_setCookie("supports_rewrite", supports_rewrite, 86400);
			$("#next_step").show();
		},
		error : function(theResponse) {
			$("#next_step").show();
		}
	});
</script>
<!-- END: check_supports_rewrite -->
<div class="clearfix"></div>
<div id="thanks">
	<div class="administrator">
		<h1>{LANG.thanks}</h1>
		<p>
			{LANG.thanks_text}
		</p>
	</div>
	<div class="list-member">
		<div class="list-member-all">
			<h4>{LANG.thanks_list_member_all}</h4>
			<a href="#">Hoaquynhtim99, hiidemo, Hồ Ngọc Triển, mynukeviet, phongaz, anhyeuviolet,Hoaquynhtim99, hiidemo, Hồ Ngọc Triển, mynukeviet, phongaz, anhyeuviolet
			Hoaquynhtim99, hiidemo, Hồ Ngọc Triển, mynukeviet, phongaz, anhyeuviolet,Hoaquynhtim99, hiidemo, Hồ Ngọc Triển, mynukeviet, phongaz, anhyeuviolet,Hoaquynhtim99, hiidemo, Hồ Ngọc Triển, mynukeviet, phongaz, anhyeuviolet,
			Hoaquynhtim99, hiidemo, Hồ Ngọc Triển, mynukeviet, phongaz, anhyeuviolet,Hoaquynhtim99, hiidemo, Hồ Ngọc Triển, mynukeviet, phongaz, anhyeuviolet,Hoaquynhtim99, hiidemo, Hồ Ngọc Triển, mynukeviet, phongaz, anhyeuviolet,
			Hoaquynhtim99, hiidemo, Hồ Ngọc Triển, mynukeviet, phongaz, anhyeuviolet,Hoaquynhtim99, hiidemo, Hồ Ngọc Triển, mynukeviet, phongaz, anhyeuviolet,Hoaquynhtim99, hiidemo, Hồ Ngọc Triển, mynukeviet, phongaz, anhyeuviolet,
			Hoaquynhtim99, hiidemo, Hồ Ngọc Triển, mynukeviet, phongaz, anhyeuviolet,Hoaquynhtim99, hiidemo, Hồ Ngọc Triển, mynukeviet, phongaz, anhyeuviolet,Hoaquynhtim99, hiidemo, Hồ Ngọc Triển, mynukeviet, phongaz, anhyeuviolet,	
			</a>
		</div>
		<div class="list-member-other-version">
			<h4>{LANG.thanks_list_member_other_version}</h4>
			<div class="version">
				<h5>{LANG.thanks_other_version}</h5>
				<a href="#">The Hung, Vu Thao, Hoaquynhtim99, hiidemo, Hồ Ngọc Triển, mynukeviet, phongaz, anhyeuviolet</a>
			</div>
			<div class="version">
				<h5>{LANG.thanks_other_version}</h5>
				<a href="#">The Hung, Vu Thao, Hoaquynhtim99, hiidemo, Hồ Ngọc Triển, mynukeviet, phongaz, anhyeuviolet</a>
			</div>
			<div class="version">
				<h5>Phiên bản 3.4.2:</h5>
				<a href="#">The Hung, Vu Thao, Hoaquynhtim99, hiidemo, Hồ Ngọc Triển, mynukeviet, phongaz, anhyeuviolet</a>
			</div>
			<div class="version">
				<h5>Phiên bản 4.0.2:</h5>
				<a href="#">The Hung, Vu Thao, Hoaquynhtim99, hiidemo, Hồ Ngọc Triển, mynukeviet, phongaz, anhyeuviolet</a>
			</div>
		</div>
	</div>
</div>
<!-- END: step -->