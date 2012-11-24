<!-- BEGIN: tree -->
<li><a title="{MENUTREE.note}" href="{MENUTREE.link}" {cla}{MENUTREE.target}>{MENUTREE.title}</a>	
	<!-- BEGIN: tree_content -->
	<ul>
		{TREE_CONTENT}
	</ul><!-- END: tree_content -->                
</li>
<!-- END: tree -->
<!-- BEGIN: main -->
<link rel="stylesheet" type="text/css" media="screen" href="{NV_BASE_SITEURL}themes/{BLOCK_THEME}/css/pro_dropdown_2.css" />
<script	type="text/javascript" href="{NV_BASE_SITEURL}themes/{BLOCK_THEME}/js/stuHover.js"></script>
<ul id="nav">
	<!-- BEGIN: loopcat1 -->
	<li{CAT1.class}><a title="{CAT1.note}" href="{CAT1.link}" class="top_link"{CAT1.target}><span {down}>{CAT1.title}</span></a>
		<!-- BEGIN: cat2 -->
		<ul class="sub">
			<!-- BEGIN: loopcat2 -->
				<li> <a title="{CAT2.note}" href="{CAT2.link}" {cla}{CAT2.target}>{CAT2.title}</a>
				<!-- BEGIN: cat3 -->
				<ul>			
					{HTML_CONTENT}	
				</ul><!-- END: cat3 -->	
			</li><!-- END: loopcat2 -->
		</ul><!-- END: cat2 -->				
	</li><!-- END: loopcat1 -->
</ul>
<div class="clear"></div>
<!-- END: main -->