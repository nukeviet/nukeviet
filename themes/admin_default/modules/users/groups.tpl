<!-- BEGIN: add -->
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.core.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.theme.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.css" rel="stylesheet" />
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<div id="pageContent">
	<form id="addCat" method="post" action="{ACTION_URL}">
		<h3 class="myh3">{PTITLE}</h3>
		<table class="tab1">
			<colgroup>
				<col class="w300"/>
				<col />
			</colgroup>
			<tbody>
				<tr>
					<td>{LANG.title} <span style="color:red">*</span>:</td>
					<td><input title="{LANG.title}" class="txt" type="text" name="title" value="{DATA.title}" maxlength="255" /></td>
				</tr>
				<tr>
					<td>{LANG.exp_time}:</td>
					<td><input type="text" name="exp_time" class="w150 datepicker" value="{DATA.exp_time}" maxlength="10" /> &nbsp;&nbsp;&nbsp;{LANG.emptyIsUnlimited} </td>
				</tr>
				<tr>
					<td>{LANG.public}:</td>
					<td><input title="{LANG.publics}" type="checkbox" name="publics" value="1"{DATA.publics} /></td>
				</tr>
				<!-- BEGIN: siteus -->
				<tr>
					<td>{LANG.siteus}:</td>
					<td><input title="{LANG.siteus}" type="checkbox" name="siteus" value="1"{DATA.siteus} /></td>
				</tr>
				<!-- END: siteus -->
			</tbody>
		</table>
		<div>
			{LANG.content}
		</div>
		<div>
			{CONTENT}
		</div>
		<input type="hidden" name="save" value="1" />
		<p class="center"><input name="submit" type="submit" class="button" value="{LANG.save}" /></p>
	</form>
</div>
<script type="text/javascript">
	//<![CDATA[
	$(document).ready(function() {
		$(".datepicker").datepicker({
			showOn : "both",
			dateFormat : "dd/mm/yy",
			changeMonth : true,
			changeYear : true,
			showOtherMonths : true,
			buttonImage : nv_siteroot + "images/calendar.gif",
			buttonImageOnly : true
		});
	});
	$("form#addCat").submit(function() {
		var a = $("input[name=title]").val(), a = trim(a);
		$("input[name=title]").val(a);
		if (a == "") {
			return alert("{LANG.title_empty}"), $("input[name=title]").select(), false
		}
		if ( typeof (CKEDITOR) !== 'undefined') {
			$("textarea[name=content]").val(CKEDITOR.instances.users_content.getData());
		}
		var a = $(this).serialize(), b = $(this).attr("action");
		$("input[name=submit]").attr("disabled", "disabled");
		$.ajax({
			type : "POST",
			url : b,
			data : a,
			success : function(a) {
				a == "OK" ? window.location.href = "{MODULE_URL}={OP}" : (alert(a), $("input[name=submit]").removeAttr("disabled"))
			}
		});
		return false
	});
	//]]>
</script>
<!-- END: add -->
<!-- BEGIN: list -->
<table class="tab1">
	<col class="w50" />
	<col span="6"/>
	<thead>
		<tr class="center">
			<td> {LANG.weight} </td>
			<td> {LANG.title} </td>
			<td> {LANG.add_time} </td>
			<td> {LANG.exp_time} </td>
			<td> {LANG.users} </td>
			<td> {GLANG.active} </td>
			<td> {GLANG.actions} </td>
		</tr>
	</thead>
	<tbody>
		<!-- BEGIN: loop -->
		<tr class="center">
			<td>
			<select name="w_{GROUP_ID}" class="newWeight">
				<!-- BEGIN: option -->
				<option value="{NEWWEIGHT.value}"{NEWWEIGHT.selected}>{NEWWEIGHT.value}</option>
				<!-- END: option -->
			</select></td>
			<td class="left"><a title="{LANG.users}" href="{MODULE_URL}={OP}&userlist={GROUP_ID}">{LOOP.title}</a></td>
			<td>{LOOP.add_time}</td>
			<td>{LOOP.exp_time}</td>
			<td>{LOOP.number}</td>
			<td><input name="a_{GROUP_ID}" type="checkbox" class="act" value="1"{LOOP.act} /></td>
			<td>
			<!-- BEGIN: action -->
			<em class="icon-edit icon-large">&nbsp;</em> <a href="{MODULE_URL}={OP}&edit&id={GROUP_ID}">{GLANG.edit}</a> &nbsp;
			<em class="icon-trash icon-large">&nbsp;</em> <a class="del" href="{GROUP_ID}">{GLANG.delete}</a>
			<!-- END: action -->
			</td>
		</tr>
		<!-- END: loop -->
	</tbody>
</table>
<!-- BEGIN: action_js -->
<script type="text/javascript">
	//<![CDATA[
	$("a.del").click(function() {
		confirm("{LANG.delConfirm} ?") && $.ajax({
			type : "POST",
			url : "{MODULE_URL}={OP}",
			data : "del=" + $(this).attr("href"),
			success : function(a) {
				a == "OK" ? window.location.href = window.location.href : alert(a)
			}
		});
		return false
	});
	$("select.newWeight").change(function() {
		var a = $(this).attr("name").split("_"), b = $(this).val(), c = this, a = a[1];
		$("#pageContent input, #pageContent select").attr("disabled", "disabled");
		$.ajax({
			type : "POST",
			url : "{MODULE_URL}={OP}",
			data : "cWeight=" + b + "&id=" + a,
			success : function(a) {
				a == "OK" ? $("div#pageContent").load("{MODULE_URL}={OP}&list&random=" + nv_randomPassword(10)) : alert("{LANG.errorChangeWeight}");
				$("#pageContent input, #pageContent select").removeAttr("disabled")
			}
		});
		return false
	});

	$("input.act").change(function() {
		var a = $(this).attr("name").split("_"), a = a[1], b = this;
		$("#pageContent input, #pageContent select").attr("disabled", "disabled");
		$.ajax({
			type : "POST",
			url : "{MODULE_URL}={OP}",
			data : "act=" + a + "&rand=" + nv_randomPassword(10),
			success : function(a) {
				a = a.split("|");
				$("#pageContent input, #pageContent select").removeAttr("disabled");
				a[0] == "ERROR" && (a[1] == "1" ? $(b).prop("checked", true) : $(b).prop("checked", false));

			}
		});
		return false
	});
	//]]>
</script>
<!-- END: action_js -->
<!-- END: list -->
<!-- BEGIN: main -->
<!-- BEGIN: addnew -->
<div id="ablist">
	<input name="addNew" type="button" value="{LANG.nv_admin_add}" />
</div>
<!-- END: addnew -->
<div class="myh3">
	{GLANG.mod_groups}
</div>
<div id="pageContent"></div>
<script type="text/javascript">
	//<![CDATA[
	$(function() {
		$("div#pageContent").load("{MODULE_URL}={OP}&list&random=" + nv_randomPassword(10))
	});
	$("input[name=addNew]").click(function() {
		window.location.href = "{MODULE_URL}={OP}&add";
		return false
	});
	//]]>
</script>
<!-- END: main -->
<!-- BEGIN: listUsers -->
<h3 class="myh3">{PTITLE}</h3>
<!-- BEGIN: ifExists -->
<table class="tab1">
	<col class="w50"/>
	<col span="4" />
	<thead>
		<tr>
			<td> {LANG.userid} </td>
			<td> {LANG.account} </td>
			<td> {LANG.name} </td>
			<td> {LANG.email} </td>
			<td> {GLANG.actions} </td>
		</tr>
	</thead>
	<tbody>
		<!-- BEGIN: loop -->
		<tr>
			<td> {LOOP.userid} </td>
			<td><a title="{LANG.detail}" href="{MODULE_URL}=edit&userid={LOOP.userid}">{LOOP.username}</a></td>
			<td>{LOOP.full_name}</td>
			<td><a href="mailto:{LOOP.email}">{LOOP.email}</a></td>
			<td>
			<!-- BEGIN: delete -->
			<em class="icon-trash icon-large">&nbsp;</em> <a class="delete" href="javascript:void(0);" title="{LOOP.userid}">{LANG.exclude_user2}</a>
			<!-- END: delete -->
			</td>
		</tr>
		<!-- END: loop -->
	</tbody>
</table>
<script type="text/javascript">
	//<![CDATA[
	$("a.delete").click(function() {
		$.ajax({
			type : "POST",
			url : "{MODULE_URL}={OP}",
			data : "gid={GID}&exclude=" + $(this).attr("title"),
			success : function(a) {
				a == "OK" ? $("div#pageContent").load("{MODULE_URL}={OP}&listUsers={GID}&random=" + nv_randomPassword(10)) : alert(a)
			}
		});
		return false
	});
	//]]>
</script>
<!-- END: ifExists -->
<!-- END: listUsers -->
<!-- BEGIN: userlist -->
<!-- BEGIN: adduser -->
<div id="ablist">
	{LANG.search_id}: <input title="{LANG.search_id}" class="txt" type="text" name="uid" id="uid" value="" maxlength="11" style="width:50px" />
	<input name="searchUser" type="button" value="{GLANG.search}" />
	<input name="addUser" type="button" value="{LANG.addMemberToGroup}" />
</div>
<!-- END: adduser -->
<div id="pageContent"></div>
<script type="text/javascript">
	//<![CDATA[
	$(function() {
		$("div#pageContent").load("{MODULE_URL}={OP}&listUsers={GID}&random=" + nv_randomPassword(10))
	});
	$("input[name=searchUser]").click(function() {
		var a = $(this).prev().attr("id");
		nv_open_browse_file("{MODULE_URL}=getuserid&area=" + a + "&filtersql={FILTERSQL}", "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
		return false
	});
	$("input[name=addUser]").click(function() {
		var a = $("#ablist input[name=uid]").val(), a = intval(a);
		a == 0 && ( a = "");
		$("#ablist input[name=uid]").val(a);
		if (a == "") {
			return alert("{LANG.choiceUserID}"), $("#ablist input[name=uid]").select(), false
		}
		$("#pageContent input, #pageContent select").attr("disabled", "disabled");
		$.ajax({
			type : "POST",
			url : "{MODULE_URL}={OP}",
			data : "gid={GID}&uid=" + a + "&rand=" + nv_randomPassword(10),
			success : function(a) {
				a == "OK" ? ($("#ablist input[name=uid]").val(""), $("div#pageContent").load("{MODULE_URL}={OP}&listUsers={GID}&random=" + nv_randomPassword(10))) : alert(a)
			}
		});
		return false
	});
	//]]>
</script>
<!-- END: userlist -->