<!-- BEGIN: main -->
<script type="text/javascript" src="{NV_BASE_SITEURL}themes/{TEMPLATE}/images/{MODULE_FILE}/jwplayer/jwplayer.js"></script>
<script type="text/javascript">jwplayer.key="KzcW0VrDegOG/Vl8Wb9X3JLUql+72MdP1coaag==";</script>

<ul class="videotop" id="blvideo_bl{BID}">
	<!-- BEGIN: youtube -->
	<li class="clearfix">
        <div class="clearfix" id="ytblvideoctn_bl{BID}_v{DATA.id}">
    		<iframe id="ytblvideo_bl{BID}_v{DATA.id}" allowfullscreen="allowfullscreen" frameborder="0"  src="//www.youtube.com/embed/{CODE}?rel=0&amp;controls=1&amp;autohide=0" ></iframe>
        </div>
        <script type="text/javascript">
        $(function() {
            var a = $("#ytblvideoctn_bl{BID}_v{DATA.id}").outerWidth(), b;
            640 < a && (a = 640);
            b = a;
            a = Math.ceil(45 * a / 80) + 4;
            $('#ytblvideo_bl{BID}_v{DATA.id}').width(b);
            $('#ytblvideo_bl{BID}_v{DATA.id}').height(a);
            $('#ytblvideo_bl{BID}_v{DATA.id}').parent().css({
                width: b,
                height: a,
                margin: "0 auto"
            });
        });
        </script>
	</li>
	<!-- END: youtube -->

	<!-- BEGIN: player -->
	<li class="clearfix">
		<div class="videoplayer_bl" id="videoContCtn_bl{BID}_v{DATA.id}">
			<div class="cont">
				<div id="videoCont_bl{BID}_v{DATA.id}"></div>
			</div>
		</div>
        <script type="text/javascript">
        $(function() {
            var ele = "videoCont_bl{BID}_v{DATA.id}";
            var a = $("#videoContCtn_bl{BID}_v{DATA.id}").outerWidth(), b;
            640 < a && (a = 640);
            b = a;
            a = Math.ceil(45 * a / 80) + 4;
            $("#" + ele).parent().css({
                width: b,
                height: a,
                margin: "0 auto"
            });
            jwplayer(ele).setup({
                file: "{DETAILCONTENT.filepath}",
                width: b,
                height: a,
                autostart: false
            });
        });
        </script>
	</li>
	<!-- END: player -->

	<!-- BEGIN: other -->
	<li class="video-other clearfix">
		<a title="{DATA.title}" href="{DATA.link}"> <img class=" pull-left" alt="{DATA.title}" src="{DATA.img}" /></a>
		<a class="show" title="{DATA.title}" href="{DATA.link}"> {DATA.titlevideo} </a>
		<!-- BEGIN: showhometext --><span class=""> {DATA.hometext60} </span><!-- END: showhometext -->
	</li>
	<!-- END: other -->
</ul>
<!-- END: main -->