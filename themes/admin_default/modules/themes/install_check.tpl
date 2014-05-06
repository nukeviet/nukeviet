<!-- BEGIN: exists -->
<div id="message" style="display:none;text-align:center;color:red">
	<img src="{NV_BASE_SITEURL}images/load_bar.gif" alt="" />{LANG.autoinstall_package_processing}
</div>
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<tbody>
			<tr>
				<td style="color:red"><strong>{LANG.autoinstall_theme_error_warning_fileexist}</strong></td>
			</tr>
		</tbody>
	</table>
	<div style="overflow:auto;max-height:300px;width:100%">
		<table class="table table-striped table-bordered table-hover">
			<tbody>
				<!-- BEGIN: loop -->
				<tr>
					<td style="color:red"> {FILE} </td>
				</tr>
				<!-- END: loop -->
			</tbody>
		</table>
	</div>
	<table class="table table-striped table-bordered table-hover">
		<tbody>
			<tr>
				<td style="color:red"><strong>{LANG.autoinstall_theme_error_warning_overwrite}</strong></td>
			</tr>
		</tbody>
	</table>
	<table class="table table-striped table-bordered table-hover">
		<tbody>
			<tr>
				<td class="text-center"><input type="button" name="install_content_overwrite" value="{LANG.autoinstall_theme_overwrite}"/></td>
			</tr>
		</tbody>
	</table>
	<script type="text/javascript">
		//<![CDATA[
		$(function() {
			$("input[name=install_content_overwrite]").click(function() {
				if (confirm("{LANG.autoinstall_theme_error_warning_overwrite}")) {
					$("#message").show();
					$("#step1").html("");
					$("#step1").load("{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}={OP}&overwrite={OVERWRITE}", function() {
						$("#message").hide();
					});
				}
			});
		});
		//]]>
	</script>
	<!-- END: exists -->
	<!-- BEGIN: nounzip -->
	<table class="table table-striped table-bordered table-hover">
		<tbody>
			<tr>
				<td class="text-center">{LANG.autoinstall_theme_cantunzip}</td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td class="text-center"><input type="button" name="checkfile" value="{LANG.autoinstall_theme_checkfile}"/></td>
			</tr>
		</tbody>
	</table>
	<div id="message" style="display:none;text-align:center;color:red">
		<img src="{NV_BASE_SITEURL}images/load_bar.gif" alt="" />{LANG.autoinstall_package_processing}
	</div>
	<script type="text/javascript">
		$(function() {
			//<![CDATA[
			$("input[name=checkfile]").click(function() {
				$("#message").show();
				$("#step1").html("");
				$("#step1").load("{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=install_check", function() {
					$("#message").hide();
				});
			});
		});
		//]]>
	</script>
	<!-- END: nounzip -->
	<!-- BEGIN: error_create_folder -->
	<table class="table table-striped table-bordered table-hover">
		<tbody>
			<tr>
				<td class="center requie">{LANG.autoinstall_theme_permission_folder}</td>
			</tr>
		</tbody>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td class="requie">{FOLDER}</td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
	<!-- END: error_create_folder -->
	<!-- BEGIN: error_move_folder -->
	<table class="table table-striped table-bordered table-hover">
		<tbody>
			<tr>
				<td class="center requie">{LANG.autoinstall_theme_error_movefile}</td>
			</tr>
		</tbody>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td class="requie">{FOLDER}</td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
	<!-- END: error_move_folder -->
	<!-- BEGIN: complete -->
	<table class="table table-striped table-bordered table-hover">
		<tbody>
			<tr>
				<td class="text-center" style="color:green"><strong>{NV_REDIRECT_LANG}</strong>
				<br />
				<br />
				<a href="{NV_REDIRECT}" title="{LANG.autoinstall_theme_unzip_setuppage}">{LANG.autoinstall_theme_unzip_setuppage}</a></td>
			</tr>
		</tbody>
	</table>
</div>
<script type="text/javascript">
	//<![CDATA[
	setTimeout("redirect_page()", 5000);
	function redirect_page() {
		parent.location = "{NV_REDIRECT}";
	}
	//]]>
</script>
<!-- END: complete -->