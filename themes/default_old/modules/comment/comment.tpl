<!-- BEGIN: main -->
<!-- BEGIN: detail -->
<div class="box-border content-box{COMMENT.bg}">
	<div class="ava">
		<a href="#"><img src="{COMMENT.photo}" alt="Avata" class="s-border" /></a>
	</div>
	<div class="comment-content">
		<strong>{COMMENT.post_name}</strong>
		<!-- BEGIN: emailcomm -->
		- <a title="mailto {COMMENT.post_email}" href="mailto:{COMMENT.post_email}"><span class="email">{COMMENT.post_email}</span></a>
		<!-- END: emailcomm -->
		- <span class="small">{LANG.pubtime}: {COMMENT.post_time}</span>
		<br/>
		{COMMENT.content}
		<div class="content_fun">
			<!-- BEGIN: delete -->
			<div class="delete">
				<a href="javascript:;" onclick="nv_delete({COMMENT.cid}, '{COMMENT.check_like}')">{LANG.delete}</a>
			</div>
			<!-- END: delete -->
			<div class="feedback">
				<a href="javascript:;" onclick="nv_feedback({COMMENT.cid}, '{COMMENT.post_name}')">{LANG.feedback}</a>
			</div>
			<div class="like">
				<a href="javascript:;" onclick="nv_like({COMMENT.cid}, '{COMMENT.check_like}', '1')">{LANG.like}</a>: <span id="like{COMMENT.cid}">{COMMENT.likes}</span>
			</div>
			<div class="dislike">
				<a href="javascript:;" onclick="nv_like({COMMENT.cid}, '{COMMENT.check_like}', '-1')">{LANG.dislike}</a>:  <span id="dislike{COMMENT.cid}">{COMMENT.dislikes}</span>
			</div>
		</div>
	</div>
	<div class="clear"></div>
</div>
<!-- END: detail -->
<div class="page">
	{PAGE}
</div>
<!-- END: main -->