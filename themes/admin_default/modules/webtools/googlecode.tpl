<!-- BEGIN: NukevietChange -->
	<table class="tab1">
	    <thead>
	        <tr>
	            <td style="width:50px">
	                {LANG.nukevietChange_id}
	            </td>
	            <td>
	                {LANG.nukevietChange_content}
	            </td>
	            <td style="width:150px">
	                {LANG.nukevietChange_author}
	            </td>
	            <td style="text-align:right;width:100px">
	                {LANG.nukevietChange_updated}
	            </td>
	        </tr>
	    </thead>
		<!-- BEGIN: loop -->
	    <tbody{CLASS}>
	        <tr>
	            <td class="idinfo">
	                <a href="{ENTRY.link}" target="_blank" title="">r{ENTRY.id}</a>
	            </td>
	            <td style="vertical-align:top">
	                <div class="ninfo">{ENTRY.title}</div>
	                <div class="tooltip">
	                	<div class="tooltiptitle">{ENTRY.title}</div>
	                	{ENTRY.tooltip}
	                </div>
	            </td>
	            <td class="author">
	                {ENTRY.author}
	            </td>
	            <td class="updated">
	                {ENTRY.updated}
	            </td>
	        </tr>
	    </tbody>
	    <!-- END: loop -->
	</table>
	<div style="text-align: right;">{UPDATED}. <a id="gcodeRefresh" href="#">{REFRESH}</a> - <a href="{VISIT}" target="_blank">{LANG.nukevietChange_go}</a></div>

	<script type="text/javascript">
	//<![CDATA[
		$(document).ready(function(){$("#gcodeRefresh").click(function(){$("#NukeVietGoogleCode").html("<center><img src='{NV_BASE_SITEURL}images/load_bar.gif' alt='' /></center>").load("{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&gcode=2&num="+nv_randomPassword(10));return false});$(".ninfo").click(function(){$(".ninfo").each(function(){$(this).show()});$(".tooltip").each(function(){$(this).hide()});$(this).hide().next(".tooltip").show();return false});$(".tooltip").click(function(){$(this).hide().prev(".ninfo").show()})});
	//]]>
	</script>
<!-- END: NukevietChange -->