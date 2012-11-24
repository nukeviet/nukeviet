<!-- BEGIN: main -->
    <div class="header-details">
        <div class="action fr right">
            <!-- BEGIN: allowed_send --><a rel="nofollow" title="{LANG.sendmail}" href="javascript:void(0);" onclick="NewWindow('{URL_SENDMAIL}','{TITLE}','500','400','no');return false" class="email">{LANG.sendmail}</a>
            <br/>
            <!-- END: allowed_send --><!-- BEGIN: allowed_print --><a title="{LANG.print}" href="javascript: void(0)" onclick="NewWindow('{URL_PRINT}','{TITLE}','840','768','yes');return false" class="print">{LANG.print}</a>
            <br/>
            <!-- END: allowed_print --><!-- BEGIN: allowed_save --><a class="save" title="{LANG.savefile}" href="{URL_SAVEFILE}">{LANG.savefile}</a>
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
        <div class="clear">
        </div>
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
            <!-- END: imgthumb -->{DETAIL.hometext}<!-- BEGIN: imgfull -->
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
                <!-- BEGIN: loop --><a title="{KEYWORD}" href="{LINK_KEYWORDS}">{KEYWORD}</a>{SLASH} <!-- END: loop -->
            </p>
        </div>
        <!-- END: keywords -->
        <div class="oop fr">
            <div class="header-oop icon-rating">
                {LANG.rating}
            </div>
            <!-- BEGIN: allowed_rating -->
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
            <!-- END: disablerating --><!-- END: allowed_rating -->
        </div>
    </div>
    <!-- BEGIN: topic -->
    <div class="other-news">
        <h4>{LANG.topic}</h4>
        <ul>
            <!-- BEGIN: loop -->
            <li>
                <a title="{TOPIC.title}" href="{TOPIC.link}">{TOPIC.title}</a>
                <span class="date">({TOPIC.time})</span>
            </li>
            <!-- END: loop -->
        </ul>
        <p>
            <a title="{TOPIC.topictitle}" href="{TOPIC.topiclink}">{LANG.more}</a>
        </p>
    </div>
    <!-- END: topic --><!-- BEGIN: related_new -->
    <div class="other-news">
        <h4>{LANG.related_new}</h4>
        <ul>
            <!-- BEGIN: loop -->
            <li>
                <a title="{RELATED_NEW.title}" href="{RELATED_NEW.link}">{RELATED_NEW.title}</a>
                <span class="date">({RELATED_NEW.time})</span>
            </li>
            <!-- END: loop -->
        </ul>
    </div>
    <!-- END: related_new --><!-- BEGIN: related -->
    <div class="other-news">
        <h4>{LANG.related}</h4>
        <ul>
            <!-- BEGIN: loop -->
            <li>
                <a title="{RELATED.title}" href="{RELATED.link}">{RELATED.title}</a>
                <span class="date">({RELATED.time})</span>
            </li>
            <!-- END: loop -->
        </ul>
    </div>
    <!-- END: related --><!-- BEGIN: comment -->
    <div id="idcomment">
        <div class="header-comment">
            <div class="fr right small">
                <a onclick="nv_show_hidden('showcomment',2);" href="javascript:void(0);" id="show-comments">+ {LANG.comment_view}</a>&nbsp;&nbsp;&nbsp;&nbsp;<a onclick="nv_show_hidden('formcomment',2);" href="javascript:void(0);" id="hide-comments">- {LANG.comment_send}</a>
            </div>
            <h3>{LANG.comment}</h3>
            <div class="clear">
            </div>
        </div>
        <div id="showcomment" class="list-comments">
            {COMMENTCONTENT}
        </div>
        <div id="formcomment" class="comment-form">
            <!-- BEGIN: form -->
            <div class="box-border content-box">
                <div class="box clearfix">
                    <input id="commentname" type="text" value="{NAME}" {DISABLED} class="input input-c fl" onblur="if(this.value=='')this.value='{LANG.comment_name}';" onclick="if(this.value=='{LANG.comment_name}')this.value='';"/>
                    <input id="commentemail_iavim" type="text" value="{EMAIL}" {DISABLED} class="input input-c fr" onblur="if(this.value=='')this.value='{LANG.comment_email}';" onclick="if(this.value=='{LANG.comment_email}')this.value='';"/>
                </div>
                <p>
                    <textarea id="commentcontent" class="input typer box2" cols="1" rows="1" onblur="if(this.value=='')this.value='{LANG.comment_content}';" onclick="if(this.value=='{LANG.comment_content}')this.value='';">{LANG.comment_content}</textarea>
                </p>
                <p>
                    {LANG.comment_seccode}: &nbsp; <img id="vimg" alt="{N_CAPTCHA}" src="{SRC_CAPTCHA}" width="{GFX_WIDTH}" height="{GFX_HEIGHT}" /><img alt="{CAPTCHA_REFRESH}" src="{CAPTCHA_REFR_SRC}" width="16" height="16" class="refresh" onclick="nv_change_captcha('vimg','commentseccode_iavim');"/>&nbsp; <input id="commentseccode_iavim" type="text" class="input capcha" />&nbsp; <input id="buttoncontent" type="submit" value="{LANG.comment_submit}" onclick="sendcommment('{NEWSID}', '{NEWSCHECKSS}', '{GFX_NUM}');" class="button" />&nbsp; <input id="reset-cm" type="reset" value="RESET" class="button-2" />
                </p>
            </div>
    		<script type="text/javascript">
    		$("#reset-cm").click(function(){
    			$("#commentcontent,#commentseccode_iavim").val("");
    		});
    		</script>
    		<!-- END: form -->
            <!-- BEGIN: form_login-->
            {COMMENT_LOGIN}<!-- END: form_login -->
        </div>
    </div><!-- END: comment -->
<!-- END: main -->

<!-- BEGIN: no_permission -->
    <div id="no_permission">
        <p>
            {NO_PERMISSION}
        </p>
    </div>
<!-- END: no_permission -->