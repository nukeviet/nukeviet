<!-- BEGIN: del -->
<div class="quote">
	<blockquote {CLASS}><span>{TITLE}</span></blockquote>
</div>
<form method="post" action="{ACTION}">
	<table class="tab1">
		<col class="w150 top" />
		<col class="w20 top" />
		<col class="top" />
		<tfoot>
			<tr>
				<td colspan="3"><input name="ok" type="hidden" value="{CHECKSS}" /><input name="go_del" type="submit" value="{LANG.nv_admin_del}" /></td>
			</tr>
		<tfoot>
			<tbody>
				<tr>
					<td>{LANG.admin_del_sendmail}:</td>
					<td>&nbsp;</td>
					<td><input name="sendmail" type="checkbox" value="1"{CHECKED} /></td>
				</tr>
				<tr>
					<td>{ADMIN_PASSWORD0}:</td>
					<td><sup class="required">&lowast;</sup></td>
					<td><input class="w200" name="adminpass_iavim" type="password" autocomplete="off" value="{ADMIN_PASSWORD1}" maxlength="{ADMIN_PASSWORD2}" /></td>
				</tr>
				<tr>
					<td>{LANG.admin_del_reason}:</td>
					<td>&nbsp;</td>
					<td><input name="reason" type="text" value="{REASON1}" class="txt-half" maxlength="{REASON2}" /></td>
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
</form>
<script type="text/javascript">$("input[name='adminpass_iavim']").focus();</script>
<!-- END: del -->