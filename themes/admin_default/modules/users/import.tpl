<!-- BEGIN: main -->
<div class="alert alert-warning">{LANG.import_note}</div>
<!-- BEGIN: read -->
<br />
{LANG.read_note}
<div class="table-responsive">
	<table id="table_field_read" class="table table-striped table-bordered table-hover">
		<colgroup>
			<col style="width: 35px" />
			<col span="2" />
		</colgroup>
		<thead>
			<tr>
				<td>&nbsp;</td>
				<td><strong>{LANG.read_filename}</strong></td>
				<td><strong>{LANG.read_filesite}</strong></td>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="3"><input name="submitfiledata" type="button"
				value="{LANG.read_submit}" /></td>
			</tr>
		</tfoot>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td><input type="checkbox" name="readfiledata[]"
				value="{DATA.file_name_base64}"></td>
				<td>{DATA.file_name}</td>
				<td>{DATA.file_size}</td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<script type="text/javascript">
	//<![CDATA[
	function nv_readfiledata(listfile) {
		$.ajax({
			type : "POST",
			url : "index.php?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=import&nocache=" + new Date().getTime(),
			data : "step=2&listfile=" + listfile,
			success : function(response) {
				if (response == "OK_GETFILE") {
					nv_readfiledata('');
				} else if (response == "OK_COMPLETE") {
					$("#table_field_read").hide();
					if (confirm('{LANG.read_complete}')) {
						window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name;
					}
				} else {
					alert(response);
					$("#table_field_read").hide();
				}
			},
			error : function(x, e) {
				if (x.status == 0) {
					alert('You are offline!!\n Please Check Your Network.');
				} else if (x.status == 404) {
					alert('Requested URL not found.');
				} else if (x.status == 500) {
					alert('{LANG.read_error_memory_limit}');
				} else if (e == 'timeout') {
					alert('Request Time out.');
				} else {
					alert('Unknow Error.\n' + x.responseText);
				}
				$("#table_field_read").hide();
			}
		});
	}


	$("input[name=submitfiledata]").click(function() {
		var listfile = '';
		$("input[name=\'readfiledata[]\']:checked").each(function() {
			listfile = listfile + '@' + $(this).val();
		});
		if (listfile != '') {
			$("#table_field_read").html("<center><img src='{NV_BASE_SITEURL}images/load_bar.gif' alt='' /></center>");
			nv_readfiledata(listfile);
		}
	});
	//]]>
</script>
<!-- END: read -->
<!-- END: main -->