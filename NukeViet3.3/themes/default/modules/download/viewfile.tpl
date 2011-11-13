<!-- BEGIN: main -->
<script type="text/javascript">
var report_thanks_mess = '{LANG.report_thanks}';
var comment_thanks_mess = '{LANG.comment_thanks}';
var comment_please_wait = '{LANG.comment_thanks2}';
var comment_subject_defaul = '{ROW.comment_subject}';
</script>
<div class="block_download">
    <div class="title_bar">
        {ROW.title}
    </div>
    <!-- BEGIN: is_image -->
    <div class="image" style="padding-top:8px">
        <a href="{FILEIMAGE.orig_src}" rel="shadowbox;height={FILEIMAGE.orig_height};width={FILEIMAGE.orig_width}"><img alt="{ROW.title}" title="{ROW.title}" src="{FILEIMAGE.src}" width="{FILEIMAGE.width}" height="{FILEIMAGE.height}" /></a>
    </div>
    <!-- END: is_image -->
    <!-- BEGIN: introtext -->
    <div class="introtext">
        {ROW.description}
    </div>
    <!-- END: introtext -->
    <div class="detail">
        {LANG.listing_details}
    </div>
    <div class="content">
        <dl class="info clearfix gray">
            <dt class="fl" style="width:35%;">{LANG.file_title}:</dt>
            <dd class="fl">{ROW.title}</dd>
        </dl>
        <dl class="info clearfix">
            <dt class="fl" style="width:35%;">{LANG.file_version}:</dt>
            <dd class="fl">{ROW.version}</dd>
        </dl>
        <dl class="info clearfix gray">
            <dt class="fl" style="width:35%;">{LANG.author_name}:</dt>
            <dd class="fl">{ROW.author_name}</dd>
        </dl>
        <dl class="info clearfix">
            <dt class="fl" style="width:35%;">{LANG.author_url}:</dt>
            <dd class="fl">{ROW.author_url}</dd>
        </dl>
        <dl class="info clearfix gray">
            <dt class="fl" style="width:35%;">{LANG.bycat2}:</dt>
            <dd class="fl">{ROW.catname}</dd>
        </dl>
        <dl class="info clearfix">
            <dt class="fl" style="width:35%;">{LANG.uploadtime}:</dt>
            <dd class="fl">{ROW.uploadtime}</dd>
        </dl>
        <dl class="info clearfix gray">
            <dt class="fl" style="width:35%;">{LANG.updatetime}:</dt>
            <dd class="fl">{ROW.updatetime}</dd>
        </dl>
        <dl class="info clearfix">
            <dt class="fl" style="width:35%;">{LANG.user_name}:</dt>
            <dd class="fl">{ROW.user_name}</dd>
        </dl>
        <dl class="info clearfix gray">
            <dt class="fl" style="width:35%;">{LANG.copyright}:</dt>
            <dd class="fl">{ROW.copyright}</dd>
        </dl>
        <dl class="info clearfix">
            <dt class="fl" style="width:35%;">{LANG.filesize}:</dt>
            <dd class="fl">{ROW.filesize}</dd>
        </dl>
        <dl class="info clearfix gray">
            <dt class="fl" style="width:35%;">{LANG.view_hits}:</dt>
            <dd class="fl">{ROW.view_hits}</dd>
        </dl>
        <dl class="info clearfix">
            <dt class="fl" style="width:35%;">{LANG.download_hits}:</dt>
            <dd class="fl">
            <div id="download_hits">
                {ROW.download_hits}
            </div>
            </dd>
        </dl>
        <!-- BEGIN: comment_allow -->
        <dl class="info clearfix gray">
            <dt class="fl" style="width:35%;">{LANG.comment_hits}:</dt>
            <dd class="fl">{ROW.comment_hits}</dd>
        </dl>
        <!-- END: comment_allow -->
    </div>
    <div class="info_download">
        <!-- BEGIN: report -->
        <div class="right report">
            <a href="javascript:void(0);" onclick="nv_link_report({ROW.id});">{LANG.report}</a>
        </div>
        <!-- END: report -->
        {LANG.download_detail}
    </div>
    <!-- BEGIN: download_allow -->
    <div class="download">
        <div class="hidden">
            <iframe name="idown"></iframe>
        </div>
        <!-- BEGIN: fileupload -->
        <dl class="info clearfix green">
            <dt class="fl"><strong>{LANG.download_fileupload} {SITE_NAME}:</strong></dt>
        </dl>
        <dl class="info clearfix">
            <dt class="fl">
                <!-- BEGIN: row -->
                <div class="download_row">
                    <a id="myfile{FILEUPLOAD.key}" href="{FILEUPLOAD.link}" onclick="nv_download_file('idown','{FILEUPLOAD.title}');return false;">{FILEUPLOAD.title}</a>
                </div>
                <!-- END: row -->
            </dt>
        </dl>
        <!-- END: fileupload -->
        <!-- BEGIN: linkdirect -->
        <dl class="info clearfix green">
            <dt class="fl"><strong>{LANG.download_linkdirect} {HOST}:</strong></dt>
        </dl>
        <dl class="info clearfix">
            <dt class="fl">
                <!-- BEGIN: row -->
                <div class="url_row">
                    <a href="{LINKDIRECT.link}" onclick="nv_linkdirect('{LINKDIRECT.code}');return false;">{LINKDIRECT.name}</a>
                </div>
                <!-- END: row -->
            </dt>
        </dl>
        <!-- END: linkdirect -->
    </div>
    <!-- END: download_allow -->
    <!-- BEGIN: download_not_allow -->
    <div class="download not_allow">
        {ROW.download_info}
    </div>
    <!-- END: download_not_allow -->
    <div class="detail">
        {LANG.file_rating}
    </div>

        <div class="rating clearfix">
            <div id="stringrating">{LANG.rating_question}</div>
            <div style="padding: 5px;">
                <input class="hover-star" type="radio" value="1" title="{LANG.file_rating1}" style="vertical-align: middle" />
                <input class="hover-star" type="radio" value="2" title="{LANG.file_rating2}" style="vertical-align: middle" />
                <input class="hover-star" type="radio" value="3" title="{LANG.file_rating3}" style="vertical-align: middle" />
                <input class="hover-star" type="radio" value="4" title="{LANG.file_rating4}" style="vertical-align: middle" />
                <input class="hover-star" type="radio" value="5" title="{LANG.file_rating5}" style="vertical-align: middle" />
                <span id="hover-test" style="margin-left:20px">{LANG.file_rating_note}</span>
            </div>
        </div>

    <script type="text/javascript">
        var sr = 0;
        $('.hover-star').rating({
            focus: function(value, link){
                var tip = $('#hover-test');
                if (sr != 2) {
                    tip[0].data = tip[0].data || tip.html();
                    tip.html('{LANG.file_your_rating}: ' + link.title || 'value: ' + value);
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
                    nv_sendrating('{ROW.id}', value);
                }
            }
        });
        
        $('.hover-star').rating('select', '{ROW.rating_point}');<!-- BEGIN: disablerating -->
        $(".hover-star").rating('disable');
        $('#hover-test').html('{ROW.rating_string}');
        $('#stringrating').html('{LANG.file_rating_note2}');
        sr = 2;<!-- END: disablerating -->
    </script>
    <!-- BEGIN: is_admin -->
    <div class="more">
        <div class="right">
            <a href="{ROW.edit_link}">{GLANG.edit}</a> &divide; 
            <a href="{ROW.del_link}" onclick="nv_del_row(this,{ROW.id});return false;">{GLANG.delete}</a>
        </div>
        {LANG.file_admin}:
    </div>
     <!-- END: is_admin -->
</div>
<!-- BEGIN: comment_allow2 -->
<a name="lcm"></a>
<div id="list_comments">
</div>
<input type="hidden" name="comment_fid" id="comment_fid" value="{ROW.id}" />
<!-- BEGIN: is_comment_allow -->
<div id="hidden_form_comment" class="comment_top">
    <a href="javascript:void(0);" onclick="show_form();">{LANG.file_your_comment}</a>
</div>
<div id="form_comment" class="form_comment" style="visibility: hidden; display: none">
    <form id="commentForm" name="commentForm" action="{FORM_ACTION}" method="post">
        <dl class="clearfix">
            <dd class="fl">
                <label>
                    {LANG.file_comment_username}:
                </label>
            </dd>
            <dt class="fr">
                <input class="txt" type="text" name="comment_uname" id="comment_uname" value="{ROW.comment_uname}" maxlength="100"{ROW.disabled} />
            </dt>
        </dl>
        <dl class="clearfix">
            <dd class="fl">
                <label>
                    {LANG.file_comment_useremail}:
                </label>
            </dd>
            <dt class="fr">
                <input class="txt" type="text" name="comment_uemail" value="{ROW.comment_uemail}" id="comment_uemail_iavim" maxlength="100"{ROW.disabled} />
            </dt>
        </dl>
        <dl class="clearfix">
            <dd class="fl">
                <label>
                    {LANG.file_comment_subject}:
                </label>
            </dd>
            <dt class="fr">
                <input class="txt" name="comment_subject" type="text" value="{ROW.comment_subject}" id="comment_subject" maxlength="255" />
            </dt>
        </dl>
        <dl class="clearfix">
            <dd class="fl">
                <label>
                    {LANG.file_comment_content}:
                </label>
                <br />
                <textarea class="textarea" cols="20" rows="2" id="comment_content" name="comment_content"></textarea>
            </dd>
        </dl>
        <dl class="clearfix">
            <dd class="fl">
                <label>
                    {LANG.file_comment_captcha}:
                </label>
            </dd>
            <dt class="fr">
                <img  style="vertical-align: middle" height="22" id="vimg" src="{NV_BASE_SITEURL}index.php?scaptcha=captcha" title="{GLANG.captcha}" alt="{GLANG.captcha}" />
                <img style="vertical-align: middle" alt="{GLANG.captcharefresh}" title="{GLANG.captcharefresh}" src="{NV_BASE_SITEURL}images/refresh.png" width="16" height="16" class="refresh" onclick="nv_change_captcha('vimg','comment_seccode_iavim');" />
            </dt>
            <dt class="fr" style="width:200px">
                <input style="width:80px;vertical-align: middle" type="text" value="" name="comment_seccode" id="comment_seccode_iavim" maxlength="{CAPTCHA_MAXLENGTH}" />
            </dt>
        </dl>
        <div style="float: right;">
            <input name="hidden_form_comment" type="button" value="{LANG.comment_form_hidden}" onclick="hidden_form();" />
        </div>
        <input id="comment_submit" type="submit" class="submit" value="{LANG.file_comment_send}" />
        <a name="cform"></a>
    </form>
</div>
<!-- END: is_comment_allow -->
<script type="text/javascript">nv_list_comments();</script>
<!-- END: comment_allow2 -->
<!-- END: main -->
