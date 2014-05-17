<!-- BEGIN: empty -->
<div class="text-center">
	<strong>{LANG.nv_lang_error_exit}</strong>
</div>
<meta http-equiv="Refresh" content="3;URL={URL}" />
<!-- END: empty -->
<!-- BEGIN: main -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<thead>
			<tr class="text-center">
				<th>{LANG.nv_lang_nb}</th>
				<th>{LANG.nv_lang_module}</th>
				<th>{LANG.nv_lang_area}</th>
				<th>{LANG.nv_lang_author}</th>
				<th>{LANG.nv_lang_createdate}</th>
				<th>{LANG.nv_lang_func}</th>
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td class="text-center">{ROW.stt}</td>
				<td>{ROW.module}</td>
				<td>{ROW.langsitename}</td>
				<td class="text-center">{ROW.author}</td>
				<td class="text-center">{ROW.createdate}</td>
				<td class="text-center"><em class="fa fa-edit fa-lg">&nbsp;</em> <a href="{ROW.url_edit}" title="{LANG.nv_admin_edit}">{LANG.nv_admin_edit}</a>
				<!-- BEGIN: write -->
				- <em class="fa fa-sun-o fa-lg">&nbsp;</em> <a href="{ROW.url_export}" title="{LANG.nv_admin_write}">{LANG.nv_admin_write}</a>
				<!-- END: write -->
				</td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<!-- END: main -->