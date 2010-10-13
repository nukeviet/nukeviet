<!-- BEGIN: main -->
<script type="text/javascript">
var report_thanks_mess = '{LANG.report_thanks}';
var comment_thanks_mess = '{LANG.comment_thanks}';
var comment_please_wait = '{LANG.comment_thanks2}';
var comment_subject_defaul = '{ROW.comment_subject}';
</script>
<div class="breakcoup">
{PAGE_TITLE}
</div>
<div class="header-details">
    <div class="title">
        <h1>{ROW.title}</h1>
        <span class="small">Đăng lúc 10:10 10-10-2010 bởi <a href="#" class="highlight">Adminstrator</a></span>
    </div>
    <div class="clear">
    </div>
</div>
<div class="details-content clearfix">
    <div class="gg fl">
        <div class="acenter m-bottom">
        	<!-- BEGIN: is_image -->
            <a href="{FILEIMAGE.orig_src}" rel="shadowbox;height={FILEIMAGE.orig_height};width={FILEIMAGE.orig_width}"><img src="{FILEIMAGE.orig_src}" alt="{ROW.title}" style="width:257px;height:161px" class="s-border"/></a>
			<!-- END: is_image -->
            <br/>
            <span class="small">{ROW.title}</span>
        </div>
		<!-- BEGIN: introtext -->
			{ROW.introtext}
			{ROW.description}
		<!-- END: introtext -->
        <div class="clear">
        </div>
        <div class="note">
            <h4 class="download-hh">{LANG.download_detail}</h4>
	    <!-- BEGIN: download_allow -->
	        <div style="display:none">
	            <iframe name="idown"></iframe>
	        </div>
	        <!-- BEGIN: fileupload -->
	        <div class="info clearfix green">
	            <dt class="fl"><strong>{LANG.download_fileupload} {SITE_NAME}:</strong></dt>
	        </div>
	        <div class="info clearfix">
	            <dt class="fl">
	                <!-- BEGIN: row -->
	                <div class="download_row">
	                    <a id="myfile{FILEUPLOAD.key}" href="{FILEUPLOAD.link}" onclick="nv_download_file('idown','{FILEUPLOAD.title}');return false;">{FILEUPLOAD.title}</a>
	                </div>
	                <!-- END: row -->
	            </dt>
	        </div>
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
	    <!-- END: download_allow -->

		    <!-- BEGIN: download_not_allow -->
		    <div class="download not_allow">
		        {ROW.download_info}
		    </div>
		    <!-- END: download_not_allow -->
            <script type="text/javascript">
                $(document).ready(function(){
                    $(".links_down li").hover(function(){
                        $(this).find("a.block_link").css({
                            'visibility': 'visible'
                        });
                    }, function(){
                        $(this).find("a.block_link").css({
                            'visibility': 'hidden'
                        });
                    });
                });
            </script>
        </div>
    </div>
    <div class="gg2 fr">
        <ul class="spec">
            <li>
                <strong>{LANG.file_version}</strong>: {ROW.version}
            </li>
            <li>
                <strong>{LANG.author_name}</strong>: {ROW.author_name}
            </li>
            <li>
                <strong>{LANG.author_url}</strong>: {ROW.author_url}
            </li>
            <li>
                <strong>{LANG.uploadtime}</strong>: {ROW.uploadtime}
            </li>
            <li>
                <strong>{LANG.updatetime}</strong>: {ROW.updatetime}
            </li>
            <li>
                <strong>{LANG.user_name}</strong>: {ROW.user_name}
            </li>
            <li>
                <strong>{LANG.copyright}</strong>: {ROW.copyright}
            </li>
            <li>
                <strong>{LANG.bycat2}</strong>: {ROW.catname}
            </li>
            <li>
                <strong>{LANG.filesize}</strong>: {ROW.filesize}
            </li>
            <li>
                <strong>{LANG.view_hits}</strong>: {ROW.view_hits}
            </li>
            <li>
                <strong>{LANG.download_hits}</strong>:             
				<div id="download_hits" style="display:inline-block;">
                {ROW.download_hits}
            	</div>
            </li>
			<!-- BEGIN: comment_allow -->
            <li>
                <strong>{LANG.comment_hits}</strong>: {ROW.comment_hits}
            </li>
			<!-- END: comment_allow -->
        </ul>
        <div class="b-rate m-bottom">
            <div class="header-rate">
                {LANG.file_rating}
            </div>
            <div class="content-box box-border">
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
            </div>
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
        
        $('.hover-star').rating('select', '{ROW.rating_point}');
		<!-- BEGIN: disablerating -->
        $(".hover-star").rating('disable');
        $('#hover-test').html('{ROW.rating_string}');
        $('#stringrating').html('{LANG.file_rating_note2}');
        sr = 2;
		<!-- END: disablerating -->
    </script>
    <div class="clear">
    </div>
</div>
<!-- BEGIN: comment_allow2 -->
<a name="lcm"></a>
<div id="comment">
    <div class="header-comment">
        <div class="fr right small">
			<a href="javascript:void(0);" id="show-comments" name="show-comments">+ Xem phản hồi</a>&nbsp;
			+ <a href="javascript:void(0);" id="showform">{LANG.file_your_comment}</a>
        </div>
        <h3>{LANG.file_comment_title}</h3>
        <div class="clear">
        </div>
    </div>
    <div id="list_comments" class="list-comments">

    </div>
<input type="hidden" name="comment_fid" id="comment_fid" value="{ROW.id}" />
<!-- BEGIN: is_comment_allow -->
    <div id="form_comment" class="comment-form" style="display:none">
        <div class="box-border content-box">
            <div class="box clearfix">
				<input id="comment_subject" name="comment_subject" type="text" class="input input-c fl" value="{LANG.file_comment_subject}" style="width:200px"/>
				<input id="comment_uname" name="comment_uname" type="text" class="input input-c fr" value="{ROW.comment_uemail}" style="width:200px" maxlength="100"{ROW.disabled}/>
                <input id="comment_uemail" name="comment_uemail" type="text" class="input input-c fr" value="{ROW.comment_uname}" maxlength="100"{ROW.disabled}/> &nbsp;
            </div>
            <p>
                <textarea rows="1" cols="1" name="comment_content" id="comment_content" class="input typer box2">{LANG.file_comment_content}</textarea>
            </p>
            <p>
                {LANG.file_comment_captcha}: &nbsp; <img  style="vertical-align: middle" height="22" id="vimg" src="{NV_BASE_SITEURL}index.php?scaptcha=captcha" title="{GLANG.captcha}" alt="{GLANG.captcha}" />
				<img style="vertical-align: middle" alt="{GLANG.captcharefresh}" title="{GLANG.captcharefresh}" src="{NV_BASE_SITEURL}images/refresh.png" width="16" height="16" class="refresh" onclick="nv_change_captcha('vimg','comment_seccode_iavim');" />
                &nbsp; <input type="text" class="input capcha" name="comment_seccode" id="comment_seccode" maxlength="{CAPTCHA_MAXLENGTH}"/>&nbsp; <input type="submit" class="button" id="comment_submit" name="comment_submit" type="submit" value="{LANG.file_comment_send}" />
            </p>
        </div>
    </div>
<!-- END: is_comment_allow -->
</div>
<!-- END: comment_allow2 -->
<script type="text/javascript">
	$("#comment_submit").click(function(){
   		var fid = $('#comment_fid').val();		
		var yoursubject = $("#comment_subject").val();
		if (yoursubject=="{LANG.file_comment_subject}"){
			alert("{LANG.comment_error4}");
			$("#comment_subject").focus();
			return false;
		}
		var yourname = $("#comment_uname").val();
		var yourmail = $("#comment_uemail").val();
		var comment_body = $("#comment_content").val();
		if (comment_body=="{LANG.comment_error5}"){
			alert("{LANG.comment_error5}");
			$("#comment_content").focus();
			return false;
		}
		var humand_verification = $("#comment_seccode").val();
		$.ajax({	
			type: 'POST',
			url: nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=getcomment',
			data: 'subject='+yoursubject+'&uname='+yourname+'&uemail='+ yourmail + '&content='+comment_body+'&seccode='+humand_verification+'&id='+fid+'&ajax=1',
			success: function(data){
				alert(data);
				nv_change_captcha('vimg','comment_seccode_iavim');
				$("#form_comment").slideUp();
			}
		});
	})
	$("#comment_subject,#comment_uemail,#comment_uname,#comment_content").click(function(){
		$(this).val("");
	});
	$("#showform").toggle(function(){
		$("#list_comments").slideUp();
		$("#form_comment").slideDown();
	}, function(){
		$("#list_comments").slideUp();
		$("#form_comment").slideUp();
	});
   	var fid = $('#comment_fid').val();
	$("#list_comments").load(nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=getcomment&list_comment=' + fid);
	$("#show-comments").toggle(function(){
		$("#form_comment").slideUp();
		$("#list_comments").slideUp();
	}, function(){
		$("#form_comment").slideUp();
		$("#list_comments").slideDown();
	})
</script>
<!-- END: main -->
