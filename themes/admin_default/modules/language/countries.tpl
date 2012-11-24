<!-- BEGIN: main -->
<form action="" method="post">
	<table class="tab1" style="width:100%">
		<thead>
			<tr>
				<td>{LANG.nv_lang_nb}</td>
				<td>{LANG.countries_name}</td>
				<td>{LANG.nv_lang_data}</td>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="3" align="center">
				<input type="submit" name="submit" value="{LANG.nv_admin_submit}" style="width: 100px;"/>
				</td>
			</tr>
		</tfoot>
		<!-- BEGIN: countries -->
		<tbody>
			<tr>
				<td>{NB}</td>
				<td>{LANG_NAME}</td>
				<td>
				<select name="countries[{LANG_KEY}]">
					<!-- BEGIN: language -->
					<option value="{DATA_KEY}" {DATA_SELECTED}>{DATA_TITLE}</option>
					<!-- END: language -->
				</select></td>
			</tr>
		</tbody>
		<!-- END: countries -->
	</table>
</form>
<!-- END: main -->
