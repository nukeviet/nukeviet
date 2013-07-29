<!-- BEGIN: main -->
<table class="tab1">
	<thead>
		<tr class="center">
			<td>{LANG.file_nb}</td>
			<td>{LANG.file_name}</td>
			<td>{LANG.file_size}</td>
			<td>{LANG.file_time}</td>
			<td>&nbsp;</td>
		</tr>
	</thead>
	<tbody>
		<!-- BEGIN: loop -->
		<tr>
			<td class="center">{ROW.stt}</td>
			<td>{ROW.name}</td>
			<td class="right">{ROW.filesize}</td>
			<td class="right">{ROW.filetime}</td>
			<td class="center"><a class="default_icon" title="{LANG.download}" href="{ROW.link_getfile}">{LANG.download}</a> - <a class="delete_icon" title="{GLANG.delete}" onclick="return confirm(nv_is_del_confirm[0]);" href="{ROW.link_delete}">{GLANG.delete}</a></span></td>
		</tr>
		<!-- END: loop -->
	</tbody>
</table>
<!-- END: main -->