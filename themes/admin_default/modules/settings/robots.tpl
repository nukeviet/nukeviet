<!-- BEGIN: main -->
<!-- BEGIN: nowrite -->
<div class="quote" style="width:98%">
	<blockquote class="error">{TITLE}</blockquote>
</div>
<div style="clear:both"></div>
<div class="codecontent">{CONTENT}</div>
<!-- END: nowrite -->
<form action="" method="post">
	<center>
		<table class="tab1" summary="" style="width:auto; text-align: left;">
			<thead>
				<tr align="center">
					<td>{LANG.robots_number}</td>
					<td>{LANG.robots_filename}</td>
					<td>{LANG.robots_type}</td>
				</tr>
			</thead>
			<!-- BEGIN: loop -->
			<tbody {DATA.class}>
				<tr>
					<td align="center">{DATA.number}</td>
					<td>{DATA.filename}</td>
					<td>
					<select name="filename[{DATA.filename}]">
						<!-- BEGIN: option -->
						<option value="{OPTION.value}" {OPTION.selected}>{OPTION.title}</option>
						<!-- END: option -->
					</select></td>
				</tr>
			</tbody>
			<!-- END: loop -->
		</table>
		<p>
			<input type="submit" name="submit" value="{LANG.submit}" />
		</p>
	</center>
</form>
<!-- END: main -->
