<!-- BEGIN: tree -->
   <li class="{MENUTREE.class1}"><a href="{MENUTREE.link}">{MENUTREE.title}</a>	
       <!-- BEGIN: tree_content -->
	     <ul>
	       {TREE_CONTENT}
	     </ul>
       <!-- END: tree_content -->                
   </li>
<!-- END: tree -->

<!-- BEGIN: main -->
<link rel="stylesheet" type="text/css" media="screen"	href="{NV_BASE_SITEURL}themes/{BLOCK_THEME}/css/ddlevelsmenu-base.css" />
<link rel="stylesheet" type="text/css" media="screen"	href="{NV_BASE_SITEURL}themes/{BLOCK_THEME}/css/ddlevelsmenu-topbar.css" />
<link rel="stylesheet" type="text/css" media="screen"	href="{NV_BASE_SITEURL}themes/{BLOCK_THEME}/css/ddlevelsmenu-sidebar.css" />
<script src="{NV_BASE_SITEURL}js/jquery/ddlevelsmenu.js" type="text/javascript"></script>

<div id="ddtopmenubar" class="mattblackmenu">
<ul>
<!-- BEGIN: loopcat1 -->
	<li{CAT1.current}><a title="{CAT1.note}" href="{CAT1.link}" {rel}><strong>{CAT1.title}</strong></a>
<!-- END: loopcat1 -->
</ul>
</div>

<script type="text/javascript">
ddlevelsmenu.setup("ddtopmenubar", "topbar") 
</script>
<!-- BEGIN: cat2 -->
<ul id="ddsubmenu{nu}" class="ddsubmenustyle">
	<!-- BEGIN: loopcat2 -->
	<li>
		<strong><a title="{CAT2.note}" href="{CAT2.link}">{CAT2.title}</a></strong>
		<!-- BEGIN: cat3 -->
		<ul >				
			{HTML_CONTENT}	
		</ul>
		<!-- END: cat3 -->
	</li>
	<!-- END: loopcat2 -->
</ul>
<!-- END: cat2 -->
<!-- END: main -->