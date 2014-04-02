<!-- BEGIN: main -->
<style type="text/css">
	body{background: #fff;}
</style>
<div id="print">
	<div id="header" class="clearfix">
		<h2>{CONTENT.sitename}</h2>
		<p>
			<a title="{CONTENT.sitename}" href="{CONTENT.url}/">{CONTENT.url}</a>
		</p>
	</div>
	<div class="clear"></div>
	<div id="content">
		<h1>{CONTENT.title}</h1>
		<ul class="control">
			<li class="time">
				{CONTENT.time}
			</li>
			<li>
				|<a title="{LANG.print}" href="javascript:;" onclick="window.print()">{LANG.print}</a>
			</li>
			<li>
				|<a title="{LANG.print_close}" href="javascript:;" onclick="window.close()">{LANG.print_close}</a>
			</li>
		</ul>
		<div class="clear"></div>
		<div id="hometext">
			<!-- BEGIN: image -->
			<div id="imghome" class="fl">
				<img alt="{CONTENT.image.alt}" src="{CONTENT.image.src}" width="{CONTENT.image.width}" />
				<!-- BEGIN: note -->
				<p>
					<em>{CONTENT.image.note}</em>
				</p>
				<!-- END: note -->
			</div>
			<!-- END: image -->
			{CONTENT.hometext}
		</div>
		<!-- BEGIN: imagefull -->
		<div id="imghome">
			<img alt="{CONTENT.image.alt}" src="{CONTENT.image.src}" width="{CONTENT.image.width}" />
			<!-- BEGIN: note -->
			<p>
				<em>{CONTENT.image.note}</em>
			</p>
			<!-- END: note -->
		</div>
		<div class="clear"></div>
		<!-- END: imagefull -->
		<div id="bodytext" class="clearfix">
			{CONTENT.bodytext}
		</div>
		<!-- BEGIN: author -->
		<div id="author">
			<!-- BEGIN: name -->
			<p>
				<strong>{LANG.author}:</strong>
				{CONTENT.author}
			</p>
			<!-- END: name -->
			<!-- BEGIN: source -->
			<p>
				<strong>{LANG.source}:</strong>
				{CONTENT.source}
			</p>
			<!-- END: source -->
		</div>
		<!-- END: author -->
		<!-- BEGIN: copyright -->
		<div class="copyright">
			{CONTENT.copyvalue}
		</div>
		<!-- END: copyright -->
	</div>
	<div id="footer">
		<div id="url">
			<strong>{LANG.print_link}: </strong>{CONTENT.link}
		</div>
		<div class="clear"></div>
		<div class="copyright">
			&copy; {CONTENT.sitename}
		</div>
		<div id="contact">
			<a href="mailto:{CONTENT.contact}">{CONTENT.contact}</a>
		</div>
	</div>
</div>
<!-- END: main-->