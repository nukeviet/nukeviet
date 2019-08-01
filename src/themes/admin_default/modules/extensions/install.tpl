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

<!-- BEGIN: require_exists -->
<p class="text-success">
	<em class="fa fa-lg fa-smile-o">&nbsp;</em> <strong>{LANG.install_check_require}</strong> <em class="fa fa-lg fa-check">&nbsp;</em>
</p>
<!-- END: require_exists -->
<!-- BEGIN: require_noexists -->
<p class="text-danger">
	<em class="fa fa-lg fa-frown-o">&nbsp;</em> <strong>{LANG.install_check_require}</strong>
</p>
<div class="alert alert-danger"><a class="text-danger ex-detail" href="{REQUIRE_LINK}" title="{REQUIRE_TITLE}">{REQUIRE_MESSAGE}</a></div>

<div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
				</button>
				<h4 class="modal-title" id="myModalLabel">{LANG.file_name}</h4>
			</div>
			<div class="modal-body">
				<p class="text-center"><em class="fa fa-spinner fa-spin fa-3x">&nbsp;</em></p>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
$(function(){
	$('.ex-detail').click(function(e){
		e.preventDefault();
		$('#myModalLabel').html( $(this).attr('title') );
		$('#imagemodal .modal-dialog').css({'width': player_width});
		$('#imagemodal .modal-body').load( $(this).attr('href') );
		$('#imagemodal').modal('show');
	});
});
</script>
<!-- END: require_noexists -->

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
<div class="alert alert-danger">{INSTALLED_MESSAGE}</div>
<!-- END: installed -->
<!-- BEGIN: not_install -->
<p class="text-success">
	<em class="fa fa-lg fa-smile-o">&nbsp;</em> <strong>{LANG.install_check_installed}</strong> <em class="fa fa-lg fa-check">&nbsp;</em>
</p>
<!-- BEGIN: paid -->
<p class="text-success">
	<em class="fa fa-lg fa-smile-o">&nbsp;</em> <strong>{LANG.install_check_paid}</strong> <em class="fa fa-lg fa-check">&nbsp;</em>
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
$(document).ready(function(){
	EXT.startDownload();
});
</script>
<!-- END: startdownload -->
<div id="file-download" class="m-bottom">
	<em class="fa fa-lg fa-meh-o status">&nbsp;</em>
	<strong>{LANG.install_file_download}<span class="waiting">...</span></strong>
	<em class="fa fa-lg fa-check complete">&nbsp;</em>
</div>
<div id="file-download-response">

</div>
<script type="text/javascript">
var LANG = [];
var CFG = [];
CFG.id = '{DATA.tid}';
CFG.string_data = '{STRING_DATA}';
CFG.cancel_link = '{CANCEL_LINK}';
LANG.download_ok = '{LANG.download_ok}';
</script>
<!-- END: paid -->
<!-- BEGIN: await -->
<p class="text-danger">
	<em class="fa fa-lg fa-frown-o">&nbsp;</em> <strong>{LANG.install_check_paid}</strong>
</p>
<div class="alert alert-danger">{LANG.install_check_paid_await}</div>
<!-- END: await -->
<!-- BEGIN: notlogin -->
<p class="text-danger">
	<em class="fa fa-lg fa-frown-o">&nbsp;</em> <strong>{LANG.install_check_paid}</strong>
</p>
<div class="alert alert-warning"><a href="{LOGIN_LINK}">{LANG.install_check_paid_nologin}</a></div>
<!-- END: notlogin -->
<!-- BEGIN: unpaid -->
<p class="text-danger">
	<em class="fa fa-lg fa-frown-o">&nbsp;</em> <strong>{LANG.install_check_paid}</strong>
</p>
<div class="alert alert-warning"><a href="{DATA.compatible.origin_link}">{LANG.install_check_paid_unpaid}</a></div>
<!-- END: unpaid -->
<!-- END: not_install -->
<!-- END: install -->

<!-- BEGIN: getfile_error -->
<p class="text-danger">
	<em class="fa fa-lg fa-frown-o">&nbsp;</em> <strong>{LANG.install_getfile}</strong>
</p>
<div class="alert alert-danger">{LANG.install_getfile_error}</div>
<!-- END: getfile_error -->
<!-- END: main -->