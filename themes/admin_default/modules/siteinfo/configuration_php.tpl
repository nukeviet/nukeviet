<!-- BEGIN: main -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<caption><em class="fa fa-file-text-o">&nbsp;</em>{CAPTION}</caption>
		<thead>
			<tr>
				<th> {THEAD0} </th>
				<th> {THEAD1} </th>
				<th> {THEAD2} </th>
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td> {KEY} </td>
				<!-- BEGIN: if -->
				<td> {VALUE} </td>
				<td> {VALUE} </td>
				<!-- END: if -->
				<!-- BEGIN: else -->
				<th> {VALUE0} </th>
				<th> {VALUE1} </th>
				<!-- END: else -->
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<!-- END: main -->