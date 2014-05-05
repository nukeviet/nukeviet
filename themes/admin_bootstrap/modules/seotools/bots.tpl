<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr class="text-center">
					<th>{LANG.bot_name}</th>
					<th>{LANG.bot_agent}(*)</th>
					<th>{LANG.bot_ips} (**)</th>
					<th>{LANG.bot_allowed}</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td class="text-center" colspan="4"><input type="submit" name="submit" value="{LANG.submit}" class="btn btn-primary" /></td>
				</tr>
			</tfoot>
			<tbody class="text-center">
				<!-- BEGIN: loop -->
				<tr>
					<td><input class="w200 form-control" type="text" value="{DATA.name}" name="bot_name[{DATA.id}]" /></td>
					<td><input class="w200 form-control" type="text" value="{DATA.agent}" name="bot_agent[{DATA.id}]" /></td>
					<td><input class="w200 form-control" type="text" value="{DATA.ips}" name="bot_ips[{DATA.id}]" /></td>
					<td><input type="checkbox" value="1" name="bot_allowed[{DATA.id}]" {DATA.checked} /></td>
				</tr>
				<!-- END: loop -->
			</tbody>
		</table>
	</div>
</form>
<!-- END: main -->