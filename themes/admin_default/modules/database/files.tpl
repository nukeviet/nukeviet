<!-- BEGIN: main -->
<a href="{BACKUPNOW}" class="btn btn-primary">{LANG.download_now}</a>
<div class="table-responsive">
	<br />
	<table class="table table-striped table-bordered table-hover">
		<thead>
			<tr class="text-center">
				<th>{LANG.file_nb}</th>
				<th>{LANG.file_name}</th>
				<th>{LANG.file_size}</th>
				<th>{LANG.file_time}</th>
				<th>&nbsp;</th>
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td class="text-center">{ROW.stt}</td>
				<td>{ROW.name}</td>
				<td class="text-right">{ROW.filesize}</td>
				<td class="text-right">{ROW.filetime}</td>
				<td class="text-center">
					<em class="fa fa-download fa-lg">&nbsp;</em> <a title="{LANG.download}" href="{ROW.link_getfile}">{LANG.download}</a>
				 <em class="fa fa-trash-o fa-lg">&nbsp;</em> <a title="{GLANG.delete}" onclick="return confirm(nv_is_del_confirm[0]);" href="{ROW.link_delete}">{GLANG.delete}</a>
				</td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<!-- END: main -->