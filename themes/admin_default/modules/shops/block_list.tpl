<!-- BEGIN: main -->
<form class="form-inline" name="block_list">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr class="text-center">
					<th><input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);" /></th>
					<th style="width:60px;">{LANG.weight}</th>
					<th>{LANG.name}</th>
					<th style="width:250px;"></th>
				</tr>
			</thead>
			<tbody>
				<!-- BEGIN: loop -->
				<tr>
					<td class="text-center"><input type="checkbox" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);" value="{ROW.id}" name="idcheck[]"></td>
					<td class="text-center">
					<select class="form-control" id="id_weight_{ROW.id}" onchange="nv_chang_block({BID}, {ROW.id}, 'weight');">
						<!-- BEGIN: weight -->
						<option value="{WEIGHT.key}"{WEIGHT.selected}>{WEIGHT.title}</option>
						<!-- END: weight -->
					</select></td>
					<td align="left"><a target="_blank" href="{ROW.link}">{ROW.title}</a></td>
					<td class="text-center"><span class="edit_icon"><a href="{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}=content&amp;id={ROW.id}">{GLANG.edit}</a></span> &nbsp;-&nbsp;<span class="delete_icon"><a href="javascript:void(0);" onclick="nv_chang_block({BID}, {ROW.id}, 'delete')">{LANG.delete_from_block}</a></span></td>
				</tr>
				<!-- END: loop -->
			</tbody>
			<tfoot>
				<tr align="left">
					<td colspan="5"><input type="button" onclick="nv_del_block_list(this.form, {BID})" value="{LANG.delete_from_block}"></td>
				</tr>
			</tfoot>
		</table>
	</div>
</form>
<!-- END: main -->