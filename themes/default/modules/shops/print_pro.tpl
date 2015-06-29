<!-- BEGIN: main -->
<style type="text/css">
body {
	font-size: 12px;
	background: #fff;
}
#footer{
	border-top: 1px solid red;
	padding-top: 5px;
}
</style>

<div id="print">

	<ul class="list-inline pull-right hidden-print">
		<li>
			<button class="btn btn-primary btn-xs" onclick="window.print()"><em class="fa fa-print">&nbsp;</em>{LANG.print_page}</button>
		</li>
		<li>
			<button class="btn btn-danger btn-xs" onclick="window.close()"><em class="fa fa-minus-circle">&nbsp;</em>{LANG.print_close}</button>
		</li>
	</ul>
	
	<div class="clear"></div>
	
	<div class="content">
		<img src="{SRC_PRO}" alt="" width="{width}" style="float: left; margin-right: 10px;" align="center">
		<span><strong>{TITLE}</strong></span>
		<br />
		<span>{DATE_UP}- {NUM_VIEW} {LANG.detail_num_view} </span>
		<br />
		<!-- BEGIN: price -->
		<span>{LANG.detail_pro_price}: </span>
		<span><strong>{PRICE.sale_format}</strong> {money_unit}</span>
		<!-- END: price -->
		<br>
		{DETAIL}
	</div>
	
	<div class="clear"></div>
	
	<div id="footer">
		<div id="contact" class="pull-right">
			<a href="mailto:{CONTENT.contact}">{contact}</a>
		</div>
		<div class="pull-left">
			&copy; {site_name}
		</div>
	</div>
</div>
<!-- END: main -->