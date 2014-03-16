<!-- BEGIN: main -->
<table class="tab1">
	<colgroup>
		<col span="2" />
		<col class="w150" />
		<col span="4" class="w50" />
		<col class="w100" />
	</colgroup>
	<thead>
		<tr class="center">
			<td> {LANG.file_title} </td>
			<td> {LANG.category_cat_parent} </td>
			<td> {LANG.file_update} </td>
			<td> {LANG.file_view_hits} </td>
			<td> {LANG.file_download_hits} </td>
			<td> {LANG.file_comment_hits} </td>
			<td> {LANG.file_active} </td>
			<td> {LANG.file_feature} </td>
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
			<td class="center"> {ROW.uploadtime} </td>
			<td> {ROW.view_hits} </td>
			<td> {ROW.download_hits} </td>
			<td> {ROW.comment_hits} </td>
			<td class="center"><input name="status" id="change_status{ROW.id}" value="1" type="checkbox"{ROW.status} onclick="nv_chang_file_status({ROW.id})" /></td>
			<td class="center"><em class="icon-edit icon-large">&nbsp;</em> <a href="{EDIT_URL}">{GLANG.edit}</a> &nbsp;&nbsp;<em class="icon-trash icon-large">&nbsp;</em> <a href="javascript:void(0);" onclick="nv_file_del({ROW.id});">{GLANG.delete}</a></td>
		</tr>
		<!-- END: row -->
	</tbody>
	<tfoot>
		<tr>
			<td colspan="8">
				<p><a class="button button-h" href="{ADD_NEW_FILE}">{LANG.file_addfile}</a></p>
			</td>
		</tr>
	</tfoot>
</table>
<!-- END: main -->