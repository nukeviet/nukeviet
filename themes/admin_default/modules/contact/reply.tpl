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
					<td class="text-center"><input name="submit1" type="submit" value="{LANG.send_title}" class="btn btn-primary" /></td>
				</tr>
			</tfoot>
			<tbody>
				<tr>
					<td>{MESS_CONTENT}</td>
				</tr>
			</tbody>
		</table>
	</div>
</form>
<!-- END: main -->