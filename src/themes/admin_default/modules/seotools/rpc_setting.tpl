<!-- BEGIN: main -->
<form action="{NV_BASE_ADMINURL}index.php" method="post">
	<input type="hidden" name ="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
	<input type="hidden" name ="{NV_OP_VARIABLE}" value="{OP}" />
	<input type="hidden" name ="bid" value="{bid}" />
	<input name="savecat" type="hidden" value="1" />
	<div class="text-center">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover w500">
				<caption><em class="fa fa-file-text-o">&nbsp;</em>{LANG.rpc_setting} </caption>
				<thead>
					<tr>
						<td>{LANG.rpc_linkname}</td>
						<td><input type="checkbox" onclick="nv_checkAll(this.form, 'prcservice[]', 'check_all[]',this.checked);" name="check_all[]" value="yes"> {LANG.rpc_ping}</td>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td class="text-center" colspan="2"><input name="submitprcservice" type="submit" value="{LANG.submit}" class="btn btn-primary" /></td>
					</tr>
				</tfoot>
				<tbody>
					<!-- BEGIN: service -->
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
					<!-- END: service -->
				</tbody>
			</table>
		</div>
	</div>
</form>
<!-- END: main -->