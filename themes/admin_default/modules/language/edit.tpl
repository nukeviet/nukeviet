<!-- BEGIN: main -->
<div class="quote" style="width:98%">
	<blockquote><span>{LANG.nv_lang_note_edit} : {ALLOWED_HTML_LANG}</span></blockquote>
</div>
<div class="clear"></div>
<form action="{NV_BASE_ADMINURL}index.php" method="post">
	<input type="hidden" name="pozauthor[langtype]" value="{LANGTYPE}" />
	<table class="tab1">
		<thead>
			<tr>
				<td>{LANG.nv_lang_nb}</td>
				<td>{LANG.nv_lang_key}</td>
				<td>{LANG.nv_lang_value}</td>
			</tr>
		</thead>
		<!-- BEGIN: array_translator -->
		<tbody{ARRAY_TRANSLATOR.class}>
			<tr>
				<td></td>
				<td>{ARRAY_TRANSLATOR.lang_key}</td>
				<td><input type="text" value="{ARRAY_TRANSLATOR.value}" name="pozauthor[{ARRAY_TRANSLATOR.lang_key}]" size="90"/></td>
			</tr>
		</tbody>
		<!-- END: array_translator -->
		<!-- BEGIN: array_body -->
		<tbody{ARRAY_BODY.class}>
			<tr>
				<td align="center">{ARRAY_BODY.key}</td>
				<td align="right"><input type="text" value="" name="pozlangkey[{ARRAY_BODY.key}]" size="10" /></td>
				<td align="left"><input type="text" value="" name="pozlangval[{ARRAY_BODY.key}]" size="90" /></td>
			</tr>
		</tbody>
		<!-- END: array_body -->
		<!-- BEGIN: array_data -->
		<tbody{ARRAY_DATA.class}>
			<tr>
			<td align="center">{ARRAY_DATA.key}</td>
			<td align="right">{ARRAY_DATA.lang_key}</td>
			<td align="left"><input type="text" value="{ARRAY_DATA.value}" name="pozlang[{ARRAY_DATA.id}]" size="90" /></td>
			</tr>
		</tbody>
		<!-- END: array_data -->
		<tfoot>
			<tr>
				<td class="center" colspan="3">
					<input type="submit" value="{LANG.nv_admin_edit_save}" />
				</td>
			</tr>
		</tfoot>
	</table>
	<input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
	<input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}" />
	<input type="hidden" name="idfile" value="{IDFILE}" />
	<input type="hidden" name="dirlang" value="{DIRLANG}" />
	<input type="hidden" name="savedata" value="{SAVEDATA}" />
</form>
<!-- END: main -->