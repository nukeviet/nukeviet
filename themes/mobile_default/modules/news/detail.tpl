<!-- BEGIN: main -->
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/star-rating/jquery.rating.pack.js"></script>
<script src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/star-rating/jquery.MetaData.js" type="text/javascript"></script>
<link href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/star-rating/jquery.rating.css" type="text/css" rel="stylesheet"/>
<link href="{NV_BASE_SITEURL}{NV_EDITORSDIR}/ckeditor/plugins/codesnippet/lib/highlight/styles/github.css" rel="stylesheet">
<div class="news_column panel panel-default">
	<div class="panel-body">
		<h1>{DETAIL.title}</h1>
		<em class="time">{DETAIL.publtime}</em>
		<hr />
		<!-- BEGIN: no_public -->
		<div class="alert alert-warning">
			{LANG.no_public}
		</div>
		<!-- END: no_public -->
		<!-- BEGIN: showhometext -->
		<div id="hometext">
			<!-- BEGIN: imgthumb -->
			<div class="imghome text-center">
				<a href="#" id="pop" title="{DETAIL.image.alt}">
				    <img id="imageresource" alt="{DETAIL.image.alt}" src="{DETAIL.homeimgfile}" alt="{DETAIL.image.note}" width="{DETAIL.image.width}" class="img-thumbnail" >
                </a>
                <div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
								<h4 class="modal-title" id="myModalLabel">{DETAIL.image.alt}</h4>
							</div>
							<div class="modal-body">
								<img src="" id="imagepreview" class="img-thumbnail" >
							</div>
						</div>
					</div>
				</div>
				<em class="show">{DETAIL.image.note}</em>
				<hr />
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

		<hr />
        <!-- BEGIN: socialbutton -->
        <div class="socialicon pull-left">
			<div class="fb-like" data-href="{SELFURL}" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true">&nbsp;</div>
	        <div class="g-plusone" data-size="medium"></div>
	        <a href="http://twitter.com/share" class="twitter-share-button">Tweet</a>
	    </div>
        <!-- END: socialbutton -->
        <!-- BEGIN: adminlink -->
		<p class="text-right adminlink">
			{ADMINLINK}
		</p>
		<!-- END: adminlink -->
		<div class="clear">&nbsp;</div>
		<div class="row">
			<div class="col-md-12">
				<!-- BEGIN: keywords -->
				<div class="keywords">
					<em class="fa fa-tags">&nbsp;</em><strong>{LANG.keywords}: </strong>
					<!-- BEGIN: loop -->
					<a title="{KEYWORD}" href="{LINK_KEYWORDS}"><em>{KEYWORD}</em></a>{SLASH}
					<!-- END: loop -->
				</div>
				<!-- END: keywords -->
			</div>
			<div class="col-md-12">
			<!-- BEGIN: allowed_rating -->
			<br />
				<form id="form3B" action="">
					<div class="clearfix">
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
	{CONTENT_COMMENT}
	<!-- END: comment -->

	<!-- BEGIN: topic -->
	<p>
		<strong>{LANG.topic}</strong>
	</p>
	<ul class="related">
		<!-- BEGIN: loop -->
		<li>
			<em class="fa fa-angle-right">&nbsp;</em>
			<a href="{TOPIC.link}" title="{TOPIC.title}">{TOPIC.title}</a>
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
			<a href="{RELATED_NEW.link}" title="{RELATED_NEW.title}">{RELATED_NEW.title}</a>
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
			<a class="list-inline" href="{RELATED.link}" title="{RELATED.title}">{RELATED.title}</a>
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
    $("#pop").on("click", function() {
       $('#imagepreview').attr('src', $('#imageresource').attr('src'));
       $('#imagemodal').modal('show');
    });
	$(".bodytext img").toggleClass('img-thumbnail');
});
</script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_EDITORSDIR}/ckeditor/plugins/codesnippet/lib/highlight/highlight.pack.js"></script>
<script type="text/javascript">hljs.initHighlightingOnLoad();</script>
<!-- END: main -->
<!-- BEGIN: no_permission -->
<div class="alert alert-info">
	{NO_PERMISSION}
</div>
<!-- END: no_permission -->
