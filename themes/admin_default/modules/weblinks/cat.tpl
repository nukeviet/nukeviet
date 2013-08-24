<!-- BEGIN: main -->
<!-- BEGIN: data -->
<script type="text/javascript">var url_back = '{url_back}';</script>
<table class="tab1">
	<thead>
		<tr class="center">
			<td class="w50">{LANG.weight}</td>
			<td>{LANG.name}</td>
			<td class="w100">{LANG.inhome}</td>
			<td class="w150">&nbsp;</td>
		</tr>
	</thead>
	<tbody>
		<!-- BEGIN: loop -->
		<tr>
			<td class="center">{ROW.weight_select}</td>
			<td><a href="{ROW.link_add}">{ROW.title}</a></td>
			<td class="center">{ROW.inhome_select}</td>
			<td class="center"><a class="edit_icon" href="{ROW.link_edit}">{LANG.edit}</a>&nbsp;-&nbsp; <a class="delete_icon" href="javascript:void(0);" onclick="nv_del_cat({ROW.catid})">{LANG.delete}</a></td>
		</tr>
		<!-- END: loop -->
	</tbody>
</table>
<!-- END: data -->

<!-- BEGIN: error -->
<div class="quote">
	<blockquote class="error"><span>{error}</span></blockquote>
</div>
<!-- END: error -->
<form action="{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<input type="hidden" name ="catid" value="{DATA.catid}" />
	<input type="hidden" name ="parentid_old" value="{DATA.parentid}" />
	<input name="savecat" type="hidden" value="1" />
	<table class="tab1">
		<caption>{caption}</caption>
		<tbody>
			<tr>
				<td class="right"><strong>{LANG.name} : </strong></td>
				<td><input style="width: 650px" name="title" type="text" value="{DATA.title}" maxlength="255" /></td>
			</tr>
			<tr>
				<td class="right w100"><strong>{LANG.alias} : </strong></td>
				<td><input style="width: 650px" name="alias" type="text" value="{DATA.alias}" maxlength="255" /></td>
			</tr>
			<tr>
				<td class="right"><strong>{LANG.cat_sub} : </strong></td>
				<td>
				<select name="parentid">
					<option value="0">{LANG.weblink_parent}</option>
					<!-- BEGIN: loopcat -->
					<option value="{CAT.catid}" {CAT.sl}>{CAT.xtitle}</option>
					<!-- END: loopcat -->
				</select></td>
			</tr>
			<tr>
				<td class="right"><strong>{LANG.weblink_fileimage} : </strong></td>
				<td><input style="width:380px" type="text" name="catimage" id="catimage" value="{DATA.catimage}"/><input type="button" value="Browse server" name="selectimg"/></td>
			</tr>
			<tr>
				<td class="right"><strong>{LANG.keywords} : </strong></td>
				<td><input style="width: 650px" name="keywords" type="text" value="{DATA.keywords}" maxlength="255" /></td>
			</tr>
			<tr>
				<td class="right">
				<br />
				<strong>{LANG.description} </strong></td>
				<td><textarea style="width: 650px" name="description" cols="100" rows="5">{DATA.description}</textarea></td>
			</tr>
			<tr>
				<td colspan="2" class="center"><input name="submit1" type="submit" value="{LANG.save}" /></td>
			</tr>
		</tbody>
	</table>
</form>
<script type="text/javascript">
	//<![CDATA[
	$("input[name=selectimg]").click(function() {
		var area = "catimage";
		var path = "{PATH}";
		var currentpath = "{UPLOAD_CURRENT}";
		var type = "image";
		nv_open_browse_file(script_name + "?" + nv_name_variable + "=upload&popup=1&area=" + area + "&path=" + path + "&type=" + type + "&currentpath=" + currentpath, "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
		return false;
	});
</script>
<!-- END: main -->