<!-- BEGIN: main -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<caption><em class="fa fa-file-text-o">&nbsp;</em>{CONTENTS.caption}</caption>
		<colgroup>
			<col class="w150">
			<col class="w100">
			<col span="3">
			<col class="w100">
		</colgroup>
		<thead>
			<tr>
				<!-- BEGIN: thead -->
				<th>{THEAD}</th>
				<!-- END: thead -->
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<!-- BEGIN: r -->
				<td>{R}</td>
				<!-- END: r -->
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<!-- BEGIN: generate_page -->
<div class="text-center">
	{CONTENTS.generate_page}
</div>
<!-- END: generate_page -->
<!-- END: main -->