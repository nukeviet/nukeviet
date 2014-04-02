<!-- BEGIN: main -->
<!-- BEGIN: facebookjssdk -->
<div id="fb-root"></div>
<script type="text/javascript">
    (function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = "//connect.facebook.net/{FACEBOOK_LANG}/all.js#xfbml=1&appId={FACEBOOK_APPID}";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>
<!-- END: facebookjssdk -->
<div class="news_column" style="padding:0 !important;margin:0 !important;">
	<div id="news_detail">
		<h1>{DETAIL.title}</h1>
		<div class="time">{DETAIL.publtime}</div>
        <!-- BEGIN: socialbutton -->
        <div class="clear"></div>
        <div style="margin-right: 50px;" class="fb-like" data-href="{SELFURL}" data-width="The pixel width of the plugin" data-height="The pixel height of the plugin" data-colorscheme="light" data-layout="button_count" data-action="like" data-show-faces="true" data-send="false"></div>

        <div class="g-plusone" data-size="medium"></div>
        <script type="text/javascript">
          window.___gcfg = {lang: nv_sitelang};
          (function() {
            var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
            po.src = 'https://apis.google.com/js/plusone.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
          })();
        </script>

        <a href="http://twitter.com/share" class="twitter-share-button">Tweet</a>
        <script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
        <!-- END: socialbutton -->
		<div class="control">
			<ul>
				<!-- BEGIN: allowed_send -->
				<li>
					<a rel="nofollow" class="sendmail" title="{LANG.sendmail}" href="javascript:void(0);" onclick="NewWindow('{URL_SENDMAIL}','{TITLE}','500','400','no');return false"></a>
				</li>
				<!-- END: allowed_send -->
				<!-- BEGIN: allowed_print -->
				<li>
					<a class="print" title="{LANG.print}" href="javascript: void(0)" onclick="NewWindow('{URL_PRINT}','{TITLE}','840','768','yes');return false"></a>
				</li>
				<!-- END: allowed_print -->
				<!-- BEGIN: allowed_save -->
				<li>
					<a class="savefile" title="{LANG.savefile}" href="{URL_SAVEFILE}"></a>
				</li>
				<!-- END: allowed_save -->
			</ul>
		</div>
		<div class="clear"></div>
		<!-- BEGIN: showhometext -->
		<div id="hometext">
			<!-- BEGIN: imgthumb -->
			<div id="imghome" class="fl" style="width:{DETAIL.image.width}px;margin-right:8px;">
				<a href="{DETAIL.homeimgfile}" title="{TITLE}" rel="shadowbox"><img alt="{DETAIL.image.alt}" src="{DETAIL.image.src}" width="{DETAIL.image.width}"/></a>
				<p>
					<em>{DETAIL.image.note}</em>
				</p>
			</div>
			<!-- END: imgthumb -->
			<h2>{DETAIL.hometext}</h2>
		</div>
		<!-- BEGIN: imgfull -->
		<div style="width:{DETAIL.image.width}px;margin:10px auto;">
			<img alt="{DETAIL.image.alt}" src="{DETAIL.image.src}" width="{DETAIL.image.width}"/>
			<p style="text-align: center;">
				<em>{DETAIL.image.note}</em>
			</p>
		</div>
		<!-- END: imgfull -->
		<!-- END: showhometext -->
		<div class="bodytext">
			{DETAIL.bodytext}
		</div>
		<!-- BEGIN: author -->
		<!-- BEGIN: name -->
		<p style="text-align: right;">
			<strong>{LANG.author}: </strong>{DETAIL.author}
		</p>
		<!-- END: name -->
		<!-- BEGIN: source -->
		<p style="text-align: right;">
			<strong>{LANG.source}: </strong>{DETAIL.source}
		</p>
		<!-- END: source -->
		<!-- END: author -->
		<!-- BEGIN: copyright -->
		<p class="copyright">
			{COPYRIGHT}
		</p>
		<!-- END: copyright -->
	</div>
	<!-- BEGIN: adminlink -->
	<p style="text-align: right;">
		{ADMINLINK}
	</p>
	<!-- END: adminlink -->
	<!-- BEGIN: allowed_rating -->
	<form id="form3B" action="">
		<div class="clearfix">
			<div id="stringrating">
				{STRINGRATING}
			</div>
            <!-- BEGIN: data_rating -->
            <span itemscope itemtype="http://data-vocabulary.org/Review-aggregate">
               {LANG.rating_average}:
               <span itemprop="rating">{DETAIL.numberrating}</span> -
               <span itemprop="votes">{DETAIL.click_rating}</span> {LANG.rating_count}
            </span>
            <!-- END: data_rating -->
			<div style="padding: 5px;">
				<input class="hover-star" type="radio" value="1" title="{LANGSTAR.verypoor}" /><input class="hover-star" type="radio" value="2" title="{LANGSTAR.poor}" /><input class="hover-star" type="radio" value="3" title="{LANGSTAR.ok}" /><input class="hover-star" type="radio" value="4" title="{LANGSTAR.good}" /><input class="hover-star" type="radio" value="5" title="{LANGSTAR.verygood}" /><span id="hover-test" style="margin: 0 0 0 20px;">{LANGSTAR.note}</span>
			</div>
		</div>
	</form>
	<script type="text/javascript">
		var sr = 0;
		$('.hover-star').rating({
			focus : function(value, link) {
				var tip = $('#hover-test');
				if (sr != 2) {
					tip[0].data = tip[0].data || tip.html();
					tip.html(link.title || 'value: ' + value);
					sr = 1;
				}
			},
			blur : function(value, link) {
				var tip = $('#hover-test');
				if (sr != 2) {
					$('#hover-test').html(tip[0].data || '');
					sr = 1;
				}
			},
			callback : function(value, link) {
				if (sr == 1) {
					sr = 2;
					$('.hover-star').rating('disable');
					sendrating('{NEWSID}', value, '{NEWSCHECKSS}');
				}
			}
		});

		$('.hover-star').rating('select', '{NUMBERRATING}');
	</script>
	<!-- BEGIN: disablerating -->
	<script type="text/javascript">
		$(".hover-star").rating('disable');
		sr = 2;
	</script>
	<!-- END: disablerating -->
	<!-- END: allowed_rating -->
	<!-- BEGIN: keywords -->
	<div class="keywords">
		<strong>{LANG.keywords}: </strong>
		<!-- BEGIN: loop -->
		<a title="{KEYWORD}" href="{LINK_KEYWORDS}"><em>{KEYWORD}</em></a>{SLASH}
		<!-- END: loop -->
	</div>
	<!-- END: keywords -->
	<!-- BEGIN: comment -->
	<iframe src="{NV_COMM_URL}" id = "fcomment" onload = "nv_setIframeHeight( this.id )" style="width: 100%; min-height: 300px; max-height: 1000px"></iframe>
	<!-- END: comment -->

	<!-- BEGIN: topic -->
	<p>
		<strong>{LANG.topic}</strong>
	</p>
	<ul class="related">
		<!-- BEGIN: loop -->
		<li>
			<a title="{TOPIC.title}" href="{TOPIC.link}">{TOPIC.title}</a>
			<span class="date">({TOPIC.time})</span>
			<!-- BEGIN: newday -->
			<span class="icon_new"></span>
			<!-- END: newday -->
		</li>
		<!-- END: loop -->
	</ul>
	<div class="clear"></div>
	<p style="text-align: right;">
		<a title="{TOPIC.topictitle}" href="{TOPIC.topiclink}">{LANG.more}</a>
	</p>
	<!-- END: topic -->
	<!-- BEGIN: related_new -->
	<p>
		<strong>{LANG.related_new}</strong>
	</p>
	<ul class="related">
		<!-- BEGIN: loop -->
		<li>
			<a title="{RELATED_NEW.title}" href="{RELATED_NEW.link}">{RELATED_NEW.title}</a>
			<span class="date">({RELATED_NEW.time})</span>
			<!-- BEGIN: newday -->
			<span class="icon_new"></span>
			<!-- END: newday -->
		</li>
		<!-- END: loop -->
	</ul>
	<!-- END: related_new -->
	<!-- BEGIN: related -->
	<div class="clear"></div>
	<p>
		<strong>{LANG.related}</strong>
	</p>
	<ul class="related">
		<!-- BEGIN: loop -->
		<li>
			<a title="{RELATED.title}" href="{RELATED.link}">{RELATED.title}</a>
			<span class="date">({RELATED.time})</span>
			<!-- BEGIN: newday -->
			<span class="icon_new"></span>
			<!-- END: newday -->
		</li>
		<!-- END: loop -->
	</ul>
	<!-- END: related -->
</div>
<!-- END: main -->
<!-- BEGIN: no_permission -->
<div id="no_permission">
	<p>
		{NO_PERMISSION}
	</p>
</div>
<!-- END: no_permission -->