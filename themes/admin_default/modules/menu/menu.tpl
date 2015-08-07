<!-- BEGIN: main -->
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css">
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.js"></script>

<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->

<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<input type="hidden" name ="id" value="{DATAFORM.id}" />
	<input name="save" type="hidden" value="1" />
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<tfoot>
				<tr>
					<td>&nbsp;</td>
					<td><input name="submit1" type="submit" value="{LANG.save}" class="btn btn-primary w100" /></td>
				</tr>
			</tfoot>
			<tbody>
				<tr>
					<td class="text-right"><strong>{LANG.name_block}: </strong></td>
					<td><input class="form-control w500" name="title" type="text" value="{DATAFORM.title}" maxlength="255" /></td>
				</tr>
				<tr>
					<td class="text-right"><strong>{LANG.action_menu}: </strong></td>
					<td>
						<select name="action_menu" id="action_menu" class="form-control w500">
							<option value="">&nbsp;</option>
							<option value="sys_mod">{LANG.action_menu_sys_1}</option>
							<option value="sys_mod_sub">{LANG.action_menu_sys_2}</option>
							<optgroup label="{LANG.action_menu_sys_3}">
							<!-- BEGIN: action_menu -->
							<option value="{OPTIONVALUE}">{OPTIONTITLE}</option>
							<!-- END: action_menu -->
							</optgroup>
						</select>
					</td>
				<tr>
			</tbody>
	</table>
</div>
</form>
<script type="text/javascript">
	$(document).ready(function() {
		$("#action_menu").select2();
	});
</script>
<!-- END: main -->