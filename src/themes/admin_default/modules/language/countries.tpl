<!-- BEGIN: main -->
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th>{LANG.nv_lang_nb}</th>
					<th>{LANG.countries_name}</th>
					<th>{LANG.nv_lang_data}</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="3" class="text-center"><input type="submit" name="submit" value="{LANG.nv_admin_submit}" class="btn btn-primary" /></td>
				</tr>
			</tfoot>
			<tbody>
				<!-- BEGIN: countries -->
				<tr>
					<td>{NB}</td>
					<td>{LANG_NAME}</td>
					<td>
					<select name="countries[{LANG_KEY}]" class="form-control w200">
						<!-- BEGIN: language -->
						<option value="{DATA_KEY}" {DATA_SELECTED}>{DATA_TITLE}</option>
						<!-- END: language -->
					</select></td>
				</tr>
				<!-- END: countries -->
			</tbody>
		</table>
	</div>
</form>
<!-- END: main -->