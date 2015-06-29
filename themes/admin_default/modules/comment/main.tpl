<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.core.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.theme.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.css" rel="stylesheet" />
<div class="well">
	<form action="{NV_BASE_ADMINURL}index.php" method="get">
		<input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
		<input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}" />

		<div class="row">
			<div class="col-xs-12 col-md-6">
				<div class="form-group">
					<input type="text" value="{FROM.q}" autofocus="autofocus" maxlength="64" name="q" class="form-control" placeholder="{LANG.search_key}" />
				</div>
			</div>
			<div class="col-xs-12 col-md-6">
				<div class="form-group">
					<select name="stype" class="form-control">
						<option value="">{LANG.search_type}</option>
						<!-- BEGIN: search_type -->
						<option value="{OPTION.key}" {OPTION.selected} >{OPTION.title}</option>
						<!-- END: search_type -->
					</select>
				</div>
			</div>
			<div class="col-xs-12 col-md-6">
				<div class="form-group">
					<select name="module" class="form-control">
						<!-- BEGIN: module -->
						<option value="{OPTION.key}" {OPTION.selected} >{OPTION.title}</option>
						<!-- END: module -->
					</select>
				</div>
			</div>
			<div class="col-xs-12 col-md-6">
				<div class="form-group">
					<select name="sstatus" class="form-control" style="margin-bottom: 10px">
						<!-- BEGIN: search_status -->
						<option value="{OPTION.key}" {OPTION.selected} >{OPTION.title}</option>
						<!-- END: search_status -->
					</select>
				</div>
			</div>
			<div class="col-xs-12 col-md-6">
				<div class="form-group">
					<select name="per_page" class="form-control">
						<option value="">{LANG.search_per_page}</option>
						<!-- BEGIN: per_page -->
						<option value="{OPTION.page}" {OPTION.selected} >{OPTION.page}</option>
						<!-- END: per_page -->
					</select>
				</div>
			</div>
			<div class="col-xs-12 col-md-6">
				<div class="form-group">
					<div class="input-group">
						<input type="text" class="form-control" name="from_date" id="from_date" value="{FROM.from_date}" readonly="readonly" placeholder="{LANG.from_date}">
						<span class="input-group-btn">
							<button class="btn btn-default" type="button" id="from-btn">
								<em class="fa fa-calendar fa-fix">&nbsp;</em>
							</button> </span>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-md-6">
				<div class="form-group">
					<div class="input-group">
						<input type="text" class="form-control" name="to_date" id="to_date" value="{FROM.to_date}" readonly="readonly" placeholder="{LANG.to_date}">
						<span class="input-group-btn">
							<button class="btn btn-default" type="button" id="to-btn">
								<em class="fa fa-calendar fa-fix">&nbsp;</em>
							</button> </span>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-md-6">
				<div class="form-group">
					<input type="submit" value="{LANG.search}" class="btn btn-info" />
				</div>
			</div>
		</div>
		<span class="help-block">{LANG.search_note}</span>
	</form>
</div>

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
				<td colspan="6">
					<em class="fa fa-check-square-o fa-lg">&nbsp;</em><a id="checkall" href="javascript:void(0);">{LANG.checkall}</a> &nbsp;&nbsp;
					<em class="fa fa-circle-o fa-lg">&nbsp;</em><a id="uncheckall" href="javascript:void(0);">{LANG.uncheckall}</a>
					<div class="pull-right">
						<em class="fa fa-exclamation-circle fa-lg">&nbsp;</em>
						<a class="disable" href="javascript:void(0);">{LANG.disable}</a>&nbsp;&nbsp;
						<em class="fa fa-external-link fa-lg">&nbsp;</em><a class="enable" href="javascript:void(0);">{LANG.enable}</a>&nbsp;&nbsp;
						<em class="fa fa-trash-o fa-lg">&nbsp;</em><a class="delete" href="javascript:void(0);">{LANG.delete}</a>
					</div>
					<div class="clear"></div>
				</td>
			</tr>
			<!-- BEGIN: generate_page -->
			<tr>
				<td colspan="6" class="text-center">
					<div class="text-center">
						{GENERATE_PAGE}
					</div>
				</td>
			</tr>
			<!-- END: generate_page -->
		</tfoot>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td class="text-center"><input name="commentid" id="checkboxid" type="checkbox" value="{ROW.cid}"/></td>
				<td>{ROW.module}</td>
				<td><a target="_blank" href="{ROW.link}" title="{ROW.content}">{ROW.title}</a></td>
				<td>{ROW.email}</td>
				<td class="text-center">
				    <input type="checkbox" name="activecheckbox" id="change_active_{ROW.cid}" onclick="nv_change_active('{ROW.cid}')" {ROW.active}>
                </td>
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
	$(function() {
		$("#from_date, #to_date").datepicker({
			dateFormat : "dd/mm/yy",
			changeMonth : true,
			changeYear : true,
			showOtherMonths : true,
			showOn : 'focus'
		});
		$('#to-btn').click(function(){
			$("#to_date").datepicker('show');
		});
		$('#from-btn').click(function(){
			$("#from_date").datepicker('show');
		});
	});

    $("#checkall").click(function(){
        $("input[name=commentid]:checkbox").each(function() {
            $(this).prop("checked", true);
        });
    });
    $("#uncheckall").click(function() {
        $("input[name=commentid]:checkbox").each(function() {
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