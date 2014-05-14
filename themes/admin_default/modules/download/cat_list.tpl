<!-- BEGIN: main -->
<div id="users">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<caption><em class="fa fa-file-text-o">&nbsp;</em>{TABLE_CAPTION}</caption>
			<colgroup>
				<col class="w50">
				<col span="2">
				<col class="w100">
				<col class="w150">
			</colgroup>
			<thead>
				<tr>
					<th> {LANG.category_cat_sort} </th>
					<th> {LANG.category_cat_name} </th>
					<th> {LANG.category_cat_parent} </th>
					<th class="text-center"> {LANG.category_cat_active} </th>
					<th class="text-center"> {LANG.category_cat_feature} </th>
				</tr>
			</thead>
			<tbody>
				<!-- BEGIN: row -->
				<tr>
					<td>
					<select name="weight" id="weight{ROW.id}" onchange="nv_chang_weight({ROW.id});" class="form-control w100">
						<!-- BEGIN: weight -->
						<option value="{WEIGHT.pos}"{WEIGHT.selected}>{WEIGHT.pos}</option>
						<!-- END: weight -->
					</select></td>
					<td><strong><a href="{ROW.titlelink}">{ROW.title}</a></strong>{ROW.numsub} </td>
					<td> {ROW.parentid} </td>
					<td class="text-center"><input type="checkbox" name="active" id="change_status{ROW.id}" value="1"{ROW.status} onclick="nv_chang_status({ROW.id});" /></td>
					<td class="text-center"><em class="fa fa-edit fa-lg">&nbsp;</em> <a href="{EDIT_URL}">{GLANG.edit}</a> &nbsp;&nbsp;<em class="fa fa-trash-o fa-lg">&nbsp;</em> <a href="javascript:void(0);" onclick="nv_row_del({ROW.id});">{GLANG.delete}</a></td>
				</tr>
				<!-- END: row -->
			</tbody>
			<tfoot>
				<tr>
					<td colspan="5">
						<p><a class="btn btn-default" href="{ADD_NEW_CAT}">{LANG.addcat_titlebox}</a></p>
					</td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>
<!-- END: main -->