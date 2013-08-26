<!-- BEGIN: main -->
<div id="users">
	<table class="tab1">
		<caption>{TABLE_CAPTION}</caption>
		<colgroup>
			<col class="w50">
			<col span="2">
			<col class="w100">
			<col class="w150">
		</colgroup>
		<thead>
			<tr>
				<td> {LANG.faq_category_cat_sort} </td>
				<td> {LANG.faq_category_cat_name} </td>
				<td> {LANG.faq_category_cat_parent} </td>
				<td class="center"> {LANG.faq_category_cat_active} </td>
				<td class="center"> {LANG.faq_category_cat_feature} </td>
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: row -->
			<tr>
				<td>
				<select name="weight" id="weight{ROW.id}" onchange="nv_chang_weight({ROW.id});">
					<!-- BEGIN: weight -->
					<option value="{WEIGHT.pos}"{WEIGHT.selected}>{WEIGHT.pos}</option>
					<!-- END: weight -->
				</select></td>
				<td><strong><a href="{ROW.titlelink}">{ROW.title}</a></strong>{ROW.numsub} </td>
				<td> {ROW.parentid} </td>
				<td class="center"><input type="checkbox" name="active" id="change_status{ROW.id}" value="1"{ROW.status} onclick="nv_chang_status({ROW.id});" /></td>
				<td class="center"><a class="edit_icon" href="{EDIT_URL}">{GLANG.edit}</a> &nbsp;&nbsp;<a class="delete_icon" href="javascript:void(0);" onclick="nv_cat_del({ROW.id});">{GLANG.delete}</a></td>
			</tr>
			<!-- END: row -->
		</tbody>
	</table>
</div>
<div style="margin-top:8px;">
	<a class="button1" href="{ADD_NEW_CAT}"><span><span>{LANG.faq_addcat_titlebox}</span></span></a>
</div>
<!-- END: main -->