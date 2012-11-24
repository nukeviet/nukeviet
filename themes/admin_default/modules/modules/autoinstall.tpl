<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div style="width: 98%;" class="quote">
    <blockquote class="error">
        <p>
            <span>{GLANG.error_zlib_support}</span>
        </p>
    </blockquote>
</div>
<div class="clear"></div>
<!-- END: error -->
<!-- BEGIN: ok -->
<div id="step1">
	<table class="tab1">
		<tbody class="second">
			<tr>
				<td align="right"><strong>{LANG.autoinstall_method}: </strong></td>
				<td>
					<select name="installtype">
						<option value="0">{LANG.autoinstall_method_none}</option>
						<option value="install_module">{LANG.autoinstall_method_module}</option>
						<option value="install_package">{LANG.autoinstall_method_packet}</option>
					</select>
				</td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td colspan="2" align="center">
					<input name="method" type="button" value="{LANG.autoinstall_continue}" />
				</td>
			</tr>
		</tbody>
	</table>
</div>
<div id="content"></div>
<script type="text/javascript">
$(document).ready(function() {
 	$("input[name=method]").click(function(){
 		var method = $("select[name=installtype]").val();
 		if (method!=0){
 			$("#step1").slideUp();
 			$("#content").load("{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=" + method);
 			$("#content").slideDown();
 		} 
		else 
		{
 			alert("{LANG.autoinstall_error_nomethod}");
 			return false;
 		}
 	});
});
</script>
<!-- END: ok -->
<!-- END: main -->