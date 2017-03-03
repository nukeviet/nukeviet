<!-- BEGIN: main -->
<div id="pageContent">
	<table class="tab1">
		<col style="width:120px" />
		<thead>
			<tr>
				<td>
					{LANG.adddate}
				</td>
				<td>
					{LANG.title}
				</td>
				<td>
					{LANG.topic_parent}
				</td>
				<td style="text-align:right">
					{LANG.feature}
				</td>
			</tr>
		</thead>
		<!-- BEGIN: loop -->
		<tbody{CLASS}>
			<tr>
				<td>
					{DATA.adddate}
				</td>
				<td>
					{DATA.title}
				</td>
				<td>
					<a href="{DATA.topicUrl}">{DATA.topicname}</a>
				</td>
				<td style="text-align:right">
					<a href="{DATA.id}" title="{DATA.status}" class="changeStatus"><img style="vertical-align:middle;margin-right:10px" alt="{DATA.status}" title="{DATA.status}" src="{NV_BASE_SITEURL}themes/{NV_ADMIN_THEME}/images/{module}/{DATA.icon}.png" width="12" height="12" /></a>
					<a href="{MODULE_URL}=main&edit&id={DATA.id}">{GLANG.edit}</a>
					|
					<a class="remove" href="{DATA.id}">{LANG.Remove}</a>
					|
					<a class="del" href="{DATA.id}">{GLANG.delete}</a>
				</td>
			</tr>
			</tbody>
			<!-- END: loop -->
	</table>
	<div id="nv_generate_page">
		{NV_GENERATE_PAGE}
	</div>
</div>
<script type="text/javascript">
//<![CDATA[
$("a.del").click(function(){confirm("{LANG.delConfirm} ?")&&$.ajax({type:"POST",url:"{MODULE_URL}=main",data:"del="+$(this).attr("href"),success:function(a){"OK"==a?window.location.href=window.location.href:alert(a)}});return!1});$("a.remove").click(function(){$.ajax({type:"POST",url:"{MODULE_URL}=vbroken",data:"remove="+$(this).attr("href"),success:function(){window.location.href=window.location.href}});return!1});
$("a.changeStatus").click(function(){var a=this;$.ajax({type:"POST",url:"{MODULE_URL}=main",data:"changeStatus="+$(this).attr("href"),success:function(b){$(a).html(b)}});return!1});
//]]>
</script>
<!-- END: main -->