<!-- BEGIN: complete -->
<a href="{LINKGETFILE}"><span style="font-size:16px;color:red">nv4_theme_{THEMENAME} - {FILESIZE}</span></a>
<!-- END: complete -->
<!-- BEGIN: main -->
<form name="install_theme" enctype="multipart/form-data" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}" method="post">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<tbody>
				<tr>
					<input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}"/>
					<td class="text-right" colspan="2"><strong class="text-middle">{LANG.autoinstall_package_select}: </strong></td>
					<td>
						<select name="themename" class="form-control w200">
							<option value="0">{LANG.autoinstall_method_none}</option>
							<!-- BEGIN: theme -->
							<option value="{THEME}">{THEME}</option>
							<!-- END: theme -->
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="3" class="text-center"><input name="continue" type="button" value="{LANG.autoinstall_continue}" class="btn btn-primary" /> <input name="back" type="button" value="{LANG.back}" class="btn btn-primary" /></td>
				</tr>
				<tr>
					<td colspan="3" class="text-center">
					<p id="message" style="color:red;display:none">
						&nbsp;
					</p></td>
				</tr>
			</tbody>
		</table>
	</div>
</form>
<script type="text/javascript">
	$(function() {
		//<![CDATA[
		$("input[name=continue]").click(function() {
			var themename = $("select[name=themename]").val();
			if (themename != 0) {
				$("#message").html('<img src="{NV_BASE_SITEURL}images/load_bar.gif" alt=""/>{LANG.autoinstall_package_processing}');
				$("#message").fadeIn();
				$("input[name=continue]").attr("disabled", "disabled");
				$("input[name=back]").attr("disabled", "disabled");
				$("#step1").slideUp();
				$.ajax({
					type : "POST",
					url : "{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}",
					data : "themename=" + themename + "&{NV_OP_VARIABLE}={OP}",
					success : function(data) {
						$("input[name=back]").removeAttr("disabled");
						$("input[name=continue]").removeAttr("disabled");
						$("#message").html(data);
					}
				});
			} else {
				alert("{LANG.autoinstall_package_noselect}");
				return false;
			}
		});
		$("input[name=back]").click(function() {
			$("#content").slideUp();
			$("#step1").slideDown();
		});
	});
	//]]>
</script>
<!-- END: main -->