<!-- BEGIN: main -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<colgroup>
			<col span="2" />
			<col class="w150" />
			<col span="4" class="w100" />
			<col class="w150" />
		</colgroup>
		<thead>
			<tr class="text-center">
				<th> {LANG.file_title} </th>
				<th> {LANG.category_cat_parent} </th>
				<th> {LANG.file_update} </th>
				<th> {LANG.file_view_hits} </th>
				<th> {LANG.file_download_hits} </th>
				<th> {LANG.file_comment_hits} </th>
				<th> {LANG.file_active} </th>
				<th> {LANG.file_feature} </th>
			</tr>
		</thead>
		<!-- BEGIN: generate_page -->
		<tfoot>
			<tr>
				<td colspan="8"> {GENERATE_PAGE} </td>
			</tr>
		</tfoot>
		<!-- END: generate_page -->
		<tbody>
			<!-- BEGIN: row -->
			<tr>
				<td><strong>{ROW.title}</strong></td>
				<td><a href="{ROW.catlink}">{ROW.cattitle}</a></td>
				<td class="text-center"> {ROW.uploadtime} </td>
				<td> {ROW.view_hits} </td>
				<td> {ROW.download_hits} </td>
				<td> {ROW.comment_hits} </td>
				<td class="text-center"><input name="status" id="change_status{ROW.id}" value="1" type="checkbox"{ROW.status} onclick="nv_chang_file_status({ROW.id})" /></td>
				<td class="text-center"><em class="fa fa-edit fa-lg">&nbsp;</em> <a href="{EDIT_URL}">{GLANG.edit}</a> &nbsp;&nbsp;<em class="fa fa-trash-o fa-lg">&nbsp;</em> <a href="javascript:void(0);" onclick="nv_file_del({ROW.id});">{GLANG.delete}</a></td>
			</tr>
			<!-- END: row -->
		</tbody>
		<tfoot>
			<tr>
				<td colspan="8">
					<p><a class="btn btn-default" href="{ADD_NEW_FILE}">{LANG.file_addfile}</a></p>
				</td>
			</tr>
		</tfoot>
	</table>
</div>
<!-- END: main -->