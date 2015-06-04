<!-- BEGIN: main -->

<!-- BEGIN: files_content -->
<div class="download_content">
	<!-- BEGIN: loop -->
	<div class="row download">
		<div class="col-md-18">
			<img src="{FILES.extension_icon}" width="16" />&nbsp;<a href="" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="{FILES.description}">{FILES.title}</a>
		</div>
		<div class="col-md-4"><span class="text-muted">{FILES.filesize}</span></div>
		<div class="col-md-2 text-right" data-toggle="tooltip" data-placement="top" data-original-title="{NOTE}">
			<button id="{FILES.id}" title="" class="btn btn-primary btn-xs download_files" <!-- BEGIN: disabled -->disabled="disabled"<!-- END: disabled --> ><em class="fa fa-download">&nbsp;</em></button>
		</div>
	</div>
	<!-- END: loop -->
</div>
<!-- END: files_content -->

<script type="text/javascript">
	$(document).ready(function(){
		$('[data-toggle="tooltip"]').tooltip({ container: 'body' });
	});

	$('button.download_files').click(function(){
		var id_files = $(this).attr('id');
		window.location.href = nv_siteroot + "index.php?" + nv_lang_variable + "=" + nv_sitelang + "&" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=download&id_files=" + id_files + '&id_rows={proid}';
		return false;
	});
</script>
<!-- END: main -->