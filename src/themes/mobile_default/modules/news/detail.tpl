<!-- BEGIN: main -->
<link href="{ASSETS_STATIC_URL}/js/highlight/github.min.css" rel="stylesheet">
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
            <div class="h2">{DETAIL.hometext}</div>
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
        <!-- BEGIN: related_top -->
        {RELATED_HTML}
        <!-- END: related_top -->
        <!-- BEGIN: navigation -->
        <script type="text/javascript" src="{ASSETS_STATIC_URL}/js/clipboard/clipboard.min.js"></script>
        <div id="navigation" class="navigation-cont auto_nav{DETAIL.auto_nav}" data-copied="{LANG.link_copied}">
            <div class="navigation-head">
                <em class="fa fa-list-ol"></em> {LANG.table_of_contents}
            </div>
            <div class="navigation-body">
                <ol class="navigation">
                    <!-- BEGIN: navigation_item -->
                    <li>
                        <a href="#" data-scroll-to="{NAVIGATION.1}" data-location="{NAVIGATION.2}">{NAVIGATION.0}</a>
                        <!-- BEGIN: sub_navigation -->
                        <ol class="sub-navigation">
                            <!-- BEGIN: sub_navigation_item -->
                            <li>
                                <a href="#" data-scroll-to="{SUBNAVIGATION.1}" data-location="{SUBNAVIGATION.2}">{SUBNAVIGATION.0}</a>
                            </li>
                            <!-- END: sub_navigation_item -->
                        </ol>
                        <!-- END: sub_navigation -->
                    </li>
                    <!-- END: navigation_item -->
                </ol>
            </div>
        </div>
        <!-- END: navigation -->
        <div class="bodytext">
            {DETAIL.bodyhtml}
        </div>
        <!-- BEGIN: related_bottom -->
        {RELATED_HTML}
        <!-- END: related_bottom -->
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
                    <a href="#" data-src="{FILE.src}" data-toggle="newsattachimage">
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
                <!-- BEGIN: twitter --><button type="button" class="social-share-x" data-toggle="nv-social-share" data-platform="x" data-url="{DETAIL.link}" data-title="{DETAIL.title}"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18.901 1.153h3.68l-8.04 9.19L24 22.846h-7.406l-5.8-7.584-6.638 7.584H.474l8.6-9.83L0 1.154h7.594l5.243 6.932ZM17.61 20.644h2.039L6.486 3.24H4.298Z"></path></svg> X</button><!-- END: twitter -->
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
            <div class="col-md-12 margin-bottom">
                <!-- BEGIN: keywords -->
                <div class="keywords">
                    <em class="fa fa-tags">&nbsp;</em><strong>{LANG.tags}: </strong>
                    <!-- BEGIN: loop -->
                    <a title="{KEYWORD}" href="{LINK_KEYWORDS}"><em>{KEYWORD}</em></a>{SLASH}
                    <!-- END: loop -->
                </div>
                <!-- END: keywords -->
            </div>
            <div class="col-md-12 margin-bottom">
                <!-- BEGIN: allowed_rating -->
                <form id="form3B" action="" data-toggle="rating" data-id="{NEWSID}" data-checkss="{NEWSCHECKSS}" data-checked="{DETAIL.numberrating_star}">
                    <div class="margin-bottom">
                        <section class="rating<!-- BEGIN: disablerating --> disabled<!-- END: disablerating -->">
                            <input type="radio" id="rat_5" name="rate" value="5"/>
                            <label for="rat_5" data-title="{LANG.star_verygood}"></label>
                            <input type="radio" id="rat_4" name="rate" value="4"/>
                            <label for="rat_4" data-title="{LANG.star_good}"></label>
                            <input type="radio" id="rat_3" name="rate" value="3"/>
                            <label for="rat_3" data-title="{LANG.star_ok}"></label>
                            <input type="radio" id="rat_2" name="rate" value="2"/>
                            <label for="rat_2" data-title="{LANG.star_poor}"></label>
                            <input type="radio" id="rat_1" name="rate" value="1"/>
                            <label for="rat_1" data-title="{LANG.star_verypoor}"></label>
                        </section>
                        <span class="feedback small" data-default="{RATINGFEEDBACK}" data-success="{LANG.rating_success}">{RATINGFEEDBACK}</span>
                    </div>
                    <div class="ratingInfo margin-top hidden">
                        <div id="stringrating">{STRINGRATING}</div>
                        <!-- BEGIN: data_rating -->
                        <div>
                            {LANG.rating_average}: <span id="numberrating">{DETAIL.numberrating}</span> / <span id="click_rating">{DETAIL.click_rating}</span> {LANG.rating_count}
                        </div>
                        <!-- END: data_rating -->
                    </div>
                </form>
                <!-- END: allowed_rating -->
            </div>
        </div>
        <div class="clear">&nbsp;</div>

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
<script type="text/javascript" src="{ASSETS_STATIC_URL}/js/highlight/highlight.min.js"></script>
<script type="text/javascript">hljs.initHighlightingOnLoad();</script>
<!-- END: main -->

<!-- BEGIN: related_articles -->
<div class="margin-bottom-lg">
    <div class="h3 text-bold">{LANG.related_sarticles}:</div>
    <ul class="inline-related-articles">
        <!-- BEGIN: loop -->
        <li>
            <a href="{ARTICLE.link}"{ARTICLE.target_blank} title="{ARTICLE.title}">{ARTICLE.title}</a>
            <!-- BEGIN: newday -->
            <span class="icon_new">&nbsp;</span>
            <!-- END: newday -->
        </li>
        <!-- END: loop -->
    </ul>
</div>
<!-- END: related_articles -->
