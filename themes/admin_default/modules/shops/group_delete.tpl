<!-- BEGIN: main -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<tbody>
			<tr>
				<td>
				<form class="form-inline" action="{NV_BASE_ADMINURL}index.php" method="post">
					<input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
					<input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}" />
					<input type="hidden" name="groupid" value="{GROUPID}" />
					<input type="hidden" name="delallcheckss" value="{DELALLCHECKSS}" />
					<div class="text-center">
						<b>{INFO}</b>
						<br>
						<br>
						<input class="btn btn-primary" name="delgroupandrows" type="submit" value="{LANG.delgroupandrows}" />
						<br>
						<br>
						<b>{LANG.delgroup_msg_rows_move}</b>:
						<select class="form-control" name="groupidnews">
							<!-- BEGIN: grouploop -->
							<option value="{GROUP_ID}">{GROUP_TITLE}</option>
							<!-- END: grouploop -->
						</select>
						<input class="btn btn-primary" name="movegroup" type="submit" value="{LANG.action}" onclick="return nv_check_movegroup(this.form, '{LANG.delgroup_msg_rows_noselect}')">
					</div>
				</form></td>
			</tr>
		</tbody>
	</table>
</div>
<!-- END: main -->