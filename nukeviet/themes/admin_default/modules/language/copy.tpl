<!-- BEGIN: empty -->
<br /><br /><p align="center"><strong>{LANG.nv_lang_error_exit}</strong></p>
<meta http-equiv="Refresh" content="3;URL={URL}" />
<!-- END: empty -->
<!-- BEGIN: copyok -->
<br /><br /><p align="center"><strong>{LANG.nv_lang_copyok}</strong></p>
<meta http-equiv="Refresh" content="3;URL={URL}" />
<!-- END: copyok -->
<!-- BEGIN: main -->
<form action="{NV_BASE_ADMINURL}index.php" method="post">
	<input type="hidden" name ="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
	<input type="hidden" name ="{NV_OP_VARIABLE}" value="{OP}" />
	<input type="hidden" name ="checksess" value="{CHECKSESS}" />
	<table class="tab1">
		<tbody>
			<tr>
				<td style="width:200px">
					<select name="newslang">
						<option value="">{LANG.nv_admin_sl1}</option>
						<!-- BEGIN: newslang -->
						<option value="{NEWSLANG.key}">{NEWSLANG.title}</option>
						<!-- END: newslang -->
					</select>
				</td>
				<td>
					<select name="typelang">
						<option value="">{LANG.nv_admin_sl2}</option>
						<!-- BEGIN: typelang -->
						<option value="-vi">{LANG.nv_lang_copy}: {NAME} {LANG.nv_lang_encstring}</option>
						<!-- END: typelang -->
						<!-- BEGIN: typelang_1 -->
						<option value="{TYPELANG.key}">{TYPELANG.title}</option>
						<!-- END: typelang_1 -->
					</select>
				</td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="2" class="center"><input type="submit" value="{LANG.nv_admin_submit}" /></td>
			</tr>
		</tfoot>
	</table>
</form>
<!-- END: main -->