<!-- BEGIN: main -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<caption>{TABLE_CAPTION}</caption>
		<colgroup>
			<col span="2" />
			<col class="w100" />
			<col class="w150" />
		</colgroup>
		<thead>
			<tr>
				<th> {LANG.file_title} </th>
				<th> {LANG.category_cat_parent} </th>
				<td class="center"> {LANG.file_update} </th>
				<td class="center"> {LANG.file_feature} </th>
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: row -->
			<tr>
				<td><strong>{ROW.title}</strong></td>
				<td><a href="{ROW.catlink}">{ROW.cattitle}</a></td>
				<td class="center"> {ROW.uploadtime} </td>
				<td class="center"><em class="icon-edit icon-large">&nbsp;</em> <a href="{EDIT_URL}">{GLANG.edit}</a> &nbsp;&nbsp;<em class="icon-trash icon-large">&nbsp;</em> <a href="javascript:void(0);" onclick="nv_filequeue_del({ROW.id});">{GLANG.delete}</a></td>
			</tr>
			<!-- END: row -->
		</tbody>
	</table>
</div>
<div style="margin-top:8px;">
	<a class="button button-h" href="javascript:void(0);" onclick="nv_filequeue_alldel();">{LANG.download_alldel}</a>
</div>
<!-- END: main -->