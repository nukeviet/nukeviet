<!-- BEGIN: main -->
<table summary="{CONTENTS.caption}" class="tab1">
	<caption>{CONTENTS.caption}</caption>
	<col style="width:120px;white-space:nowrap" />
	<col style="width:100px;white-space:nowrap" />
	<col span="3" style="white-space:nowrap" />
	<col style="width:90px;white-space:nowrap" />
	<thead>
		<tr>
			<!-- BEGIN: thead -->
			<td>{THEAD}</td>
			<!-- END: thead -->
		</tr>
	</thead>
	<!-- BEGIN: loop -->
	<tbody{ROW.class}>
	<tr>
		<!-- BEGIN: r -->
		<td>{R}</td>
		<!-- END: r -->
	</tr>
	</tbody>
	<!-- END: loop -->
</table>
<!-- BEGIN: generate_page -->
<div class="generate_page">
	{CONTENTS.generate_page}
</div>
<!-- END: generate_page -->
<!-- END: main -->