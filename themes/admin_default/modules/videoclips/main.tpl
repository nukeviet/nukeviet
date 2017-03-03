<!-- BEGIN: main -->
<div id="ablist">
	<select name="tList">
		<option value="">
			{LANG.topicselect}
		</option>
		<!-- BEGIN: psopt4 -->
		<option value="{OPTION4.id}">
			{OPTION4.name}
		</option>
		<!-- END: psopt4 -->
	</select>
	<input style="margin-right:50px" name="ok2" type="button" value="OK" />
	<input name="addNew" type="button" value="{LANG.addClip}" />
</div>
<div class="myh3">
	{PTITLE}
</div>
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
					<a href="{MODULE_URL}=main&tid={DATA.tid}">{DATA.topicname}</a>
				</td>
				<td style="text-align:right">
					<a href="{DATA.id}" title="{DATA.status}" class="changeStatus"><img style="vertical-align:middle;margin-right:10px" alt="{DATA.status}" title="{DATA.status}" src="{NV_BASE_SITEURL}themes/{NV_ADMIN_THEME}/images/{module}/{DATA.icon}.png" width="12" height="12" /></a>
					<a href="{MODULE_URL}=main&edit&id={DATA.id}">{GLANG.edit}</a>
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
$("a.del").click(function(){confirm("{LANG.delConfirm} ?")&&$.ajax({type:"POST",url:"{MODULE_URL}",data:"del="+$(this).attr("href"),success:function(a){"OK"==a?window.location.href=window.location.href:alert(a)}});return!1});$("input[name=addNew]").click(function(){window.location.href="{MODULE_URL}&add";return!1});$("a.changeStatus").click(function(){var a=this;$.ajax({type:"POST",url:"{MODULE_URL}",data:"changeStatus="+$(this).attr("href"),success:function(b){$(a).html(b)}});return!1});
$("input[name=ok2]").click(function(){var a=$("select[name=tList]").val();window.location.href=""!=a?"{MODULE_URL}=main&tid="+a[0]:"{MODULE_URL}=main";return!1});
//]]>
</script>
<!-- END: main -->
<!-- BEGIN: add -->
<h3 class="myh3">
	{INFO_TITLE}
</h3>
<div class="red">
	{ERROR_INFO}
</div>
<form id="addInformation" method="post" action="{POST.action}">
	<table class="tab1">
		<col style="width:220px" />
		<tbody class="second">
			<tr>
				<td>
					{LANG.title}
					<span style="color:red">
						*
					</span>
				</td>
				<td>
					<input title="{LANG.title}" type="text" name="title" value="{POST.title}" style="width:400px" maxlength="255" />
				</td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td>
					{LANG.topic_parent}
				</td>
				<td>
					<select name="tid">
						<!-- BEGIN: option3 -->
						<option value="{OPTION3.value}"{OPTION3.selected}>
							{OPTION3.name}
						</option>
						<!-- END: option3 -->
					</select>
				</td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td>
					{LANG.internalpath}
				</td>
				<td>
					<input title="{LANG.internalpath}" type="text" name="internalpath" id="internalpath" value="{POST.internalpath}" style="width:280px" maxlength="255" />
					<input type="button" value="Browse server" class="selectfile" />
				</td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td>
					{LANG.externalpath}
				</td>
				<td>
					<input title="{LANG.externalpath}" type="text" name="externalpath" value="{POST.externalpath}" style="width:400px" maxlength="255" />
				</td>
			</tr>
		</tbody>
        <tbody class="second">
            <tr>
                <td style="vertical-align:top">
                    {LANG.who_view}
                </td>
                <td>
                    <select name="who_view">
                        <!-- BEGIN: who_view -->
                        <option value="{WHO_VIEW.key}"{WHO_VIEW.selected}>{WHO_VIEW.title}</option>
                        <!-- END: who_view -->
                    </select>
                    <!-- BEGIN: group_view_empty -->
                    <br />
                    {LANG.group_view}<br />
                        <!-- BEGIN: groups_view -->
                        <input name="groups_view[]" value="{GROUPS_VIEW.key}" type="checkbox"{GROUPS_VIEW.checked} /> {GROUPS_VIEW.title}<br />
                        <!-- END: groups_view -->
                    <!-- END: group_view_empty -->
                </td>
            </tr>
        </tbody>
        <tbody>
			<tr>
				<td>
					{LANG.commAllow}
				</td>
				<td>
					<input name="comm" type="checkbox"{POST.comm} value="1" />
				</td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td>
					{LANG.homeImg}
				</td>
				<td>
					<input title="{LANG.homeImg}" type="text" name="img" id="img" value="{POST.img}" style="width:280px" maxlength="255" />
					<input type="button" value="Browse server" class="selectimg" />
				</td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td>
					{LANG.hometext}
                    <span style="color:red">
						*
					</span>
				</td>
				<td>
					<textarea title="{LANG.hometext}" name="hometext" style="width:400px;height:100px">{POST.hometext}</textarea>
				</td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td>
					{LANG.keywords}
				</td>
				<td>
					<input title="{LANG.keywords}" type="text" name="keywords" value="{POST.keywords}" style="width:400px" maxlength="255" />
				</td>
			</tr>
		</tbody>
	</table>
	<div>
		{LANG.bodytext}
	</div>
	<div>
		{CONTENT}
	</div>
    <input name="redirect" type="hidden" value="{POST.redirect}" />
	<input name="submit" type="submit" value="{LANG.save}" />
</form>
<script type="text/javascript">
//<![CDATA[
$("input.selectfile").click(function(){var a=$(this).prev().attr("id");nv_open_browse_file(script_name+"?"+nv_name_variable+"=upload&popup=1&area="+a+"&path={UPLOAD_CURRENT}/video&type=all&currentpath={UPLOAD_CURRENT}/video","NVImg","850","420","resizable=no,scrollbars=no,toolbar=no,location=no,status=no");return!1});
$("input.selectimg").click(function(){var a=$(this).prev().attr("id");nv_open_browse_file(script_name+"?"+nv_name_variable+"=upload&popup=1&area="+a+"&path={UPLOAD_CURRENT}/images&type=image&currentpath={UPLOAD_CURRENT}/images","NVImg","850","420","resizable=no,scrollbars=no,toolbar=no,location=no,status=no");return!1});
$("form#addInformation").submit(function(){var a=trim($("input[name=title]").val());$("input[name=title]").val(a);if(""==a)return alert("{LANG.error1}"),$("input[name=title]").val("").select(),!1;a=trim($("input[name=internalpath]").val());$("input[name=internalpath]").val(a);b=trim($("input[name=externalpath]").val());$("input[name=externalpath]").val(b);if(""==a&&""==b)return alert("{LANG.error5}"),$("input[name=internalpath]").select(),!1;a=trim($("textarea[name=hometext]").val());$("textarea[name=hometext]").val(a);
if(""==a)return alert("{LANG.error7}"),$("textarea[name=hometext]").val("").select(),!1;$("form#addInformation").submit();return!1});
//]]>
</script>
<!-- END: add -->