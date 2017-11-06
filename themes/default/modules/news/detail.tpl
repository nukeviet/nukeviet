<!-- BEGIN: main -->
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/star-rating/jquery.rating.pack.js"></script>
<script src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/star-rating/jquery.MetaData.js" type="text/javascript"></script>
<link href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/star-rating/jquery.rating.css" type="text/css" rel="stylesheet"/>
<link href="{NV_BASE_SITEURL}{NV_EDITORSDIR}/ckeditor/plugins/codesnippet/lib/highlight/styles/github.css" rel="stylesheet">
<div class="news_column panel panel-default">
	<div class="panel-body">
		<h1 class="title margin-bottom-lg">{DETAIL.title}</h1>
        <div class="row margin-bottom-lg">
            <div class="col-md-12">
                <span class="h5">{DETAIL.publtime}</span>
            </div>
            <div class="col-md-12">
                <ul class="list-inline text-right">
        			<!-- BEGIN: allowed_send -->
        			<li><a class="dimgray" rel="nofollow" title="{LANG.sendmail}" href="javascript:void(0);" onclick="nv_open_browse('{URL_SENDMAIL}','{TITLE}',650,500,'resizable=no,scrollbars=yes,toolbar=no,location=no,status=no');return false"><em class="fa fa-envelope fa-lg">&nbsp;</em></a></li>
        			<!-- END: allowed_send -->
        			<!-- BEGIN: allowed_print -->
        			<li><a class="dimgray" rel="nofollow" title="{LANG.print}" href="javascript: void(0)" onclick="nv_open_browse('{URL_PRINT}','{TITLE}',840,500,'resizable=yes,scrollbars=yes,toolbar=no,location=no,status=no');return false"><em class="fa fa-print fa-lg">&nbsp;</em></a></li>
        			<!-- END: allowed_print -->
        			<!-- BEGIN: allowed_save -->
        			<li><a class="dimgray" rel="nofollow" title="{LANG.savefile}" href="{URL_SAVEFILE}"><em class="fa fa-save fa-lg">&nbsp;</em></a></li>
        			<!-- END: allowed_save -->
        		</ul>
            </div>
        </div>
		<!-- BEGIN: no_public -->
		<div class="alert alert-warning">
			{LANG.no_public}
		</div>
		<!-- END: no_public -->
		<!-- BEGIN: showhometext -->
		<div class="clearfix">
			<!-- BEGIN: imgthumb -->
            <!-- BEGIN: note -->
			<figure class="article left pointer" onclick="modalShowByObj('#imgpreview');">
                <div id="imgpreview" style="width:{DETAIL.image.width}px;">
                    <p class="text-center"><img alt="{DETAIL.image.alt}" src="{DETAIL.homeimgfile}" alt="{DETAIL.image.note}" class="img-thumbnail"/></p>
                    <figcaption>{DETAIL.image.note}</figcaption>
                </div>
            </figure>
            <!-- END: note -->
            <!-- BEGIN: empty -->
            <figure class="article left noncaption pointer" style="width:{DETAIL.image.width}px;" onclick="modalShowByObj(this);">
                    <p class="text-center"><img alt="{DETAIL.image.alt}" src="{DETAIL.homeimgfile}" alt="{DETAIL.image.note}" class="img-thumbnail"/></p>
            </figure>
            <!-- END: empty -->
			<!-- END: imgthumb -->

			 <div class="hometext m-bottom">{DETAIL.hometext}</div>

    		<!-- BEGIN: imgfull -->
    		<figure class="article center">
    			<img alt="{DETAIL.image.alt}" src="{DETAIL.image.src}" width="{DETAIL.image.width}" class="img-thumbnail" />
    			<!-- BEGIN: note --><figcaption>{DETAIL.image.note}</figcaption><!-- END: note -->
    		</figure>
    		<!-- END: imgfull -->
		</div>
		<!-- END: showhometext -->
		<div id="news-bodyhtml" class="bodytext margin-bottom-lg">
			{DETAIL.bodyhtml}
		</div>
		<!-- BEGIN: files -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-download fa-fw"></i><strong>{LANG.files}</strong>
            </div>
    		<div class="list-group news-download-file">
    		    <!-- BEGIN: loop -->
    		    <div class="list-group-item">
    		        <!-- BEGIN: show_quick_viewpdf -->
                    <span class="badge">
                        <a role="button" data-toggle="collapse" href="#pdf{FILE.key}" aria-expanded="false" aria-controls="pdf{FILE.key}">
                            <i class="fa fa-file-pdf-o" data-rel="tooltip" data-content="{LANG.quick_view_pdf}"></i>
                        </a>
                    </span>
                    <!-- END: show_quick_viewpdf -->
    		        <!-- BEGIN: show_quick_viewimg -->
                    <span class="badge">
                        <a href="javascript:void(0)" data-src="{FILE.src}" data-toggle="newsattachimage">
                            <i class="fa fa-file-image-o" data-rel="tooltip" data-content="{LANG.quick_view_pdf}"></i>
                        </a>
                    </span>
                    <!-- END: show_quick_viewimg -->
    		        <a href="{FILE.url}" title="{FILE.titledown}{FILE.title}">{FILE.titledown}: <strong>{FILE.title}</strong></a>
    		        <!-- BEGIN: content_quick_viewpdf -->
    		        <div class="clearfix"></div>
    		        <div class="collapse" id="pdf{FILE.key}" data-src="{FILE.urlpdf}" data-toggle="collapsepdf">
    		            <div class="well margin-top">
    		                <iframe frameborder="0" height="600" scrolling="yes" src="" width="100%"></iframe>
    		            </div>
    		        </div>
    		        <!-- END: content_quick_viewpdf -->
    		        <!-- BEGIN: content_quick_viewdoc -->
    		        <div class="clearfix"></div>
    		        <div class="collapse" id="pdf{FILE.key}" data-src="{FILE.urldoc}" data-toggle="collapsepdf">
    		            <div class="well margin-top">
    		                <iframe frameborder="0" height="600" scrolling="yes" src="" width="100%"></iframe>
    		            </div>
    		        </div>
    		        <!-- END: content_quick_viewdoc -->
    		    </div>
    		    <!-- END: loop -->
    		</div>
        </div>
		<!-- END: files -->
		<!-- BEGIN: author -->
        <div class="margin-bottom-lg">
    		<!-- BEGIN: name -->
    		<p class="h5 text-right">
    			<strong>{LANG.author}: </strong>{DETAIL.author}
    		</p>
    		<!-- END: name -->
    		<!-- BEGIN: source -->
    		<p class="h5 text-right">
    			<strong>{LANG.source}: </strong>{DETAIL.source}
    		</p>
    		<!-- END: source -->
        </div>
		<!-- END: author -->
		<!-- BEGIN: copyright -->
		<div class="alert alert-info margin-bottom-lg">
			{COPYRIGHT}
		</div>
		<!-- END: copyright -->
    </div>
</div>

<!-- BEGIN: keywords -->
<div class="news_column panel panel-default">
	<div class="panel-body">
        <div class="h5">
            <em class="fa fa-tags">&nbsp;</em><strong>{LANG.keywords}: </strong><!-- BEGIN: loop --><a title="{KEYWORD}" href="{LINK_KEYWORDS}"><em>{KEYWORD}</em></a>{SLASH}<!-- END: loop -->
        </div>
    </div>
</div>
<!-- END: keywords -->

<!-- BEGIN: adminlink -->
<p class="text-center margin-bottom-lg">
    {ADMINLINK}
</p>
<!-- END: adminlink -->

<!-- BEGIN: allowed_rating -->
<div class="news_column panel panel-default">
	<div class="panel-body">
        <form id="form3B" action="">
            <div class="h5 clearfix">
                <p id="stringrating">{STRINGRATING}</p>
                <!-- BEGIN: data_rating -->
                <span itemscope itemtype="http://data-vocabulary.org/Review-aggregate">{LANG.rating_average}:
                    <span itemprop="rating" id="numberrating">{DETAIL.numberrating}</span> -
                    <span itemprop="votes" id="click_rating">{DETAIL.click_rating}</span> {LANG.rating_count}
                </span>
                <!-- END: data_rating -->
                <div style="padding: 5px;">
                    <input class="hover-star" type="radio" value="1" title="{LANGSTAR.verypoor}" /><input class="hover-star" type="radio" value="2" title="{LANGSTAR.poor}" /><input class="hover-star" type="radio" value="3" title="{LANGSTAR.ok}" /><input class="hover-star" type="radio" value="4" title="{LANGSTAR.good}" /><input class="hover-star" type="radio" value="5" title="{LANGSTAR.verygood}" /><span id="hover-test" style="margin: 0 0 0 20px;">{LANGSTAR.note}</span>
                </div>
            </div>
        </form>
        <script type="text/javascript">
        $(function() {
            var sr = 0;
            $(".hover-star").rating({
            	focus: function(b, c) {
            		var a = $("#hover-test");
            		2 != sr && (a[0].data = a[0].data || a.html(), a.html(c.title || "value: " + b), sr = 1)
            	},
            	blur: function(b, c) {
            		var a = $("#hover-test");
            		2 != sr && ($("#hover-test").html(a[0].data || ""), sr = 1)
            	},
            	callback: function(b, c) {
            		1 == sr && (sr = 2, $(".hover-star").rating("disable"), sendrating("{NEWSID}", b, "{NEWSCHECKSS}"))
            	}
            });
            $(".hover-star").rating("select", "{NUMBERRATING}");
            <!-- BEGIN: disablerating -->
            $(".hover-star").rating('disable');
            sr = 2;
            <!-- END: disablerating -->
        })
        </script>
    </div>
</div>
<!-- END: allowed_rating -->

<!-- BEGIN: socialbutton -->
<div class="news_column panel panel-default">
	<div class="panel-body">
        <div class="socialicon clearfix margin-bottom-lg">
        	<div class="fb-like" data-href="{SELFURL}" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true">&nbsp;</div>
	        <div class="g-plusone" data-size="medium"></div>
	        <a href="http://twitter.com/share" class="twitter-share-button">Tweet</a>
	    </div>
     </div>
</div>
<!-- END: socialbutton -->

<!-- BEGIN: comment -->
<div class="news_column panel panel-default">
	<div class="panel-body">
	{CONTENT_COMMENT}
    </div>
</div>
<!-- END: comment -->

<!-- BEGIN: others -->
<div class="news_column panel panel-default">
	<div class="panel-body other-news">
    	<!-- BEGIN: topic -->
        <div class="clearfix">
        	<p class="h3"><strong>{LANG.topic}</strong></p>
            <div class="clearfix">
            	<ul class="related">
            		<!-- BEGIN: loop -->
            		<li>
            			<em class="fa fa-angle-right">&nbsp;</em>
            			<a href="{TOPIC.link}" {TOPIC.target_blank} <!-- BEGIN: tooltip -->data-placement="{TOOLTIP_POSITION}" data-content="{TOPIC.hometext_clean}" data-img="{TOPIC.imghome}" data-rel="tooltip"<!-- END: tooltip --> title="{TOPIC.title}"><h4>{TOPIC.title}</h4></a>
            			<em>({TOPIC.time})</em>
            			<!-- BEGIN: newday -->
            			<span class="icon_new">&nbsp;</span>
            			<!-- END: newday -->
            		</li>
            		<!-- END: loop -->
            	</ul>
            </div>
        	<p class="text-right">
        		<a title="{TOPIC.topictitle}" href="{TOPIC.topiclink}">{LANG.more}</a>
        	</p>
        </div>
    	<!-- END: topic -->

    	<!-- BEGIN: related_new -->
    	<p class="h3"><strong>{LANG.related_new}</strong></p>
    	<div class="clearfix">
            <ul class="related list-inline">
        		<!-- BEGIN: loop -->
        		<li>
        			<em class="fa fa-angle-right">&nbsp;</em>
        			<a href="{RELATED_NEW.link}" {RELATED_NEW.target_blank} <!-- BEGIN: tooltip -->data-placement="{TOOLTIP_POSITION}" data-content="{RELATED_NEW.hometext_clean}" data-img="{RELATED_NEW.imghome}" data-rel="tooltip"<!-- END: tooltip --> title="{RELATED_NEW.title}"><h4>{RELATED_NEW.title}</h4></a>
        			<em>({RELATED_NEW.time})</em>
        			<!-- BEGIN: newday -->
        			<span class="icon_new">&nbsp;</span>
        			<!-- END: newday -->
        		</li>
        		<!-- END: loop -->
        	</ul>
        </div>
    	<!-- END: related_new -->

    	<!-- BEGIN: related -->
    	<p class="h3"><strong>{LANG.related}</strong></p>
    	<div class="clearfix">
            <ul class="related list-inline">
        		<!-- BEGIN: loop -->
        		<li>
        			<em class="fa fa-angle-right">&nbsp;</em>
        			<a href="{RELATED.link}" {RELATED.target_blank} <!-- BEGIN: tooltip --> data-placement="{TOOLTIP_POSITION}" data-content="{RELATED.hometext_clean}" data-img="{RELATED.imghome}" data-rel="tooltip"<!-- END: tooltip --> title="{RELATED.title}"><h4>{RELATED.title}</h4></a>
        			<em>({RELATED.time})</em>
        			<!-- BEGIN: newday -->
        			<span class="icon_new">&nbsp;</span>
        			<!-- END: newday -->
        		</li>
        		<!-- END: loop -->
        	</ul>
        </div>
    	<!-- END: related -->
    </div>
</div>
<!-- END: others -->

<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_EDITORSDIR}/ckeditor/plugins/codesnippet/lib/highlight/highlight.pack.js"></script>
<script type="text/javascript">hljs.initHighlightingOnLoad();</script>
<!-- END: main -->
<!-- BEGIN: no_permission -->
<div class="alert alert-info">
	{NO_PERMISSION}
</div>
<!-- END: no_permission -->