<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div style="width: 98%;" class="quote">
    <blockquote class="error">
        <p>
            <span>{ERROR}</span>
        </p>
    </blockquote>
</div>
<div class="clear"></div>
<!-- END: error -->
<form class="form-inline" action="{FORM_ACTION}" method="post">
    <table class="table table-striped table-bordered table-hover">
		<caption>{TABLE_CAPTION}</caption>
		<tbody>
			<tr>
				<td style="width:150px">
					<strong>{LANG.signer_title}</strong>
				</td>
				<td>
					<input class="form-control" type="text" style="width:350px" value="{DATA.title}" name="title" />
				</td>
			</tr>
			<tr>
				<td style="width:150px">
					<strong>{LANG.signer_offices}</strong>
				</td>
				<td>
					<input class="form-control" type="text" style="width:350px" value="{DATA.offices}" name="offices" />
				</td>
			</tr>
			<tr>
				<td style="width:150px">
					<strong>{LANG.signer_positions}</strong>
				</td>
				<td>
					<input class="form-control" type="text" style="width:350px" value="{DATA.positions}" name="positions" />
				</td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="2" class="text-center">
					<input class="btn btn-primary" type="submit" name="submit" value="{LANG.save}" />
				</td>
			</tr>
		</tfoot>
    </table>
</form>
<!-- END: main -->