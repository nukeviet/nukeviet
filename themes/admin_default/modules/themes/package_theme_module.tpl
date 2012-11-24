<!-- BEGIN: complete -->
<a href="{LINKGETFILE}"><span style="font-size:16px;color:red">nv3_theme_{THEMENAME}_{MODULENAME} - {FILESIZE}</span></a>
<!-- END: complete -->
<!-- BEGIN: main -->
<form name="install_theme" enctype="multipart/form-data" action="{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}" method="post">
	<table class="tab1">
		<tbody class="second">
			<tr>
				<td align="center" colspan="2"><strong>{LANG.autoinstall_package_module_select}: </strong>
					<input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}"/>
					<select name="themename">
						<option value="0">{LANG.autoinstall_method_theme_none}</option>
						<!-- BEGIN: theme -->
						<option value="{THEME}">{THEME}</option>
						<!-- END: theme -->
					</select>
					<select name="modulename">
						<option value="0">{LANG.autoinstall_method__module_none}</option>
						<!-- BEGIN: module -->
						<option value="{MODULE.module_file}">{MODULE.custom_title}</option>
						<!-- END: module -->
					</select>
				</td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td colspan="2" class="center">
					<input name="continue" type="button" value="{LANG.autoinstall_continue}" />
					<input name="back" type="button" value="{LANG.autoinstall_back}" />
				</td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td colspan="2" class="center">
					<p id="message" style="color: red;display:none"></p>
				</td>
			</tr>
		</tbody>
	</table>
</form>
<script type="text/javascript">
//<![CDATA[
$(function(){
 	$("input[name=continue]").click(function(){
 		var themename = $("select[name=themename]").val();
 		var modulename = $("select[name=modulename]").val();
	    if ( themename != 0 && modulename != 0 ){
			$("#message").html('<img src="{NV_BASE_SITEURL}images/load_bar.gif" alt="" />{LANG.autoinstall_package_processing}');
 			$("#message").fadeIn();
 			$("input[name=continue]").attr("disabled", "disabled");
 			$("input[name=back]").attr("disabled", "disabled");
 			$("#step1").slideUp();
			$.ajax({	
				type: "POST",
				url: "{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}",
				data: "themename="+ themename+"&modulename="+modulename+"&{NV_OP_VARIABLE}={OP}",
				success: function(data){				
					$("input[name=back]").removeAttr( "disabled");
					$("input[name=continue]").removeAttr( "disabled");
					$("#message").html( data );
    			}
			});
		} else {
			alert("{LANG.autoinstall_package_noselect_module_theme}");
			return false;
 		}
 	});
 	$("input[name=back]").click(function(){
 		$("#content").slideUp();
		$("#step1").slideDown();
 	});
});
//]]>
</script>
<!-- END: main -->