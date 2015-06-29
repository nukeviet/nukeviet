<!-- BEGIN: main -->
<div class="alert alert-info">{LANG.main_note}</div>
<div class="table-responsive">
	<!-- BEGIN: table -->
	<table class="table table-striped table-bordered table-hover">
		<colgroup>
			<col class="w50">
			<col class="w200">
			<col span="1" />
			<col class="w150">
		</colgroup>
		<thead>
			<tr class="text-center">
				<th>{LANG.number}</th>
				<th>{LANG.name_block}</th>
				<th>{LANG.menu}</th>
				<th>{LANG.action}</th>
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: loop1 -->
			<tr>
				<td class="text-center"> {ROW.nb} </td>
				<td><a href="{ROW.link_view}" title="{ROW.title}"><strong>{ROW.title}</strong></a></td>
				<td> {ROW.menu_item} </td>
				<td class="text-center">
					<em class="fa fa-edit fa-lg">&nbsp;</em> <a href="{ROW.edit_url}">{LANG.edit}</a> &nbsp;
					<em class="fa fa-trash-o fa-lg">&nbsp;</em> <a href="javascript:void(0);" onclick="nv_menu_delete({ROW.id});">{LANG.delete}</a>
				</td>
			</tr>
			<!-- END: loop1 -->
		</tbody>
	</table>
	<!-- END: table -->
</div>
<!-- END: main -->