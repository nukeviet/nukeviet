<!-- BEGIN: main -->
<script type="text/javascript" src="{NV_STATIC_URL}{NV_ASSETS_DIR}/js/star-rating/jquery.rating.pack.js"></script>
<script src="{NV_STATIC_URL}{NV_ASSETS_DIR}/js/star-rating/jquery.MetaData.js" type="text/javascript"></script>
<link href="{NV_STATIC_URL}{NV_ASSETS_DIR}/js/star-rating/jquery.rating.css" type="text/css" rel="stylesheet"/>
<link href="{NV_STATIC_URL}{NV_ASSETS_DIR}/js/highlight/github.min.css" rel="stylesheet">
<div class="news_column panel panel-default" itemtype="http://schema.org/NewsArticle" itemscope>
    <div class="panel-body">
        <h1 itemprop="headline">{DETAIL.title}</h1>
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
        <em class="time">{DETAIL.publtime}</em>
        <hr />
        <!-- BEGIN: no_public -->
        <div class="alert alert-warning">
            {LANG.no_public}
        </div>
        <!-- END: no_public -->
        <!-- BEGIN: show_player -->
        <link rel="stylesheet" href="{NV_STATIC_URL}{NV_ASSETS_DIR}/js/plyr/plyr.css" />
        <script src="{NV_STATIC_URL}{NV_ASSETS_DIR}/js/plyr/plyr.polyfilled.js"></script>
        <div class="news-detail-player">
            <div class="player">
                <audio id="newsVoicePlayer" data-voice-id="{DETAIL.current_voice.id}" data-voice-path="{DETAIL.current_voice.path}" data-voice-title="{DETAIL.current_voice.title}" data-autoplay="{DETAIL.autoplay}"></audio>
            </div>
            <div class="source">
                <div class="btn-group">
                    <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-microphone" aria-hidden="true"></i> <span data-news="voiceval" class="val">{DETAIL.current_voice.title}</span> <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <!-- BEGIN: loop -->
                        <li><a href="#" data-news="voicesel" data-id="{VOICE.id}" data-path="{VOICE.path}" data-tokend="{NV_CHECK_SESSION}">{VOICE.title}</a></li>
                        <!-- END: loop -->
                    </ul>
                </div>
            </div>
            <div class="tools">
                <div class="news-switch">
                    <div class="news-switch-label">
                        {LANG.autoplay}:
                    </div>
                    <div data-news="switchapl" class="news-switch-btn{DETAIL.css_autoplay}" role="button" data-busy="false" data-tokend="{NV_CHECK_SESSION}">
                        <span class="news-switch-slider"></span>
                    </div>
                </div>
            </div>
        </div>
        <!-- END: show_player -->
        <!-- BEGIN: showhometext -->
        <div id="hometext">
            <!-- BEGIN: imgthumb -->
            <div class="imghome text-center">
                <a href="#" id="pop" title="{DETAIL.image.alt}">
                    <img id="imageresource" alt="{DETAIL.image.alt}" src="{DETAIL.image.src}" alt="{DETAIL.image.note}" width="{DETAIL.image.width}" class="img-thumbnail"/>
                </a>
                <div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <div class="modal-title h3" id="myModalLabel"><strong>{DETAIL.image.alt}</strong></div>
                            </div>
                            <div class="modal-body">
                                <img src="{DETAIL.homeimgfile}" srcset="{DETAIL.srcset}" id="imagepreview" class="img-thumbnail" >
                            </div>
                        </div>
                    </div>
                </div>
                <em class="show">{DETAIL.image.note}</em>
                <hr />
            </div>
            <!-- END: imgthumb -->
            <div class="h2" itemprop="description">{DETAIL.hometext}</div>
        </div>
        <!-- BEGIN: imgfull -->
        <div style="max-width:{DETAIL.image.width}px;margin: 10px auto 10px auto">
            <img alt="{DETAIL.image.alt}" src="{DETAIL.image.src}" srcset="{DETAIL.srcset}" width="{DETAIL.image.width}" class="img-thumbnail" />
            <p class="imgalt">
                <em>{DETAIL.image.note}</em>
            </p>
        </div>
        <!-- END: imgfull -->
        <!-- END: showhometext -->
        <div class="bodytext">
            {DETAIL.bodyhtml}
        </div>
        <!-- BEGIN: files -->
        <h3 class="newh3"><i class="fa fa-download"></i> <strong>{LANG.files}</strong></h3>
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
                <a href="{FILE.url}" title="{FILE.titledown} {FILE.title}">{FILE.titledown}: <strong>{FILE.title}</strong></a>
                <!-- BEGIN: content_quick_viewfile -->
                <div class="clearfix"></div>
                <div class="collapse" id="file-{FILE.key}" data-src="{FILE.urlfile}" data-toggle="collapsefile" data-loaded="false">
                    <div style="height:10px"></div>
                    <div class="well">
                        <iframe height="600" scrolling="yes" src="" width="100%"></iframe>
                    </div>
                </div>
                <!-- END: content_quick_viewfile -->
                <!-- BEGIN: show_quick_viewimg -->
                <span class="badge">
                    <a href="javascript:void(0)" data-src="{FILE.src}" data-toggle="newsattachimage">
                        <i class="fa fa-eye" data-rel="tooltip" data-content="{LANG.preview}"></i>
                    </a>
                </span>
                <!-- END: show_quick_viewimg -->
            </div>
            <!-- END: loop -->
        </div>
        <!-- END: files -->
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
        <div class="social-share-container margin-bottom-lg">
            <div class="social-share-label"><i class="fa fa-share-alt" aria-hidden="true"></i> {LANG_GLOBAL.share}:</div>
            <div class="social-share-buttons">
                <!-- BEGIN: facebook --><button type="button" class="social-share-facebook" data-toggle="nv-social-share" data-platform="facebook" data-url="{DETAIL.link}" data-title="{DETAIL.title}"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg> Facebook</button><!-- END: facebook -->
                <!-- BEGIN: twitter --><button type="button" class="social-share-twitter" data-toggle="nv-social-share" data-platform="twitter" data-url="{DETAIL.link}" data-title="{DETAIL.title}"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"></path></svg> Tweet</button><!-- END: twitter -->
            </div>
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
                    <em class="fa fa-tags">&nbsp;</em><strong>{LANG.tags}: </strong>
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
        $('#imagemodal').modal('show');
    });
    $(".bodytext img").toggleClass('img-thumbnail');
});
</script>
<script type="text/javascript" src="{NV_STATIC_URL}{NV_ASSETS_DIR}/js/highlight/highlight.min.js"></script>
<script type="text/javascript">hljs.initHighlightingOnLoad();</script>
<!-- END: main -->
<!-- BEGIN: no_permission -->
<div class="alert alert-info">
    {NO_PERMISSION}
</div>
<!-- END: no_permission -->
