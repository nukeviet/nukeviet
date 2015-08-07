<!-- BEGIN: complete -->
<a href="{LINKGETFILE}"><span style="font-size:16px;color:red">nv4_theme_{THEMENAME}_{MODULENAME} - {FILESIZE}</span></a>
<!-- END: complete -->
<!-- BEGIN: main -->
<form name="install_theme" enctype="multipart/form-data" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}" method="post">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<tbody>
				<tr>
					<th class="text-right">{LANG.autoinstall_method_theme_none}:<input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}"/></th>
					<td>
						<select name="themename" class="form-control w200">
							<option value="0">{LANG.autoinstall_method_theme_none}</option>
							<!-- BEGIN: theme -->
							<option value="{THEME}">{THEME}</option>
							<!-- END: theme -->
						</select>
					</td>
				</tr>
				<tr>
					<th class="text-right">{LANG.autoinstall_method_module_none}:</th>
					<td>
						<!-- BEGIN: module -->
						<input type="checkbox" value="{MODULE.module_file}" name="module_file[]"> {MODULE.custom_title}<br>
						<!-- END: module -->
					</td>
				</tr>
				<tr>
					<td colspan="2" class="text-center"><input name="continue_ptm" type="button" value="{LANG.autoinstall_continue}" class="btn btn-primary" /></td>
				</tr>
				<tr>
					<td colspan="2" class="text-center">
					<p id="message" style="color: red;display:none">
						&nbsp;
					</p></td>
				</tr>
			</tbody>
		</table>
	</div>
</form>
<script type="text/javascript">
//<![CDATA[
LANG.autoinstall_package_processing = "{LANG.autoinstall_package_processing}";
LANG.package_noselect_module_theme = "{LANG.package_noselect_module_theme}";
//]]>
</script>
<!-- END: main -->