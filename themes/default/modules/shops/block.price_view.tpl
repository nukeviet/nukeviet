<!-- BEGIN: main -->
<style type="text/css">
	ul.price_view, ul.price_view li {
		padding: 0;
		margin: 0;
		list-style: none;
	}

	ul.price_view {
		padding: 5px
	}

	ul.price_view li {
		background: url('{NV_BASE_SITEURL}themes/{TEMPLATE}/images/{MODULE_FILE}/access_head_bg.png') no-repeat center left;
		padding-left: 20px;
	}
</style>
<ul class="price_view">
	<!-- BEGIN: loopprice -->
	<li>
		<a href="{ROW.link}">{ROW.title}</a>
	</li>
	<!-- END: loopprice -->
</ul>
<!-- END: main -->