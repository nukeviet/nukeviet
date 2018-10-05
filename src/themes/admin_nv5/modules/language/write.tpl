<!-- BEGIN: main -->
<!-- BEGIN: complete -->
<br />
<br />
<p class="text-center">
	<strong><a href="{URL}">{LANG.nv_lang_wite_ok}: {INCLUDE_LANG}</a></strong>
</p>
<meta http-equiv="Refresh" content="10;URL={URL}" />
<!-- END: complete -->
<!-- BEGIN: error -->
<br />
<br />
<p class="text-center">
	<strong>{CONTENT}</strong>
</p>
<!-- END: error -->
<!-- BEGIN: error_write_allfile -->
<br />
<br />
<p class="text-center">
	<strong>{LANG.nv_error_write_file}</strong>
</p>
<!-- END: error_write_allfile -->
<!-- BEGIN: write_allfile_complete -->
<br />
<br />
<p class="text-center">
	<strong><a href="{URL}">{LANG.nv_lang_wite_ok}</a></strong>
</p>
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td>{NAME}</td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<meta http-equiv="Refresh" content="10;URL={URL}" />
<!-- END: write_allfile_complete -->
<!-- END: main -->