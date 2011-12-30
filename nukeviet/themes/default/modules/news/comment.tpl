<!-- BEGIN: main -->
	<!-- BEGIN: detail -->
		<div class="commentdetail {COMMENT.bg}">
		<div class="name">{COMMENT.post_name}
		<!-- BEGIN: emailcomm --> - <a	title="mailto {COMMENT.post_email}" href="mailto:{COMMENT.post_email}">
		<span class="email">{COMMENT.post_email}</span></a>
		<!-- END: emailcomm --> -
		<span class="time">{COMMENT.post_time}</span></div>
		<div>{COMMENT.content}</div>
		</div>
	<!-- END: detail -->
	<div class="page">{PAGE}</div>
<!-- END: main -->