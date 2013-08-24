<!-- BEGIN: main -->
<style type="text/css">
	.other_blocknews {
		padding: 5px;
	}

	.other_blocknews ul {
	}

	.other_blocknews ul li {
		background: #FFFFFF;
		padding: 5px;
	}

	.other_blocknews ul li a {
	}

	.other_blocknews ul li img {
		padding: 2px;
		border: 1px solid #F7F7F7;
		width: 60px;
		float: left;
		margin-right: 4px;
	}

	.other_blocknews ul li.bg {
		background: #F3F3F3;
	}
</style>
<div class="other_blocknews">
	<ul>
		<!-- BEGIN: loop -->
		<li class="clearfix {bg}">
			<!-- BEGIN: img -->
			<a href="{ROW.link}" title="{ROW.title}"><img src="{ROW.thumb}" alt=""/></a>
			<!-- END: img -->
			<a href="{ROW.link}" title="{ROW.title}">{ROW.title}</a>
		</li>
		<!-- END: loop -->
	</ul>
</div>
<!-- END: main -->