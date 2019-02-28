<!-- BEGIN: main -->
<script type="text/javascript" src="{NV_BASE_SITEURL}themes/{TEMPLATE}/js/{MODULE_THEME}_jquery.autoresize.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}themes/{TEMPLATE}/js/{MODULE_THEME}_jquery.matchHeight-min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}themes/{TEMPLATE}/images/{MODULE_THEME}/jwplayer/jwplayer.js"></script>
<script type="text/javascript">jwplayer.key="KzcW0VrDegOG/Vl8Wb9X3JLUql+72MdP1coaag==";</script>
<div class="detailContent clearfix">
    <h1>{DETAILCONTENT.title}</h1>
    <div class="message" id="mesHide"></div>
    <!-- BEGIN: video_flash -->
    <div class="videoTitle" id="videoTitle">{DETAILCONTENT.title}</div>
    <div class="videoplayer">
        <div class="cont">
            <div id="videoCont"></div>
        </div>
    </div>
    <div class="clearfix"></div>
    <script type="text/javascript">
    $(function() {
        var ele = "videoCont";
        var a = $("#" + ele).outerWidth(), b;
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
            autostart: {MODULECONFIG.playerAutostart} ? true : false
        });
        $("html,body").animate({
            scrollTop : $(".detailContent").offset().top
        }, 500);
    });
    </script>
    <!-- END: video_flash -->
    <!-- BEGIN: video_youtube -->
    <script type="text/javascript">
    $(function() {
        var a = $("div.detailContent").width(), b;
        640 < a && (a = 640);
        b = a;
        a = Math.ceil(45 * a / 80) + 4;
        $("div.detailContent").append('<div class="videoplayer"><div class="clearfix" style="height: ' + a + 'px;width:' + b + 'px;margin:0 auto;"><iframe class="detailContent clearfix" allowfullscreen="" frameborder="0" style="height: ' + a + 'px;width:' + b + 'px" src="//www.youtube.com/embed/{CODE}?rel=0&amp;controls=1{autoplay}"></iframe></div></div>');
    });
    </script>
    <!-- END: video_youtube -->
</div>
<div id="otherClipsAj">
    <div class="videoInfo marginbottom15 clearfix">
        <div class="cont">
            <div class="cont2">
                <!-- BEGIN: liketool1 -->
                <div class="fl">
                    <div class="shareFeelings">{LANG.shareFeelings}</div>
                    <a class="likeButton" href="{DETAILCONTENT.url}"><img class="like" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/images/pix.gif" alt="" width="15" height="14" /><span>{LANG.like}</span></a> <a class="likeButton" href="{DETAILCONTENT.url}"><img class="unlike" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/images/pix.gif" alt="" width="15" height="14" /><span>{LANG.unlike}</span></a> <a class="likeButton" href="{DETAILCONTENT.url}"><img class="broken" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/images/pix.gif" alt="" width="15" height="14" /><span>{LANG.broken}</span></a>
                </div>
                <style>
.videoInfo .cont .cont2 {
    height: 56px
}
</style>
                <script>
                function addLikeImage() {
                    var b = intval($("#ilike").text()), c = intval($("#iunlike").text()), d = $(".image").width();
                    if (0 == b && 0 == c)
                        $(".image").removeClass("imageunlike").addClass("image0"), $("#imglike").removeClass("like").width(1), $("#plike,#punlike").text("");
                    else if ($(".image").removeClass("image0").addClass("imageunlike"), 0 == b)
                        $("#imglike").removeClass("like").width(1), $("#plike").text("0%"), $("#punlike").text("100%");
                    else {
                        var a = intval(100 * b / (b + c)), b = intval(a * (d / 100)), e = 100 - a;
                        $("#imglike").addClass("like").animate({
                            width : b
                        }, 1500, function() {
                            $("#plike").text(a + "%");
                            $("#punlike").text(e + "%")
                        })
                    }
                }$(function() {
                    addLikeImage()
                });
                $("a.likeButton").click(function(){
                    var b = $(this).attr("href"), c = $("img", this).attr("class"), d = $(this).offset();
                    $.ajax({
                        type: "POST",
                        url: b,
                        data: "aj=" + c,
                        success: function(a) {
                            if ("access forbidden" == a)
                                return alert("{LANG.accessForbidden}"), !1;
                            var a = a.split("_"), b = "liked" == a[0] || "unlike" == a[0] ? "{LANG.thank}" : "{LANG.thankBroken}";
                            $("#i" + a[0]).text(a[1]);
                            addLikeImage();
                            $("#mesHide").text(b).css({
                                "z-index" : 1E4
                            }).show("slow");
                            setTimeout(function() {
                                $("div#mesHide").css({
                                    "z-index" : "-1"
                                }).hide("slow")
                            }, 3E3)
                        }
                    });

                    return !1
                });
                </script>
                <!-- END: liketool1 -->
                <div class="fr">
                    <div class="viewcount">
                        <!-- BEGIN: isAdmin -->
                        <a href="{DETAILCONTENT.editUrl}">{LANG.edit}</a>,&nbsp;
                        <!-- END: isAdmin -->
                        {LANG.viewHits}: <span>{DETAILCONTENT.view}</span>
                    </div>
                    <!-- BEGIN: liketool -->
                    <div style="float: right;">
                        <div class="image image0">
                            <img id="imglike" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/images/pix.gif" alt="" width="1" />
                        </div>
                        <div class="likeDetail">
                            <div class="likeLeft">
                                {LANG.like}: <span class="strong" id="ilike">{DETAILCONTENT.liked}</span> <br /> <span id="plike"></span>
                            </div>
                            <div class="likeRight">
                                {LANG.unlike}: <span class="strong" id="iunlike">{DETAILCONTENT.unlike}</span> <br /> <span id="punlike"></span>
                            </div>
                        </div>
                    </div>
                    <!-- END: liketool -->
                </div>
            </div>
            <div class="socialicon pull-left">
                <div id="fb-root"></div>
                <script>(function(d, s, id) {
              var js, fjs = d.getElementsByTagName(s)[0];
              if (d.getElementById(id)) return;
              js = d.createElement(s); js.id = id;
              js.src = "//connect.facebook.net/{FACEBOOK_LANG}/sdk.js#xfbml=1&version=v2.5";
              fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));</script>
                <div class="fb-share-button" data-href="{SELFURL}" data-layout="button_count">&nbsp;</div>
                <div class="g-plus" data-action="share" data-size="medium" data-annotation="bubble"></div>
                <script type="text/javascript">
              window.___gcfg = {lang: nv_lang_data};
              (function() {
                var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
                po.src = '//apis.google.com/js/plusone.js';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
              })();
            </script>
                <a href="//twitter.com/share" class="twitter-share-button">Tweet</a>
                <script type="text/javascript" src="//platform.twitter.com/widgets.js"></script>
            </div>
            <div class="clearfix"></div>
            <div class="hometext">
                {DETAILCONTENT.hometext}
                <!-- BEGIN: bodytext -->
                <div class="bodytext hide">{DETAILCONTENT.bodytext}</div>
                <div class="bodybutton">
                    <a href="open" class="bodybutton">{LANG.moreContent}</a>
                </div>
                <!-- END: bodytext -->
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $("a.bodybutton").click(function() {
            "open" == $(this).attr("href") ? ($(".bodytext").removeClass('hide').slideDown("slow"), $(this).attr("href", "close").text("{LANG.collapseContent}"), $("html,body").animate({
                scrollTop : $(".hometext").offset().top
            }, 500)) : ($(".bodytext").addClass('hide').slideUp("slow"), $(this).attr("href", "open").text("{LANG.moreContent}"), $("html,body").animate({
                scrollTop : $(".detailContent").offset().top
            }, 500));
            return !1
        });
    </script>
    <!-- BEGIN: comment -->
    {CONTENT_COMMENT}
    <!-- END: comment -->
    <!-- BEGIN: otherClips -->
    <div class="panel panel-default otherClips">
        <div class="panel-heading">{LANG.incat}</div>
        <div class="panel-body">{OTHERCLIPSCONTENT}</div>
    </div>
    <!-- END: otherClips -->
</div>
<script type="text/javascript">
$('.otherClips .otherClipsContent .ctn1 .ctn2').matchHeight({ property: 'min-height' });
</script>
<!-- END: main -->