<!-- BEGIN: main -->
<!-- BEGIN: management -->
<ul class="nav nav-tabs m-bottom">
	<li><a href="{clientinfo_link}">{LANG.client_info}</a></li>
	<li><a href="{clientinfo_addads}">{LANG.client_addads}</a></li>
	<li class="active"><a href="{clientinfo_stats}">{LANG.client_stats}</a></li>
</ul>
<!-- END: management -->
<div id="clinfo">
	<div class="m-bottom">
		{LANG.stats_views_ads}
		<div class="btn-group">
			<button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
				<span id="text-ads">{LANG.stats_views_select}</span>
				<span class="caret">&nbsp;</span>
			</button>
			<ul class="dropdown-menu" id="adsstat-ads">
				<!-- BEGIN: ads -->
				<li><a href="#" rel="{ads.id}">{ads.title}</a></li>
				<!-- END: ads -->
			</ul>
		</div>
		{LANG.stats_views}
		<div class="btn-group">
			<button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
				<span id="text-type">{LANG.stats_views_select}</span>
				<span class="caret">&nbsp;</span>
			</button>
			<ul class="dropdown-menu" id="adsstat-type">
				<li><a href="#" rel="country">Country</a></li>
				<li><a href="#" rel="browser">Browser</a></li>
				<li><a href="#" rel="date">Date</a></li>
				<li><a href="#" rel="os">Operating System</a></li>
			</ul>
		</div>	
		{LANG.stats_views_month}
		<div class="btn-group">
			<button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
				<span id="text-month">{LANG.stats_views_select}</span>
				<span class="caret">&nbsp;</span>
			</button>
			<ul class="dropdown-menu" id="adsstat-month">
				<!-- BEGIN: month -->
				<li><a href="#" rel="{month}">{month}</a></li>
				<!-- END: month -->
			</ul>
		</div>	
	</div>
	<script type="text/javascript">
	var type = '', month = '', ads = '';
	$(function() {
		$('#adsstat-ads a').click(function() {
			ads = $(this).attr('rel');
			$('#text-ads').html($(this).text());
			if (type != "" && month != "" & ads != "") {
				$('#chartdata').html('<img src="{charturl}&type=' + type + '&month=' + month + '&ads=' + ads + '" style="width:100%"/>');
			}
		});
		$('#adsstat-type a').click(function() {
			type = $(this).attr('rel');
			$('#text-type').html($(this).text());
			if (type != "" && month != "" & ads != "") {
				$('#chartdata').html('<img src="{charturl}&type=' + type + '&month=' + month + '&ads=' + ads + '" style="width:100%"/>');
			}
		});
		$('#adsstat-month a').click(function() {
			month = $(this).attr('rel');
			$('#text-month').html($(this).text());
			if (type != "" && month != "" & ads != "") {
				$('#chartdata').html('<img src="{charturl}&type=' + type + '&month=' + month + '&ads=' + ads + '" style="width:100%"/>');
			}
		});
	});
	</script>
</div>
<div id="chartdata"></div>
<!-- END: main -->