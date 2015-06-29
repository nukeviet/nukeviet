<!-- BEGIN: main -->
<div class="text-center">
	<strong>{MSG1}</strong>
	<br />
	<br />
	<img src="{NV_BASE_SITEURL}images/load_bar.gif" />
	<br />
	<br />
	<strong><a href="{NV_REDIRECT}">{MSG2}</a></strong>
</div>
<!-- BEGIN: removelocalstorage-->
<script type="text/javascript">
	if(typeof (Storage) !== 'undefined')
	{
		var autoSaveKey = '{AUTOSAVEKEY}';
		if (localStorage.getItem(autoSaveKey)) {
			localStorage.removeItem(autoSaveKey);
		}
	}
</script>
<!-- END: removelocalstorage-->
<meta http-equiv="refresh" content="2;url={NV_REDIRECT}" />
<!-- END: main -->