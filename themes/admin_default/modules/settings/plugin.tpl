<!-- BEGIN: main-->
<!-- BEGIN: error-->
<div class="quote">
	<blockquote class="error"><span>{ERROR}</span></blockquote>
</div>
<!-- END: error -->
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post" id="form_edit_ftp">
	<table class="tab1">
		<thead>
			<tr class="center">
				<td>{LANG.plugin_area}</td>
				<td>{LANG.plugin_number}</td>
				<td>{LANG.plugin_file}</td>
				<td>{LANG.plugin_func}</td>
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td>{DATA.plugin_area}</td>
				<td class="center">
				<select id="weight_{DATA.pid}" onchange="nv_chang_weight('{DATA.pid}');">
					<!-- BEGIN: weight -->
					<option value="{WEIGHT}" {WEIGHT_SELECTED}>{WEIGHT}</option>
					<!-- END: weight -->
				</select></td>
				<td>{DATA.plugin_file}</td>
				<td class="center"><em class="icon-trash icon-large"> </em><a onclick="return confirm(nv_is_del_confirm[0]);" href="{DATA.plugin_delete}">{LANG.isdel}</a></td>
			</tr>
			<!-- END: loop -->
			<!-- BEGIN: add -->
			<tr>
				<td colspan="4" class="center"> {LANG.plugin_add}
				<select name="plugin_file">
					<option value=""> -- </option>
					<!-- BEGIN: file -->
					<option value="{PLUGIN_FILE}">{PLUGIN_FILE} </option>
					<!-- END: file -->
				</select> &nbsp;
				<select name="plugin_area">
					<option value=""> -- </option>
					<!-- BEGIN: area -->
					<option value="{AREA_VALUE}">{AREA_TEXT} </option>
					<!-- END: area -->
				</select> &nbsp; <input type="submit" name="submit" value="{LANG.submit}" style="width: 100px;"/> &nbsp; <input type="submit" name="delete" onclick="return confirm(nv_is_del_confirm[0]);" value="{LANG.plugin_file_delete}" style="width: 150px;"/></td>
			</tr>
			<!-- END: add -->
		</tbody>
	</table>
</form>
<script type="text/javascript">
	function nv_chang_weight(pid) {
		window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=plugin&pid=' + pid + '&weight=' + $('#weight_' + pid).val();
	}
</script>
<!-- END: main -->
