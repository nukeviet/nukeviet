<!-- BEGIN: suspend -->
<!-- BEGIN: suspend_info -->
<div class="quote">
	<blockquote><span>{SUSPEND_INFO}</span></blockquote>
</div>
<!-- END: suspend_info -->
<!-- BEGIN: suspend_info1 -->
<table class="tab1">
	<caption>{SUSPEND_INFO}:</caption>
	<col span="2" style="width: 25%" />
	<col style="width: 50%" />
	<thead>
		<tr>
			<td>{SUSPEND_INFO2}</td>
			<td>{SUSPEND_INFO3}</td>
			<td>{SUSPEND_INFO4}</td>
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
<!-- END: suspend_info1 -->
<!-- BEGIN: change_suspend -->
<div class="quote">
	<blockquote {CLASS}><span>{NEW_SUSPEND_CAPTION}</span></blockquote>
</div>
<form method="post" action="{ACTION}">
	<table class="tab1">
		<col class="w200">
		<col class="w20">
		<col/>
		<tfoot>
			<tr>
				<td colspan="2"><input name="save" type="hidden" value="1" /></td>
				<td><input name="go_change" type="submit" value="{SUBMIT}" /></td>
			</tr>
		</tfoot>
		<tbody>
			<!-- BEGIN: new_reason -->
			<tr>
				<td>{NEW_REASON0}:</td>
				<td><sup class="required">&lowast;</sup></td>
				<td><input name="new_reason" type="text" value="{NEW_REASON1}" class="txt-half" maxlength="{NEW_REASON2}" /></td>
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
</form>
<!-- END: change_suspend -->
<!-- END: suspend -->