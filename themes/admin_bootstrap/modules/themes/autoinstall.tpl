<!-- BEGIN: error -->
<table class="table">
	<tbody>
		<tr>
			<td class="requie text-center"><strong>{GLANG.error_zlib_support}</strong></td>
		</tr>
	</tbody>
</table>
<!-- END: error -->
<!-- BEGIN: main -->
<div id="step1">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<tfoot>
				<tr>
					<td colspan="2" class="text-center"><input name="method" type="button" value="{LANG.autoinstall_continue}" class="btn btn-primary" /></td>
				</tr>
			</tfoot>
			<tbody>
				<tr>
					<td class="text-right"><strong class="text-middle">{LANG.autoinstall_theme_install}: </strong></td>
					<td>
					<select name="installtype" class="form-control w200">
						<option value="0">{LANG.autoinstall_method_none}</option>
						<option value="install_theme">{LANG.autoinstall_method_install}</option>
						<option value="package_theme">{LANG.autoinstall_method_packet}</option>
						<option value="package_theme_module">{LANG.autoinstall_method_packet_module}</option>
					</select></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<div id="content">
	&nbsp;
</div>
<script type="text/javascript">
	//<![CDATA[
	$(function() {
		$("input[name=method]").click(function() {
			var method = $("select[name=installtype]").val();
			if (method != 0) {
				$("#step1").slideUp();
				$("#content").load("{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=" + method);
				$("#content").slideDown();
			} else {
				alert("{LANG.autoinstall_error_nomethod}");
				return false;
			}
		});
	});
	//]]>
</script>
<!-- END: main -->