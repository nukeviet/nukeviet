<!-- BEGIN: main -->
<div id="module_show_list">{BLOCK_LIST}</div>
<br />
<div id="add">
	<!-- BEGIN: news -->
	<form action="{NV_BASE_ADMINURL}index.php" method="post">
		<input type="hidden" name ="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
		<input type="hidden" name ="{NV_OP_VARIABLE}" value="{OP}" />
		<table class="tab1">
			<caption>{LANG.addtoblock}</caption>
			<thead>
				<tr>
					<td align="center" width="60">
						<input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);" />
					</td>
					<td>{LANG.name}</td>
				</tr>
			</thead>
			<!-- BEGIN: loop -->
			<tbody{ROW.class}>
				<tr>
					<td align="center">
						<input type="checkbox" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);" value="{ROW.id}" name="idcheck[]"{ROW.checked}/>
					</td>
					<td>{ROW.title}</td>
				</tr>
			</tbody>
			<!-- END: loop -->
			<tfoot>
				<tr align="left">
					<td align="center">
						<input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);" />
					</td>
					<td>
						<select name="bid">
							<!-- BEGIN: bid -->
							<option value="{BID.key}"{BID.selected}>{BID.title}</option>
							<!-- END: bid -->
						</select>
						<input type="hidden" name ="checkss" value="{CHECKSESS}" />
						<input name="submit1" type="submit" value="{LANG.save}" />
					</td>
				</tr>
			</tfoot>
		</table>
	</form>
	<!-- END: news -->
</div>
<!-- END: main -->