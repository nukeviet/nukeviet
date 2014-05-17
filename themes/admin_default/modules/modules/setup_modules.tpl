<!-- BEGIN: main -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<caption><em class="fa fa-file-text-o">&nbsp;</em>{CAPTION}</caption>
		<thead>
			<tr>
				<!-- BEGIN: head -->
				<th>{HEAD}</th>
				<!-- END: head -->
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td>{ROW.stt}</td>
				<td>{ROW.values.title}</td>
				<td>{ROW.values.version}</td>
				<td>{ROW.values.addtime}</td>
				<td>{ROW.values.author}</td>
				<td>{ROW.values.setup} {ROW.values.delete}</td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<!-- BEGIN: vmodule -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<caption><em class="fa fa-file-text-o">&nbsp;</em>{VCAPTION}</caption>
		<thead>
			<tr>
				<!-- BEGIN: vhead -->
				<th>{VHEAD}</th>
				<!-- END: vhead -->
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td>{VROW.stt}</td>
				<td>{VROW.values.title}</td>
				<td>{VROW.values.module_file}</td>
				<td>{VROW.values.addtime}</td>
				<td>{VROW.values.note}</td>
				<td>{VROW.values.setup}</td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<!-- END: vmodule -->
<!-- END: main -->