<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.core.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.theme.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.css" rel="stylesheet" />

<form action="{NV_BASE_ADMINURL}index.php" method="get">
	<br />
	<input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
	<input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}" />
	<label> {LANG.search_module}: </label>
	<select name="module">
		<!-- BEGIN: module -->
		<option value="{OPTION.key}" {OPTION.selected} >{OPTION.title}</option>
		<!-- END: module -->
	</select>

	<label> {LANG.search_status}: </label>

	<select name="sstatus">
		<!-- BEGIN: search_status -->
		<option value="{OPTION.key}" {OPTION.selected} >{OPTION.title}</option>
		<!-- END: search_status -->
	</select>

	<label> {LANG.search_per_page}: </label>
	<select name="per_page">
		<!-- BEGIN: per_page -->
		<option value="{OPTION.page}" {OPTION.selected} >{OPTION.page}</option>
		<!-- END: per_page -->
	</select>
	{LANG.from_date}:
	<input name="from_date" id="from_date" value="{FROM.from_date}" style="width: 90px;" maxlength="10" type="text" />
	{LANG.to_date}:
	<input name="to_date" id="to_date" value="{FROM.to_date}" style="width: 90px;" maxlength="10" type="text" />

	<br />
	<br />
	{LANG.search_key}:
	<input type="text" value="{FROM.q}" autofocus="autofocus" maxlength="64" name="q" style="width: 265px" />

	<label> {LANG.search_type}: </label>
	<select name="stype">
		<!-- BEGIN: search_type -->
		<option value="{OPTION.key}" {OPTION.selected} >{OPTION.title}</option>
		<!-- END: search_type -->
	</select>
	<input type="submit" value="{LANG.search}" />
	<br />
	<label><em>{LANG.search_note}</em></label>
</form>

<br/>
<table class="tab1">
	<colgroup>
		<col class="w50" />
		<col class="center" />
		<col class="center" />
		<col class="w200" />
		<col class="w50" />
		<col class="w150" />
	</colgroup>
	<thead>
		<tr class="center">
			<td>&nbsp;</td>
			<td>{LANG.mod_name}</td>
			<td>{LANG.content}</td>
			<td>{LANG.email}</td>
			<td>{LANG.status}</td>
			<td>{LANG.funcs}</td>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="3">
				<em class="icon-check icon-large">&nbsp;</em><a id="checkall" href="javascript:void(0);">{LANG.checkall}</a> &nbsp;&nbsp; 
				<em class="icon-check-empty icon-large">&nbsp;</em><a id="uncheckall" href="javascript:void(0);">{LANG.uncheckall}</a> 
				<span style="width:100px;display:inline-block">&nbsp;</span> 
				<em class="icon-ok-circle icon-large">&nbsp;</em><a class="disable" href="javascript:void(0);">{LANG.disable}</a> 
				<em class="icon-ok icon-large">&nbsp;</em><a class="enable" href="javascript:void(0);">{LANG.enable}</a> 
				<em class="icon-trash icon-large">&nbsp;</em><a class="delete" href="javascript:void(0);">{LANG.delete}</a> 
			</td>
			<td colspan="3" class="center">
			<!-- BEGIN: generate_page -->
			<div class="center">
				{GENERATE_PAGE}
			</div>
			<!-- END: generate_page -->
			</td>
		</tr>
	</tfoot>
	<tbody>
		<!-- BEGIN: loop -->
		<tr>
			<td class="center"><input name="commentid" type="checkbox" value="{ROW.cid}"/></td>
			<td>{ROW.module}</td>
			<td><a target="_blank" href="{ROW.link}">{ROW.content}</a></td>
			<td>{ROW.email}</td>
			<td class="center"><em class="icon-{ROW.status} icon-large">&nbsp;</em></td>
			<td class="center"><em class="icon-edit icon-large">&nbsp;</em><a href="{ROW.linkedit}">{LANG.edit}</a> &nbsp; <em class="icon-trash icon-large">&nbsp;</em><a class="deleteone" href="{ROW.linkdelete}">{LANG.delete}</a></td>
		</tr>
		<!-- END: loop -->
	</tbody>
</table>

<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>

<script type="text/javascript">
	$("#from_date,#to_date").datepicker({
		showOn : "both",
		dateFormat : "dd/mm/yy",
		changeMonth : true,
		changeYear : true,
		showOtherMonths : true,
		buttonImage : nv_siteroot + "images/calendar.gif",
		buttonImageOnly : true
	});

	$("#checkall").click(function() {
		$("input:checkbox").each(function() {
			$(this).prop("checked", true);
		});
	});
	$("#uncheckall").click(function() {
		$("input:checkbox").each(function() {
			$(this).prop("checked", false);
		});
	});
	$("a.enable").click(function() {
		var list = [];
		$("input[name=commentid]:checked").each(function() {
			list.push($(this).val());
		});
		if (list.length < 1) {
			alert("{LANG.nocheck}");
			return false;
		}
		$.ajax({
			type : "POST",
			url : "index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=active",
			data : "list=" + list + "&active=1",
			success : function(data) {
				alert(data);
				window.location = window.location.href;
				;
			}
		});
		return false;
	});
	$("a.disable").click(function() {
		var list = [];
		$("input[name=commentid]:checked").each(function() {
			list.push($(this).val());
		});
		if (list.length < 1) {
			alert("{LANG.nocheck}");
			return false;
		}
		$.ajax({
			type : "POST",
			url : "index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=active",
			data : "list=" + list + "&active=0",
			success : function(data) {
				alert(data);
				window.location = window.location.href;
				;
			}
		});
		return false;
	});
	$("a.delete").click(function() {
		var list = [];
		$("input[name=commentid]:checked").each(function() {
			list.push($(this).val());
		});
		if (list.length < 1) {
			alert("{LANG.nocheck}");
			return false;
		}
		if (confirm("{LANG.delete_confirm}")) {
			$.ajax({
				type : "POST",
				url : "index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=del",
				data : "list=" + list,
				success : function(data) {
					alert(data);
					window.location = window.location.href;
				}
			});
		}
		return false;
	});
	$("a.deleteone").click(function() {
		if (confirm("{LANG.delete_confirm}")) {
			var url = $(this).attr("href");
			$.ajax({
				type : "POST",
				url : url,
				data : "",
				success : function(data) {
					alert(data);
					window.location = window.location.href;
				}
			});
		}
		return false;
	});
</script>
<!-- END: main -->