<!-- BEGIN: main -->
<form action="{NV_BASE_ADMINURL}index.php" method="post">
	<input type="hidden" name ="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
	<input type="hidden" name ="{NV_OP_VARIABLE}" value="{OP}" />
	<input type="hidden" name ="bid" value="{bid}" />
	<input name="savecat" type="hidden" value="1" />
	<div align="center">
		<table class="tab1" style="width: 400px;">
			<caption> {LANG.rpc_setting} </caption>
			<thead>
				<tr>
					<td>{LANG.rpc_linkname}</td>
					<td><input type="checkbox" onclick="nv_checkAll(this.form, 'prcservice[]', 'check_all[]',this.checked);" name="check_all[]" value="yes"> {LANG.rpc_ping}</td>
				</tr>
			</thead>
			<!-- BEGIN: service -->
			<tbody {SERVICE.class}>
				<tr>
					<td>
					<!-- BEGIN: icon -->
					<img src="{IMGPATH}/{SERVICE.icon}" alt="" />
					<!-- END: icon -->
					<!-- BEGIN: noticon -->
					<img src="{IMGPATH}/link.png" alt="" />
					<!-- END: noticon -->
					{SERVICE.title}</td>
					<td><input type="checkbox" name="prcservice[]" value="{SERVICE.title}" onclick="nv_UncheckAll(this.form, 'prcservice[]', 'check_all[]', this.checked);" {SERVICE.checked}/></td>
				</tr>
			</tbody>
			<!-- END: service -->
			<tfoot>
				<tr>
					<td align="center" colspan="3"><input name="submitprcservice" type="submit" value="{LANG.submit}" /></td>
				</tr>
			</tfoot>
		</table>
	</div>
</form>
<!-- END: main -->