<!-- BEGIN: main -->
<table class="tab1">
	<caption> {MODULE} </caption>
	<colgroup>
		<col style="width: 40%"/>
		<col style="width: 30%"/>
		<col style="width: 30%"/>
	</colgroup>
	<thead>
		<tr>
			<td> {THEAD0} </td>
			<td> {THEAD1} </td>
			<td> {THEAD2} </td>
		</tr>
	</thead>
	<tbody>
		<!-- BEGIN: loop -->
		<tr>
			<td> {KEY} </td>
			<!-- BEGIN: if -->
			<td colspan="2"> {VALUE} </td>
			<!-- END: if -->
			<!-- BEGIN: else -->
			<th> {VALUE0} </th>
			<th> {VALUE1} </th>
			<!-- END: else -->
		</tr>
		<!-- END: loop -->
	</tbody>
</table>
<!-- END: main -->