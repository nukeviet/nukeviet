<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->
<script type="text/javascript" src="{NV_BASE_SITEURL}js/jquery/jquery.validate.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/jquery.validator-{NV_LANG_INTERFACE}.js"></script>

<link rel="stylesheet" type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.core.css">
<link rel="stylesheet" type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.theme.css">
<link rel="stylesheet" type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.css">

<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<form action="{NV_BASE_ADMINURL}index.php" method="post" id="frm">
	<input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
	<input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}" />
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<td colspan="2">{LANG.config}</td>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="2"><input type="submit" value=" {LANG.save} " name="Submit1" class="btn btn-primary" /><input type="hidden" value="1" name="savesetting" /></td>
				</tr>
			</tfoot>
			<tbody>
				<tr>
					<td>{LANG.admfirewall}</td>
					<td><input type="checkbox" value="1" name="admfirewall"{DATA.admfirewall} /></td>
				</tr>
				<tr>
					<td>{LANG.block_admin_ip}</td>
					<td><input type="checkbox" value="1" name="block_admin_ip"{DATA.block_admin_ip} /></td>
				</tr>
	
				<tr>
					<td>{LANG.authors_detail_main}</td>
					<td><input type="checkbox" value="1" name="authors_detail_main"{DATA.authors_detail_main} /></td>
				</tr>
				<tr>
					<td>{LANG.spadmin_add_admin}</td>
					<td><input type="checkbox" value="1" name="spadmin_add_admin"{DATA.spadmin_add_admin} /></td>
				</tr>
				<tr>
					<td>{LANG.adminrelogin_max}</td>
					<td>
					<select name="adminrelogin_max" class="form-control">
						<!-- BEGIN: adminrelogin_max -->
						<option value="{OPTION.value}"{OPTION.select}>{OPTION.text}</option>
						<!-- END: adminrelogin_max -->
					</select></td>
				</tr>
				<tr>
					<td>{LANG.admin_check_pass_time}</td>
					<td><input class="form-control pull-left" style="width:50px;" type="text" value="{ADMIN_CHECK_PASS_TIME}" name="admin_check_pass_time" maxlength="3"/>({GLANG.min})</td>
				</tr>
			</tbody>
		</table>
	</div>
</form>
<!-- BEGIN: list_firewall -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover" id="iduser">
		<caption><em class="fa fa-file-text-o">&nbsp;</em>{LANG.title_username} </caption>
		<thead>
			<tr class="text-center">
				<th>{GLANG.username}</th>
				<th>{LANG.adminip_timeban}</th>
				<th>{LANG.adminip_timeendban}</th>
				<th>{LANG.adminip_funcs}</th>
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td class="text-left">{ROW.keyname}</td>
				<td class="text-center">{ROW.dbbegintime}</td>
				<td class="text-center">{ROW.dbendtime}</td>
				<td class="text-center">
					<em class="fa fa-edit fa-lg">&nbsp;</em> <a class="edit" href="{ROW.url_edit}">{GLANG.edit}</a> 
					<em class="fa fa-trash-o fa-lg">&nbsp;</em> <a class="deleteuser" href="{ROW.url_delete}">{GLANG.delete}</a>
				</td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<!-- END: list_firewall -->
<form id="form_add_user" action="{NV_BASE_ADMINURL}index.php" method="post">
	<input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
	<input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}" />
	<input type="hidden" name="uid" value="{FIREWALLDATA.uid}" />
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<tfoot>
				<tr>
					<td colspan="2"><input type="submit" value="{LANG.save}" name="submituser" class="btn btn-primary"/>
					<!-- BEGIN: nochangepass -->
					<br />
					<br />
					{LANG.nochangepass}
					<!-- END: nochangepass -->
					</td>
				</tr>
			</tfoot>
			<tbody>
				<tr>
					<td colspan="2"><strong>{LANG.username_add}</strong></td>
				</tr>
	
				<tr>
					<td class="w150">{GLANG.username} (<span style="color:red">*</span>)</td>
					<td><input class="w200 form-control" type="text" name="username" value="{FIREWALLDATA.username}" /></td>
				</tr>
				<tr>
					<td>{GLANG.password} (<span style="color:red">*</span>)</td>
					<td><input class="w200 form-control" type="password" name="password" value="{FIREWALLDATA.password}" /></td>
				</tr>
	
				<tr>
					<td>{GLANG.password2} (<span style="color:red">*</span>)</td>
					<td><input class="w200 form-control" type="password" name="password2" value="{FIREWALLDATA.password2}" /></td>
				</tr>
				<tr>
					<td>{LANG.adminip_begintime}</td>
					<td><input type="text" name="begintime1" class="w100 datepicker form-control pull-left" value="{FIREWALLDATA.begintime1}" /></td>
				</tr>
	
				<tr>
					<td>{LANG.adminip_endtime}</td>
					<td><input type="text" name="endtime1" class="w100 datepicker form-control pull-left" value="{FIREWALLDATA.endtime1}" /></td>
				</tr>
			</tbody>
		</table>
	</div>
</form>
<script type="text/javascript">
	//<![CDATA[
	document.getElementById('form_add_user').setAttribute("autocomplete", "off");
	//]]>
</script>
<!-- BEGIN: ipaccess -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover" id="idip">
		<caption><em class="fa fa-file-text-o">&nbsp;</em>{LANG.adminip} </caption>
		<thead>
			<tr class="text-center">
				<th>{LANG.adminip_ip}</th>
				<th>{LANG.adminip_mask}</th>
				<th>{LANG.adminip_timeban}</th>
				<th>{LANG.adminip_timeendban}</th>
				<th>{LANG.adminip_funcs}</th>
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td class="text-center">{ROW.keyname}</td>
				<td class="text-center">{ROW.mask_text_array}</td>
				<td class="text-center">{ROW.dbbegintime}</td>
				<td class="text-center">{ROW.dbendtime}</td>
				<td class="text-center">
					<em class="fa fa-edit fa-lg">&nbsp;</em> <a title="{GLANG.edit}" class="edit" href="{ROW.url_edit}">{GLANG.edit}</a> 
					<em class="fa fa-trash-o fa-lg">&nbsp;</em> <a title="{GLANG.delete}" class="deleteone" href="{ROW.url_delete}">{GLANG.delete}</a>
				</td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<!-- END: ipaccess -->
<form action="{NV_BASE_ADMINURL}index.php" method="post">
	<input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
	<input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}" />
	<input type="hidden" name="cid" value="{IPDATA.cid}" />
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<tbody>
				<tr>
					<td colspan="2"><strong>{LANG.adminip_add}</strong></td>
				</tr>
				<tr>
					<td class="w150">{LANG.adminip_address} (<span style="color:red">*</span>)</td>
					<td><input class="w200 required form-control pull-left" type="text" name="keyname" value="{IPDATA.keyname}" /> <span class="text-middle">(xxx.xxx.xxx.xxx)</span></td>
				</tr>
	
				<tr>
					<td>{LANG.adminip_mask}</td>
					<td>
					<select name="mask" class="form-control w200">
						<option value="0">{MASK_TEXT_ARRAY.0}</option>
						<option value="3"{IPDATA.selected3}>{MASK_TEXT_ARRAY.3}</option>
						<option value="2"{IPDATA.selected2}>{MASK_TEXT_ARRAY.2}</option>
						<option value="1"{IPDATA.selected1}>{MASK_TEXT_ARRAY.1}</option>
					</select></td>
				</tr>
				<tr>
					<td>{LANG.adminip_begintime}</td>
					<td><input type="text" name="begintime" class="datepicker form-control pull-left" value="{IPDATA.begintime}" style="width:80px"/></td>
				</tr>
	
				<tr>
					<td>{LANG.adminip_endtime}</td>
					<td><input type="text" name="endtime" class="datepicker form-control pull-left" value="{IPDATA.endtime}" style="width:80px"/></td>
				</tr>
				<tr>
					<td>{LANG.adminip_notice}</td>
					<td><textarea rows="4" name="notice" style="width:400px;height:50px" class="form-control">{IPDATA.notice}</textarea></td>
				</tr>
				<tr>
					<td colspan="2"><input type="submit" value="{LANG.save}" name="submitip" class="btn btn-primary"/>
					<br />
					<br />
					<span class="text-info">{LANG.adminip_note}</span></td>
				</tr>
			</tbody>
		</table>
	</div>
</form>
<script type='text/javascript'>
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
		$('form').validate();
	});
	$('input[name=submitip]').click(function() {
		var ip = $('input[name=keyname]').val();
		$('input[name=keyname]').focus();
		if (ip == '') {
			alert('{LANG.adminip_error_ip}');
			return false;
		}
	});
	$('input[name=submituser]').click(function() {
		var username = $('input[name=username]').val();
		var nv_rule = /^([a-zA-Z0-9_-])+$/;
		if (username == '') {
			$('input[name=username]').focus();
			alert('{GLANG.username_empty}');
			return false;
		} else if (!nv_rule.test(username)) {
			$('input[name=username]').focus();
			alert('{LANG.rule_user}');
			return false;
		}
		var password = $('input[name=password]').val();
		if (password == '' && $('input[name=uid]').val() == '0') {
			$('input[name=password]').focus();
			alert('{GLANG.password_empty}');
			return false;
		}
		if (password != $('input[name=password2]').val()) {
			$('input[name=password2]').focus();
			alert('{LANG.passwordsincorrect}');
			return false;
		} else if (password != '' && !nv_rule.test(password)) {
			$('input[name=password]').focus();
			alert('{LANG.rule_pass}');
			return false;
		}
	});
	$('a.deleteone').click(function() {
		if (confirm('{LANG.adminip_delete_confirm}')) {
			var url = $(this).attr('href');
			$.ajax({
				type : 'POST',
				url : url,
				data : '',
				success : function(data) {
					alert('{LANG.adminip_del_success}');
					window.location = 'index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}={OP}';
				}
			});
		}
		return false;
	});
	$('a.deleteuser').click(function() {
		if (confirm('{LANG.nicknam_delete_confirm}')) {
			var url = $(this).attr('href');
			$.ajax({
				type : 'POST',
				url : url,
				data : '',
				success : function(data) {
					alert('{LANG.adminip_del_success}');
					window.location = 'index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}={OP}';
				}
			});
		}
		return false;
	});
	//]]>
</script>
<!-- END: main -->