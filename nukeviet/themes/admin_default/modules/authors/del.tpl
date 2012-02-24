<!-- BEGIN: del -->
<div class="quote" style="width:98%">
	<blockquote{CLASS}><span>{TITLE}</span></blockquote>
</div>
<div class="clear"></div>
<form method="post" action="{ACTION}">
	<table class="tab1">
		<col valign="top" width="150px" />
		<col valign="top" width="10px" />
		<col valign="top" />
		<tbody>
			<tr>
				<td>{SENDMAIL}:</td>
				<td></td>
				<td><input name="sendmail" type="checkbox" value="1"{CHECKED} /></td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td>{REASON0}:</td>
				<td></td>
				<td><input name="reason" type="text" value="{REASON1}" class="txt-half" maxlength="{REASON2}" /></td>
			</tr>
		</tbody>
			<tr>
				<td>{ADMIN_PASSWORD0}:</td>
				<td><sup class="required">&lowast;</sup></td>
				<td>
					<input name="adminpass_iavim" type="password" autocomplete="off" value="{ADMIN_PASSWORD1}" class="txt-half" maxlength="{ADMIN_PASSWORD2}" />
				</td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="3">
					<input name="ok" type="hidden" value="1" />
					<input name="go_del" type="submit" value="{SUBMIT}" />
				</td>
			</tr>
		<tfoot>
    </table>
</form>
<!-- END: del -->