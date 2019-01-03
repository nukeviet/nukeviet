<!-- BEGIN: main -->
<script type="text/javascript" src="{NV_BASE_SITEURL}themes/{TEMPLATE}/images/{MODULE_FILE}/jwplayer/jwplayer.js"></script>
<script type="text/javascript">jwplayer.key="KzcW0VrDegOG/Vl8Wb9X3JLUql+72MdP1coaag==";</script>
<div id="hot-news">
    <div class="panel panel-default news_column">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-14 margin-bottom-lg">
                    <!-- BEGIN: youtube -->
                    <div class="clearfix" id="ytblvideoctn_bl{BID}_v{DATA.id}">
                        <iframe id="ytblvideo_bl{BID}_v{DATA.id}" allowfullscreen="allowfullscreen" frameborder="0" src="//www.youtube.com/embed/{CODE}?rel=0&amp;controls=1&amp;autohide=0"></iframe>
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
                    <!-- END: youtube -->
                    <!-- BEGIN: player -->
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
                    <!-- END: player -->
                </div>
                <div class="col-md-10 margin-bottom-lg">
                    <ul class="column-margin-left">
                        <!-- BEGIN: other -->
                        <li class="clearfix"><a class="show black h4" href="{DATA.link}" title="{DATA.title}"><img src="{DATA.img}" alt="{DATA.title}" class="img-thumbnail pull-right margin-left-sm" style="width: 65px;" /> <i class="fa fa-video-camera p-r5" aria-hidden="true"></i> {DATA.title}</a></li>
                        <!-- END: other -->
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END: main -->