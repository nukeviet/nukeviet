<!-- BEGIN: main -->
<div id="users">
	<form action="{FORM_ACTION}" method="post">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover">
				<colgroup>
					<col style="width: 260px" />
					<col/>
				</colgroup>
				<tfoot>
					<tr>
						<td colspan="2"><input type="submit" name="submit" value="{LANG.config_save}" class="btn btn-primary" /></td>
					</tr>
				</tfoot>
				<tbody>
					<tr>
						<td>{LANG.config_view_type}</td>
						<td>
							<select name="viewtype" class="form-control w200">
								<!-- BEGIN: loop -->
								<option value="{VIEWTYPE.id}" {VIEWTYPE.selected}>{VIEWTYPE.title}</option>
								<!-- END: loop -->
							</select>
						</td>
					</tr>
					<tr>
						<td>{LANG.config_facebookapi}</td>
						<td>
							<input class="form-control w200" name="facebookapi" value="{DATA.facebookapi}" />
							<span class="help-block">{LANG.config_facebookapi_note}</span>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</form>
</div>
<!-- END: main -->