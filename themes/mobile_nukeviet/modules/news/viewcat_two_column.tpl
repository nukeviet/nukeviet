<!-- BEGIN: main -->
<!-- BEGIN: catcontent -->
<!-- BEGIN: content -->
<!-- BEGIN: image -->
<a href="{NEWSTOP.link}"><img class="fl" src="{HOMEIMG0}" width="{IMGWIDTH0}" /></a>
<!-- END: image -->
<h3>
	<a href="{NEWSTOP.link}">{NEWSTOP.title}</a>
	<!-- BEGIN: newday -->
	<span class="icon_new"></span>
	<!-- END: newday -->	
</h3>
<p>
	{NEWSTOP.hometext}
</p>
<!-- BEGIN: adminlink -->
<p>
	{ADMINLINK}
</p>
<!-- END: adminlink -->
<div class="clear hr"></div>
<!-- END: content -->
<ul class="list">
	<!-- BEGIN: other -->
	<li>
		<a title="{NEWSTOP.title}" href="{NEWSTOP.link}">{NEWSTOP.title}</a>
	</li>
	<!-- END: other -->
</ul>
<br />
<!-- END: catcontent -->
<!-- BEGIN: loopcat -->
<div class="hfit">
	<div class="mr10{LAST}">
		<ul class="hd">
			<li class="cr">
				<a href="{CAT.link}">{CAT.title}</a>
			<li>
		</ul>
		<div class="clear"></div>
		<div class="cbox">
			<!-- BEGIN: content -->
			<h3>
				<a href="{CONTENT.link}">{CONTENT.title}</a>
				<!-- BEGIN: newday -->
				<span class="icon_new"></span>
				<!-- END: newday -->
			</h3>
			<!-- BEGIN: image -->
			<a href="{CONTENT.link}"><img class="fl" src="{HOMEIMG01}" width="{IMGWIDTH01}" /></a>
			<!-- END: image -->
			<p>
				{CONTENT.hometext}
			</p>
			<!-- BEGIN: adminlink -->
			<p>
				{ADMINLINK}
			</p>
			<!-- END: adminlink -->
			<div class="clear"></div>
			<!-- END: content -->
			<ul class="list">
				<!-- BEGIN: other -->
				<li>
					<a href="{OTHER.link}">{OTHER.title}</a>
				</li>
				<!-- END: other -->
			</ul>

		</div>
	</div>
</div>
<!-- BEGIN: br -->
<div class="clear"></div>
<!-- END: br -->
<!-- END: loopcat -->
<div class="clear"></div>
<!-- END: main -->