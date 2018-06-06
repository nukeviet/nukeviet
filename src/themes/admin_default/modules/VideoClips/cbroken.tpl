<!-- BEGIN: main -->
<div id="pageContent">
    <!-- BEGIN: loop -->
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<tbody>
	            <thead>
					<th>
						<a href="{DATA.userUrl}">{DATA.full_name}</a> (IP: {DATA.ip}). {LANG.cpubDate}: {DATA.pubDate}
	                </th>
	            </thead>
	            <tr>
	                <td>
	                    {LANG.cnumbroken}: {DATA.broken}
	                </td>
	            </tr>
	            <tr>
	                <td>
	                    {LANG.videoClip}: <a href="{DATA.clipUrl}">{DATA.title}</a>
					</td>
	            </tr>
	            <tr>
					<td><textarea style="width:98%" rows="4" name="content{DATA.id}">{DATA.content}</textarea>
					</td>
	            </tr>
	            <tr>
					<td>
						<a class="save_{DATA.id}" href="{MODURL}">{LANG.saveAndChecked}</a>
						|
						<a class="del_{DATA.id}" href="{MODURL}">{GLANG.delete}</a>
					</td>
				</tr>
				</tbody>
		</table>
	</div>
    <!-- END: loop -->
	<div id="nv_generate_page">
		{NV_GENERATE_PAGE}
	</div>
</div>
<script type="text/javascript">
$("a[class^=save_]").click(function(){var a=$(this).attr("class").split("_")[1],b=trim($("textarea[name=content"+a+"]").val()),c=$(this).attr("href");$("textarea[name=content"+a+"]").val(b);if(""==b)return alert("{LANG.error8}"),$("textarea[name=content"+a+"]").select(),!1;$.ajax({type:"POST",url:c,data:"ischecked="+a+"&content="+b,success:function(a){if("ERROR"==a)return alert("{LANG.error8}"),!1;window.location.href=window.location.href;return!1}});return!1});
$("a[class^=del_]").click(function(){var a=$(this).attr("class").split("_")[1],b=$(this).attr("href");$.ajax({type:"POST",url:b,data:"delcomm="+a,success:function(){window.location.href=window.location.href;return!1}});return!1});
</script>
<!-- END: main -->