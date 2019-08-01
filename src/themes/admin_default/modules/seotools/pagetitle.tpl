<!-- BEGIN: main -->
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<tbody>
				<tr>
					<td>{LANG.pagetitle2}</td>
					<td><input class="w500 form-control" type="text" value="{DATA.pageTitleMode}" name="pageTitleMode" /></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="text-info">
		{LANG.pagetitleNote}
	</div>
	<div class="text-center"><input type="hidden" name="save" value="1" /><input type="submit" name="submit" value="{LANG.submit}" class="btn btn-primary" />
	</div>
</form>
<!-- END: main -->