<!-- BEGIN: main -->
<form class="form-inline" action="{FORM_ACTION}" method="post" name="levelnone" id="levelnone">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th>{LANG.signer_title}</th>
					<th>{LANG.signer_offices}</th>
					<th>{LANG.signer_positions}</th>
					<th style="width:120px" class="text-center">{LANG.feature}</th>
				</tr>
			</thead>
			<tbody>
			<!-- BEGIN: row -->
				<tr class="topalign">
					<td><strong>{ROW.title}</strong></td>
					<td>{ROW.offices}</td>
					<td>{ROW.positions}</td>
					<td class="text-center">
                        <em class="fa fa-edit fa-lg">&nbsp;</em><a href="{ROW.url_edit}">{GLANG.edit}</a> -
						<em class="fa fa-trash-o fa-lg">&nbsp;</em><a href="javascript:void(0);" onclick="nv_delete_signer({ROW.id});">{GLANG.delete}</a>
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
<!-- END: main -->