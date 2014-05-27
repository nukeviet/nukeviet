<!-- BEGIN: main -->
<div id="idcomment" class="nv-fullbg">
	<div class="header-comment">
		<div class="fr right small">
			<select id="sortcomm" onchange="window.location='{BASE_URL_COMM}&sortcomm='+this.value;">
				<!-- BEGIN: sortcomm -->
				<option value="{OPTION.key}" {OPTION.selected}>{OPTION.title}</option>
				<!-- END: sortcomm -->
			</select>
			<a onclick="nv_show_hidden('showcomment',2);" href="javascript:void(0);" id="show-comments">+ {LANG.comment_hide_show}</a>
		</div>
		<h3>{LANG.comment}</h3>
		<div class="clear"></div>
	</div>
	<div id="showcomment" class="list-comments">
		{COMMENTCONTENT}
	</div>
	<div id="formcomment" class="comment-form">
		<div class="box-border content-box">
		<!-- BEGIN: allowed_comm -->
			<div class="box clearfix">
				<input type="hidden" id="commentpid" value="0"/>
				<input id="commentname" type="text" value="{NAME}" {DISABLED} class="input input-c fl" onblur="if(this.value=='')this.value='{LANG.comment_name}';" onclick="if(this.value=='{LANG.comment_name}')this.value='';"/>
				<input id="commentemail_iavim" type="text" value="{EMAIL}" {DISABLED} class="input input-c fr" onblur="if(this.value=='')this.value='{LANG.comment_email}';" onclick="if(this.value=='{LANG.comment_email}')this.value='';"/>
			</div>
			<p><textarea id="commentcontent" class="input typer box2" cols="60" rows="3" onblur="if(this.value=='')this.value='{LANG.comment_content}';" onclick="if(this.value=='{LANG.comment_content}')this.value='';">{LANG.comment_content}</textarea>
			</p>
			<p>
				<!-- BEGIN: captcha -->
				{LANG.comment_seccode}: &nbsp; <img id="vimg" alt="{N_CAPTCHA}" src="{SRC_CAPTCHA}" width="{GFX_WIDTH}" height="{GFX_HEIGHT}" /><img alt="{CAPTCHA_REFRESH}" src="{CAPTCHA_REFR_SRC}" width="16" height="16" class="refresh" onclick="nv_change_captcha('vimg','commentseccode_iavim');"/>&nbsp; <input id="commentseccode_iavim" type="text" class="input capcha" maxlength="{GFX_NUM}"/>&nbsp;
				 <!-- END: captcha -->
				<input id="reset-cm" type="reset" value="RESET" class="button-2" />
				<input id="buttoncontent" type="submit" value="{LANG.comment_submit}" onclick="sendcommment('{MODULE_COMM}', '{AREA_COMM}', '{ID_COMM}', '{ALLOWED_COMM}', '{CHECKSS_COMM}', {GFX_NUM});" class="button" />&nbsp;
			</p>
		<script type="text/javascript">
			$("#reset-cm").click(function() {
				$("#commentcontent,#commentseccode_iavim").val("");
				$("#commentpid").val(0);
			});
		</script>
		<!-- END: allowed_comm -->
		<!-- BEGIN: form_login-->
		{COMMENT_LOGIN}
		<!-- END: form_login -->
		</div>
	</div>
</div>
<!-- END: main -->