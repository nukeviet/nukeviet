<!-- BEGIN: main -->
<form name="block_list" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}&amp;bid={BID}" method="get">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th class="text-center"><input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);" /></th>
					<th class="w100">{LANG.weight}</th>
					<th>{LANG.name}</th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="5"><input class="btn btn-primary" type="button" onclick="nv_del_block_list(this.form, {BID})" value="{LANG.delete_from_block}"></td>
				</tr>
			</tfoot>
			<tbody>
			<!-- BEGIN: loop -->
				<tr>
					<td class="text-center"><input type="checkbox" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);" value="{ROW.id}" name="idcheck[]" /></td>
					<td class="text-center">
					<select class="form-control" id="id_weight_{ROW.id}" onchange="nv_chang_block({BID},{ROW.id},'weight');">
						<!-- BEGIN: weight -->
						<option value="{WEIGHT.key}"{WEIGHT.selected}>{WEIGHT.title}</option>
						<!-- END: weight -->
					</select></td>
					<td class="text-left"><a target="_blank" href="{ROW.link}">{ROW.title}</a></td>
					<td class="text-center">
						<em class="fa fa-edit fa-lg">&nbsp;</em> <a href="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}=content&amp;id={ROW.id}">{GLANG.edit}</a> &nbsp;
						<em class="fa fa-trash-o fa-lg">&nbsp;</em> <a href="javascript:void(0);" onclick="nv_chang_block({BID},{ROW.id},'delete')">{LANG.delete_from_block}</a></td>
				</tr>
			<!-- END: loop -->
			</tbody>
		</table>
	</div>
</form>
<!-- END: main -->