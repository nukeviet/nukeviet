<!-- BEGIN: main -->
<div class="news_column" style="padding:0 !important;margin:0 !important;">
    <div id="news_detail">
        <h1>{DETAIL.title}</h1>
        <span class="time">{DETAIL.publtime}</span>
        <div class="control">
            <ul>
                <!-- BEGIN: allowed_send -->
                <li>
                    <a rel="nofollow" class="sendmail" title="{LANG.sendmail}" href="javascript:void(0);" onclick="NewWindow('{URL_SENDMAIL}','{TITLE}','500','400','no');return false"></a>
                </li>
                <!-- END: allowed_send --><!-- BEGIN: allowed_print -->
                <li>
                    <a class="print" title="{LANG.print}" href="javascript: void(0)" onclick="NewWindow('{URL_PRINT}','{TITLE}','840','768','yes');return false"></a>
                </li>
                <!-- END: allowed_print --><!-- BEGIN: allowed_save -->
                <li>
                    <a class="savefile" title="{LANG.savefile}" href="{URL_SAVEFILE}"></a>
                </li>
                <!-- END: allowed_save -->
            </ul>
        </div>
        <div style="clear: both;"></div>
        <!-- BEGIN: showhometext -->
        <div id="hometext">
            <!-- BEGIN: imgthumb -->
            <div id="imghome" class="fl" style="width:{DETAIL.image.width}px;margin-right:8px;">
                <a href="{DETAIL.homeimgfile}" title="{TITLE}" rel="shadowbox"><img alt="{DETAIL.image.alt}" src="{DETAIL.image.src}" width="{DETAIL.image.width}"/></a>
                <p>
                    <em>{DETAIL.image.note}</em>
                </p>
            </div>
            <!-- END: imgthumb -->{DETAIL.hometext}
        </div>
        <!-- BEGIN: imgfull -->
        <div style="width:{DETAIL.image.width}px;margin:10px auto;">
            <img alt="{DETAIL.image.alt}" src="{DETAIL.image.src}" width="{DETAIL.image.width}"/>
            <p style="text-align: center;">
                <em>{DETAIL.image.note}</em>
            </p>
        </div>
        <!-- END: imgfull --><!-- END: showhometext -->
        <div class="bodytext">{DETAIL.bodytext}</div>
        <!-- BEGIN: author --><!-- BEGIN: name -->
        <p style="text-align: right;">
            <strong>{LANG.author}: </strong>{DETAIL.author}
        </p>
        <!-- END: name --><!-- BEGIN: source -->
        <p style="text-align: right;">
            <strong>{LANG.source}: </strong>{DETAIL.source}
        </p>
        <!-- END: source --><!-- END: author --><!-- BEGIN: copyright -->
        <p class="copyright">{COPYRIGHT}</p>
        <!-- END: copyright -->
    </div>
    <!-- BEGIN: adminlink -->
    <p style="text-align: right;">{ADMINLINK}</p>
    <!-- END: adminlink --><!-- BEGIN: allowed_rating -->
    <form id="form3B" action="">
        <div class="clearfix">
            <div id="stringrating">{STRINGRATING}</div>
            <div style="padding: 5px;">
                <input class="hover-star" type="radio" value="1" title="{LANGSTAR.verypoor}" /><input class="hover-star" type="radio" value="2" title="{LANGSTAR.poor}" /><input class="hover-star" type="radio" value="3" title="{LANGSTAR.ok}" /><input class="hover-star" type="radio" value="4" title="{LANGSTAR.good}" /><input class="hover-star" type="radio" value="5" title="{LANGSTAR.verygood}" /><span id="hover-test" style="margin: 0 0 0 20px;">{LANGSTAR.note}</span>
            </div>
        </div>
    </form>
    <script type="text/javascript">
        var sr = 0;
        $('.hover-star').rating({
            focus: function(value, link){
                var tip = $('#hover-test');
                if (sr != 2) {
                    tip[0].data = tip[0].data || tip.html();
                    tip.html(link.title || 'value: ' + value);
                    sr = 1;
                }
            },
            blur: function(value, link){
                var tip = $('#hover-test');
                if (sr != 2) {
                    $('#hover-test').html(tip[0].data || '');
                    sr = 1;
                }
            },
            callback: function(value, link){
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
    <!-- END: disablerating --><!-- END: allowed_rating --><!-- BEGIN: keywords -->
    <div class="keywords">
        <strong>{LANG.keywords}: </strong>
        <!-- BEGIN: loop --><a title="{KEYWORD}" href="{LINK_KEYWORDS}"><em>{KEYWORD}</em></a>{SLASH}<!-- END: loop -->
    </div>
    <!-- END: keywords -->
	<!-- BEGIN: comment -->
    <div id="idcomment">
        <ul class="control">
            <li>
                <a onclick="nv_show_hidden('showcomment',2);" href="javascript:void(0);"><img src="{IMGSHOWCOMMENT}" alt="Show comment" /><strong>{LANG.comment_view}</strong></a>
            </li>
            <li>
                -- <a onclick="nv_show_hidden('formcomment',2);" href="javascript:void(0);"><img alt="Add comment" src="{IMGADDCOMMENT}" /><strong>{LANG.comment_send}</strong></a>
            </li>
        </ul>
        <div class="clearfix"></div>
        <div id="showcomment">{COMMENTCONTENT}</div>
        <div id="formcomment">
        	<!-- BEGIN: form -->
            <div class="add">
                <img alt="Comment add" src="{IMGADDCOMMENT}" /><strong>{LANG.comment_title}</strong>
            </div>
            <div class="name">
                <label>
                    <strong>{LANG.comment_name}</strong>
                </label>
                <input {DISABLED} type="text" id="commentname" value="{NAME}" />
            </div>
            <div class="email">
                <label>
                    <strong>{LANG.comment_email}</strong>
                </label>
                <input {DISABLED} type="text" id="commentemail_iavim" value="{EMAIL}" />
            </div>
            <div class="content">
                <label>
                    <strong>{LANG.comment_content}</strong>
                </label>
                <textarea cols="50" rows="5" id="commentcontent"></textarea>
            </div>
            <div class="captcha">
                <label>
                    <strong>{LANG.comment_seccode}</strong>
                </label>
                <input type="text" id="commentseccode_iavim" /><img id="vimg" alt="{N_CAPTCHA}" src="{SRC_CAPTCHA}" width="{GFX_WIDTH}" height="{GFX_HEIGHT}" /><img alt="{CAPTCHA_REFRESH}" src="{CAPTCHA_REFR_SRC}" width="16" height="16" class="refresh" onclick="nv_change_captcha('vimg','commentseccode_iavim');"/>
            </div>
            <div style="text-align: center;">
                <input type="button" id="buttoncontent" value="{LANG.comment_submit}" onclick="sendcommment('{NEWSID}', '{NEWSCHECKSS}', '{GFX_NUM}');"/>
            </div>
			<!-- END: form -->
			<!-- BEGIN: form_login-->
				{COMMENT_LOGIN}
			<!-- END: form_login -->
        </div>
    </div>
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
        </li>
        <!-- END: loop -->
    </ul>
    <div class="clear"></div>
    <p style="text-align: right;">
        <a title="{TOPIC.topictitle}" href="{TOPIC.topiclink}">{LANG.more}</a>
    </p>
    <!-- END: topic --><!-- BEGIN: related_new -->
    <p>
        <strong>{LANG.related_new}</strong>
    </p>
    <ul class="related">
        <!-- BEGIN: loop -->
        <li>
            <a title="{RELATED_NEW.title}" href="{RELATED_NEW.link}">{RELATED_NEW.title}</a>
            <span class="date">({RELATED_NEW.time})</span>
        </li>
        <!-- END: loop -->
    </ul>
    <!-- END: related_new --><!-- BEGIN: related -->
    <div class="clear"></div>
    <p>
        <strong>{LANG.related}</strong>
    </p>
    <ul class="related">
        <!-- BEGIN: loop -->
        <li>
            <a title="{RELATED.title}" href="{RELATED.link}">{RELATED.title}</a>
            <span class="date">({RELATED.time})</span>
        </li>
        <!-- END: loop -->
    </ul>
    <!-- END: related -->
</div>
<!-- END: main -->
<!-- BEGIN: no_permission -->
<div id="no_permission">
    <p>{NO_PERMISSION}</p>
</div>
<!-- END: no_permission -->
