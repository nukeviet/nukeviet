<!-- BEGIN: main -->
<form class="form-inline" action="{FORM_ACTION}" method="post">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover">
				<colgroup>
					<col style="width: 260px" />
					<col/>
				</colgroup>
				<tfoot>
					<tr>
						<td colspan="2"><input type="hidden" name="save" value="1"><input type="submit" value="{LANG.config_save}" class="btn btn-primary" /></td>
					</tr>
				</tfoot>
				<tbody>
                    <tr>
                        <th>{LANG.difftimeout}</th>
                        <td>
                            <input type="text" class="form-control w50" name="difftimeout" value="{DATA.difftimeout}" /><span class="m-left">{LANG.hours}</span>
                        </td>
                    </tr>
				</tbody>
			</table>
		</div>
</form>
<!-- END: main -->