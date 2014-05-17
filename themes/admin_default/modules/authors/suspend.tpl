<!-- BEGIN: suspend -->
<!-- BEGIN: suspend_info -->
<div class="alert alert-info">{SUSPEND_INFO}</div>
<!-- END: suspend_info -->
<!-- BEGIN: suspend_info1 -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<caption><em class="fa fa-file-text-o">&nbsp;</em>{SUSPEND_INFO}:</caption>
		<col span="2" style="width: 25%" />
		<col style="width: 50%" />
		<thead>
			<tr>
				<th>{SUSPEND_INFO2}</th>
				<th>{SUSPEND_INFO3}</th>
				<th>{SUSPEND_INFO4}</th>
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td>{VALUE0}</td>
				<td>{VALUE1}</td>
				<td>{VALUE2}</td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<!-- END: suspend_info1 -->
<!-- BEGIN: change_suspend -->
<div {CLASS}>
	{NEW_SUSPEND_CAPTION}
</div>
<form method="post" action="{ACTION}">
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<colgroup>
			<col class="w200" />
			<col class="w50" />
		</colgroup>
		<tfoot>
			<tr>
				<td colspan="2"><input name="save" type="hidden" value="1" /></td>
				<td><input name="go_change" type="submit" value="{SUBMIT}" class="btn btn-primary" /></td>
			</tr>
		</tfoot>
		<tbody>
			<!-- BEGIN: new_reason -->
			<tr>
				<td>{NEW_REASON0}:</td>
				<td><sup class="required">&lowast;</sup></td>
				<td><input name="new_reason" type="text" value="{NEW_REASON1}" class="form-control" maxlength="{NEW_REASON2}" /></td>
			</tr>
			<!-- END: new_reason -->
			<tr>
				<td>{SENDMAIL}:</td>
				<td>&nbsp;</td>
				<td><input name="sendmail" type="checkbox" value="1"{CHECKED} /></td>
			</tr>
			<!-- BEGIN: clean_history -->
			<tr>
				<td>{CLEAN_HISTORY}:</td>
				<td>&nbsp;</td>
				<td><input name="clean_history" type="checkbox" value="1"{CHECKED1} /></td>
			</tr>
			<!-- END: clean_history -->
		</tbody>
	</table>
</div>
</form>
<!-- END: change_suspend -->
<!-- END: suspend -->