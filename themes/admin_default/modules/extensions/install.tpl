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
<script type="text/javascript">
Shadowbox.init({
	animate: false,
	animateFade: false,
    enableKeys: false,
    modal: true,
    overlayOpacity: 0.8,
    handleOversize: 'resize',
});
var player_width = $(window).width();
var player_height = $(window).height();
if( player_width > 1060 ){
	player_width = 1000;
}else{
	player_width = player_width - 60;
}
if( player_height > 660 ){
	player_height = 600;
}else{
	player_height = player_height - 60;
}
$(function(){
	$('.ex-detail').click(function(e){
		e.preventDefault();
		Shadowbox.open({
	        content: '<iframe style="width:' + player_width + 'px;height:' + player_height + 'px;border:0" src="' + $(this).attr('href') + '"></iframe>',
	        player: "html",
	        title: $(this).attr('title'),
	        height: player_height,
	        width: player_width
	    });
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
<div class="alert alert-danger">{LANG.install_check_installed_error}</div>
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
var EXT = {
	tid: {DATA.tid},
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
				data: nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=download&data={STRING_DATA}',
				success: function(e){
					$('#file-download .waiting').hide();
					e = e.split('|');
					if( e[0] == 'OK' ){
						EXT.handleOk(e[1]);
					}else{
						EXT.handleError(e[1]);
					}
				}
			});
		}
	},
	cancel: function(){
		window.location = '{CANCEL_LINK}';
	},
	handleOk: function(f){
		$('#file-download').addClass('text-success');
		$('#file-download .status').removeClass('fa-meh-o').addClass('fa-smile-o');
		$('#file-download .complete').show();
		
		$('#file-download-response').html('<div class="alert alert-success">{LANG.download_ok}</div>');
		
		setTimeout( "EXT.redirect()", 3000 );
	},
	handleError: function(m){
		$('#file-download').addClass('text-danger');
		$('#file-download .status').removeClass('fa-meh-o').addClass('fa-frown-o');
		$('#file-download-response').html('<div class="alert alert-danger">' + m + '</div>');
	},
	redirect: function(){
		var url = '{NV_BASE_ADMINURL}index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=extensions&' + nv_fc_variable + '=upload&uploaded=1';
		window.location = url;
	},
};
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