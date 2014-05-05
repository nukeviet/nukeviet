<!-- BEGIN: main -->
<table class="tab1">
	<tbody>
		<tr>
			<td>
			<form action="{NV_BASE_ADMINURL}index.php" method="post">
				<input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
				<input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}" />
				<input type="hidden" name="catid" value="{CATID}" />
				<input type="hidden" name="delallcheckss" value="{DELALLCHECKSS}" />
				<div class="center">
					<strong>{TITLE}</strong>
					<br />
					<br />
					<input name="delcatandrows" type="submit" value="{LANG.delcatandrows}" />
					<br />
					<br />
					<strong>{LANG.delcat_msg_rows_move}</strong>:
					<select name="catidnews">
						<!-- BEGIN: catidnews -->
						<option value="{CATIDNEWS.key}">{CATIDNEWS.title}</option>
						<!-- END: catidnews -->
					</select>
					<input name="movecat" type="submit" value="{LANG.action}" onclick="return nv_check_movecat(this.form, '{LANG.delcat_msg_rows_noselect}')"/>
				</div>
			</form></td>
		</tr>
	</tbody>
</table>
<!-- END: main -->