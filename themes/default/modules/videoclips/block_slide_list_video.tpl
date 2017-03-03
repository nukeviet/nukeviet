<!-- BEGIN: main -->
<link rel="stylesheet" href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/flexslider.css" type="text/css" media="screen" />
<div id="videotop" class="flexslider videotop col-md-24">
	<ul class="groups_notice slides">
		<!-- BEGIN: loop -->
		<li class="clearfix">
			<!-- BEGIN: youtube -->
			<div class="col-md-24 col-sm-24 col-xs-24" style="padding:0px 0px;">
				<iframe style="width: 100%;" width="100%" allowfullscreen="allowfullscreen" frameborder="0"  src="//www.youtube.com/embed/{CODE}?rel=0&amp;controls=1&amp;autohide=0" ></iframe>
			</div>
			<!-- END: youtube -->
		
			<!-- BEGIN: player -->
			<div>
				<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js"></script>
				<script type="text/javascript">
					function BlockvideoPlay(d, c) {
						var a = $("#" + c).outerWidth(), b;
						640 < a && ( a = 640);
						b = a;
						a = Math.ceil(45 * a / 80) + 4;
						$("#" + c).parent().css({
							width : b,
							height : a,
							margin : "0 auto"
						});
						swfobject.embedSWF("{NV_BASE_SITEURL}themes/{TEMPLATE}/images/{MODULE_NAME}/player.swf", c, b, a, "10.0.0.0", !1, {
							file : d,
							backcolor : "0x000000",
							frontcolor : "0x666666",
							lightcolor : "0xFF6600",
							width : b,
							height : a,
							controlbar : "over",
							autostart : 0,
							smoothing : 1,
							autoscroll : 1,
							stretching : "fill",
							volume : 100,
							largecontrols : 1
						}, {
							bgcolor : "#000000",
							wmode : "window",
							allowFullScreen : "true",
							allowScriptAccess : "always"
						});
						return !1
					};
		
				</script>
				<div class="videoplayer_bl">
					<div class="cont">
						<div id="videoCont_bl"></div>
					</div>
				</div>
				<script type="text/javascript">
					$(function() {
						BlockvideoPlay("{DETAILCONTENT.filepath}", "videoCont_bl"), $("html,body").animate({
							scrollTop : $(".detailContent_bl").offset().top
						}, 500)
					});
				</script>
			</div>
			<!-- END: player -->
		</li>
		<!-- END: loop -->
	</ul>
</div>
<div class="custom-navigation">
   <a href="#" class="flex-next">Sau <i class="fa fa-angle-right fa-lg"></i></a>
  <div class="custom-controls-container"></div>
  <a href="#" class="flex-prev"><i class="fa fa-angle-left fa-lg"></i> Trước</a>
</div>
<script defer src="{NV_BASE_SITEURL}themes/{TEMPLATE}/js/jquery.flexslider.js"></script>
<script type="text/javascript">
	$(window).load(function() {
		$('#videotop').flexslider({
			animation : "slide",
			controlNav : false,
			animationLoop : false,
			slideshow : false,
			controlsContainer: $(".custom-controls-container"),
    		customDirectionNav: $(".custom-navigation a")
		});
	}); 
</script>
<br/><br/>
<!-- END: main -->