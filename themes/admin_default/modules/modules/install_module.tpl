<!-- BEGIN: main -->
<form name="install_module" enctype="multipart/form-data" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}" method="post">
	<table class="tab1">
		<tbody>
			<tr>
				<td class="right"><strong>{LANG.autoinstall_module_select_file}: </strong></td>
				<td><input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}"/><input type="file" name="modulefile"/></td>
			</tr>
			<tr>
				<td colspan="2" class="center"><input name="continue" type="button" value="{LANG.autoinstall_continue}" /> <input name="back" type="button" value="{LANG.autoinstall_back}" /></td>
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
			var modulefile = $("input[name=modulefile]").val();
			if (modulefile == "") {
				alert("{LANG.autoinstall_module_error_nofile}");
				return false;
			}
			var filezip = modulefile.slice(-3);
			var filegzip = modulefile.slice(-2);
			var allowext = new Array("zip", "gz");
			if (! checkext(allowext, filezip) || ! checkext(allowext, filegzip)) {
				alert("{LANG.autoinstall_module_error_filetype}");
				return false;
			}
			$("form[name=install_module]").submit();
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
<div class="quote">
	<blockquote class="error">
		<p>
			<span>{ERROR}</span>
		</p></blockquote>
</div>
<!-- END: error -->
<!-- BEGIN: fileinfo -->
<table class="tab1">
	<tbody>
		<tr>
			<td class="center"> {LANG.autoinstall_module_uploadedfile} <strong style="color:red">{INFO.filename}</strong> - {LANG.autoinstall_module_uploadedfilesize} <strong style="color:red">{INFO.filesize}</strong></td>
		</tr>
		<tr>
			<td class="center"> {LANG.autoinstall_module_uploaded_filenum} <strong style="color:red">{INFO.filenum}</strong></td>
		</tr>
	</tbody>
</table>
<table class="tab1">
	<tbody>
		<tr>
			<td class="center"><strong>{LANG.autoinstall_module_checkfile_notice}</strong>
			<br />
			<br />
			<input type="button" name="checkfile" value="{LANG.autoinstall_module_checkfile}"/></td>
		</tr>
	</tbody>
</table>
<script type="text/javascript">
	$(function() {
		$("input[name=checkfile]").click(function() {
			$("#filelist").html('<div style="text-align:center"><strong>{LANG.autoinstall_package_processing}</strong><br /><br /><img src="{NV_BASE_SITEURL}images/load_bar.gif" alt="" /></div>');
			$("#filelist").load("{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=install_check");
		});
	});
</script>
<div id="filelist">
	<!-- BEGIN: file -->
	<table class="tab1">
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td> {FILE} </td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
	<!-- END: file -->
</div>
<!-- END: fileinfo -->
<!-- END: info -->