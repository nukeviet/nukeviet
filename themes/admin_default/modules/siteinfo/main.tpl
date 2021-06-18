<!-- BEGIN: main -->
<!-- BEGIN: updateinfo -->
<div class="alert alert-info text-center" id="infodetectedupg">
	<p>{LANG.update_package_detected}</p>
	<strong><a href="{URL_UPDATE}" title="{LANG.update_package_do}">{LANG.update_package_do}</a></strong>
	 - 
	<strong><a href="{URL_DELETE_PACKAGE}" title="{LANG.update_package_delete}" class="delete_update_backage">{LANG.update_package_delete}</a></strong>
</div>
<!-- END: updateinfo -->
<!-- BEGIN: pendinginfo -->
<h3><em class="fa fa-clock-o">&nbsp;</em><strong>{LANG.pendingInfo}</strong></h3>
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
<script type="text/javascript">
	$(function () {
	    $("img.imgstatnkv").attr("src","//static.nukeviet.vn/img.jpg");
	});
</script>
<!-- END: main -->