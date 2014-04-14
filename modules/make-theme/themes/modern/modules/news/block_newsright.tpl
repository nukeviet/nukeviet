<!-- BEGIN: main -->
<div class="box-border m-bottom">
	<div class="box-inside" id="tabs_top">
		<ul class="list-tab clearfix">
			<li>
				<a href="#topviews" class="current">{LANG.hotnews}</a>
			</li>
			<li>
				<a href="#topcomment">{LANG.lastest_comment}</a>
			</li>
		</ul>
		<div class="clear"></div>
		<div class="box-border" id="topviews">
			<div class="content-box">
				<!-- BEGIN: topviews -->
				<ul class="list-number">
					<!-- BEGIN: loop -->
					<li>
						<span class="small">{topviews.catname}</span>
						<br/>
						<a href="{topviews.link}">{topviews.title}</a>
					</li>
					<!-- END: loop -->
				</ul>
				<!-- END: topviews -->
			</div>
		</div>
		<div class="box-border" id="topcomment">
			<div class="content-box">
				<!-- BEGIN: topcomment -->
				<ul class="list-number">
					<!-- BEGIN: loop -->
					<li>
						<a title="{topcomment.content}" href="{topcomment.link}">{topcomment.content}</a>
					</li>
					<!-- END: loop -->
				</ul>
				<!-- END: topcomment -->
			</div>
		</div>
	</div>
</div>
<!-- END: main -->