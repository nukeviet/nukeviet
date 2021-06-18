<!-- BEGIN: main -->
<div class="statistics-responsive">
	<table class="table table-bordered table-striped statistics">
		<thead>
			<tr>
				<td> {CTS.thead.0} </td>
				<td class="text-right"> {CTS.thead.1} </td>
				<td>&nbsp;</td>
				<td> {CTS.thead.2} </td>
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td> {KEY} </td>
				<td class="text-right"> {VALUE.0} </td>
				<td>
				<!-- BEGIN: img -->
				<img width="{WIDTH}" height="10" src="{SRC}" alt="" />
				<!-- END: img -->
				</td>
				<td class="w250"> {VALUE.1} </td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<!-- BEGIN: gp -->
<div class="text-center">
	{CTS.generate_page}
</div>
<!-- END: gp -->
<!-- END: main -->