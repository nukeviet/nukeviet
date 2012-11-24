<!-- BEGIN: module_info -->
<table id="checkchmod" cellspacing="0" summary="summary" style="width: 100%;">
	<tr>
		<th scope="col" abbr="{LANG.update_mod_list}" class="nobg">{LANG.update_mod_list}</th>
		<th scope="col">{LANG.update_mod_version}</th>
		<th scope="col">{LANG.update_mod_author}</th>
		<th scope="col">{LANG.update_mod_note}</th>
	</tr>
	<!-- BEGIN: loop -->
	<tr>
		<th scope="col" class="spec text_normal">{ROW.module_file}</th>
		<td><span class="highlight_green">{ROW.mod_version} ({ROW.time})</span></td>
		<td><span class="highlight_green">{ROW.author}</span></td>
		<td><span class="highlight_green">{ROW.note}</span></td>
	</tr>
	<!-- END: loop -->
</table>
<!-- END: module_info -->
<!-- BEGIN: version_info -->
<table id="checkchmod" cellspacing="0" summary="summary" style="width: 100%;">
	<tr>
		<th scope="col" abbr="{LANG.update_step_title_1}" class="nobg" style="width:380px">{LANG.update_step_title_1}</th>
		<th scope="col">{LANG.update_value}</th>
	</tr>
	<tr>
		<th scope="col" class="spec text_normal">{LANG.update_current_version}</th>
		<td><span class="highlight_green">{DATA.current_version}</span></td>
	</tr>
	<tr>
		<th scope="col" class="specalt text_normal">{LANG.update_lastest_version}</th>
		<td><span class="highlight_green">{DATA.newVersion}</span></td>
	</tr>
</table>
<!-- BEGIN: checkversion -->
<div class="infoerror">
	{LANG.update_check_version}
</div>
<!-- END: checkversion -->
<!-- END: version_info -->
<!-- BEGIN: commodule -->
<table id="checkchmod" cellspacing="0" summary="summary" style="width: 100%;">
	<tr>
		<th scope="col" abbr="{LANG.update_step_title_1}" class="nobg" style="width:380px">{LANG.update_step_title_1}</th>
		<th scope="col">{LANG.update_value}</th>
	</tr>
	<tr>
		<th scope="col" class="spec text_normal">{LANG.update_current_version}</th>
		<td><span class="highlight_green">{CONFIG.to_version}</span></td>
	</tr>
	<tr>
		<th scope="col" class="specalt text_normal">{LANG.update_lastest_version}</th>
		<td><span class="highlight_green">{LASTEST_VERSION}</span></td>
	</tr>
</table>
<!-- BEGIN: notcertified -->
<div class="infoerror">
	{LANG.updatemod_notcertified}
</div>
<!-- END: notcertified -->
<!-- BEGIN: checkversion -->
<div class="infoerror">
	{LANG.update_check_version}
</div>
<!-- END: checkversion -->
<!-- END: commodule -->
<!-- BEGIN: main -->
<div class="infook">
	{LANG.update_info_complete}
</div>
<!-- BEGIN: typemodule -->
<script type="text/javascript">
$(window).load(function(){
	$('#versioninfo').load('{NV_BASE_SITEURL}install/update.php?step=3&load=module');
});
</script>
<div id="versioninfo">
	<div class="infoalert">	
		<img src="{NV_BASE_SITEURL}images/load_bar.gif" alt="Loading..."/><br />
		{LANG.update_waiting}
	</div>
</div>
<!-- END: typemodule -->
<!-- BEGIN: typefull -->
<script type="text/javascript">
function LoadModInfo(){
	$('#modinfo').load('{NV_BASE_SITEURL}install/update.php?step=3&load=mod');
}
$(window).load(function(){
	$('#versioninfo').load('{NV_BASE_SITEURL}install/update.php?step=3&load=ver', function(){
		$('#versioninfo').append(
			'<div id="modinfo">' +
				'<div class="infoalert">' +
					'<img src="{NV_BASE_SITEURL}images/load_bar.gif" alt="Loading..."/><br />' +
					'{LANG.update_waiting_continue}' +
				'</div>' +
			'</div>'
		);
		setTimeout( "LoadModInfo()", 1000 );
	});
});
</script>
<div id="versioninfo">
	<div class="infoalert">	
		<img src="{NV_BASE_SITEURL}images/load_bar.gif" alt="Loading..."/><br />
		{LANG.update_waiting}
	</div>
</div>
<!-- END: typefull -->
<div id="endupdate">
	<div class="infoalert" id="infodetectedupg">
		{LANG.update_info_end}<br />
		<strong><a class="delete_update_backage" href="{URL_DELETE}" title="{LANG.update_package_delete}">{LANG.update_package_delete}</a></strong>
		<script type="text/javascript">
		var completeUpdate = 0;
		$(document).ready(function(){
			$('.delete_update_backage').click(function(){
				if( completeUpdate == 0 ){
					if( confirm( nv_is_del_confirm[0] ) ){
						$('#infodetectedupg').append('<div id="dpackagew"><img src="{NV_BASE_SITEURL}images/load_bar.gif" alt="Waiting..."/></div>');
						$.get( $(this).attr('href') , function(e){
							$('#dpackagew').remove()
							if( e == 'OK' ){
								completeUpdate = 1;
								$('#endupdate').append(
									'<div class="infook">' +
										'{LANG.update_package_deleted}<br />' +
										'<a href="{URL_GOHOME}" title="{LANG.gohome}">{LANG.gohome}</a> - ' +
										'<a href="{URL_GOADMIN}" title="{LANG.update_goadmin}">{LANG.update_goadmin}</a>' +
									'</div>'
								);
							}else{
								alert(e);
								$('#endupdate').append(
									'<div class="infoerror">' +
										'{LANG.update_package_not_deleted}<br />' +
										'<a href="{URL_GOHOME}" title="{LANG.gohome}">{LANG.gohome}</a> - ' +
										'<a href="{URL_GOADMIN}" title="{LANG.update_goadmin}">{LANG.update_goadmin}</a>' +
									'</div>'
								);
							}
						});
					}
				}
				return !1;
			});
		});
		</script>
	</div>
</div>
<!-- END: main -->