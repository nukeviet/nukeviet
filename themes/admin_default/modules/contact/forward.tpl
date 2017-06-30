<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->
<form method="post" action="{FORM_ACTION}" class="confirm-reload">
	<input name="save" type="hidden" value="1" />
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<tfoot>
				<tr>
					<td colspan="2" class="text-center"><input name="submit1" type="submit" value="{LANG.send_title}" class="btn btn-primary" /></td>
				</tr>
			</tfoot>
			<tbody>
				<tr>
                    <td>{LANG.title_send_title}</td>
                    <td class="text-center"><input name="title" type="text" value="{POST.title}" class="w300 form-control pull-left" disabled="true"/></td>
                </tr>
                <tr>
                    <td>{LANG.admin_send2mail_title}</td>
                    <td class="text-center"><input name="email" type="text" value="{POST.email}"  class="w300 form-control pull-left"/></td>
                </tr>
				<tr>
					<td colspan="2">{MESS_CONTENT}</td>
				</tr>
			</tbody>
		</table>
	</div>
</form>
<!-- END: main -->