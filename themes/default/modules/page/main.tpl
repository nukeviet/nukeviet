<!-- BEGIN: main -->
<div class="page">
	<!-- BEGIN: facebookjssdk -->
	<div id="fb-root"></div>
	<script type="text/javascript">
	(function(d, s, id) {
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) return;
		js = d.createElement(s); js.id = id;
		js.src = "//connect.facebook.net/{FACEBOOK_LANG}/all.js#xfbml=1&appId={FACEBOOK_APPID}";
		fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));
	</script>
	<!-- END: facebookjssdk -->
	<h1>{CONTENT.title}</h1>
	<!-- BEGIN: socialbutton -->
	<div class="well well-sm">
		<ul class="nv-social-share">
			<!-- BEGIN: facebook -->
			<li class="facebook">
				<div class="fb-like" data-href="{SELFURL}" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true">&nbsp;</div>
			</li>
			<!-- END: facebook -->
			<li>
				<div class="g-plusone" data-size="medium"></div>
				<script type="text/javascript">
				  window.___gcfg = {lang: nv_sitelang};
				  (function() {
					var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
					po.src = 'https://apis.google.com/js/plusone.js';
					var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
				  })();
				</script>
			</li>
			<li>
				<a href="http://twitter.com/share" class="twitter-share-button">Tweet</a>
				<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
			</li>
		</ul>
	</div>
	<!-- END: socialbutton -->
	<p style="font-weight: bold;text-align: justify;font-style: italic;">{CONTENT.description}</p>
	<!-- BEGIN: image -->
	<div class="image text-center">
		<a rel="shadowbox" href="{CONTENT.image}"><img src="{CONTENT.image}" class="img-thumbnail" /></a>
		<em style="display: block;margin: 10px 0 10px 0;">{CONTENT.imagealt}</em>
	</div>
	<!-- END: image -->
	<div class="bodytext">
		{CONTENT.bodytext}
	</div>
	<div class="clear">
		<!-- BEGIN: comment -->
		<iframe src="{NV_COMM_URL}" id = "fcomment" onload = "nv_setIframeHeight( this.id )" style="width: 100%; min-height: 300px; max-height: 1000px"></iframe>
		<!-- END: comment -->
	</div>
	<!-- BEGIN: other -->
	<hr />
	<ul class="nv-list-item">
		<!-- BEGIN: loop -->
		<li><em class="fa fa-angle-double-right">&nbsp;</em> <a title="{OTHER.title}" href="{OTHER.link}">{OTHER.title}</a></li>
		<!-- END: loop -->
	</ul>
	<!-- END: other -->
	<script type="text/javascript">
		$(document).ready(function() {
			$(".bodytext img").toggleClass('img-thumbnail');
		});
	</script>
	</div>
<!-- END: main -->