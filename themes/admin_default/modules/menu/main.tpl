<!-- BEGIN: first -->
<table class="tab1 fixtab">
	<tbody>
		<tr>
			<td>{LANG.main_note_0} <a href="{ADD_NEW}" title="{LANG.add_menu}"><strong>{LANG.here}</strong></a> {LANG.main_note_1}</td>
		</tr>
	</tbody>
</table>
<!-- BEGIN: table -->
<script type="text/javascript">
	var block = '{LANG.block}';
	var block2 = '{LANG.block2}';
</script>
<table class="tab1">
	<colgroup>
		<col class="w50">
		<col span="3" />
		<col class="w150">
	</colgroup>
	<thead>
		<tr class="center">
			<td><strong>{LANG.number}</strong></td>
			<td><strong>{LANG.name_block}</strong></td>
			<td><strong>{LANG.menu}</strong></td>
			<td><strong>{LANG.menu_description}</strong></td>
			<td><strong>{LANG.action}</strong></td>
		</tr>
	</thead>
	<!-- BEGIN: generate_page -->
	<tfoot>
		<tr>
			<td colspan="5"> {GENERATE_PAGE} </td>
		</tr>
	</tfoot>
	<!-- END: generate_page -->
	<tbody>
		<!-- BEGIN: loop1 -->
		<tr>
			<td class="center"> {ROW.nb} </td>
			<td><a href="{ROW.link_view}" title="{ROW.title}"><strong>{ROW.title}</strong></a></td>
			<td> {ROW.menu_item} </td>
			<td> {ROW.description} </td>
			<td class="center">
				<em class="icon-edit icon-large">&nbsp;</em> <a href="{ROW.edit_url}">{LANG.edit}</a> &nbsp;
				<em class="icon-trash icon-large">&nbsp;</em> <a href="javascript:void(0);" onclick="nv_menu_delete({ROW.id},{ROW.num});">{LANG.delete}</a>
			</td>
		</tr>
		<!-- END: loop1 -->
	</tbody>
</table>
<!-- END: table -->
<!-- END: first -->
<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="quote">
	<blockquote class="error"><span>{ERROR}</span></blockquote>
</div>
<!-- END: error -->
<form action="{FORM_ACTION}" method="post">
	<input type="hidden" name ="id" value="{DATAFORM.id}" />
	<input name="save" type="hidden" value="1" />
	<table class="tab1">
		<tfoot>
			<tr>
				<td class="center" colspan="2"><input name="submit1" type="submit" value="{LANG.save}" /></td>
			</tr>
		</tfoot>
		<tbody>
			<tr>
				<td class="right"><strong>{LANG.name_block}: </strong></td>
				<td><input style="width: 650px" name="title" type="text" value="{DATAFORM.title}" maxlength="255" /></td>
			</tr>
			<tr>
				<td class="right"><strong>{LANG.menu_description}: </strong></td>
				<td><input style="width: 650px" name="description" type="text" value="{DATAFORM.description}" maxlength="255" /></td>
			</tr>
		</tbody>
	</table>
</form>
<!-- END: main -->