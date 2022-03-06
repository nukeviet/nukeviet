<!-- BEGIN: main -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<tbody>
			<tr>
				<td>
					<form class="form-inline" action="{NV_BASE_ADMINURL}index.php" method="post">
						<input type="hidden" name ="{NV_NAME_VARIABLE}"value="{MODULE_NAME}" />
						<input type="hidden" name ="{NV_OP_VARIABLE}"value="{OP}" />
						<input type="hidden" name ="catid" value="{CATID}" />
						<input type="hidden" name ="delallcheckss" value="{DELALLCHECKSS}" />
						<div class="text-center">
							<b>{INFO}</b>
							<br>
							<br>
							<input class="btn btn-primary" name="delcatandrows" type="submit" value="{LANG.delcatandrows}" />
							<br>
							<br>
							<b>{LANG.delcat_msg_rows_move}</b>:
							<select class="form-control" name="catidnews">
								<!-- BEGIN: catloop -->
								<option value="{CAT_ID}">{CAT_TITLE}</option>
								<!-- END: catloop -->
							</select>
							<input class="btn btn-primary" name="movecat" type="submit" value="{LANG.action}" onclick="return nv_check_movecat(this.form, '{LANG.delcat_msg_rows_noselect}')">
						</div>
					</form>
				</td>
			</tr>
		</tbody>
	</table>
</div>
<!-- END: main -->