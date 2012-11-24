<!-- BEGIN: main -->
<table class="tab1">
	<thead>
		<tr align="center">
			<td>{LANG.file_nb}</td>
			<td>{LANG.file_name}</td>
			<td>{LANG.file_site}</td>
			<td>{LANG.file_time}</td>
			<td></td>
		</tr>
	</thead>
	<!-- BEGIN: loop -->
	<tbody{ROW.class}>
		<tr>
			<td align="center">{ROW.stt}</td>
			<td>{ROW.name}</td>
			<td align="right">{ROW.filesize}</td>
			<td align="right">{ROW.filetime}</td>
			<td align="center">
				<span class="default_icon"><a title="{LANG.download}" href="{ROW.link_getfile}">{LANG.download}</a></span>
				 - 
				<span class="delete_icon"><a title="{GLANG.delete}" onclick="return confirm(nv_is_del_confirm[0]);" href="{ROW.link_delete}">{GLANG.delete}</a></span>
			</td>
		</tr>
	</tbody>
	<!-- END: loop -->
</table>
<!-- END: main -->
