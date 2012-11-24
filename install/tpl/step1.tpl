<!-- BEGIN: step -->
<script type="text/javascript">	
		function nv_checklang( url )
		{
			if (url=='none'){
				alert('{LANG.select_language}')
				return false;
			}
			else if (url=='other')
			{
				top.location.href='http://translate.nukeviet.vn/en/translate/download/';
			}
			else{
				top.location.href=url;
			}
		}
		</script>
<p>{LANG.select_lang_des}</p>
<form action="#" id="checklang">
<p><select class="select" id="lang"
	onchange="return nv_checklang(this.options[this.selectedIndex].value)">
	<option value="none">{LANG.choose_lang}</option>
	<!-- BEGIN: languagelist -->
	<option value="{BASE_SITEURL}install/index.php?{LANG_VARIABLE}={LANGTYPE}&amp;step=1"{SELECTED}>{LANGNAME}</option>
	<!-- END: languagelist -->
	<option value="other">Other Language</option>
</select></p>
<ul class="control_t fr">
	<li><span class="next_step"><a
		href="{BASE_SITEURL}install/index.php?{LANG_VARIABLE}={CURRENTLANG}&amp;step=2">{LANG.next_step}</a></span></li>
</ul>
</form>
<!-- END: step -->