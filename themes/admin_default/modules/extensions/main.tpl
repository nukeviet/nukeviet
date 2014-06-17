<!-- BEGIN: main -->
<form class="form-inline m-bottom" role="form" action="{NV_BASE_ADMINURL}index.php">
	<input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}"/>
	<input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}"/>
	<input type="hidden" name="mode" value="search"/>
	<div class="form-group">
		<label class="sr-only" for="search_q">{LANG.search_key}</label>
		<input name="q" value="{REQUEST.q}" type="text" class="form-control" id="search_q" placeholder="{LANG.search_key}">
	</div>
	<button type="submit" class="btn btn-primary">{LANG.search_go}</button>
</form>
<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->
<!-- BEGIN: empty -->
<div class="alert alert-info">
	{LANG.empty_response}
</div>
<!-- END: empty -->
<!-- BEGIN: data -->
<div class="ext-wrap clearfix">
	<!-- BEGIN: loop -->
	<div class="ext-item clearfix">
		<div class="col-img">
			<span class="img-thumbnail"><span><img src="{ROW.image_small}" width="100" alt="{ROW.title}"/></span></span>
		</div>
		<div class="col-data">
			<div class="col-info">
				<p>
					<span class="rating">
						<!-- BEGIN: star -->
						<span class="star{STAR}"></span>
						<!-- END: star -->
					</span>
				</p>
				<p>
					<button type="button" class="btn btn-default btn-xs ext-btn">
						<em class="fa fa-share-square-o fa-lg">&nbsp;</em> <a class="ex-detail" title="{ROW.detail_title}" href="{ROW.detail_link}">{LANG.detail}</a>
					</button>
					<!-- BEGIN: install -->
					<button type="button" class="btn btn-default btn-xs ext-btn">
						<em class="fa fa-download fa-lg">&nbsp;</em> <a href="{ROW.install_link}">{LANG.install}</a>
					</button>
					<!-- END: install -->
				</p>
			</div>
			<div class="col-explain">
				<p>{LANG.author}: <span class="text-primary">{ROW.username}</span></p>
				<p>{LANG.ext_type}: <span class="text-primary">{ROW.type}</span></p>
			</div>
			<h3>{ROW.title}</h3>
			<p>{ROW.introtext}</p>
			<p class="{ROW.compatible_class}">{ROW.compatible_title}</p>
		</div>
	</div>
	<!-- END: loop -->
</div>
<!-- BEGIN: generate_page -->
<div class="text-center">
	{GENERATE_PAGE}
</div>
<!-- END: generate_page -->
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
<!-- END: data -->
<!-- END: main -->
<!-- BEGIN: dev -->
<div class="alert alert-info">{LANG.develop_note}</div>
<!-- END: dev -->