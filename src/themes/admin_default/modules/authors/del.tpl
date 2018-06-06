<!-- BEGIN: del -->
<div {CLASS}>{TITLE}</div>
<form method="post" action="{ACTION}">
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<tfoot>
			<tr>
				<td colspan="3"><input name="ok" type="hidden" value="{CHECKSS}" /><input name="go_del" type="submit" value="{LANG.nv_admin_del}" class="btn btn-danger" /></td>
			</tr>
		</tfoot>
		<tbody>
			<tr>
				<td>{LANG.admin_del_sendmail}:</td>
				<td>&nbsp;</td>
				<td><input name="sendmail" type="checkbox" value="1"{CHECKED} /></td>
			</tr>
			<tr>
				<td>{ADMIN_PASSWORD0}:</td>
				<td><sup class="required">&lowast;</sup></td>
				<td><input name="adminpass_iavim" type="password" autocomplete="off" value="{ADMIN_PASSWORD1}" maxlength="{ADMIN_PASSWORD2}" class="form-control w300" /></td>
			</tr>
			<tr>
				<td>{LANG.admin_del_reason}:</td>
				<td>&nbsp;</td>
				<td><input name="reason" type="text" value="{REASON1}" maxlength="{REASON2}" class="form-control w300" /></td>
			</tr>
			<tr>
				<td>{LANG.action_account}:</td>
				<td>&nbsp;</td>
				<td>
				<!-- BEGIN: action_account -->
				<label for="action_account_{ACTION_ACCOUNT_KEY}"> <input id="action_account_{ACTION_ACCOUNT_KEY}" name="action_account" type="radio" value="{ACTION_ACCOUNT_KEY}"  {ACTION_ACCOUNT_CHECK}/> {ACTION_ACCOUNT_TITLE} </label>
				<!-- END: action_account -->
				</td>
			</tr>
		</tbody>
	</table>
</div>
</form>
<script type="text/javascript">$("input[name='adminpass_iavim']").focus();</script>
<!-- END: del -->