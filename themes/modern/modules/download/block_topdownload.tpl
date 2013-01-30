<!-- BEGIN: main -->
<ul class="list-down-w">
	<!-- BEGIN: loop -->
	<li>
		<span class="number">{loop.order}</span>
		<p class="item">
			<a href="{loop.link}">{loop.title}</a>
			<br/>
			<span class="small">{LANG.download_hits}: {loop.download_hits}</span>
		</p>
	</li>
	<!-- END: loop -->
</ul>
<!-- END: main -->