<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="quote" style="width:98%">
	<blockquote class="error">{ERROR}</blockquote>
</div>
<div class="clear"></div>
<!-- END: error -->
<form action="{NV_BASE_ADMINURL}index.php" method="post">
	<input type="hidden" name="{NV_NAME_VARIABLE}"value="{MODULE_NAME}" />
	<input type="hidden" name="{NV_OP_VARIABLE}"value="{OP}" />
	<table  class="tab1">
		<col width="65%" />
		<col width="45%" />
		<thead>
			<tr>
				<td colspan="2">{LANG.config}</td>
			</tr>
		</thead>
		<tbody class="second">
			<tr>
				<td>{LANG.admfirewall}</td>
				<td><input type="checkbox" value="1" name="admfirewall"{DATA.admfirewall} /></td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td>{LANG.block_admin_ip}</td>
				<td><input type="checkbox" value="1" name="block_admin_ip"{DATA.block_admin_ip} /></td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td>{LANG.authors_detail_main}</td>
				<td><input type="checkbox" value="1" name="authors_detail_main"{DATA.authors_detail_main} /></td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td>{LANG.spadmin_add_admin}</td>
				<td><input type="checkbox" value="1" name="spadmin_add_admin"{DATA.spadmin_add_admin} /></td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td colspan="2">
					<input type="submit" value=" {LANG.save} " name="Submit1" />
					<input type="hidden" value="1" name="savesetting" />
				</td>
			</tr>
		</tbody>
	</table>
</form>
<!-- BEGIN: list_firewall -->
<table id="iduser" class="tab1">
	<caption>{LANG.title_username}</caption>
	<thead>
		<tr align="center">
			<td>{GLANG.username}</td>
			<td>{LANG.adminip_timeban}</td>
			<td>{LANG.adminip_timeendban}</td>
			<td>{LANG.adminip_funcs}</td>
		</tr>
	</thead>
	<!-- BEGIN: loop -->
	<tbody{ROW.class}>
		<tr>
			<td align="left">{ROW.keyname}</td>
			<td align="center">{ROW.dbbegintime}</td>
			<td align="center">{ROW.dbendtime}</td>
			<td align="center">
				<span class="edit_icon">
					<a class="edit" href="{ROW.url_edit}">{GLANG.edit}</a>
				</span>	- 
				<span class="delete_icon">
					<a class="deleteuser" href="{ROW.url_delete}">{GLANG.delete}</a>
				</span>
			</td>
		</tr>
	</tbody>
	<!-- END: loop -->
</table>
<!-- END: list_firewall -->
<form id="form_add_user" action="{NV_BASE_ADMINURL}index.php" method="post">
	<input type="hidden" name="{NV_NAME_VARIABLE}"value="{MODULE_NAME}" />
	<input type="hidden" name="{NV_OP_VARIABLE}"value="{OP}" />
	<input type="hidden" name="uid" value="{FIREWALLDATA.uid}" />
	<table class="tab1">
	<tbody>
		<tr>
			<td colspan="2"><strong>{LANG.username_add}</strong></td>
		</tr>
	</tbody>
	<tbody class="second">
		<tr>
			<td style="width:150px">{GLANG.username} (<span style="color:red">*</span>)</td>
			<td><input type="text" name="username" value="{FIREWALLDATA.username}" style="width:200px"/></td>
		</tr>
	</tbody>
	<tbody>
		<tr>
			<td>{GLANG.password} (<span style="color:red">*</span>)</td>
			<td><input type="password" name="password" value="{FIREWALLDATA.password}" style="width:200px"/></td>
		</tr>
	</tbody>
	<tbody class="second">
		<tr>
			<td>{GLANG.password2} (<span style="color:red">*</span>)</td>
			<td><input type="password" name="password2" value="{FIREWALLDATA.password2}" style="width:200px"/></td>
		</tr>
	</tbody>
	<tbody>
		<tr>
			<td>{LANG.adminip_begintime}</td>
			<td>
				<input type="text" name="begintime1" id="begintime1" value="{FIREWALLDATA.begintime1}" style="width:150px"/>
			</td>
		</tr>
	</tbody>
	<tbody class="second">
		<tr>
			<td>{LANG.adminip_endtime}</td>
			<td>
				<input type="text" name="endtime1" id="endtime1" value="{FIREWALLDATA.endtime1}" style="width:150px"/>
			</td>
		</tr>
	</tbody>
	<tbody>
		<tr>
			<td colspan="2">
				<input type="submit" value="{LANG.save}" name="submituser"/><br /><br />
				<!-- BEGIN: nochangepass -->{LANG.nochangepass}<!-- END: nochangepass -->
			</td>
		</tr>
	</tbody>
</table>
</form>
<script type="text/javascript">
//<![CDATA[
document.getElementById('form_add_user').setAttribute("autocomplete", "off");
//]]>
</script>
<!-- BEGIN: ipaccess -->
<table id="idip" class="tab1">
<caption>{LANG.adminip}</caption>
	<thead>
		<tr align="center">
			<td>{LANG.adminip_ip}</td>
			<td>{LANG.adminip_mask}</td>
			<td>{LANG.adminip_timeban}</td>
			<td>{LANG.adminip_timeendban}</td>
			<td>{LANG.adminip_funcs}</td>
		</tr>
	</thead>
	<!-- BEGIN: loop -->
	<tbody{ROW.class}>
		<tr>
			<td align="center">{ROW.keyname}</td>
			<td align="center">{ROW.mask_text_array}</td>
			<td align="center">{ROW.dbbegintime}</td>
			<td align="center">{ROW.dbendtime}</td>
			<td align="center">
				<span class="edit_icon">
					<a title="{GLANG.edit}" class="edit" href="{ROW.url_edit}">{GLANG.edit}</a>
				</span>	- 
				<span class="delete_icon">
					<a title="{GLANG.delete}" class="deleteone" href="{ROW.url_delete}">{GLANG.delete}</a>
				</span>
			</td>
		</tr>
	</tbody>
	<!-- END: loop -->
</table>
<!-- END: ipaccess -->
<form action="{NV_BASE_ADMINURL}index.php" method="post">
	<input type="hidden" name="{NV_NAME_VARIABLE}"value="{MODULE_NAME}" />
	<input type="hidden" name="{NV_OP_VARIABLE}"value="{OP}" />
	<input type="hidden" name="cid" value="{IPDATA.cid}" />
	<table class="tab1">
	<tbody class="second">
		<tr>
			<td colspan="2"><strong>{LANG.adminip_add}</strong></td>
		</tr>
	</tbody>
	<tbody>
		<tr>
			<td style="width:150px">{LANG.adminip_address} (<span style="color:red">*</span>)</td>
			<td><input type="text" name="keyname" value="{IPDATA.keyname}" style="width:200px"/> (xxx.xxx.xxx.xxx)</td>
		</tr>
	</tbody>
	<tbody class="second">
		<tr>
			<td>{LANG.adminip_mask}</td>
			<td>
				<select name="mask">
					<option value="0">{MASK_TEXT_ARRAY.0}</option>
					<option value="3"{IPDATA.selected3}>{MASK_TEXT_ARRAY.3}</option>
					<option value="2"{IPDATA.selected2}>{MASK_TEXT_ARRAY.2}</option>
					<option value="1"{IPDATA.selected1}>{MASK_TEXT_ARRAY.1}</option>
				</select>
			</td>
		</tr>
	</tbody>
	<tbody>
		<tr>
			<td>{LANG.adminip_begintime}</td>
			<td>
				<input type="text" name="begintime" id="begintime" value="{IPDATA.begintime}" style="width:150px"/>
			</td>
		</tr>
	</tbody>
	<tbody class="second">
		<tr>
			<td>{LANG.adminip_endtime}</td>
			<td>
				<input type="text" name="endtime" id="endtime" value="{IPDATA.endtime}" style="width:150px"/>
			</td>
		</tr>
	</tbody>
	<tbody>
		<tr>
			<td>{LANG.adminip_notice}</td>
			<td><textarea rows="4" name="notice" style="width:400px;height:50px">{IPDATA.notice}</textarea></td>
		</tr>
	</tbody>
	<tbody class="second">
		<tr>
			<td colspan="2"><input type="submit" value="{LANG.save}" name="submitip"/><br /><br />{LANG.adminip_note}</td>
		</tr>
	</tbody>
</table>
</form>
<script type='text/javascript'>
	//<![CDATA[
	$(document).ready(function(){
		$("#endtime,#begintime,#endtime1,#begintime1").datepicker({
			showOn: "button",
			dateFormat: "dd.mm.yy",
			changeMonth: true,
			changeYear: true,
			showOtherMonths: true,
			buttonText: '',
			showButtonPanel: true,
			showOn: 'focus'
		});
	});
	$('input[name=submitip]').click(function(){
		var ip = $('input[name=keyname]').val();
		$('input[name=keyname]').focus();
		if (ip==''){
			alert('{LANG.adminip_error_ip}');
			return false;
		}
	});
	$('input[name=submituser]').click(function(){
		var username= $('input[name=username]').val();
		var nv_rule = /^([a-zA-Z0-9_-])+$/;
		if (username==''){
			$('input[name=username]').focus();
			alert('{GLANG.username_empty}');
			return false;
		}
		else if (!nv_rule.test(username)){
			$('input[name=username]').focus();
			alert('{LANG.rule_user}');
			return false;
		}
		var password = $('input[name=password]').val();
		if (password== '' && $('input[name=uid]').val()=='0'){
			$('input[name=password]').focus();
			alert('{GLANG.password_empty}');
			return false;
		}			
		if (password!=$('input[name=password2]').val()){
			$('input[name=password2]').focus();
			alert('{LANG.passwordsincorrect}');
			return false;
		}
		else if (password!='' && !nv_rule.test(password)){
			$('input[name=password]').focus();
			alert('{LANG.rule_pass}');
			return false;
		}		
	});
	$('a.deleteone').click(function(){
        if (confirm('{LANG.adminip_delete_confirm}')){
        	var url = $(this).attr('href');	
	        $.ajax({        
		        type: 'POST',
		        url: url,
		        data:'',
		        success: function(data){  
		            alert('{LANG.adminip_del_success}');
		            window.location='index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}={OP}';
		        }
	        });  
        }
		return false;
	});
	$('a.deleteuser').click(function(){
        if (confirm('{LANG.nicknam_delete_confirm}')){
        	var url = $(this).attr('href');	
	        $.ajax({        
		        type: 'POST',
		        url: url,
		        data:'',
		        success: function(data){  
		            alert('{LANG.adminip_del_success}');
		            window.location='index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}={OP}';
		        }
	        });  
        }
		return false;
	});
	//]]>
</script>
<!-- END: main -->