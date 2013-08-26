<!-- BEGIN: main -->
<table class="tab1">
	<caption>{TABLE_CAPTION}</caption>
	<colgroup>
		<!-- BEGIN: is_cat0 -->
		<col class="w20" />
		<!-- END: is_cat0 -->
		<col/>
		<col class="w250" />
		<col class="w100" />
		<col class="w100" />
	</colgroup>
	<thead>
		<tr class="center">
			<!-- BEGIN: is_cat1 -->
			<td> {LANG.faq_pos} </td>
			<!-- END: is_cat1 -->
			<td> {LANG.faq_title_faq} </td>
			<td> {LANG.faq_catid_faq} </td>
			<td> {LANG.faq_active} </td>
			<td> {LANG.faq_feature} </td>
		</tr>
	</thead>
	<!-- BEGIN: generate_page -->
	<tfoot>
		<tr>
			<td colspan="{COLSPAN}"> {GENERATE_PAGE} </td>
		</tr>
	</tfoot>
	<!-- END: generate_page -->
	<tbody>
		<!-- BEGIN: row -->
		<tr>
			<!-- BEGIN: is_cat2 -->
			<td>
			<select name="weight" id="weight{ROW.id}" onchange="nv_chang_row_weight({ROW.id});">
				<!-- BEGIN: weight -->
				<option value="{WEIGHT.pos}"{WEIGHT.selected}>{WEIGHT.pos}</option>
				<!-- END: weight -->
			</select></td>
			<!-- END: is_cat2 -->
			<td> {ROW.title} </td>
			<td><a href="{ROW.catlink}">{ROW.cattitle}</a></td>
			<td class="center"><input name="status" id="change_status{ROW.id}" value="1" type="checkbox"{ROW.status} onclick="nv_chang_row_status({ROW.id})" /></td>
			<td class="center"><a class="edit_icon" href="{EDIT_URL}">{GLANG.edit}</a> &nbsp;&nbsp;<a class="delete_icon" href="javascript:void(0);" onclick="nv_row_del({ROW.id});">{GLANG.delete}</a></td>
		</tr>
		<!-- END: row -->
	</tbody>
</table>
<div style="margin-top:8px;">
	<a class="button1" href="{ADD_NEW_FAQ}"><span><span>{LANG.faq_addfaq}</span></span></a>
</div>
<!-- END: main -->