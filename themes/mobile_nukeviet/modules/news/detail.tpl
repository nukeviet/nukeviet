<!-- BEGIN: main -->
<h1>{DETAIL.title}</h1>
<span class="smll">{DETAIL.publtime}</span>
<!-- BEGIN: allowed_send -->
<a rel="nofollow" href="javascript:void(0);" onclick="nv_open_browse('{URL_SENDMAIL}','{TITLE}',500,400,'resizable=no,scrollbars=no,toolbar=no,location=no,status=no');return false"><img src="{IMG_DIR}email.png"/></a>
<!-- END: allowed_send -->
<!-- BEGIN: allowed_print -->
<a href="javascript: void(0)" onclick="nv_open_browse('{URL_PRINT}','{TITLE}',840,500,'resizable=no,scrollbars=yes,toolbar=no,location=no,status=no');return false"><img src="{IMG_DIR}print.png"/></a>
<!-- END: allowed_print -->
<!-- BEGIN: allowed_save -->
<a class="savefile" href="{URL_SAVEFILE}"><img src="{IMG_DIR}save.png"/></a>
<!-- END: allowed_save -->
<div class="clear"></div>
<!-- BEGIN: showhometext -->
<!-- BEGIN: imgthumb -->
<div class="fl" style="width:{DETAIL.image.width}px;margin-right:8px">
	<a href="{DETAIL.homeimgfile}"><img src="{DETAIL.image.src}" width="{DETAIL.image.width}"/></a>
	<p>
		<em>{DETAIL.image.note}</em>
	</p>
</div>
<!-- END: imgthumb -->
{DETAIL.hometext}
<!-- BEGIN: imgfull -->
<div class="ac">
	<img src="{DETAIL.image.src}" width="{DETAIL.image.width}"/>
	<p>
		<em>{DETAIL.image.note}</em>
	</p>
</div>
<!-- END: imgfull -->
<!-- END: showhometext -->
<div class="fiximg">
	{DETAIL.bodytext}
</div>
<!-- BEGIN: author -->
<!-- BEGIN: name -->
<p class="ar">
	<strong>{LANG.author}: </strong>{DETAIL.author}
</p>
<!-- END: name -->
<!-- BEGIN: source -->
<p class="ar">
	<strong>{LANG.source}: </strong>{DETAIL.source}
</p>
<!-- END: source -->
<!-- END: author -->
<!-- BEGIN: copyright -->
<div class="hr"></div>
{COPYRIGHT}<div class="hr"></div>
<!-- END: copyright -->
<!-- BEGIN: adminlink -->
<p class="ar">
	{ADMINLINK}
</p>
<!-- END: adminlink -->
<!-- BEGIN: allowed_rating -->
<div class="hr"></div>
<form id="form3B" action="">
	<div id="stringrating">
		{STRINGRATING}
	</div>
	<div>
		<input class="hover-star" type="radio" value="1" title="{LANGSTAR.verypoor}" /><input class="hover-star" type="radio" value="2" title="{LANGSTAR.poor}" /><input class="hover-star" type="radio" value="3" title="{LANGSTAR.ok}" /><input class="hover-star" type="radio" value="4" title="{LANGSTAR.good}" /><input class="hover-star" type="radio" value="5" title="{LANGSTAR.verygood}" /><span id="hover-test" style="margin: 0 0 0 20px;">{LANGSTAR.note}</span>
	</div>
</form>
<script type="text/javascript">
	var sr = 0;
	$('.hover-star').rating({
		focus : function(v, l) {
			var tip = $('#hover-test');
			if (sr != 2) {
				tip[0].data = tip[0].data || tip.html();
				tip.html(l.title || 'value: ' + v);
				sr = 1;
			}
		},
		blur : function(v, l) {
			var tip = $('#hover-test');
			if (sr != 2) {
				$('#hover-test').html(tip[0].data || '');
				sr = 1;
			}
		},
		callback : function(v, l) {
			if (sr == 1) {
				sr = 2;
				$('.hover-star').rating('disable');
				sendrating('{NEWSID}', v, '{NEWSCHECKSS}');
			}
		}
	});
	$('.hover-star').rating('select', '{NUMBERRATING}');
</script>
<!-- BEGIN: disablerating -->
<script type="text/javascript">
	$(".hover-star").rating('disable');
	sr = 2;
</script>
<!-- END: disablerating -->
<!-- END: allowed_rating -->
<!-- BEGIN: keywords -->
<div class="hr"></div>
{LANG.keywords}:
<!-- BEGIN: loop -->
<a href="{LINK_KEYWORDS}"><em>{KEYWORD}</em></a>{SLASH}
<!-- END: loop -->
<!-- END: keywords -->
<!-- BEGIN: comment -->
<iframe src="{NV_COMM_URL}" id = "fcomment" onload = "nv_setIframeHeight( this.id )" style="width: 100%; min-height: 300px; max-height: 1000px"></iframe>
<!-- END: comment -->
<!-- BEGIN: topic -->
<h2>{LANG.topic}</h2>
<ul class="list">
	<!-- BEGIN: loop -->
	<li>
		<a href="{TOPIC.link}">{TOPIC.title}</a>
		<span class="smll">({TOPIC.time})</span>
		<!-- BEGIN: newday -->
		<span class="icon_new"></span>
		<!-- END: newday -->
	</li>
	<!-- END: loop -->
</ul>
<p class="ar">
	<a href="{TOPIC.topiclink}">{LANG.more}</a>
</p>
<!-- END: topic -->
<!-- BEGIN: related_new -->
<h2>{LANG.related_new}</h2>
<ul class="list">
	<!-- BEGIN: loop -->
	<li>
		<a title="{RELATED_NEW.title}" href="{RELATED_NEW.link}">{RELATED_NEW.title}</a>
		<span class="smll">({RELATED_NEW.time})</span>
		<!-- BEGIN: newday -->
		<span class="icon_new"></span>
		<!-- END: newday -->
	</li>
	<!-- END: loop -->
</ul>
<!-- END: related_new -->
<!-- BEGIN: related -->
<h2>{LANG.related}</h2>
<ul class="list">
	<!-- BEGIN: loop -->
	<li>
		<a href="{RELATED.link}">{RELATED.title}</a>
		<span class="smll">({RELATED.time})</span>
		<!-- BEGIN: newday -->
		<span class="icon_new"></span>
		<!-- END: newday -->
	</li>
	<!-- END: loop -->
</ul>
<!-- END: related -->
<!-- END: main -->
<!-- BEGIN: no_permission -->
<p class="ac">
	{NO_PERMISSION}
</p>
<!-- END: no_permission -->