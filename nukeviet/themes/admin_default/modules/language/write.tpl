<!-- BEGIN: main -->
<!-- BEGIN: complete -->
<br /><br /><p align="center"><strong>{LANG.nv_lang_wite_ok}: {INCLUDE_LANG}</strong></p>
<meta http-equiv="Refresh" content="10;URL={URL}" />
<!-- END: complete -->
<!-- BEGIN: error -->
<br /><br /><p align="center"><strong>{CONTENT}</strong></p>
<!-- END: error -->
<!-- BEGIN: error_write_allfile -->
<br /><br /><p align="center"><strong>{LANG.nv_error_write_file}</strong></p>
<!-- END: error_write_allfile -->
<!-- BEGIN: write_allfile_complete -->
<br /><br /><p align="center"><strong>{LANG.nv_lang_wite_ok}</strong></p>
<table class="tab1">
	<!-- BEGIN: loop -->
	<tbody{CLASS}>
		<tr>
			<td>{NAME}</td>
		</tr>
	</tbody>
	<!-- END: loop -->
</table>
<meta http-equiv="Refresh" content="10;URL={URL}" />
<!-- END: write_allfile_complete -->
<!-- END: main -->