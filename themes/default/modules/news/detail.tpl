<!-- BEGIN: main -->
<script type="text/javascript" src="{NV_STATIC_URL}{NV_ASSETS_DIR}/js/star-rating/jquery.rating.pack.js"></script>
<script src="{NV_STATIC_URL}{NV_ASSETS_DIR}/js/star-rating/jquery.MetaData.js" type="text/javascript"></script>
<link href="{NV_STATIC_URL}{NV_ASSETS_DIR}/js/star-rating/jquery.rating.css" type="text/css" rel="stylesheet"/>
<link href="{NV_STATIC_URL}{NV_EDITORSDIR}/ckeditor/plugins/codesnippet/lib/highlight/styles/github.css" rel="stylesheet">
<div class="news_column panel panel-default" itemtype="http://schema.org/NewsArticle" itemscope>
    <div class="panel-body">
        <h1 class="title margin-bottom-lg" itemprop="headline">{DETAIL.title}</h1>
        <div class="hidden hide d-none" itemprop="author" itemtype="http://schema.org/Person" itemscope>
            <span itemprop="name">{SCHEMA_AUTHOR}</span>
        </div>
        <span class="hidden hide d-none" itemprop="datePublished">{SCHEMA_DATEPUBLISHED}</span>
        <span class="hidden hide d-none" itemprop="dateModified">{SCHEMA_DATEPUBLISHED}</span>
        <span class="hidden hide d-none" itemprop="mainEntityOfPage">{SCHEMA_URL}</span>
        <span class="hidden hide d-none" itemprop="image">{SCHEMA_IMAGE}</span>
        <div class="hidden hide d-none" itemprop="publisher" itemtype="http://schema.org/Organization" itemscope>
            <span itemprop="name">{SCHEMA_ORGNAME}</span>
            <span itemprop="logo" itemtype="http://schema.org/ImageObject" itemscope>
                <span itemprop="url">{SCHEMA_ORGLOGO}</span>
            </span>
        </div>
        <div class="row margin-bottom-lg">
            <div class="col-md-12">
                <span class="h5">{DETAIL.publtime}</span>
            </div>
            <div class="col-md-12">
                <ul class="list-inline text-right">
                    <!-- BEGIN: allowed_send -->
                    <li><a class="dimgray" title="{LANG.sendmail}" href="javascript:void(0);" onclick="newsSendMailModal('#newsSendMailModal', '{URL_SENDMAIL}', '{CHECKSESSION}');"><em class="fa fa-envelope fa-lg">&nbsp;</em></a></li>
                    <!-- START FORFOOTER -->
<div class="modal fade" id="newsSendMailModal" tabindex="-1" role="dialog" data-loaded="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">{LANG.sendmail}</h4>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>
                    <!-- END FORFOOTER -->
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
                <div style="width:{DETAIL.image.width}px;">
                    <p class="text-center"><img alt="{DETAIL.image.alt}" src="{DETAIL.image.src}" alt="{DETAIL.image.note}" class="img-thumbnail"/></p>
                    <figcaption>{DETAIL.image.note}</figcaption>
                </div>
            </figure>
            <div id="imgpreview" style="display:none">
                <p class="text-center"><img alt="{DETAIL.image.alt}" src="{DETAIL.homeimgfile}" srcset="{DETAIL.srcset}" alt="{DETAIL.image.note}" class="img-thumbnail"/></p>
                <figcaption>{DETAIL.image.note}</figcaption>
            </div>
            <!-- END: note -->
            <!-- BEGIN: empty -->
            <figure class="article left noncaption pointer" style="width:{DETAIL.image.width}px;" onclick="modalShowByObj('#imgpreview');">
                <p class="text-center"><img alt="{DETAIL.image.alt}" src="{DETAIL.image.src}" alt="{DETAIL.image.note}" class="img-thumbnail"/></p>
            </figure>
            <div id="imgpreview" style="display:none">
                <p class="text-center"><img alt="{DETAIL.image.alt}" src="{DETAIL.homeimgfile}" srcset="{DETAIL.srcset}" alt="{DETAIL.image.note}" class="img-thumbnail"/></p>
            </div>
            <!-- END: empty -->
            <!-- END: imgthumb -->

            <div class="hometext m-bottom" itemprop="description">{DETAIL.hometext}</div>

            <!-- BEGIN: imgfull -->
            <figure class="article center">
                <img alt="{DETAIL.image.alt}" src="{DETAIL.image.src}" srcset="{DETAIL.srcset}" width="{DETAIL.image.width}" class="img-thumbnail"/>
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
                <i class="fa fa-download"></i> <strong>{LANG.files}</strong>
            </div>
            <div class="list-group news-download-file">
                <!-- BEGIN: loop -->
                <div class="list-group-item">
                    <!-- BEGIN: show_quick_viewfile -->
                    <span class="badge">
                        <a role="button" data-toggle="collapse" href="#file-{FILE.key}" aria-expanded="false" aria-controls="file-{FILE.key}">
                            <i class="fa fa-eye" data-rel="tooltip" data-content="{LANG.preview}"></i>
                        </a>
                    </span>
                    <!-- END: show_quick_viewfile -->
                    <!-- BEGIN: show_quick_viewimg -->
                    <span class="badge">
                        <a href="javascript:void(0)" data-src="{FILE.src}" data-toggle="newsattachimage">
                            <i class="fa fa-eye" data-rel="tooltip" data-content="{LANG.preview}"></i>
                        </a>
                    </span>
                    <!-- END: show_quick_viewimg -->
                    <a href="{FILE.url}" title="{FILE.titledown} {FILE.title}" download>{FILE.titledown}: <strong>{FILE.title}</strong></a>
                    <!-- BEGIN: content_quick_viewfile -->
                    <div class="clearfix"></div>
                    <div class="collapse" id="file-{FILE.key}" data-src="{FILE.urlfile}" data-toggle="collapsefile" data-loaded="false">
                        <div class="well margin-top">
                            <iframe height="600" scrolling="yes" src="" width="100%"></iframe>
                        </div>
                    </div>
                    <!-- END: content_quick_viewfile -->
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
            <em class="fa fa-tags">&nbsp;</em><strong>{LANG.tags}: </strong><!-- BEGIN: loop --><a title="{KEYWORD}" href="{LINK_KEYWORDS}"><em>{KEYWORD}</em></a>{SLASH}<!-- END: loop -->
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
                <span itemscope itemtype="https://schema.org/AggregateRating">
                    <span class="hidden d-none hide" itemprop="itemReviewed" itemscope itemtype="https://schema.org/CreativeWorkSeries">
                        <span class="hidden d-none hide" itemprop="name">{DETAIL.title}</span>
                    </span>
                    {LANG.rating_average}:
                    <span id="numberrating" itemprop="ratingValue">{DETAIL.numberrating}</span> -
                    <span id="click_rating" itemprop="ratingCount">{DETAIL.click_rating}</span> {LANG.rating_count}
                    <span class="hidden d-none hide" itemprop="bestRating">5</span>
                </span>
                <!-- END: data_rating -->
                <div style="padding: 5px;">
                    <!-- BEGIN: star --><input class="hover-star required" type="radio" value="{STAR.val}" title="{STAR.title}"{STAR.checked}/><!-- END: star -->
                    <span id="hover-test" style="margin: 0 0 0 20px;">{LANG.star_note}</span>
                </div>
            </div>
        </form>
        <script type="text/javascript">
        $(function() {
            var isDisable = false;
            $('.hover-star').rating({
                focus : function(value, link) {
                    var tip = $('#hover-test');
                    if (!isDisable) {
                        tip[0].data = tip[0].data || tip.html();
                        tip.html(link.title || 'value: ' + value)
                    }
                },
                blur : function(value, link) {
                    var tip = $('#hover-test');
                    if (!isDisable) {
                        $('#hover-test').html(tip[0].data || '')
                    }
                },
                callback : function(value, link) {
                    if (!isDisable) {
                        isDisable = true;
                        $('.hover-star').rating('disable');
                        sendrating('{NEWSID}', value, '{NEWSCHECKSS}');
                    }
                }
            });
            <!-- BEGIN: disablerating -->
            $(".hover-star").rating('disable');
            isDisable = true;
            <!-- END: disablerating -->
        })
        </script>
    </div>
</div>
<!-- END: allowed_rating -->

<!-- BEGIN: socialbutton -->
<div class="news_column panel panel-default">
    <div class="panel-body" style="margin-bottom:0">
        <div style="display:flex;align-items:flex-start;">
            <!-- BEGIN: facebook --><div class="margin-right"><div class="fb-like" style="float:left!important;margin-right:0!important" data-href="{DETAIL.link}" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true"></div></div><!-- END: facebook -->
            <!-- BEGIN: twitter --><div class="margin-right"><a href="http://twitter.com/share" class="twitter-share-button">Tweet</a></div><!-- END: twitter -->
            <!-- BEGIN: zalo --><div><div class="zalo-share-button" data-href="" data-oaid="{ZALO_OAID}" data-layout="1" data-color="blue" data-customize=false></div></div><!-- END: zalo -->
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
                <ul class="detail-related related list-none list-items">
                    <!-- BEGIN: loop -->
                    <li>
                        <em class="fa fa-angle-right">&nbsp;</em>
                        <h4><a href="{TOPIC.link}" {TOPIC.target_blank} <!-- BEGIN: tooltip -->data-placement="{TOOLTIP_POSITION}" data-content="{TOPIC.hometext_clean}" data-img="{TOPIC.imghome}" data-rel="tooltip"<!-- END: tooltip --> title="{TOPIC.title}">{TOPIC.title}</a></h4>
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
            <ul class="detail-related related list-none list-items">
                <!-- BEGIN: loop -->
                <li>
                    <em class="fa fa-angle-right">&nbsp;</em>
                    <h4><a href="{RELATED_NEW.link}" {RELATED_NEW.target_blank} <!-- BEGIN: tooltip -->data-placement="{TOOLTIP_POSITION}" data-content="{RELATED_NEW.hometext_clean}" data-img="{RELATED_NEW.imghome}" data-rel="tooltip"<!-- END: tooltip --> title="{RELATED_NEW.title}">{RELATED_NEW.title}</a></h4>
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
            <ul class="detail-related related list-none list-items">
                <!-- BEGIN: loop -->
                <li>
                    <em class="fa fa-angle-right">&nbsp;</em>
                    <h4><a href="{RELATED.link}" {RELATED.target_blank} <!-- BEGIN: tooltip --> data-placement="{TOOLTIP_POSITION}" data-content="{RELATED.hometext_clean}" data-img="{RELATED.imghome}" data-rel="tooltip"<!-- END: tooltip --> title="{RELATED.title}">{RELATED.title}</a></h4>
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
