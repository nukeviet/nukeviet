<!-- BEGIN: main -->
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
<div class="box-border">
	<div class="page-header">
		<h1>{CONTENT.title}</h1>
		<span class="small">{LANG.add_time}: {CONTENT.add_time}</span>
		<!-- BEGIN: socialbutton -->
			<div class="fb-like" data-href="{SELFURL}" data-width="The pixel width of the plugin" data-height="The pixel height of the plugin" data-colorscheme="light" data-layout="button_count" data-action="like" data-show-faces="true" data-send="false">&nbsp;</div>
			<div class="g-plusone" data-size="medium"></div>
			<script type="text/javascript">
			  window.___gcfg = {lang: nv_sitelang};
			  (function() {
			    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
			    po.src = 'https://apis.google.com/js/plusone.js';
			    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
			  })();
			</script>

			<a href="http://twitter.com/share" class="twitter-share-button">Tweet</a>
			<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
		<!-- END: socialbutton -->
		<div class="clear"></div>
		<p class="hometext">{CONTENT.description}</p>
		<!-- BEGIN: image -->
		<div class="image" align="center">
			<a rel="shadowbox" href="{CONTENT.image}"><img src="{CONTENT.image}" width="500" /></a>
		</div>
		<!-- END: image -->
	</div>
	<div class="content-box">
		<div class="content-page">
			{CONTENT.bodytext}
		</div>
		<div class="content-page">
			<!-- BEGIN: comment -->
			<iframe src="{NV_COMM_URL}" id = "fcomment" onload = "nv_setIframeHeight( this.id )" style="width: 100%; min-height: 300px; max-height: 1000px"></iframe>
			<!-- END: comment -->
		</div>
		<!-- BEGIN: other -->
		<div class="other-news" style="border-top: 1px solid #d8d8d8;">
			<ul style="margin:10px;">
				<!-- BEGIN: loop -->
				<li>
					<a title="{OTHER.title}" href="{OTHER.link}">{OTHER.title}</a>
				</li>
				<!-- END: loop -->
			</ul>
		</div>
		<!-- END: other -->
	</div>
</div>
<!-- END: main -->