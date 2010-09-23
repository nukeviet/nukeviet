<!-- BEGIN: main -->
<table class="statistics" summary="">
	<thead>
		<tr>
			<td colspan="2">
				{CTS.thead.0}
			</td>
			<td class="align_r">
				{CTS.thead.1}
			</td>
			<td>
			</td>
			<td>
				{CTS.thead.2}
			</td>
		</tr>
	</thead>
	<!-- BEGIN: loop -->
	<tbody {CLASS}>
		<tr>
			<td>
				{VALUE.0}
			</td>
			<td>
				{KEY}
			</td>
			<td class="align_r">
				{VALUE.1}
			</td>
			<td class="col2">
				<!-- BEGIN: img -->
				<img width="{WIDTH}" height="10" src="{SRC}" alt="" />
				<!-- END: img -->
			</td>
			<td style="width: 250px;">
				{VALUE.2}
			</td>
		</tr>
	</tbody>
	<!-- END: loop -->
</table>
<!-- BEGIN: gp -->
<br />
<br />
<div>
	{CTS.generate_page}
</div>
<!-- END: gp -->
<!-- END: main -->