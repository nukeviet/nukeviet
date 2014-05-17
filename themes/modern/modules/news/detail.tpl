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
<div class="header-details">
	<div class="action fr right">
		<!-- BEGIN: allowed_send -->
		<a rel="nofollow" title="{LANG.sendmail}" href="javascript:void(0);" onclick="nv_open_browse('{URL_SENDMAIL}','{TITLE}',500,400,'resizable=no,scrollbars=no,toolbar=no,location=no,status=no');return false" class="email">{LANG.sendmail}</a>
		<br/>
		<!-- END: allowed_send -->
		<!-- BEGIN: allowed_print -->
		<a title="{LANG.print}" href="javascript: void(0)" onclick="nv_open_browse('{URL_PRINT}','{TITLE}',840,500,'resizable=no,scrollbars=yes,toolbar=no,location=no,status=no');return false" class="print">{LANG.print}</a>
		<br/>
		<!-- END: allowed_print -->
		<!-- BEGIN: allowed_save -->
		<a class="save" title="{LANG.savefile}" href="{URL_SAVEFILE}">{LANG.savefile}</a>
		<!-- END: allowed_save -->
	</div>
	<div class="title">
		<h1>{DETAIL.title}</h1>
		<span class="small">{LANG.pubtime}: {DETAIL.publtime}
			<!-- BEGIN: post_name -->
			- {LANG.post_name}: <a href="#" class="highlight">{DETAIL.post_name}</a>
			<!-- END: post_name -->
		</span>
	</div>

    <!-- BEGIN: socialbutton -->
    <div class="fb-like" data-href="{SELFURL}" data-width="The pixel width of the plugin" data-height="The pixel height of the plugin" data-colorscheme="light" data-layout="button_count" data-action="like" data-show-faces="true" data-send="false">&nbsp;</div>

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

 	<div class="clear"></div>
</div>
<!-- BEGIN: showhometext -->
<div id="hometext" class="short-desc clearfix">
	<!-- BEGIN: imgthumb -->
	<div id="imghome" class="fl left" style="width:{DETAIL.image.width}px;margin-right:20px;">
		<a href="{DETAIL.homeimgfile}" title="{TITLE}" rel="shadowbox"><img alt="{DETAIL.image.alt}" src="{DETAIL.image.src}" width="{DETAIL.image.width}"/></a>
		<p>
			<em>{DETAIL.image.note}</em>
		</p>
	</div>
	<!-- END: imgthumb -->
	<h2>{DETAIL.hometext}</h2>
	<!-- BEGIN: imgfull -->
	<div style="width:{DETAIL.image.width}px;margin:10px auto;">
		<img alt="{DETAIL.image.alt}" src="{DETAIL.image.src}" width="{DETAIL.image.width}"/>
		<p style="text-align: center;">
			<em>{DETAIL.image.note}</em>
		</p>
	</div>
	<!-- END: imgfull -->
</div>
<!-- END: showhometext -->

<div id="bodytext" class="details-content clearfix">
	{DETAIL.bodytext}
</div>
<div class="clear"></div>

<!-- BEGIN: author -->
<div class="aright source">
	<!-- BEGIN: name -->
	<strong>{LANG.author}: </strong>{DETAIL.author}
	<!-- END: name -->
	<!-- BEGIN: source -->
	<br/>
	<strong>{LANG.source}: </strong>{DETAIL.source}
	<!-- END: source -->
</div>
<!-- END: author -->

<!-- BEGIN: copyright -->
<div class="copyright">
	{COPYRIGHT}
</div>
<!-- END: copyright -->

<!-- BEGIN: adminlink -->
<p style="text-align: right;">
	{ADMINLINK}
</p>
<!-- END: adminlink -->
<div class="box clearfix">
	<!-- BEGIN: keywords -->
	<div class="oop fl">
		<div class="header-oop tag">
			{LANG.keywords}:
		</div>
		<p>
			<!-- BEGIN: loop -->
			<a title="{KEYWORD}" href="{LINK_KEYWORDS}">{KEYWORD}</a>{SLASH}
			<!-- END: loop -->
		</p>
	</div>
	<!-- END: keywords -->
	<!-- BEGIN: allowed_rating -->
	<div class="oop fr">
		<div class="header-oop icon-rating">
			{LANG.rating}
		</div>
        <!-- BEGIN: data_rating -->
        <span itemscope itemtype="http://data-vocabulary.org/Review-aggregate">
           {LANG.rating_average}:
           <span itemprop="rating">{DETAIL.numberrating}</span> -
           <span itemprop="votes">{DETAIL.click_rating}</span> {LANG.rating_count}
        </span>
        <!-- END: data_rating -->
		<form id="form3B" action="">
			<div class="clearfix">
				<div id="stringrating" class="small">
					{STRINGRATING}
				</div>
				<div class="star">
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
        </div>
    <!-- END: allowed_rating -->
</div>
<!-- BEGIN: topic -->
<div class="other-news">
	<h4>{LANG.topic}</h4>
	<ul>
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
	<p>
		<a title="{TOPIC.topictitle}" href="{TOPIC.topiclink}">{LANG.more}</a>
	</p>
</div>
<!-- END: topic -->
<!-- BEGIN: related_new -->
<div class="other-news">
	<h4>{LANG.related_new}</h4>
	<ul>
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
</div>
<!-- END: related_new -->
<!-- BEGIN: related -->
<div class="other-news">
	<h4>{LANG.related}</h4>
	<ul>
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
</div>
<!-- END: related -->
<!-- BEGIN: comment -->
	<iframe src="{NV_COMM_URL}" id = "fcomment" onload = "nv_setIframeHeight( this.id )" style="width: 100%; min-height: 300px; max-height: 1000px"></iframe>
<!-- END: comment -->
<!-- BEGIN: commentfacebook -->
    <div class="fb-comments" data-href="{SELFURL}" data-numposts="5" data-width="620"></div>
<!-- END: commentfacebook -->
<!-- END: main -->

<!-- BEGIN: no_permission -->
<div id="no_permission">
	<p>
		{NO_PERMISSION}
	</p>
</div>
<!-- END: no_permission -->