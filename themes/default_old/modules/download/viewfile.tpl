<!-- BEGIN: main -->
<div class="block_download">
	<div class="title_bar">
		{ROW.title}
	</div>
	<!-- BEGIN: is_image -->
	<div class="image" style="padding-top:8px">
		<a  title="{ROW.title}" href="{FILEIMAGE.orig_src}" rel="shadowbox;height={FILEIMAGE.orig_height};width={FILEIMAGE.orig_width}"><img alt="{ROW.title}"src="{FILEIMAGE.src}" width="{FILEIMAGE.width}" height="{FILEIMAGE.height}" /></a>
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
			<dt class="fl" style="width:35%;">
				{LANG.file_title}:
			</dt>
			<dd class="fl">
				{ROW.title}
			</dd>
		</dl>
		<dl class="info clearfix">
			<dt class="fl" style="width:35%;">
				{LANG.file_version}:
			</dt>
			<dd class="fl">
				{ROW.version}
			</dd>
		</dl>
		<dl class="info clearfix gray">
			<dt class="fl" style="width:35%;">
				{LANG.author_name}:
			</dt>
			<dd class="fl">
				{ROW.author_name}
			</dd>
		</dl>
		<dl class="info clearfix">
			<dt class="fl" style="width:35%;">
				{LANG.author_url}:
			</dt>
			<dd class="fl">
				{ROW.author_url}
			</dd>
		</dl>
		<dl class="info clearfix gray">
			<dt class="fl" style="width:35%;">
				{LANG.bycat2}:
			</dt>
			<dd class="fl">
				{ROW.catname}
			</dd>
		</dl>
		<dl class="info clearfix">
			<dt class="fl" style="width:35%;">
				{LANG.uploadtime}:
			</dt>
			<dd class="fl">
				{ROW.uploadtime}
			</dd>
		</dl>
		<dl class="info clearfix gray">
			<dt class="fl" style="width:35%;">
				{LANG.updatetime}:
			</dt>
			<dd class="fl">
				{ROW.updatetime}
			</dd>
		</dl>
		<dl class="info clearfix">
			<dt class="fl" style="width:35%;">
				{LANG.user_name}:
			</dt>
			<dd class="fl">
				{ROW.user_name}
			</dd>
		</dl>
		<dl class="info clearfix gray">
			<dt class="fl" style="width:35%;">
				{LANG.copyright}:
			</dt>
			<dd class="fl">
				{ROW.copyright}
			</dd>
		</dl>
		<dl class="info clearfix">
			<dt class="fl" style="width:35%;">
				{LANG.filesize}:
			</dt>
			<dd class="fl">
				{ROW.filesize}
			</dd>
		</dl>
		<dl class="info clearfix gray">
			<dt class="fl" style="width:35%;">
				{LANG.view_hits}:
			</dt>
			<dd class="fl">
				{ROW.view_hits}
			</dd>
		</dl>
		<dl class="info clearfix">
			<dt class="fl" style="width:35%;">
				{LANG.download_hits}:
			</dt>
			<dd class="fl">
				<div id="download_hits">
					{ROW.download_hits}
				</div>
			</dd>
		</dl>
		<!-- BEGIN: comment_allow -->
		<dl class="info clearfix gray">
			<dt class="fl" style="width:35%;">
				{LANG.comment_hits}:
			</dt>
			<dd class="fl">
				{ROW.comment_hits}
			</dd>
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
			<dt class="fl">
				<strong>{LANG.download_fileupload} {SITE_NAME}:</strong>
			</dt>
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
			<dt class="fl">
				<strong>{LANG.download_linkdirect} {HOST}:</strong>
			</dt>
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
		<div id="stringrating">
			{LANG.rating_question}
		</div>
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
			focus : function(value, link) {
				var tip = $('#hover-test');
				if (sr != 2) {
					tip[0].data = tip[0].data || tip.html();
					tip.html('{LANG.file_your_rating}: ' + link.title || 'value: ' + value);
					sr = 1;
				}
			},
			blur : function(value, link) {
				var tip = $('#hover-test');
				if (sr != 2) {
					$('#hover-test').html(tip[0].data || '');
					sr = 1;
				}
			},
			callback : function(value, link) {
				if (sr == 1) {
					sr = 2;
					$('.hover-star').rating('disable');
					nv_sendrating('{ROW.id}', value);
				}
			}
		});

		$('.hover-star').rating('select', '{ROW.rating_point}');
	</script>

	<!-- BEGIN: disablerating -->
	<script type="text/javascript">
		$(".hover-star").rating('disable');
		$('#hover-test').html('{ROW.rating_string}');
		$('#stringrating').html('{LANG.file_rating_note2}');
		sr = 2;
	</script>
	<!-- END: disablerating -->

	<!-- BEGIN: is_admin -->
	<div class="more">
		<div class="right">
			<a href="{ROW.edit_link}">{GLANG.edit}</a> &divide; <a href="{ROW.del_link}" onclick="nv_del_row(this,{ROW.id});return false;">{GLANG.delete}</a>
		</div>
		{LANG.file_admin}:
	</div>
	<!-- END: is_admin -->
</div>
<!-- BEGIN: comment -->
<iframe src="{NV_COMM_URL}" width="100%" height="600px;"></iframe>
<!-- END: comment -->
<!-- END: main -->