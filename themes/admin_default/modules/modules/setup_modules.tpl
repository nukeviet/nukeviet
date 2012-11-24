<!-- BEGIN: main -->
<table class="tab1">
	<caption>{CAPTION}</caption>
	<thead>
		<tr>
			<!-- BEGIN: head -->
			<td>{HEAD}</td>
			<!-- END: head -->
		</tr>
	</thead>
	<!-- BEGIN: loop -->
	<tbody{ROW.class}>
		<tr>
			<td>{ROW.stt}</td>
			<td>{ROW.values.title}</td>
			<td>{ROW.values.version}</td>
			<td>{ROW.values.addtime}</td>
			<td>{ROW.values.author}</td>
			<td>{ROW.values.setup} {ROW.values.delete}</td>
		</tr>
	</tbody>
	<!-- END: loop -->
</table>
<!-- BEGIN: vmodule -->
<table class="tab1">
	<caption>{VCAPTION}</caption>
	<thead>
		<tr>
			<!-- BEGIN: vhead -->
			<td>{VHEAD}</td>
			<!-- END: vhead -->
		</tr>
	</thead>
	<!-- BEGIN: loop -->
	<tbody{VROW.class}>
		<tr>
			<td>{VROW.stt}</td>
			<td>{VROW.values.title}</td>
			<td>{VROW.values.module_file}</td>
			<td>{VROW.values.addtime}</td>
			<td>{VROW.values.note}</td>
			<td>{VROW.values.setup}</td>
		</tr>
	</tbody>
	<!-- END: loop -->
</table>
<!-- END: vmodule -->
<!-- END: main -->