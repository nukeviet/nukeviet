<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->
<!-- BEGIN: errorfile -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<tbody>
			<tr>
				<td class="text-center"><strong style="color:red">{LANG.autoinstall_module_error_warning_fileexist}</strong></td>
			</tr>
			<!-- BEGIN: loop -->
			<tr>
				<td style="color:red">{FILENAME}</td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<!-- END: errorfile -->
<!-- BEGIN: errorfolder -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<tbody>
			<tr>
				<td class="text-center"><strong style="color:red">{LANG.autoinstall_module_error_warning_invalidfolder}</strong></td>
			</tr>
			<!-- BEGIN: loop -->
			<tr>
				<td style="color:red">{FILENAME}</td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<!-- END: errorfolder -->
<!-- BEGIN: infoerror -->
<div id="checkmessage">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<tbody>
				<tr>
					<td class="text-center"><strong style="color:red">{LANG.autoinstall_module_error_warning_overwrite}</strong></td>
				</tr>
				<tr>
					<td class="text-center"><input type="button" name="install_content_overwrite" value="{LANG.autoinstall_module_overwrite}" class="btn btn-primary"/></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<script type="text/javascript">
	//<![CDATA[
	$(function() {
		$("input[name=install_content_overwrite]").click(function() {
			if (confirm("{LANG.autoinstall_module_error_warning_overwrite}")) {
				$("#checkmessage").html('<div style="text-align:center"><strong>{LANG.autoinstall_package_processing}</strong><br /><br /><img src="{NV_BASE_SITEURL}images/load_bar.gif" alt="" /></div>');
				$("#checkmessage").load("{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=install_check&overwrite={CHECKSESS}");
			}
		});
	});
	//]]>
</script>
<!-- END: infoerror -->
<!-- END: main -->

<!-- BEGIN: complete -->
<!-- BEGIN: no_extract -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<tbody>
			<tr>
				<td class="text-center"><strong style="color:red">{LANG.autoinstall_module_cantunzip}</strong></td>
			</tr>
			<!-- BEGIN: loop -->
			<tr>
				<td style="color:red">{FILENAME}</td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<!-- END: no_extract -->
<!-- BEGIN: error_create_folder -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<tbody>
			<tr>
				<td class="text-center"><strong style="color:red">{LANG.autoinstall_module_error_warning_permission_folder}</strong></td>
			</tr>
			<!-- BEGIN: loop -->
			<tr>
				<td style="color:red">{FILENAME}</td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<!-- END: error_create_folder -->
<!-- BEGIN: error_move_folder -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<tbody>
			<tr>
				<td class="text-center"><strong style="color:red">{LANG.autoinstall_module_error_movefile}</strong></td>
			</tr>
			<!-- BEGIN: loop -->
			<tr>
				<td style="color:red">{FILENAME}</td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<!-- END: error_move_folder -->
<!-- BEGIN: ok -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<tbody>
			<tr>
				<td class="text-center"><strong class="text-success">{LANG.autoinstall_module_unzip_success}</strong></td>
			</tr>
			<tr>
				<td class="text-center"><a href="{URL_GO}" title="{LANG.autoinstall_module_unzip_setuppage}">{LANG.autoinstall_module_unzip_setuppage}</a></td>
			</tr>
		</tbody>
	</table>
</div>
<script type="text/javascript">
	//<![CDATA[
	setTimeout("redirect_page()", 5000);
	function redirect_page() {
		parent.location = "{URL_GO}";
	}

	//]]>
</script>
<!-- END: ok -->
<!-- END: complete -->