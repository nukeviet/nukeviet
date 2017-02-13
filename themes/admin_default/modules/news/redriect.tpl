<!-- BEGIN: main -->
<div class="text-center">
	<strong>{MSG1}</strong>
	<br />
	<br />
	<img src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/images/load_bar.gif" />
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

<!-- BEGIN: meta_refresh -->
<meta http-equiv="refresh" content="{REDRIECT_T1};url={NV_REDIRECT}" />
<!-- END: meta_refresh -->
<!-- BEGIN: go_back -->
<script type="text/javascript">
	setTimeout('history.back()',{REDRIECT_T2})
</script>
<!-- END: go_back -->

<!-- END: main -->