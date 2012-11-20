<!-- BEGIN: main -->
<script	type="text/javascript" src="{NV_BASE_SITEURL}js/jquery/ddaccordion.js"></script>
<script type="text/javascript">
ddaccordion.init({
	headerclass: "menuheaders",
	contentclass: "menucontents",
	revealtype: "clickgo",
	mouseoverdelay: 200,
	collapseprev: true,
	defaultexpanded: [0],
	onemustopen: false,
	animatedefault: false,
	persiststate: true,
	toggleclass: ["unselected", "selected"],
	togglehtml: ["none", "", ""],
	animatespeed: 500,
	oninit:function(expandedindices){
		return;
	},
	onopenclose:function(header, index, state, isuseractivated){
		return;
	}
});
</script>
<style type="text/css">
.arrowsidemenu{
	width: 188px;
	border-style: solid solid none solid;
	border-color: #94AA74;
	border-size: 1px;
	border-width: 1px;
}
.arrowsidemenu div a{
	font: bold 12px Verdana, Arial, Helvetica, sans-serif;
	display: block;
	background: transparent url({NV_BASE_SITEURL}themes/{BLOCK_THEME}/images/arrowgreen.gif) 100% 0;
 	height: 24px;
	padding: 4px 0 4px 10px;
	line-height: 24px;
	text-decoration: none;
}
.arrowsidemenu div a:link, .arrowsidemenu div a:visited{
	color: #26370A;
}
.arrowsidemenu div a:hover{
	background-position: 100% -32px;
}
.arrowsidemenu div.unselected a{
	color: #6F3700;
}
.arrowsidemenu div.selected a{
	color: blue;
	background-position: 100% -64px !important;
}
.arrowsidemenu ul{
	list-style-type: none;
	margin: 0;
	padding: 0;
}
.arrowsidemenu ul li{
	border-bottom: 1px solid #a1c67b;
}
.arrowsidemenu ul li a{
	display: block;
	font: normal 12px Verdana, Arial, Helvetica, sans-serif;
	text-decoration: none;
	color: black;
	padding: 5px 0;
	padding-left: 10px;
	border-left: 10px double #a1c67b;
}
.arrowsidemenu ul li a:hover{
	background: #d5e5c1;
}
</style>
<div class="arrowsidemenu">
	<!-- BEGIN: loopcat1 -->
	<div{menuheaders}><a href="{CAT1.link}" title="{CAT1.note}"{CAT1.target}><strong>{CAT1.title}</strong></a></div>					
	<!-- BEGIN: cat2 -->
	<ul class="menucontents">			
		<!-- BEGIN: loop2 -->
		<li><a href="{CAT2.link}" title="{CAT2.note}"{CAT2.target}>{CAT2.title}</a></li><!-- END: loop2 -->
	</ul><!-- END: cat2 -->	
	<!-- END: loopcat1 -->
</div>
<div class="clear"></div>
<!-- END: main -->