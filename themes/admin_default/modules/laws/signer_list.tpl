<!-- BEGIN: main -->
<form class="form-inline" action="{FORM_ACTION}" method="post" name="levelnone" id="levelnone">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th>{LANG.signer_title}</th>
					<th>{LANG.signer_offices}</th>
					<th>{LANG.signer_positions}</th>
					<th style="width:90px" class="text-center">{LANG.feature}</th>
				</tr>
			</thead>
			<tbody>
			<!-- BEGIN: row -->
				<tr class="topalign">
					<td><strong>{ROW.title}</strong></td>
					<td>{ROW.offices}</td>
					<td>{ROW.positions}</td>
					<td class="text-center">
						<!--<span class="edit_icon"><a href="{ROW.url_edit}">{GLANG.edit}</a></span>-->
						&nbsp;&nbsp;
                        <a href="{ROW.url_edit}">{GLANG.edit}</a> |
						<span class="delete_icon"><a href="javascript:void(0);" onclick="nv_delete_signer({ROW.id});">{GLANG.delete}</a></span>
					</td>
				</tr>
			<!-- END: row -->
			<tbody>
			<!-- BEGIN: generate_page -->
			<tfoot>
				<tr>
					<td colspan="7">
						{GENERATE_PAGE}
					</td>
				</tr>
			</tfoot>
			<!-- END: generate_page -->
		</table>
	</div>
</form>

<!--

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
</form>-->
<!-- END: main -->