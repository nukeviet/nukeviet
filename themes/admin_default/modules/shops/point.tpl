<!-- BEGIN: main -->
<form class="form-inline" action="{NV_BASE_ADMINURL}index.php" method="get">
	<input type="hidden" name="{NV_LANG_VARIABLE}"  value="{NV_LANG_DATA}" />
	<input type="hidden" name="{NV_NAME_VARIABLE}"  value="{MODULE_NAME}" />
	<input type="hidden" name="{NV_OP_VARIABLE}"  value="{OP}" />
	<input class="form-control" type="text" value="{Q}" name="q" maxlength="255" placeholder="{LANG.search_key}" />&nbsp;
	<input class="btn btn-primary" type="submit" value="{LANG.search}" />
</form>
<br>

<form class="form-inline" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<colgroup>
				<col span="2" />
				<col class="w200" />
				<col span="4" class="w150" />
			</colgroup>
			<thead>
				<tr>
					<th>{LANG.point_username}</th>
					<th>{LANG.point_fullname}</th>
					<th>{LANG.point_email}</th>
					<th>{LANG.point}</th>
					<th class="text-right">{LANG.setting_point_value_conversion} ({money_unit})</th>
				</tr>
			</thead>
			<!-- BEGIN: generate_page -->
			<tfoot>
				<tr class="text-center">
					<td colspan="5">{NV_GENERATE_PAGE}</td>
				</tr>
			</tfoot>
			<!-- END: generate_page -->
			<tbody>
				<!-- BEGIN: loop -->
				<tr>
					<td> {VIEW.username} </td>
					<td> {VIEW.full_name} </td>
					<td> <a href="mailto:{VIEW.email}" title="Mail to: {VIEW.email}">{VIEW.email}</a> </td>
					<td> {VIEW.point_total} </td>
					<td class="text-right"> {VIEW.money} </td>
				</tr>
				<!-- END: loop -->
			</tbody>
		</table>
	</div>
</form>
<!-- END: main -->