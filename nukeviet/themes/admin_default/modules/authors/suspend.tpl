<!-- BEGIN: suspend -->
<!-- BEGIN: suspend_info -->
<div class="quote" style="width:98%">
	<blockquote><span>{SUSPEND_INFO}</span></blockquote>
</div>
<!-- END: suspend_info -->
<!-- BEGIN: suspend_info1 -->
<table class="tab1">
	<caption>{SUSPEND_INFO}:</caption>
	<col span="2" valign="top" width="25%" />
	<col valign="top" width="50%" />
	<thead>
		<tr>
			<td>{SUSPEND_INFO2}</td>
			<td>{SUSPEND_INFO3}</td>
			<td>{SUSPEND_INFO4}</td>
		</tr>
	</thead>
	<!-- BEGIN: loop -->
	<tbody{CLASS}>
		<tr>
			<td>{VALUE0}</td>
			<td>{VALUE1}</td>
			<td>{VALUE2}</td>
		</tr>
	</tbody><!-- END: loop -->
</table>
<!-- END: suspend_info1 -->
<!-- BEGIN: change_suspend -->
<div class="quote" style="width:98%">
	<blockquote{CLASS}><span>{NEW_SUSPEND_CAPTION}</span></blockquote>
</div>
<div class="clear"></div>
<form method="post" action="{ACTION}">
    <table class="tab1">
		<col valign="top" width="150px" />
		<col valign="top" width="10px" />
		<col valign="top" />
		<tfoot>
			<tr>
				<td colspan="2"><input name="save" type="hidden" value="1" /></td>
				<td><input name="go_change" type="submit" value="{SUBMIT}" /></td>
			</tr>
		</tfoot>    
		<!-- BEGIN: new_reason -->
		<tbody>
        <tr>
			<td>{NEW_REASON0}:</td>
			<td><sup class="required">&lowast;</sup></td>
			<td><input name="new_reason" type="text" value="{NEW_REASON1}" class="txt-half" maxlength="{NEW_REASON2}" /></td>
        </tr>
		</tbody><!-- END: new_reason -->

		<tbody>
			<tr>
				<td>{SENDMAIL}:</td>
				<td></td>
				<td><input name="sendmail" type="checkbox" value="1"{CHECKED} /></td>
			</tr>
		</tbody>

		<!-- BEGIN: clean_history -->
		<tbody>
			<tr>
				<td>{CLEAN_HISTORY}:</td>
				<td></td>
				<td><input name="clean_history" type="checkbox" value="1"{CHECKED1} /></td>
			</tr>
		</tbody><!-- END: clean_history -->
    </table>
</form><!-- END: change_suspend --><!-- END: suspend -->