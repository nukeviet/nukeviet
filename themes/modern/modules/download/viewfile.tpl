<!-- BEGIN: main -->
<div class="header-details">
	<div class="title">
		<h1>{ROW.title}</h1>
		<span class="small"><strong>{LANG.uploadtime}:</strong> {ROW.uploadtime}, <strong>{LANG.user_name}:</strong> <span class="highlight">{ROW.user_name}</span>, <strong>{LANG.view_hits}:</strong> {ROW.view_hits}</span>
	</div>
	<div class="clear"></div>
</div>
<div class="details-content clearfix">
	<div class="gg fl">
		<!-- BEGIN: is_image -->
		<div class="acenter m-bottom">
			<a href="{FILEIMAGE.orig_src}" rel="shadowbox;height={FILEIMAGE.orig_height};width={FILEIMAGE.orig_width}"><img src="{FILEIMAGE.src}" alt="{ROW.title}" style="width:257px;height:161px" class="s-border"/></a>
			<br/>
			<span class="small">{ROW.title}</span>
		</div>
		<!-- END: is_image -->
		<!-- BEGIN: introtext -->
		{ROW.introtext}
		{ROW.description}
		<!-- END: introtext -->
		<div class="clear"></div>
		<div class="note clearfix">
			<div class="fr">
				<!-- BEGIN: report -->
				<a class="block_link" href="javascript:void(0);" onclick="nv_link_report({ROW.id});">{LANG.report}</a>
				<!-- END: report -->
			</div>
			<h4 class="download-hh">{LANG.download_detail}</h4>
			<!-- BEGIN: download_allow -->
			<div style="display:none">
				<iframe name="idown"></iframe>
			</div>
			<!-- BEGIN: fileupload -->
			<p>
				<strong>{LANG.download_fileupload} {SITE_NAME}:</strong>
			</p>
			<ul class="links_down">
				<!-- BEGIN: row -->
				<li>
					<a id="myfile{FILEUPLOAD.key}" href="{FILEUPLOAD.link}" onclick="nv_download_file('idown','{FILEUPLOAD.title}');return false;">{FILEUPLOAD.title}</a>
				</li>
				<!-- END: row -->
			</ul>
			<!-- END: fileupload -->
			<!-- BEGIN: linkdirect -->
			<p>
				<strong>{LANG.download_linkdirect} {HOST}:</strong>
			</p>
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
			$(document).ready(function() {
				$(".note").hover(function() {
					$(this).find("a.block_link").css({
						'visibility' : 'visible'
					});
				}, function() {
					$(this).find("a.block_link").css({
						'visibility' : 'hidden'
					});
				});
			});
		</script>
		<!-- BEGIN: is_admin -->
		<div class="aright content-box clearfix">
			{LANG.file_admin}:
			<a href="{ROW.edit_link}">{GLANG.edit}</a> &divide; <a href="{ROW.del_link}" onclick="nv_del_row(this,{ROW.id});return false;">{GLANG.delete}</a>
		</div>
		<!-- END: is_admin -->
	</div>
	<div class="gg2 fr">
		<ul class="spec">
			<li>
				<strong>{LANG.filesize}</strong>: {ROW.filesize}
			</li>
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
			<!-- BEGIN: comment_hits -->
			<li>
				<strong>{LANG.comment_hits}</strong>: {ROW.comment_hits}
			</li>
			<!-- END: comment_hits -->
		</ul>
		<div class="b-rate m-bottom">
			<div class="header-rate">
				{LANG.file_rating}
			</div>
			<div class="content-box box-border">
				<div class="rating clearfix">
					<div id="stringrating">
						{LANG.rating_question}
					</div>
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

	<div class="clear"></div>
</div>
<!-- BEGIN: comment -->
<iframe src="{NV_COMM_URL}" width="100%" height="600px;"></iframe>
<!-- END: comment -->
<!-- END: main -->