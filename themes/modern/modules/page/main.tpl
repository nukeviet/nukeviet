<!-- BEGIN: main -->
<div class="box-border">
	<div class="page-header">
		<h1>{CONTENT.title}</h1>
		<span class="small">{LANG.add_time}: {CONTENT.add_time}</span>
		<div class="clear"></div>
		<p class="hometext">{CONTENT.description}</p>
		<div class="image" align="center"><a rel="shadowbox" href="{CONTENT.image}"><img src="{CONTENT.image}" width="500" /></div>
	</div>
	<div class="content-box">
		<div class="content-page">
			{CONTENT.bodytext}
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