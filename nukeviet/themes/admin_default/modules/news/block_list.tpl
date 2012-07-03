<!-- BEGIN: main -->
<form name="block_list" action="{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}&amp;bid={BID}" method="get">
	<table class="tab1">
		<thead>
			<tr>
				<td align="center">
					<input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);" />
				</td>
				<td style="width:60px;">{LANG.weight}</td>
				<td>{LANG.name}</td>
				<td></td>
			</tr>
		</thead>
		<tfoot>
			<tr align="left">
				<td colspan="5">
					<input type="button" onclick="nv_del_block_list(this.form, {BID})" value="{LANG.delete_from_block}">
				</td>
			</tr>
		</tfoot>
		<!-- BEGIN: loop -->
		<tbody{ROW.class}>
			<tr>
				<td align="center">
					<input type="checkbox" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);" value="{ROW.id}" name="idcheck[]" />
				</td>
				<td align="center">
					<select id="id_weight_{ROW.id}" onchange="nv_chang_block({BID},{ROW.id},'weight');">
						<!-- BEGIN: weight -->
						<option value="{WEIGHT.key}"{WEIGHT.selected}>{WEIGHT.title}</option>
						<!-- END: weight -->
					</select>
				</td>
				<td align="left"><a target="_blank" href="{ROW.link}">{ROW.title}</a></td>
				<td align="center">
					<span class="edit_icon"><a href="{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}=content&amp;id={ROW.id}">{GLANG.edit}</a></span>
					&nbsp;-&nbsp;
					<span class="delete_icon"><a href="javascript:void(0);" onclick="nv_chang_block({BID},{ROW.id},'delete')">{LANG.delete_from_block}</a></span>
				</td>
			</tr>
		</tbody>
		<!-- END: loop -->
	</table>
</form>
<!-- END: main -->