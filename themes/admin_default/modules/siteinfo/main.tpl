<!-- BEGIN: main -->
<!-- BEGIN: updateinfo -->
<div class="infoalert" id="infodetectedupg">
	{LANG.update_package_detected}<br />
	<strong><a href="{URL_UPDATE}" title="{LANG.update_package_do}">{LANG.update_package_do}</a></strong> - <strong><a href="{URL_DELETE_PACKAGE}" title="{LANG.update_package_delete}" class="delete_update_backage">{LANG.update_package_delete}</a></strong>
	<script type="text/javascript">
	$(document).ready(function(){
		$('.delete_update_backage').click(function(){
			if( confirm( nv_is_del_confirm[0] ) ){
				$('#infodetectedupg').append('<div id="dpackagew"><img src="' + nv_siteroot + 'images/load_bar.gif" alt="Waiting..."/></div>');
				$.get( $(this).attr('href') , function(e){
					$('#dpackagew').remove()
					if( e == 'OK' ){
						$('#infodetectedupg').slideUp(500, function(){ $('#infodetectedupg').remove() });
					}else{
						alert(e);
					}
				});
			}
			return !1;
		});
	});
	</script>
</div>
<!-- END: updateinfo -->
<!-- BEGIN: pendinginfo -->
<table class="tab1">
	<caption>{LANG.pendingInfo}</caption>
	<col valign="top" width="20%" />
	<col valign="top" />
	<col valign="top" width="10%" />
	<thead>
		<tr>
			<td>{LANG.moduleName}</td>
			<td>{LANG.moduleContent}</td>
			<td class="aright">{LANG.moduleValue}</td>
		</tr>
	</thead>
	<!-- BEGIN: loop -->
	<tbody{CLASS}>
		<tr>
			<td>{MODULE}</td>
			<td>
				<!-- BEGIN: link -->
				<a class="link" href="{LINK}" title="{KEY}">{KEY}</a>
				<!-- END: link -->
				<!-- BEGIN: text -->
				{KEY}
				<!-- END: text -->
			</td>
			<td class="aright">{VALUE}</td>
		</tr>
	</tbody>
	<!-- END: loop -->
</table>
<!-- END: pendinginfo -->
<!-- BEGIN: info -->
<table class="tab1">
	<caption>{LANG.moduleInfo}</caption>
	<col valign="top" width="20%" />
	<col valign="top" />
	<col valign="top" width="10%" />
	<thead>
		<tr>
			<td>{LANG.moduleName}</td>
			<td>{LANG.moduleContent}</td>
			<td class="aright">{LANG.moduleValue}</td>
		</tr>
	</thead>
	<!-- BEGIN: loop -->
	<tbody{CLASS}>
		<tr>
			<td>{MODULE}</td>
			<td>
				<!-- BEGIN: link -->
				<a class="link" href="{LINK}" title="{KEY}">{KEY}</a>
				<!-- END: link -->
				<!-- BEGIN: text -->
				{KEY}
				<!-- END: text -->
			</td>
			<td class="aright">{VALUE}</td>
		</tr>
	</tbody>
	<!-- END: loop -->
</table>
<!-- END: info -->
<!-- BEGIN: version -->
<table class="tab1">
	<caption>{LANG.version} <span style="font-weight:400">(<a href="{ULINK}">{CHECKVERSION}</a>)</span></caption>
	<thead>
		<tr>
			<td>{LANG.moduleContent}</td>
			<td class="aright">{LANG.moduleValue}</td>
		</tr>
	</thead>
	<!-- BEGIN: loop -->
	<tbody{CLASS}>
		<tr>
			<td>{KEY}</td>
			<td class="aright">{VALUE}</td>
		</tr>
	</tbody>
	<!-- END: loop -->
</table>
<!-- BEGIN: inf --><div class="newVesionInfo">{INFO}</div><!-- END: inf -->
<!-- END: version -->
<!-- END: main -->