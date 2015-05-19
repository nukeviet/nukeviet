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
					<td colspan="2" class="text-center"><input name="continue" type="button" value="{LANG.autoinstall_continue}" class="btn btn-primary" /></td>
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
	$(function() {
		$("input[name=continue]").click(function() {
			var themename = $("select[name=themename]").val();
			module_file = '';
			$("input[name='module_file[]']:checked").each(function() {
				module_file = module_file + ',' + $(this).val();
			});
			if (themename != 0 && module_file != '') {
				$("#message").html('<img src="{NV_BASE_SITEURL}images/load_bar.gif" alt="" />{LANG.autoinstall_package_processing}');
				$("#message").fadeIn();
				$("input[name=continue]").attr("disabled", "disabled");
				$("#step1").slideUp();
				$.ajax({
					type : "POST",
					url : "{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}",
					data : "themename=" + themename + "&module_file=" + module_file + "&{NV_OP_VARIABLE}={OP}",
					success : function(data) {
						$("input[name=continue]").removeAttr("disabled");
						$("#message").html(data);
					}
				});
			} else {
				alert("{LANG.package_noselect_module_theme}");
				return false;
			}
		});
	});
	//]]>
</script>
<!-- END: main -->