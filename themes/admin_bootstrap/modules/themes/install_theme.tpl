<!-- BEGIN: autoinstall_theme_error_uploadfile -->
<p class="text-center">
	<br />
	<strong style="color:red">{LANG.autoinstall_theme_error_uploadfile}</strong>
</p>
<!-- END: autoinstall_theme_error_uploadfile -->

<!-- BEGIN: autoinstall_theme_error_invalidfile -->
<p class="text-center">
	<br />
	<strong style="color:red">{LANG.autoinstall_theme_error_invalidfile} <a href="javascript:history.go(-1)">{LANG.autoinstall_theme_error_invalidfile_back}</a></strong>
</p>
<!-- END: autoinstall_theme_error_invalidfile -->
<!-- BEGIN: autoinstall_theme_uploadedfile -->
<table class="table">
	<tbody>
		<tr>
			<td><strong>{LANG.autoinstall_theme_uploadedfile}</strong><span style="color:red;font-weight:bold">{FILENAME}</span> - {LANG.autoinstall_theme_uploadedfilesize}<span style="color:red;font-weight:bold">{FILESIZE}</span></td>
		</tr>
	</tbody>
</table>
<table class="table">
	<tbody>
		<tr>
			<td> {LANG.autoinstall_theme_uploaded_filenum} {FILENUM} </td>
		</tr>
	</tbody>
</table>
<div style="overflow:auto;max-height:300px;width:100%">
	<table class="table">
		<col style="width:40px" />
		<col style="width:50%" />
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td>{FILE.stt}</td>
				<td>{FILE.filename}</td>
				<td>{FILE.size}</td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
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
				<td class="text-center"><strong>{LANG.autoinstall_theme_checkfile_notice}</strong>
				<br />
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
<!-- END: autoinstall_theme_uploadedfile -->
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