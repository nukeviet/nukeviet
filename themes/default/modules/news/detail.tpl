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
<div class="news_column panel panel-default">
	<div class="panel-body">
		<h2>{DETAIL.title}</h2>
		<em class="pull-left time">{DETAIL.publtime}</em>
		<ul class="list-inline pull-right">
			<!-- BEGIN: allowed_send -->
			<li><a rel="nofollow" title="{LANG.sendmail}" href="javascript:void(0);" onclick="nv_open_browse('{URL_SENDMAIL}','{TITLE}',650,500,'resizable=no,scrollbars=yes,toolbar=no,location=no,status=no');return false"><em class="fa fa-envelope fa-lg">&nbsp;</em></a></li>
			<!-- END: allowed_send -->
			<!-- BEGIN: allowed_print -->
			<li><a title="{LANG.print}" href="javascript: void(0)" onclick="nv_open_browse('{URL_PRINT}','{TITLE}',840,500,'resizable=yes,scrollbars=yes,toolbar=no,location=no,status=no');return false"><em class="fa fa-print fa-lg">&nbsp;</em></a></li>
			<!-- END: allowed_print -->
			<!-- BEGIN: allowed_save -->
			<li><a title="{LANG.savefile}" href="{URL_SAVEFILE}"><em class="fa fa-save fa-lg">&nbsp;</em></a></li>
			<!-- END: allowed_save -->
		</ul>
		<div class="clear">&nbsp;</div>
        <!-- BEGIN: socialbutton -->
        <div class="socialicon pull-left">
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
	    </div>
        <!-- END: socialbutton -->
		<div class="clear">&nbsp;</div>
		<hr />
		<!-- BEGIN: showhometext -->
		<div id="hometext">
			<!-- BEGIN: imgthumb -->
			<div class="imghome pull-left text-center" style="width:{DETAIL.image.width}px;">
				<a href="{DETAIL.homeimgfile}" title="{DETAIL.image.note}" rel="shadowbox"><img alt="{DETAIL.image.alt}" src="{DETAIL.image.src}" alt="{DETAIL.image.note}" width="{DETAIL.image.width}" class="img-thumbnail" /></a>
				<em>{DETAIL.image.note}</em>
			</div>
			<!-- END: imgthumb -->
			<h2>{DETAIL.hometext}</h2>
		</div>
		<!-- BEGIN: imgfull -->
		<div style="max-width:{DETAIL.image.width}px;margin: 10px auto 10px auto">
			<img alt="{DETAIL.image.alt}" src="{DETAIL.image.src}" width="{DETAIL.image.width}" class="img-thumbnail" />
			<p class="imgalt">
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
		<p class="text-right">
			<strong>{LANG.author}: </strong>{DETAIL.author}
		</p>
		<!-- END: name -->
		<!-- BEGIN: source -->
		<p class="text-right">
			<strong>{LANG.source}: </strong>{DETAIL.source}
		</p>
		<!-- END: source -->
		<!-- END: author -->
		<!-- BEGIN: copyright -->
		<div class="alert alert-info copyright">
			{COPYRIGHT}
		</div>
		<!-- END: copyright -->

		<!-- BEGIN: adminlink -->
		<p class="text-center adminlink">
			{ADMINLINK}
		</p>
		<!-- END: adminlink -->
		<hr />
		<div class="row">
			<div class="col-md-6">
				<!-- BEGIN: keywords -->
				<div class="keywords">
					<em class="fa fa-tags">&nbsp;</em><strong>{LANG.keywords}: </strong>
					<!-- BEGIN: loop -->
					<a title="{KEYWORD}" href="{LINK_KEYWORDS}"><em>{KEYWORD}</em></a>{SLASH}
					<!-- END: loop -->
				</div>
				<!-- END: keywords -->
			</div>
			<div class="col-md-6">
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
			</div>
		</div>

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
			<em class="fa fa-angle-right">&nbsp;</em>
			<a href="{TOPIC.link}" data-content="{TOPIC.hometext}" data-img="{TOPIC.imghome}" rel="tooltip">{TOPIC.title}</a>
			<em>({TOPIC.time})</em>
			<!-- BEGIN: newday -->
			<span class="icon_new">&nbsp;</span>
			<!-- END: newday -->
		</li>
		<!-- END: loop -->
	</ul>
	<div class="clear">&nbsp;</div>
	<p class="text-right">
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
			<em class="fa fa-angle-right">&nbsp;</em>
			<a href="{RELATED_NEW.link}" data-content="{RELATED_NEW.hometext}" data-img="{RELATED_NEW.imghome}" rel="tooltip">{RELATED_NEW.title}</a>
			<em>({RELATED_NEW.time})</em>
			<!-- BEGIN: newday -->
			<span class="icon_new">&nbsp;</span>
			<!-- END: newday -->
		</li>
		<!-- END: loop -->
	</ul>
	<!-- END: related_new -->
	<!-- BEGIN: related -->
	<div class="clear">&nbsp;</div>
	<p>
		<strong>{LANG.related}</strong>
	</p>
	<ul class="related">
		<!-- BEGIN: loop -->
		<li>
			<em class="fa fa-angle-right">&nbsp;</em>
			<a class="list-inline" href="{RELATED.link}" data-content="{RELATED.hometext}" data-img="{RELATED.imghome}" data-rel="tooltip">{RELATED.title}</a>
			<em>({RELATED.time})</em>
			<!-- BEGIN: newday -->
			<span class="icon_new">&nbsp;</span>
			<!-- END: newday -->
		</li>
		<!-- END: loop -->
	</ul>
	<!-- END: related -->
</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$(".bodytext img").toggleClass('img-thumbnail');
		<!-- BEGIN: tooltip -->
		$("[data-rel='tooltip']").tooltip({
			placement: "{TOOLTIP_POSITION}",
			html: true,
			title: function(){return '<img class="img-thumbnail pull-left margin_image" src="' + $(this).data('img') + '" width="90" /><p class="text-justify">' + $(this).data('content') + '</p><div class="clearfix"></div>';}
		});
		<!-- END: tooltip -->
	});
</script>
<!-- END: main -->
<!-- BEGIN: no_permission -->
<div class="alert alert-info">
	{NO_PERMISSION}
</div>
<!-- END: no_permission -->