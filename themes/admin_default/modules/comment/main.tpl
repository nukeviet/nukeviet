<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.core.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.theme.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.css" rel="stylesheet" />

<form action="{NV_BASE_ADMINURL}index.php" method="get">
	<br />
	<input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
	<input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}" />
	<table>
		<caption><em class="fa fa-file-text-o">&nbsp;</em>{LANG.search_note}</caption>
		<tr>
			<td>{LANG.search_module}:</td>
			<td>
				<select name="module" class="form-control w200 pull-left" style="margin-bottom: 10px">
					<!-- BEGIN: module -->
					<option value="{OPTION.key}" {OPTION.selected} >{OPTION.title}</option>
					<!-- END: module -->
				</select>
			</td>
			<td>{LANG.search_status}:</td>
			<td>
				<select name="sstatus" class="form-control w200 pull-left" style="margin-bottom: 10px">
					<!-- BEGIN: search_status -->
					<option value="{OPTION.key}" {OPTION.selected} >{OPTION.title}</option>
					<!-- END: search_status -->
				</select>
			</td>
			<td>{LANG.search_per_page}:</td>
			<td>
				<select name="per_page" class="form-control w200 pull-left">
					<!-- BEGIN: per_page -->
					<option value="{OPTION.page}" {OPTION.selected} >{OPTION.page}</option>
					<!-- END: per_page -->
				</select>
			</td>
			<td><input name="from_date" id="from_date" value="{FROM.from_date}" class="form-control w100 pull-left" maxlength="10" type="text" placeholder="{LANG.from_date}" /></td>
			<td><input name="to_date" id="to_date" value="{FROM.to_date}" class="form-control w100 pull-left" maxlength="10" type="text" placeholder="{LANG.to_date}" /></td>
		</tr>
		<tr>
			<td>{LANG.search_key}:</td>
			<td><input type="text" value="{FROM.q}" autofocus="autofocus" maxlength="64" name="q" class="form-control w200" /></td>	
			<td>{LANG.search_type}:</td>
			<td>
				<select name="stype" class="form-control w200">
					<!-- BEGIN: search_type -->
					<option value="{OPTION.key}" {OPTION.selected} >{OPTION.title}</option>
					<!-- END: search_type -->
				</select>
			</td>
			<td><input type="submit" value="{LANG.search}" class="btn btn-info" /></td>
		</tr>
	</table>
</form>

<br/>
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<colgroup>
			<col class="w50" />
			<col class="text-center" />
			<col class="text-center" />
			<col class="w200" />
			<col class="w100" />
			<col class="w150" />
		</colgroup>
		<thead>
			<tr class="text-center">
				<th>&nbsp;</th>
				<th>{LANG.mod_name}</th>
				<th>{LANG.content}</th>
				<th>{LANG.email}</th>
				<th>{LANG.status}</th>
				<th>{LANG.funcs}</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="3">
					<em class="fa fa-check-square-o fa-lg">&nbsp;</em><a id="checkall" href="javascript:void(0);">{LANG.checkall}</a> &nbsp;&nbsp; 
					<em class="fa fa-circle-o fa-lg">&nbsp;</em><a id="uncheckall" href="javascript:void(0);">{LANG.uncheckall}</a> 
					<span style="width:100px;display:inline-block">&nbsp;</span> 
					<em class="fa fa-exclamation-circle fa-lg">&nbsp;</em><a class="disable" href="javascript:void(0);">{LANG.disable}</a> 
					<em class="fa fa-external-link fa-lg">&nbsp;</em><a class="enable" href="javascript:void(0);">{LANG.enable}</a> 
					<em class="fa fa-trash-o fa-lg">&nbsp;</em><a class="delete" href="javascript:void(0);">{LANG.delete}</a> 
				</td>
				<td colspan="3" class="text-center">
				<!-- BEGIN: generate_page -->
				<div class="text-center">
					{GENERATE_PAGE}
				</div>
				<!-- END: generate_page -->
				</td>
			</tr>
		</tfoot>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td class="text-center"><input name="commentid" type="checkbox" value="{ROW.cid}"/></td>
				<td>{ROW.module}</td>
				<td><a target="_blank" href="{ROW.link}">{ROW.content}</a></td>
				<td>{ROW.email}</td>
				<td class="text-center"><em class="fa fa-{ROW.status} fa-lg">&nbsp;</em></td>
				<td class="text-center"><em class="fa fa-edit fa-lg">&nbsp;</em><a href="{ROW.linkedit}">{LANG.edit}</a> &nbsp; <em class="fa fa-trash-o fa-lg">&nbsp;</em><a class="deleteone" href="{ROW.linkdelete}">{LANG.delete}</a></td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>

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