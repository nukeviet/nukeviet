<!-- BEGIN: main -->
<ul class="videotop">
	<!-- BEGIN: youtube -->
	<li>
		<iframe style="width: 100%;" width="300" height="399"  allowfullscreen="allowfullscreen" frameborder="0"  src="//www.youtube.com/embed/{CODE}?rel=0&amp;controls=1&amp;autohide=0" ></iframe>
	</li>
	<!-- END: youtube -->

	<!-- BEGIN: player -->
	<li>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js"></script>
		<script type="text/javascript">
			function BlockvideoPlay(d, c) {
				var a = $("#" + c).outerWidth(),
				    b;
				640 < a && ( a = 640);
				b = a;
				a = Math.ceil(45 * a / 80) + 4;
				$("#" + c).parent().css({
					width : b,
					height : a,
					margin : "0 auto"
				});
				swfobject.embedSWF("{NV_BASE_SITEURL}themes/{TEMPLATE}/images/{MODULE_FILE}/player.swf", c, b, a, "10.0.0.0", !1, {
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
			BlockvideoPlay("{DETAILCONTENT.filepath}", "videoCont_bl");
		});
		</script>
	</li>
	<!-- END: player -->

	<!-- BEGIN: other -->
	<li class="video-other clearfix">
		<a title="{DATA.title}" href="{DATA.link}"> <img class=" pull-left" alt="{DATA.title}" src="{DATA.img}" /></a>
		<a class="show" title="{DATA.title}" href="{DATA.link}"> {DATA.titlevideo} </a>
		<span class=""> {DATA.hometext60} </span>
	</li>
	<!-- END: other -->
</ul>
<!-- END: main -->