<!-- BEGIN: main -->
<div class="panel panel-info block_download">
	<div class="panel-heading">{ROW.title}</div>
	<div class="panel-body">
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
		<em class="glyphicon glyphicon-th"></em> {LANG.listing_details}
	</div>
	<div class="panel panel-default">
		<div class="row gray">
			<div class="col-md-9 col-md-push-3">{ROW.title}</div>
			<div class="col-md-3 col-md-pull-9">{LANG.file_title}:</div>
		</div>
		
		<div class="row">
			<div class="col-md-9 col-md-push-3">{ROW.version}</div>
			<div class="col-md-3 col-md-pull-9">{LANG.file_version}:</div>
		</div>
		
		<div class="row gray">
			<div class="col-md-9 col-md-push-3">{ROW.author_name}</div>
			<div class="col-md-3 col-md-pull-9">{LANG.author_name}:</div>
		</div>
		
		<div class="row">
			<div class="col-md-9 col-md-push-3">{ROW.author_url}</div>
			<div class="col-md-3 col-md-pull-9">{LANG.author_url}:</div>
		</div>
		
		<div class="row gray">
			<div class="col-md-9 col-md-push-3">{ROW.catname}</div>
			<div class="col-md-3 col-md-pull-9">{LANG.bycat2}:</div>
		</div>
		
		<div class="row">
			<div class="col-md-9 col-md-push-3">{ROW.uploadtime}</div>
			<div class="col-md-3 col-md-pull-9">{LANG.uploadtime}:</div>
		</div>

		<div class="row gray">
			<div class="col-md-9 col-md-push-3">{ROW.updatetime}</div>
			<div class="col-md-3 col-md-pull-9">{LANG.updatetime}:</div>
		</div>

		<div class="row">
			<div class="col-md-9 col-md-push-3">{ROW.user_name}</div>
			<div class="col-md-3 col-md-pull-9">{LANG.user_name}:</div>
		</div>
		
		<div class="row gray">
			<div class="col-md-9 col-md-push-3">{ROW.copyright}</div>
			<div class="col-md-3 col-md-pull-9">{LANG.copyright}:</div>
		</div>
		
		<div class="row">
			<div class="col-md-9 col-md-push-3">{ROW.filesize}</div>
			<div class="col-md-3 col-md-pull-9">{LANG.filesize}:</div>
		</div>
		
		<div class="row gray">
			<div class="col-md-9 col-md-push-3">{ROW.view_hits}</div>
			<div class="col-md-3 col-md-pull-9">{LANG.view_hits}:</div>
		</div>

		<div class="row">
			<div class="col-md-9 col-md-push-3" id="download_hits">{ROW.download_hits}</div>
			<div class="col-md-3 col-md-pull-9">{LANG.download_hits}:</div>
		</div>

		<!-- BEGIN: comment_allow -->
		<div class="row">
			<div class="col-md-9 col-md-push-3">{ROW.comment_hits}</div>
			<div class="col-md-3 col-md-pull-9">{LANG.comment_hits}:</div>
		</div>
		<!-- END: comment_allow -->
	</div>
	
	<div class="info_download">
		<!-- BEGIN: report -->
		<div class="right report">
			<a href="javascript:void(0);" onclick="nv_link_report({ROW.id});">{LANG.report}</a>
		</div>
		<!-- END: report -->
		<em class="glyphicon glyphicon-save"></em> {LANG.download_detail}
	</div>
	<!-- BEGIN: download_allow -->
		<div class="panel panel-default download">
			<div class="hidden">
				<iframe name="idown"></iframe>
			</div>
			<!-- BEGIN: fileupload -->
			<div class="panel-heading">
				{LANG.download_fileupload} {SITE_NAME}:
			</div>
			
			<div class="panel-body">
				<!-- BEGIN: row -->
					<span class="glyphicon glyphicon-paperclip"></span>&nbsp;&nbsp;<a id="myfile{FILEUPLOAD.key}" href="{FILEUPLOAD.link}" onclick="nv_download_file('idown','{FILEUPLOAD.title}');return false;">{FILEUPLOAD.title}</a>
				<!-- END: row -->
			</div>
		</div>
		<!-- END: fileupload -->
		
		<!-- BEGIN: linkdirect -->
		<div class="panel panel-default download">
			<div class="panel-heading">
				{LANG.download_linkdirect} {HOST}:
			</div>
			
			<div class="panel-body">
				<!-- BEGIN: row -->
					<span class="glyphicon glyphicon-link"></span>&nbsp;&nbsp;<a href="{LINKDIRECT.link}" onclick="nv_linkdirect('{LINKDIRECT.code}');return false;">{LINKDIRECT.name}</a>
				<!-- END: row -->
			</div>
		</div>
		<!-- END: linkdirect -->
	<!-- END: download_allow -->
	<!-- BEGIN: download_not_allow -->
	<div class="download not_allow">
		{ROW.download_info}
	</div>
	<!-- END: download_not_allow -->
	<div class="detail">
		<span class="glyphicon glyphicon-star-empty"></span>&nbsp;&nbsp;{LANG.file_rating}
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
		<div class="text-right right">
			<a href="{ROW.edit_link}">{GLANG.edit}</a> &divide; <a href="{ROW.del_link}" onclick="nv_del_row(this,{ROW.id});return false;">{GLANG.delete}</a>
		</div>
		{LANG.file_admin}:
	</div>
	<!-- END: is_admin -->
	</div>
</div>
<!-- BEGIN: comment -->
<iframe src="{NV_COMM_URL}" width="100%" height="600px;"></iframe>
<!-- END: comment -->
<!-- END: main -->