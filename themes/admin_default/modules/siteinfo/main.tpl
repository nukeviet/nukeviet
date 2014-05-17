<!-- BEGIN: main -->
<!-- BEGIN: updateinfo -->
<div class="infoalert" id="infodetectedupg">
	{LANG.update_package_detected}
	<br />
	<strong><a href="{URL_UPDATE}" title="{LANG.update_package_do}">{LANG.update_package_do}</a></strong> - <strong><a href="{URL_DELETE_PACKAGE}" title="{LANG.update_package_delete}" class="delete_update_backage">{LANG.update_package_delete}</a></strong>
	<script type="text/javascript">
		$(document).ready(function() {
			$('.delete_update_backage').click(function() {
				if (confirm(nv_is_del_confirm[0])) {
					$('#infodetectedupg').append('<div id="dpackagew"><img src="' + nv_siteroot + 'images/load_bar.gif" alt="Waiting..."/></div>');
					$.get($(this).attr('href'), function(e) {
						$('#dpackagew').remove()
						if (e == 'OK') {
							$('#infodetectedupg').slideUp(500, function() {
								$('#infodetectedupg').remove()
							});
						} else {
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
<h3>{LANG.pendingInfo}</h3>
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th>{LANG.moduleName}</th>
				<th>{LANG.moduleContent}</th>
				<th>{LANG.moduleValue}</th>
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: loop -->
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
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<!-- END: pendinginfo -->
<!-- BEGIN: info -->
<h3><em class="fa fa-info">&nbsp;</em><strong>{LANG.moduleInfo}</strong></h3>
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th>{LANG.moduleName}</th>
				<th>{LANG.moduleContent}</th>
				<th class="aright">{LANG.moduleValue}</th>
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: loop -->
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
				<td>{VALUE}</td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<!-- END: info -->
<!-- BEGIN: version -->
<h3><em class="fa fa-globe">&nbsp;</em><strong>{LANG.version}</strong> <span style="font-weight:400">(<a href="{ULINK}">{CHECKVERSION}</a>)</span></h3>
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th>{LANG.moduleContent}</th>
				<th>{LANG.moduleValue}</th>
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td>{KEY}</td>
				<td class="aright">{VALUE}</td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<!-- BEGIN: inf -->
<div class="newVesionInfo">
	{INFO}
</div>
<!-- END: inf -->
<!-- END: version -->
<!-- END: main -->