<!-- BEGIN: main -->
<ul class="media-list comment-list">
	<!-- BEGIN: detail -->
	<li class="media">
		<a class="pull-left" href="#">
			<img class="media-object" src="{COMMENT.photo}" alt="{COMMENT.post_name}" width="60"/>
		</a>
		<div class="media-body">
			<div class="comment-info">
				<em class="fa fa-user">&nbsp;</em> <strong class="item">{COMMENT.post_name} </strong>
				<!-- BEGIN: emailcomm -->
			 	<em class="fa fa-envelope-o">&nbsp;</em> <a class="item" title="mailto {COMMENT.post_email}" href="mailto:{COMMENT.post_email}">{COMMENT.post_email}</a>
				<!-- END: emailcomm -->
				<em class="fa fa-clock-o">&nbsp;</em> <span class="small">{LANG.pubtime} {COMMENT.post_time}</span>
			</div>
			<p>{COMMENT.content}</p>
			<div class="text-right">
				<ul class="comment-tool">
					<!-- BEGIN: delete --><li><em class="fa fa-trash-o fa-lg">&nbsp;</em> <a href="#" onclick="nv_delete({COMMENT.cid}, '{COMMENT.check_like}')">{LANG.delete}</a></li><!-- END: delete -->
					<li><em class="fa fa-reply">&nbsp;</em> <a href="#" onclick="nv_feedback({COMMENT.cid}, '{COMMENT.post_name}')">{LANG.feedback}</a></li>
					<li><em class="fa fa-thumbs-o-up">&nbsp;</em> <a href="#" onclick="nv_like({COMMENT.cid}, '{COMMENT.check_like}', '1')">{LANG.like}</a> <span id="like{COMMENT.cid}">{COMMENT.likes}</span></li>
					<li><em class="fa fa-thumbs-o-down">&nbsp;</em> <a href="#" onclick="nv_like({COMMENT.cid}, '{COMMENT.check_like}', '-1')">{LANG.dislike}</a> <span id="dislike{COMMENT.cid}">{COMMENT.dislikes}</span></li>
				</ul>
			</div>
		</div>		
	</li>
	<!-- END: detail -->
</ul>
<div class="text-center">
	{PAGE}
</div>
<!-- END: main -->