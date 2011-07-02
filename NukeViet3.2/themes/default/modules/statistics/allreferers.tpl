<!-- BEGIN: main -->
<table class="statistics" summary="">
	<tbody class="thead_box">
		<tr>
			<td>{CTS.thead.0}</td>
			<td style="text-align: right;">{CTS.thead.1}</td>
			<td></td>
			<td>{CTS.thead.2}</td>
			<td></td>
		</tr>
	</tbody>
	<!-- BEGIN: loop -->
	<tbody {CLASS}>
		<tr>
			<td>
				<a target="_blank" href="http://{KEY}">{KEY}</a>
			</td>
			<td style="text-align: right;">{VALUE.0}</td>
			<td style="font-size: 8px; width: 300px;">
				<!-- BEGIN: img -->
				<img width="{WIDTH}" height="10" src="{SRC}" alt="" />
				<!-- END: img -->
			</td>
			<td style="width: 250px;">{VALUE.1}</td>
			<td>{VALUE.2}</td>
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