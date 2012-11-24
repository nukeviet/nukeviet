<!-- BEGIN: main -->
<script type="text/javascript">
var report_thanks_mess = '{LANG.report_thanks}';
var comment_thanks_mess = '{LANG.comment_thanks}';
var comment_please_wait = '{LANG.comment_thanks2}';
var comment_subject_defaul = '{ROW.comment_subject}';
</script>
<div class="header-details">
    <div class="title">
        <h1>{ROW.title}</h1>
        <span class="small"><strong>{LANG.uploadtime}:</strong> {ROW.uploadtime}, <strong>{LANG.user_name}:</strong> <span class="highlight">{ROW.user_name}</span>, <strong>{LANG.view_hits}:</strong> {ROW.view_hits}</span>
    </div>
    <div class="clear">
    </div>
</div>
<div class="details-content clearfix">
    <div class="gg fl">
        <!-- BEGIN: is_image -->
			<div class="acenter m-bottom">
				<a href="{FILEIMAGE.orig_src}" rel="shadowbox;height={FILEIMAGE.orig_height};width={FILEIMAGE.orig_width}"><img src="{FILEIMAGE.orig_src}" alt="{ROW.title}" style="width:257px;height:161px" class="s-border"/></a>
				<br/>
				<span class="small">{ROW.title}</span>
			</div>
		<!-- END: is_image -->
		<!-- BEGIN: introtext -->
			{ROW.introtext}
			{ROW.description}
		<!-- END: introtext -->
        <div class="clear">
        </div>
        <div class="note clearfix">
			<div class="fr"><!-- BEGIN: report -->
            <a class="block_link" href="javascript:void(0);" onclick="nv_link_report({ROW.id});">{LANG.report}</a>
        <!-- END: report --></div>
            <h4 class="download-hh">{LANG.download_detail}</h4>
	    <!-- BEGIN: download_allow -->
	        <div style="display:none">
	            <iframe name="idown"></iframe>
	        </div>
	        <!-- BEGIN: fileupload -->
	            <p><strong>{LANG.download_fileupload} {SITE_NAME}:</strong>
				<br />
				<span class="small">( {LANG.filesize}: {ROW.filesize} )</span></p>
				<ul class="links_down">
					<!-- BEGIN: row -->
						<li><a id="myfile{FILEUPLOAD.key}" href="{FILEUPLOAD.link}" onclick="nv_download_file('idown','{FILEUPLOAD.title}');return false;">{FILEUPLOAD.title}</a>				
						</li>
					<!-- END: row -->
				</ul>
	        <!-- END: fileupload -->
	        <!-- BEGIN: linkdirect -->
	            <p><strong>{LANG.download_linkdirect} {HOST}:</strong></p>
			<ul class="links_down">
	                <!-- BEGIN: row -->
	                <li>
	                    <a href="{LINKDIRECT.link}" onclick="nv_linkdirect('{LINKDIRECT.code}');return false;">{LINKDIRECT.name}</a> 
	                </li>
	                <!-- END: row -->
			<ul>		
	        <!-- END: linkdirect -->
	    <!-- END: download_allow -->
		    <!-- BEGIN: download_not_allow -->
		    <div class="download not_allow">
		        {ROW.download_info}
		    </div>
		    <!-- END: download_not_allow -->
        </div>
		<script type="text/javascript">
                                    $(document).ready(function(){
                                        $(".note").hover(function(){
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
		<!-- BEGIN: is_admin -->
        <div class="aright content-box clearfix">
			{LANG.file_admin}:
            <a href="{ROW.edit_link}">{GLANG.edit}</a> &divide; 
            <a href="{ROW.del_link}" onclick="nv_del_row(this,{ROW.id});return false;">{GLANG.delete}</a>
        </div>
     <!-- END: is_admin -->
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
                <strong>{LANG.updatetime}</strong>: {ROW.updatetime}
            </li>
            <li>
                <strong>{LANG.copyright}</strong>: {ROW.copyright}
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
		            <div class="clearfix">
		                <input class="hover-star" type="radio" value="1" title="{LANG.file_rating1}" style="vertical-align: middle" />
		                <input class="hover-star" type="radio" value="2" title="{LANG.file_rating2}" style="vertical-align: middle" />
		                <input class="hover-star" type="radio" value="3" title="{LANG.file_rating3}" style="vertical-align: middle" />
		                <input class="hover-star" type="radio" value="4" title="{LANG.file_rating4}" style="vertical-align: middle" />
		                <input class="hover-star" type="radio" value="5" title="{LANG.file_rating5}" style="vertical-align: middle" />
						<br />
		                <span id="hover-test" class="small">{LANG.file_rating_note}</span>
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
<div id="idcomment">
    <div class="header-comment">
        <div class="fr right small">
			<a href="javascript:void(0);" id="show-comments" name="show-comments">+ {LANG.view_comment_title}</a>&nbsp;
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
   <form id="commentForm" name="commentForm" action="{FORM_ACTION}" method="post">
        <div class="box-border content-box">
            <div class="box clearfix">
				<input id="comment_uname" name="comment_uname" type="text" class="input input-c fl" onblur="if(this.value=='')this.value='{ROW.comment_uname}';" onclick="if(this.value=='{ROW.comment_uname}')this.value='';" value="{ROW.comment_uname}" maxlength="100"{ROW.disabled}/>
                <input id="comment_uemail_iavim" name="comment_uemail" type="text" class="input input-c fr" onblur="if(this.value=='')this.value='{ROW.comment_uemail}';" onclick="if(this.value=='{ROW.comment_uemail}')this.value='';" value="{ROW.comment_uemail}" maxlength="100"{ROW.disabled}/> &nbsp;
            </div>
			<p>{LANG.file_comment_subject}: <input id="comment_subject" name="comment_subject" type="text" class="input input-c" value="{ROW.comment_subject}" /></p>
            <p>
            	{LANG.file_comment_content}:
                <textarea rows="1" cols="1" name="comment_content" id="comment_content" class="input typer box2" value=""></textarea>
            </p>
            <p>
                {LANG.file_comment_captcha}: &nbsp; <img  style="vertical-align: middle" height="22" id="vimg" src="{NV_BASE_SITEURL}index.php?scaptcha=captcha" title="{GLANG.captcha}" alt="{GLANG.captcha}" />
				<img style="vertical-align: middle" alt="{GLANG.captcharefresh}" title="{GLANG.captcharefresh}" src="{NV_BASE_SITEURL}images/refresh.png" width="16" height="16" class="refresh" onclick="nv_change_captcha('vimg','comment_seccode_iavim');" />
                &nbsp; <input type="text" class="input capcha" name="comment_seccode" id="comment_seccode_iavim" maxlength="{CAPTCHA_MAXLENGTH}"/>&nbsp; <input type="submit" class="button" id="comment_submit" name="comment_submit" type="submit" value="{LANG.file_comment_send}" />
            </p>
        </div>
    </form>
</div>
<!-- END: is_comment_allow -->
</div>
<!-- END: comment_allow2 -->
<script type="text/javascript">nv_list_comments();</script>
<script type="text/javascript">
	$("#showform").toggle(function(){
		$("#form_comment").slideDown();
	}, function(){
		$("#form_comment").slideUp();
	});
	$("#show-comments").toggle(function(){
		$("#list_comments").slideUp();
	}, function(){
		$("#list_comments").slideDown();
	})
</script>
<!-- END: main -->