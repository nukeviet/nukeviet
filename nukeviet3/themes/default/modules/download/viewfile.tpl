<!-- BEGIN: main -->
<div class="download_column" style="border:0">
    <div class="title_file">
        {TITLE_FILE}
    </div>
    <div class="div_content_view">
        <div>
            <p class="pleft">
                <b>{LANG.up_by_title}: 
                    <font class="color">
                        {AUTHOR}
                    </font>
                </b>
                -  {DATE_UP},<b>{LANG.count_view_title}: 
                    <font class="color">
                        {NUM_VIEW}
                    </font>
                    , 
                    {LANG.download_title}: 
                    <font class="color">
                        {NUM_DOW}
                    </font>, 
                    {LANG.comment_title}: 
                    <font class="color">
                        {NUM_COM}
                    </font>
                </b>
            </p>
            <p class="pright">
                <!-- BEGIN: errlink --><a href="{URL_REPORT}">[{LANG.viewfile_report}]</a>
                <!-- END: errlink -->
            </p>
            <div style="clear:both">
            </div>
        </div>
        <div class="div_body_text">
            <div class="div_top_file">
                <!-- BEGIN: img --><img src="{SRC_IMG}" alt="" width="133px" style="margin-right:5px"><!-- END: img -->
                <form action="" method="post" style="padding:0; margin:0" id="fsubmit">
                    <input type="hidden" name="atc" value="1" />
                    <div class="dvleft">
                        <b>{LANG.main_copyright}</b>: {COPY_RIGHT} 
                        <br>
                        <b>{LANG.main_filesize}</b>: {FILE_SIZE} 
                        <br>
                        <a href="javascript:Sendsubmit()"><img src="{URL_BASE_THEMES}images/download/arrow_down_32.png" border="0" /></a>
                    </div>
                    <!-- BEGIN: capcha -->
                    <div class="div_capcha">
                        <img id="vimgfile" alt="{N_CAPTCHA}" src="{SRC_CAPTCHA}" style='vertical-align:middle' height="22" /><input type="text" name="captcha" class="input_capcha" id="captcha"/><input type="button" onclick="nv_change_captcha('vimgfile','commentseccode');" value="" class="bt_reset" />
                        <div style="clear:both">
                        </div>
                        <!-- BEGIN: err -->
                        <font class="err_red">
                            {LANG.comment_error_captcha}
                        </font>
                        <!-- END: err --><!-- BEGIN: dow_err -->
                        <font class="err_red">
                            {LANG.comment_error_down}
                        </font>
                        <!-- END: dow_err -->
                    </div>
                    <!-- END: capcha -->
                </form>
                <div class="dvright">
                    <!-- BEGIN: adminlink -->{ADMINLINK}&nbsp;<!-- END: adminlink --><a href="{URL_BACK}">{LANG.main_back_title}</a>
                    <br/>
                </div>
                <script language="javascript">
                    function Sendsubmit(){
                        document.getElementById('fsubmit').submit();
                    }
                </script>
                <div style="clear:both">
                </div>
            </div>
            <!-- BEGIN: linkdir -->
            <div>
                <div class="div_linkdir">
                    Link down 
                    <br/>
                    {LINK_FILE}
                </div>
            </div>
            <!-- END: linkdir -->
            <div>
                {BODY_TEXT}
            </div>
        </div>
    </div>
    {ADMIN_LINK} <!-- BEGIN: others -->
    <div class="others">
        <h4 style="padding:0; margin:0; font-size:12px; color:#06C">{LANG.viewfile_fileother}:</h4>
        <ul>
            <!-- BEGIN: otherfile -->
            <li>
                <a href="{otherfile.url}">{otherfile.title} ({otherfile.uploadtime})</a>
            </li>
            <!-- END: otherfile -->
        </ul>
    </div>
    <!-- END: others --><!-- BEGIN: comment -->
    <script type="text/javascript">
        $(function(){
            $('#showcomment').load('{GET_COMMENT}');
            $('#postcomment').click(function(){
                var commentname = $('#commentname').val();
                var commentemail = $('#commentemail').val();
                var commentcontent = $('#commentcontent').val();
                var commentseccode = $('#commentseccode').val();
                $.ajax({
                    type: 'POST',
                    url: '{GET_COMMENT}',
                    data: 'commentname=' + commentname + '&commentemail=' + commentemail + '&commentcontent=' + commentcontent + '&commentseccode=' + commentseccode,
                    success: function(data){
                        alert(data);
                        $('#showcomment').load('{GET_COMMENT}');
                    }
                });
            });
        });
    </script>
    <div id="idcomment" style="margin-top: 15px;">
        <a onclick="nv_show_hidden('showcomment',2);" href="javascript:void(0);"><img src="{URL_BASE_THEMES}images/download/comment.png" alt="Show comment" /><strong>{LANG.comment_view}</strong></a>
        -- <a onclick="nv_show_hidden('formcomment',2);" href="javascript:void(0);"><img alt="Add comment" src="{URL_BASE_THEMES}images/download/comment_add.png" /><strong>{LANG.comment_send}</strong></a>
        <hr/>
        <div class="clearfix">
        </div>
        <div id="formcomment">
            <form action="" method="post" name="fcomment">
                <div>
                    <strong>{LANG.comment_title}</strong>
                </div>
                <div class="name">
                    <label style='width:80px;display:inline-block'>
                        <strong>{LANG.comment_name}</strong>
                    </label>
                    <input type="text" id="commentname" value="{NAME}" />
                </div>
                <div class="email">
                    <label style='width:80px;display:inline-block'>
                        <strong>{LANG.comment_email}</strong>
                    </label>
                    <input type="text" id="commentemail" value="{EMAIL}" />
                </div>
                <div class="content">
                    <label style='width:80px;display:inline-block;vertical-align:top'>
                        <strong>{LANG.comment_content}</strong>
                    </label>
                    <textarea style="width: 400px;" cols="50" rows="5" id="commentcontent">
                    </textarea>
                </div>
                <div class="captcha">
                    <label style='width:80px;display:inline-block'>
                        <strong>{LANG.comment_seccode}</strong>
                    </label>
                    <input type="text" id="commentseccode" /><img id="vimg" alt="{N_CAPTCHA}" title="{N_CAPTCHA}" src="{URL_BASE_SITE}?scaptcha=captcha" style='vertical-align:middle' height="22" /><img width="16" height="16" onclick="nv_change_captcha('vimg','commentseccode');" class="refresh" src="{URL_BASE_THEMES}images/download/refresh.png" title="{LANG.comment_seccode_refresh}" alt="{LANG.comment_seccode_refresh}">
                </div>
                <div style="text-align:left;margin-left:140px">
                    <input type="button" id="postcomment" value="{LANG.comment_submit}" class="bt_sendcomment"/>
                </div>
            </form>
        </div>
        <div id="showcomment">
        </div>
        <script language="javascript">
            nv_show_hidden('formcomment', 2);
        </script>
    </div>
    <!-- END: comment -->
</div>
<!-- BEGIN: script -->
<script language="javascript">
    $('a[class="delfile"]').click(function(event){
        event.preventDefault();
        if (confirm('{LANG.file_del_confirm}')) {
            var href = $(this).attr('href');
            $.ajax({
                type: 'POST',
                url: href,
                data: '',
                success: function(data){
                    alert(data);
                    window.location = '{URL_RE}';
                }
            });
        }
    });
</script>
<!-- END: script -->
<!-- END: main -->
