<!-- BEGIN: main -->
<link rel="stylesheet" href="{NV_BASE_SITEURL}themes/admin_default/css/seotools_rpc.css" type="text/css" />
<div id="rpc">
	<h3>{LANG.rpc_ftitle}</h3>
	<div class="list">
		<div class="head">
			<div class="col1">
				{LANG.rpc_linkname}
			</div>
			<div class="col2">
				{LANG.rpc_reruslt}
			</div>
			<div class="col3a">
				{LANG.rpc_message}
			</div>
			<div class="clearfix"></div>
		</div>
		<!-- BEGIN: service -->
		<div class="service">
			<div class="col1">
				<!-- BEGIN: icon -->
				<img src="{NV_BASE_SITEURL}themes/admin_default/images/seotools/{SERVICE.icon}" alt="" />
				<!-- END: icon -->
				<!-- BEGIN: noticon -->
				<img src="{NV_BASE_SITEURL}themes/admin_default/images/seotools/link.png" alt="" />
				<!-- END: noticon -->
				<span>{SERVICE.title}</span>
			</div>
			<div class="col2 ld" id="res{SERVICE.id}">
				&nbsp;
			</div>
			<div class="col3" id="mes{SERVICE.id}" title=""></div>
			<div class="clearfix"></div>
		</div>
		<!-- END: service -->
	</div>
</div>
<script type="text/javascript">
//<![CDATA[
var LANG = [];
var CFG = [];
LANG.rpc_finish = '{LANG.rpc_finish}';
CFG.load_data = '{LOAD_DATA}';

$("#rpc .borderRed").removeClass("borderRed");
$("#rpc .ok").removeClass("ok");
$("#rpc .error").removeClass("error");
$("#rpc .col3").html("");
$("#rpc .col3").attr("title", "");
$("#rpc .ld").addClass("load");
sload(0);
//]]>
</script>
<!-- END: main -->