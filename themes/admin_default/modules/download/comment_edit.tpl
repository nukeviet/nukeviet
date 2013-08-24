<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="quote">
	<blockquote class="error">
		<p>
			<span>{ERROR}</span>
		</p></blockquote>
</div>
<!-- END: error -->
<form action="{FORM_ACTION}" method="post">
	<table class="tab1">
		<tbody>
			<tr>
				<td> {LANG.comment_subject} </td>
				<td><input class="w500" value="{DATA.subject}" name="subject" id="subject" maxlength="255" /></td>
			</tr>
			<tr>
				<td colspan="2"> {LANG.comment_content}
				<br /><textarea name="comment" style="width:100%;height:150px">{DATA.comment}</textarea>				</td>
			</tr>
			<tr>
				<td> {LANG.comment_admin_reply} </td>
				<td><input class="w500" value="{DATA.admin_reply}" name="admin_reply" id="admin_reply" maxlength="255" /></td>
			</tr>
		</tbody>
	</table>
	<div style="text-align:center;padding-top:15px">
		<input type="submit" name="submit" value="{LANG.confirm}" />
	</div>
</form>
<!-- END: main -->