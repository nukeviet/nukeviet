<!-- BEGIN: main -->
<div class="well">{LANG.nv_lang_note_edit} : {ALLOWED_HTML_LANG}</div>
<form action="{NV_BASE_ADMINURL}index.php" method="post">
	<input type="hidden" name="pozauthor[langtype]" value="{LANGTYPE}" />
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th>{LANG.nv_lang_nb}</th>
					<th>{LANG.nv_lang_key}</th>
					<th>{LANG.nv_lang_value}</th>
				</tr>
			</thead>
			<tbody>
				<!-- BEGIN: array_translator -->
				<tr>
					<td>&nbsp;</td>
					<td>{ARRAY_TRANSLATOR.lang_key}</td>
					<td><input type="text" value="{ARRAY_TRANSLATOR.value}" name="pozauthor[{ARRAY_TRANSLATOR.lang_key}]" size="90" class="form-control"/></td>
				</tr>
				<!-- END: array_translator -->
				<!-- BEGIN: array_body -->
				<tr>
					<td class="text-center">{ARRAY_BODY}</td>
					<td class="text-right"><input type="text" value="" name="pozlangkey[{ARRAY_BODY}]" size="10" class="form-control" /></td>
					<td class="text-left"><input type="text" value="" name="pozlangval[{ARRAY_BODY}]" size="90" class="form-control" /></td>
				</tr>
				<!-- END: array_body -->
				<!-- BEGIN: array_data -->
				<tr>
					<td class="text-center">{ARRAY_DATA.key}</td>
					<td class="text-right">{ARRAY_DATA.lang_key}</td>
					<td class="text-left"><input type="text" value="{ARRAY_DATA.value}" name="pozlang[{ARRAY_DATA.id}]" size="90" class="form-control" /></td>
				</tr>
				<!-- END: array_data -->
			</tbody>
			<tfoot>
				<tr>
					<td class="text-center" colspan="3"><input type="submit" value="{LANG.nv_admin_edit_save}" class="btn btn-primary" /></td>
				</tr>
			</tfoot>
		</table>
	</div>
	<input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
	<input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}" />
	<input type="hidden" name="idfile" value="{IDFILE}" />
	<input type="hidden" name="dirlang" value="{DIRLANG}" />
	<input type="hidden" name="savedata" value="{SAVEDATA}" />
</form>
<!-- END: main -->