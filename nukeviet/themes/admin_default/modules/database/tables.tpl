<!-- BEGIN: main -->
<form name="myform" method="post" action="{ACTION}" onsubmit="nv_chsubmit(this,'tables[]');return false;">
	<table class="tab1">
		<caption>{CAPTIONS}</caption>
		<col valign="middle" width="10px" />
		<col span="10" valign="top" />
		<thead>
			<tr>
				<td><input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'tables[]', 'check_all[]',this.checked);" /></td>
				<!-- BEGIN: columns --><td>{COLNAME}</td>
				<!-- END: columns -->
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="11">
				<select id="op_name" name="{OP_NAME}" onchange="nv_checkForm();">
					<!-- BEGIN: op -->
					<option value="{KEY}">{VAL}</option>
					<!-- END: op -->
				</select>
				<select id="type_name" name="{TYPE_NAME}">
					<!-- BEGIN: type -->
					<option value="{KEY}">{VAL}</option>
					<!-- END: type -->
				</select>
				<select id="ext_name" name="{EXT_NAME}">
					<!-- BEGIN: ext -->
					<option value="{KEY}">{VAL}</option>
					<!-- END: ext -->
				</select>
				<input name="Submit1" id="subm_form" type="submit" value="{SUBMIT}" />
				</td>
			</tr>
		</tfoot>
		<!-- BEGIN: loop -->
		<tbody{ROW.class}>
			<tr>
				<{ROW.tag}><input name="tables[]" type="checkbox" value="{ROW.key}" onclick="nv_UncheckAll(this.form, 'tables[]', 'check_all[]', this.checked);" /></{ROW.tag}>
				<!-- BEGIN: col --><{ROW.tag}>{VALUE}</{ROW.tag}>
				<!-- END: col -->
			</tr>
		</tbody>
		<!-- END: loop -->
		<tbody class="third">
			<tr>
				<td><input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'tables[]', 'check_all[]',this.checked);" /></td>
				<td colspan="10"><strong>{THIRD}</strong></td>
			</tr>
		</tbody>
	</table>
</form>
<!-- END: main -->
