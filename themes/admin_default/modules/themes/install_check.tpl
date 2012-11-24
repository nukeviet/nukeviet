<!-- BEGIN: exists -->
<div id="message" style="display:none;text-align:center;color:red">
	<img src="{NV_BASE_SITEURL}images/load_bar.gif" alt="" />{LANG.autoinstall_package_processing}
</div>
<table class="tab1 fixtab">
	<tbody>
		<tr>
			<td style="color:red">
				<strong>{LANG.autoinstall_theme_error_warning_fileexist}</strong>
			</td>
		</tr>
	</tbody>
</table>
<div style="overflow:auto;max-height:300px;width:100%">
	<table class="tab1 fixtab">
		<!-- BEGIN: loop -->
		<tbody{CLASS}>
			<tr>
				<td style="color:red">
					{FILE}
				</td>
			</tr>
		</tbody>
		<!-- END: loop -->
	</table>
</div>
<table class="tab1 fixtab">
	<tbody>
		<tr>
			<td style="color:red">
				<b>{LANG.autoinstall_theme_error_warning_overwrite}</b>
			</td>
		</tr>
	</tbody>
</table>
<table class="tab1 fixtab">
	<tbody>
		<tr>
			<td class="center">
				<input type="button" name="install_content_overwrite" value="{LANG.autoinstall_theme_overwrite}"/>
			</td>
		</tr>
	</tbody>
</table>
<script type="text/javascript">
//<![CDATA[		 
$(function(){
	$("input[name=install_content_overwrite]").click(function(){
		if(confirm("{LANG.autoinstall_theme_error_warning_overwrite}")){
			$("#message").show();
			$("#step1").html("");
			$("#step1").load("{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}={OP}&overwrite={OVERWRITE}", function(){
				$("#message").hide();
			});
		}
	});
});
//]]>
</script>
<!-- END: exists -->
<!-- BEGIN: nounzip -->
<table class="tab1 fixtab">
	<tbody>
		<tr>
			<td class="center">{LANG.autoinstall_theme_cantunzip}</td>
		</tr>
	</tbody>
	<tbody>
		<tr>
			<td class="center"><input type="button" name="checkfile" value="{LANG.autoinstall_theme_checkfile}"/></td>
		</tr>
	</tbody>
</table>
<div id="message" style="display:none;text-align:center;color:red">
	<img src="{NV_BASE_SITEURL}images/load_bar.gif" alt="" />{LANG.autoinstall_package_processing}
</div>
<script type="text/javascript">
$(function(){
//<![CDATA[	
	$("input[name=checkfile]").click(function(){
		$("#message").show();
		$("#step1").html("");
		$("#step1").load("{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=install_check", function(){
			$("#message").hide();
		});
	});
});
//]]>
</script>
<!-- END: nounzip -->
<!-- BEGIN: error_create_folder -->
<table class="tab1 fixtab">
	<tbody>
		<tr>
			<td class="center requie">{LANG.autoinstall_theme_permission_folder}</td>
		</tr>
	</tbody>
	<!-- BEGIN: loop -->
	<tbody{CLASS}>
		<tr>
			<td class="requie">{FOLDER}</td>
		</tr>
	</tbody>
	<!-- END: loop -->
</table>
<!-- END: error_create_folder -->
<!-- BEGIN: error_move_folder -->
<table class="tab1 fixtab">
	<tbody>
		<tr>
			<td class="center requie">{LANG.autoinstall_theme_error_movefile}</td>
		</tr>
	</tbody>
	<!-- BEGIN: loop -->
	<tbody{CLASS}>
		<tr>
			<td class="requie">{FOLDER}</td>
		</tr>
	</tbody>
	<!-- END: loop -->
</table>
<!-- END: error_move_folder -->
<!-- BEGIN: complete -->
<table class="tab1 fixtab">
	<tbody>
		<tr>
			<td class="center" style="color:green">
				<strong>{LANG.autoinstall_theme_unzip_success}</strong><br /><br />
				<a href="{NV_REDIRECT}" title="{LANG.autoinstall_theme_unzip_setuppage}">{LANG.autoinstall_theme_unzip_setuppage}</a>
			</td>
		</tr>
	</tbody>
</table>
<script type="text/javascript">
//<![CDATA[
setTimeout("redirect_page()",5000);
function redirect_page(){
	parent.location="{NV_REDIRECT}";
}
//]]>
</script>
<!-- END: complete -->