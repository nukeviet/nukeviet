<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->

<!-- BEGIN: install -->
<!-- BEGIN: getfile -->
<p class="text-success">
	<em class="fa fa-lg fa-smile-o">&nbsp;</em> <strong>{LANG.install_getfile}</strong> <em class="fa fa-lg fa-check">&nbsp;</em>
</p>
<!-- END: getfile -->

<!-- BEGIN: compatible -->
<p class="text-success">
	<em class="fa fa-lg fa-smile-o">&nbsp;</em> <strong>{LANG.install_check_compatible}</strong> <em class="fa fa-lg fa-check">&nbsp;</em>
</p>
<!-- END: compatible -->
<!-- BEGIN: incompatible -->
<p class="text-danger">
	<em class="fa fa-lg fa-frown-o">&nbsp;</em> <strong>{LANG.install_check_compatible}</strong>
</p>
<div class="alert alert-danger">{LANG.install_check_compatible_error}</div>
<!-- END: incompatible -->

<!-- BEGIN: auto -->
<p class="text-success">
	<em class="fa fa-lg fa-smile-o">&nbsp;</em> <strong>{LANG.install_check_auto_install}</strong> <em class="fa fa-lg fa-check">&nbsp;</em>
</p>
<!-- END: auto -->
<!-- BEGIN: manual -->
<p class="text-danger">
	<em class="fa fa-lg fa-frown-o">&nbsp;</em> <strong>{LANG.install_check_auto_install}</strong>
</p>
<div class="alert alert-danger">{MANUAL_MESSAGE}</div>
<div class="ext-wrap panel-body">
	<div class="ext-dinfo pull-right">
		<a target="_blank" class="btn btn-primary btn-lg btn-block" href="{DATA.compatible.origin_link}" title="{LANG.download}">{LANG.download}</a>
	</div>
	<h3>{LANG.install_documentation}</h3>
	<hr />
	<div class="ext-detail">
		{DATA.documentation}
	</div>
</div>
<!-- END: manual -->

<!-- BEGIN: installed -->
<p class="text-danger">
	<em class="fa fa-lg fa-frown-o">&nbsp;</em> <strong>{LANG.install_check_installed}</strong>
</p>
<div class="alert alert-danger">{LANG.install_check_installed_error}</div>
<!-- END: installed -->
<!-- BEGIN: not_install -->
<p class="text-success">
	<em class="fa fa-lg fa-smile-o">&nbsp;</em> <strong>{LANG.install_check_installed}</strong> <em class="fa fa-lg fa-check">&nbsp;</em>
</p>
<!-- BEGIN: unsure -->
<div id="warnning" class="alert alert-warning">
	<div class="m-bottom">{LANG.install_check_installed_unsure}.</div>
	<div class="text-center">
		<button onclick="EXT.startDownload();" type="button" class="btn btn-primary">{LANG.install_continue}</button>
		<button onclick="EXT.cancel();" type="button" class="btn btn-default">{LANG.install_cancel}</button>
	</div>
</div>
<!-- END: unsure -->
<!-- BEGIN: startdownload -->
<script type="text/javascript">

</script>
<!-- END: startdownload -->
<div id="file-download" class="m-bottom">
	<em class="fa fa-lg fa-meh-o status">&nbsp;</em> 
	<strong>{LANG.install_file_download}<span class="waiting">...</span></strong> 
	<em class="fa fa-lg fa-check complete">&nbsp;</em>
</div>
<div id="file-download-error">
	
</div>
<script type="text/javascript">
var EXT = {
	isDownloaded: false,
	startDownload: function(){
		if( ! EXT.isDownloaded ){
			EXT.isDownloaded = true;
			
			$('#warnning').hide();
			$('#file-download').show();
			$('#file-download .waiting').show();
			
			$.ajax({
				type: 'POST',
				url: script_name,
				data: nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=download&id={DATA.id}&fid={DATA.compatible.id}',
				success: function(e){
					if( e == 'OK' ){
						EXT.handleOk();
					}else{
						EXT.handleError(e);
					}
				}
			});
		}
	},
	cancel: function(){
		window.location = '{CANCEL_LINK}';
	},
	handleOk: function(){
		$('#file-download').addClass('text-success');
		$('#file-download .status').removeClass('fa-meh-o').addClass('fa-smile-o');
		$('#file-download .complete').show();
	},
	handleError: function(m){
		$('#file-download').addClass('text-danger');
		$('#file-download .status').removeClass('fa-meh-o').addClass('fa-frown-o');
		$('#file-download-error').html('<div class="alert alert-danger">' + m + '</div>');
	},
};
</script>
<!-- END: not_install -->
<!-- END: install -->

<!-- BEGIN: getfile_error -->
<p class="text-danger">
	<em class="fa fa-lg fa-frown-o">&nbsp;</em> <strong>{LANG.install_getfile}</strong>
</p>
<div class="alert alert-danger">{LANG.install_getfile_error}</div>
<!-- END: getfile_error -->
<!-- END: main -->