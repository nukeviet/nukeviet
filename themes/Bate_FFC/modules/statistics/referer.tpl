<!-- BEGIN: main -->
<div class="statistics-responsive">
	<table class="table table-bordered table-striped statistics" summary="{CTS.caption}">
		<caption>{CTS.caption}</caption>
		<tbody>
			<tr>
				<!-- BEGIN: loop -->
				<td class="data">
				<!-- BEGIN: img -->
				{M.count}
				<br/>
				<img width="10" height="{HEIGHT}" src="{SRC}" alt="Statistics image"/></td>
				<!-- END: loop -->
				<!-- END: img -->
			</tr>
			<tr>
				<!-- BEGIN: loop_1 -->
				<!-- BEGIN: m_c -->
				<th class="text-center"><span class="label label-primary">{M.fullname}</span></th>
				<!-- END: m_c -->
				<!-- BEGIN: m_o -->
				<th class="text-center">{M.fullname}</th>
				<!-- END: m_o -->
				<!-- END: loop_1 -->
			</tr>
			<tr>
				<td class="text-right" colspan="12"> {CTS.total.0}: <strong>{CTS.total.1}</strong></td>
			</tr>
		</tbody>
	</table>
</div>
<!-- END: main -->