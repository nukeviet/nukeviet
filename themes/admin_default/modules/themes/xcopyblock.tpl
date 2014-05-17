<!-- BEGIN: main -->
<div class="alert alert-info"><span id="message">{LANG.xcopyblock_notice}</span></div>
<form name="copy_block" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}" method="post">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<tfoot>
				<tr>
					<td class="text-center" colspan="4"><input name="continue" type="button" value="{LANG.xcopyblock_process}" class="btn btn-primary" /></td>
				</tr>
			</tfoot>
			<tbody>
				<tr>
					<td class="text-right"><strong class="text-middle">{LANG.xcopyblock} {LANG.xcopyblock_from}: </strong><input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}"/></td>
					<td width="200">
						<select name="theme1" class="form-control w200 pull-left">
							<option value="0">{LANG.autoinstall_method_theme_none}</option>
							<!-- BEGIN: theme_from -->
							<option value="{THEME_FROM}">{THEME_FROM}</option>
							<!-- END: theme_from -->
						</select>
					</td>
					<td><strong class="text-middle">{LANG.xcopyblock_to}: </strong></td>
					<td>
					<select name="theme2" class="form-control w200 pull-left">
						<option value="0">{LANG.autoinstall_method_theme_none}</option>
						<!-- BEGIN: theme_to -->
						<option value="{THEME_TO.key}"{THEME_TO.selected}>{THEME_TO.key}</option>
						<!-- END: theme_to -->
					</select></td>
				</tr>
				<tr>
					<td colspan="4" class="text-center">
					<p id="loadposition" style="color:red;font-weight:bold">
						&nbsp;
					</p></td>
				</tr>
			</tbody>
		</table>
	</div>
</form>
<script type="text/javascript">
	//<![CDATA[
	$(document).ready(function() {
		$("select[name=theme1]").change(function() {
			var theme1 = $(this).val();
			var theme2 = $("select[name=theme2]").val();
			if (theme2 != 0 && theme1 != 0 && theme1 != theme2) {
				$("#loadposition").html('<img src="{NV_BASE_SITEURL}images/load_bar.gif" alt="" />{LANG.autoinstall_package_processing}');
				$("#loadposition").load("{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=loadposition&theme2=" + theme2 + "&theme1=" + theme1);
			} else {
				$("#loadposition").html("");
			}
		});
		$("select[name=theme2]").change(function() {
			var theme2 = $(this).val();
			var theme1 = $("select[name=theme1]").val();
			if (theme2 != 0 && theme1 != 0 && theme1 != theme2) {
				$("#loadposition").html('<img src="{NV_BASE_SITEURL}images/load_bar.gif" alt="" />{LANG.autoinstall_package_processing}');
				$("#loadposition").load("{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=loadposition&theme2=" + theme2 + "&theme1=" + theme1);
			} else {
				$("#loadposition").html("");
			}
		});
		$("input[name=continue]").click(function() {
			var theme1 = $("select[name=theme1]").val();
			var theme2 = $("select[name=theme2]").val();
			var positionlist = [];
			$('input[name="position[]"]:checked').each(function() {
				positionlist.push($(this).val());
			});
			if (positionlist.length < 1) {
				alert("{LANG.xcopyblock_no_position}");
				return false;
			} else {
				$("#loadposition").html('<img src="{NV_BASE_SITEURL}images/load_bar.gif" alt="" />{LANG.autoinstall_package_processing}');
				$.ajax({
					type : "POST",
					url : "{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=xcopyprocess",
					data : "position=" + positionlist + "&theme1=" + theme1 + "&theme2=" + theme2,
					success : function(data) {
						$("#loadposition").html(data);
					}
				});
			}
		});
	});
	//]]>
</script>
<!-- END: main -->