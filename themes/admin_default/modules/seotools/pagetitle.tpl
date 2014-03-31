<!-- BEGIN: main -->
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<table class="tab1">
		<tbody>
			<tr>
				<td>{LANG.pagetitle2}</td>
				<td><input class="w500" type="text" value="{DATA.pageTitleMode}" name="pageTitleMode" /></td>
			</tr>
		</tbody>
	</table>
	<div style="margin: 20px auto;">
		{LANG.pagetitleNote}
	</div>
	<div><input type="hidden" name="save" value="1" /><input type="submit" name="submit" value="{LANG.submit}" />
	</div>
</form>
<!-- END: main -->