<!-- BEGIN: main -->
<form  class="form-inline" role="form" action="{FORM_ACTION}" method="post">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<colgroup>
				<col style="width: 320px;" />
				<col />
			</colgroup>
			<tfoot>
				<tr>
					<td class="text-center" colspan="2"><input class="btn btn-primary w100" type="submit" value="{LANG.save}" name="submit"></td>
				</tr>
			</tfoot>
			<tbody>
				<tr>
					<td><strong>{LANG.oauth_client_id}</strong></td>
					<td><input type="text" class="form-control" style="width: 500px;" name="oauth_client_id" value="{DATA.oauth_client_id}"/></td>
				</tr>
				<tr>
					<td><strong>{LANG.oauth_client_secret}</strong></td>
					<td><input type="text" class="form-control" style="width: 500px;" name="oauth_client_secret" value="{DATA.oauth_client_secret}"/></td>
				</tr>
			<tbody>
		</table>
	</div>
</form>
<!-- END: main -->