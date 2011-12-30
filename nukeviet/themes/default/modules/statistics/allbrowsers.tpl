<!-- BEGIN: main -->
<table class="statistics" summary="">
	<tbody class="thead_box">
		<tr>
			<td>
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
	</tbody>
	<!-- BEGIN: loop -->
	<tbody {CLASS}>
		<tr>
			<td>
				{KEY}
			</td>
			<td class="align_r">
				{VALUE.0}
			</td>
			<td class="col2">
				<!-- BEGIN: img -->
				<img width="{WIDTH}" height="10" src="{SRC}" alt="" />
				<!-- END: img -->
			</td>
			<td style="width: 250px;">
				{VALUE.1}
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