<!-- BEGIN: error -->
<table class="tab1 fixtab">
	<tbody>
		<tr>
			<td class="requie center">
				<strong>{GLANG.error_zlib_support}</strong>
			</td>
		</tr>
	</tbody>
</table>
<!-- END: error -->
<!-- BEGIN: main -->
<div id="step1">
	<table class="tab1">
		<tbody class="second">
			<tr>
				<td align="right"><strong>{LANG.autoinstall_theme_install}: </strong></td>
				<td>
					<select name="installtype">
						<option value="0">{LANG.autoinstall_method_none}</option>
						<option value="install_theme">{LANG.autoinstall_method_install}</option>
						<option value="package_theme">{LANG.autoinstall_method_packet}</option>
						<option value="package_theme_module">{LANG.autoinstall_method_packet_module}</option>
					</select>
				</td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td colspan="2" class="center">
					<input name="method" type="button" value="{LANG.autoinstall_continue}" />
				</td>
			</tr>
		</tbody>
	</table>
</div>
<div id="content"></div>
<script type="text/javascript">
//<![CDATA[
$(function(){
 	$("input[name=method]").click(function(){
 		var method = $("select[name=installtype]").val();
 		if (method!=0){
 			$("#step1").slideUp();
 			$("#content").load("{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}="+method );
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