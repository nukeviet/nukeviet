<!-- BEGIN: main -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<tbody>
			<tr>
				<td>
				<form action="{NV_BASE_ADMINURL}index.php" method="post">
					<input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
					<input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}" />
					<input type="hidden" name="catid" value="{CATID}" />
					<input type="hidden" name="delallcheckss" value="{DELALLCHECKSS}" />
					<div class="text-center">
						<strong>{TITLE}</strong>
						<br />
						<br />
						<input class="btn btn-warning" name="delcatandrows" type="submit" value="{LANG.delcatandrows}" />
						<br />
						<br />
						<strong>{LANG.delcat_msg_rows_move}</strong>:<br />
						<select class="form-control w300" name="catidnews" style="display: inline-block; text-align: center">
							<!-- BEGIN: catidnews -->
							<option value="{CATIDNEWS.key}">{CATIDNEWS.title}</option>
							<!-- END: catidnews -->
						</select>
						<br /><br /><input class="btn btn-primary" name="movecat" type="submit" value="{LANG.action}" onclick="return nv_check_movecat(this.form, '{LANG.delcat_msg_rows_noselect}')"/>
					</div>
				</form></td>
			</tr>
		</tbody>
	</table>
</div>
<!-- END: main -->