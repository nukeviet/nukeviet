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
			<a target="_blank" href="https://github.com/vuthao">vuthao</a> (1.411 commits),
			<a target="_blank" href="https://github.com/hoaquynhtim99">hoaquynhtim99</a> (606 commits),
			<a target="_blank" href="https://github.com/anhtunguyen">anhtunguyen</a> (308 commits),
			<a target="_blank" href="https://github.com/mynukeviet">mynukeviet</a> (221 commits),
			<a target="_blank" href="https://github.com/thehung">thehung</a> (46 commits),
			<a target="_blank" href="https://github.com/anhyeuviolet">anhyeuviolet</a> (37 commits),
			<a target="_blank" href="https://github.com/trinhthinhhp">trinhthinhhp</a> (35 commits),
			<a target="_blank" href="https://github.com/thinhhpvn">thinhhpvn</a> (33 commits),
			<a target="_blank" href="https://github.com/PhamQuocTien132">PhamQuocTien132</a> (33 commits),
			<a target="_blank" href="https://github.com/htuyen9x">htuyen9x</a> (22 commits),
			<a target="_blank" href="https://github.com/thangbv">thangbv</a> (20 commits),
			<a target="_blank" href="https://github.com/dangdinhtu2014">dangdinhtu2014</a> (18 commits),
			<a target="_blank" href="https://github.com/thuvp1995">thuvp1995</a> (9 commits),
			<a target="_blank" href="https://github.com/phongaz">phongaz</a> (7 commits),
			<a target="_blank" href="https://github.com/volong1012">volong1012</a> (6 commits),
			<a target="_blank" href="https://github.com/dangdlinhtu">dangdlinhtu</a> (5 commits),
			<a target="_blank" href="https://github.com/htuyen1994">htuyen1994</a> (3 commits),
			<a target="_blank" href="https://github.com/ThinhNguyenVB">ThinhNguyenVB</a> (2 commits),
			<a target="_blank" href="https://github.com/tkhuyenbk">tkhuyenbk</a> (2 commits),
			<a target="_blank" href="https://github.com/hiidemo">hiidemo</a> (2 commits),
			<a target="_blank" href="https://github.com/webvangvn">webvangvn</a> (1 commit),
			<a target="_blank" href="https://github.com/thethao">thethao</a> (1 commit),
			<a target="_blank" href="https://github.com/tuanta">tuanta</a> (1 commit),
			<a target="_blank" href="https://github.com/truongdacngoc1993">truongdacngoc1993</a> (1 commit),
			<a target="_blank" href="https://github.com/webvang">webvang</a> (1 commit),
			<a target="_blank" href="https://github.com/duyetdev">duyetdev</a> (1 commit)
		</div>
		<div class="list-member-other-version">
			<h4>{LANG.thanks_list_member_other_version}:</h4>
			<div class="version">
				<h5>{LANG.thanks_other_version} 4.1</h5>
    			<a target="_blank" href="https://github.com/hoaquynhtim99">hoaquynhtim99</a> (119 commits),
    			<a target="_blank" href="https://github.com/vuthao">vuthao</a> (59 commits),
    			<a target="_blank" href="https://github.com/anhtunguyen">anhtunguyen</a> (42 commits),
    			<a target="_blank" href="https://github.com/anhyeuviolet">anhyeuviolet</a> (14 commits),
    			<a target="_blank" href="https://github.com/mynukeviet">mynukeviet</a> (10 commits),
    			<a target="_blank" href="https://github.com/thuvp1995">thuvp1995</a> (9 commits),
    			<a target="_blank" href="https://github.com/thangbv">thangbv</a> (6 commits),
    			<a target="_blank" href="https://github.com/thehung">thehung</a> (3 commits),
    			<a target="_blank" href="https://github.com/htuyen1994">htuyen1994</a> (3 commits),
    			<a target="_blank" href="https://github.com/hiidemo">hiidemo</a> (2 commits),
    			<a target="_blank" href="https://github.com/duyetdev">duyetdev</a> (1 commit),
    			<a target="_blank" href="https://github.com/webvangvn">webvangvn</a> (1 commit),
    			<a target="_blank" href="https://github.com/phongaz">phongaz</a> (1 commit),
    			<a target="_blank" href="https://github.com/htuyen9x">htuyen9x</a> (1 commit)
			</div>
		</div>
	</div>
</div>
<!-- END: step -->