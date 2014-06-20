<!-- BEGIN: main -->
<form name="install_theme" enctype="multipart/form-data" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}" method="post">
	<table class="table">
		<tbody>
			<tr>
				<td class="text-right"><strong>{LANG.autoinstall_theme_select_file}: </strong></td>
				<td><input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}"/><input type="file" name="themefile"/></td>
			</tr>
			<tr>
				<td colspan="2" class="text-center"><input name="continue" type="button" value="{LANG.autoinstall_continue}" class="btn btn-primary" /> <input name="back" type="button" value="{LANG.back}" class="btn btn-primary" /></td>
			</tr>
		</tbody>
	</table>
</form>
<script type="text/javascript">
	//<![CDATA[
	function checkext(myArray, myValue) {
		var type = eval(myArray).join().indexOf(myValue) >= 0;
		return type;
	}

	$(function() {
		$("input[name=continue]").click(function() {
			var themefile = $("input[name=themefile]").val();
			if (themefile == "") {
				alert("{LANG.autoinstall_theme_error_nofile}");
				return false;
			}
			var filezip = themefile.slice(-3);
			var filegzip = themefile.slice(-2);
			var allowext = new Array("zip", "gz");
			if (!checkext(allowext, filezip) || !checkext(allowext, filegzip)) {
				alert("{LANG.autoinstall_theme_error_filetype}");
				return false;
			}
			$("form[name=install_theme]").submit();
		});
		$("input[name=back]").click(function() {
			$("#content").slideUp();
			$("#step1").slideDown();
		});
	});
	//]]>
</script>
<!-- END: main -->

<!-- BEGIN: info -->
<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->
<!-- BEGIN: fileinfo -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<tbody>
			<tr>
				<td class="text-center"> {LANG.autoinstall_theme_uploadedfile} <strong style="color:red">{INFO.filename}</strong> - {LANG.autoinstall_theme_uploadedfilesize} <strong style="color:red">{INFO.filesize}</strong></td>
			</tr>
			<tr>
				<td class="text-center"> {LANG.autoinstall_theme_uploaded_filenum} <strong style="color:red">{INFO.filenum}</strong></td>
			</tr>
		</tbody>
	</table>
</div>
<div id="filelist">
	<!-- BEGIN: file -->
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<tbody>
				<!-- BEGIN: loop -->
				<tr>
					<td> {FILE} </td>
				</tr>
				<!-- END: loop -->
			</tbody>
		</table>
	</div>
	<!-- END: file -->
</div>
<div id="message" style="display:none;text-align:center;color:red">
	<table class="table">
		<tbody>
			<tr>
				<td class="text-center"><img src="{NV_BASE_SITEURL}images/load_bar.gif" alt="" />
				<br />
				{LANG.autoinstall_package_processing} </td>
			</tr>
		</tbody>
	</table>
</div>
<div id="step1">
	<table class="table">
		<tbody>
			<tr>
				<td class="text-center">
				<p><strong>{LANG.autoinstall_theme_checkfile_notice}</strong></p>
				<input type="button" name="checkfile" value="{LANG.autoinstall_theme_checkfile}" class="btn btn-primary"/></td>
			</tr>
		</tbody>
	</table>
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
<!-- END: fileinfo -->
<!-- END: info -->