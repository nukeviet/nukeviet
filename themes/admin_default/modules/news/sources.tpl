<!-- BEGIN: main -->
<div id="module_show_list">
	{SOURCES_LIST}
</div>
<br />
<a id="edit"></a>
<!-- BEGIN: error -->
<div class="quote">
	<blockquote class="error">
		<span>{ERROR}</span>
	</blockquote>
</div>
<!-- END: error -->
<form enctype="multipart/form-data" action="{NV_BASE_ADMINURL}index.php" method="post">
	<input type="hidden" name ="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
	<input type="hidden" name ="{NV_OP_VARIABLE}" value="{OP}" />
	<input type="hidden" name ="sourceid" value="{sourceid}" />
	<input name="savecat" type="hidden" value="1" />
	<table class="tab1">
		<caption>{LANG.add_sources}</caption>
		<tfoot>
			<tr>
				<td class="center" colspan="2"><input name="submit1" type="submit" value="{LANG.save}" /></td>
			</tr>
		</tfoot>
		<tbody>
			<tr>
				<td class="right"><strong>{LANG.name}: </strong></td>
				<td><input class="w500" name="title" type="text" value="{title}" maxlength="255" /></td>
			</tr>
			<tr>
				<td class="right"><strong>{LANG.link}: </strong></td>
				<td><input class="w500" name="link" type="text" value="{link}" maxlength="255" /></td>
			</tr>
			<tr>
				<td class="right"><strong>{LANG.source_logo}: </strong></td>
				<td><input class="w500" type="text" name="logo" id="logo" value="{logo}"/> <input type="button" value="{GLANG.browse_image}" name="selectimg"/>
				<!-- BEGIN: logo -->
				<br />
				<img src="{logo}"/></td>
				<!-- END: logo -->
				</td>
			</tr>
		</tbody>
	</table>
	<br />
</form>
<script type="text/javascript">
//<![CDATA[
$("input[name=selectimg]").click(function() {
	var area = "logo";
	var path = "{NV_UPLOADS_DIR}/{MODULE_NAME}/source";
	var type = "image";
	nv_open_browse_file("{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}=upload&popup=1&area=" + area + "&path=" + path + "&type=" + type, "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
	return false;
});
//]]>
</script>
<!-- END: main -->