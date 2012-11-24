<!-- BEGIN: main -->
<!-- BEGIN: listip -->
<table class="tab1">
	<thead>
		<tr class="center">
			<td>{LANG.banip_ip}</td>
			<td>{LANG.banip_mask}</td>
			<td>{LANG.banip_area}</td>
			<td>{LANG.banip_timeban}</td>
			<td>{LANG.banip_timeendban}</td>
			<td>{LANG.banip_funcs}</td>
		</tr>
	</thead>
	<!-- BEGIN: loop -->
	<tbody{ROW.class}>
		<tr>
			<td class="center">{ROW.dbip}</td>
			<td class="center">{ROW.dbmask}</td>
			<td class="center">{ROW.dbarea}</td>
			<td class="center">{ROW.dbbegintime}</td>
			<td class="center">{ROW.dbendtime}</td>
			<td class="center">
				<span class="edit_icon">
					<a class="edit" title="{LANG.banip_edit}" href="{ROW.url_edit}">{LANG.banip_edit}</a>
				</span>	- 
				<span class="delete_icon">
					<a class="deleteone" title="{LANG.banip_delete}" href="{ROW.url_delete}">{LANG.banip_delete}</a>
				</span>
			</td>
		</tr>
	</tbody>
	<!-- END: loop -->
</table>
<!-- END: listip -->
<!-- BEGIN: error -->
<div class="quote" style="width:98%">
	<blockquote class="error">{ERROR}</blockquote>
</div>
<div class="clear"></div>
<!-- END: error -->
<!-- BEGIN: manual_save -->
<div class="quote" style="width:98%">
	<blockquote class="error">{MESSAGE}</blockquote>
</div>
<div class="clear"></div>
<div class="codecontent">{CODE}</div>
<!-- END: manual_save -->
<form action="{NV_BASE_ADMINURL}index.php" method="post">
	<input type="hidden" name ="{NV_NAME_VARIABLE}"value="{MODULE_NAME}" />
	<input type="hidden" name ="{NV_OP_VARIABLE}"value="{OP}" />
	<input type="hidden" name ="cid" value="{DATA.cid}" />
	<table class="tab1">
		<col width="200"/>
		<tbody class="second">
			<tr>
				<td colspan="2"><strong>{LANG.banip_add}</strong></td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td>{LANG.banip_address} (<span style="color:red">*</span>)<br/>(xxx.xxx.xxx.xxx)</td>
				<td><input type="text" name="ip" value="{DATA.ip}" style="width:200px"/></td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td>{LANG.banip_mask}</td>
				<td>
					<select name="mask">
						<option value="0">{MASK_TEXT_ARRAY.0}</option>
						<option value="3"{DATA.selected3}>{MASK_TEXT_ARRAY.3}</option>
						<option value="2"{DATA.selected2}>{MASK_TEXT_ARRAY.2}</option>
						<option value="1"{DATA.selected1}>{MASK_TEXT_ARRAY.1}</option>
					</select>
				</td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td>{LANG.banip_area}</td>
				<td>
					<select name="area" id="area">
						<option value="0">{BANIP_AREA_ARRAY.0}</option>
						<option value="1"{DATA.selected_area_1}>{BANIP_AREA_ARRAY.1}</option>
						<option value="2"{DATA.selected_area_2}>{BANIP_AREA_ARRAY.2}</option>
						<option value="3"{DATA.selected_area_3}>{BANIP_AREA_ARRAY.3}</option>
					</select>
				</td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td>{LANG.banip_begintime}</td>
				<td><input type="text" name="begintime" id="begintime" value="{DATA.begintime}" style="width:150px"/></td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td>{LANG.banip_endtime}</td>
				<td><input type="text" name="endtime" id="endtime" value="{DATA.endtime}" style="width:150px"/></td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td>{LANG.banip_notice}</td>
				<td><textarea cols="" rows="7" name="notice" style="width:250px;height:100px">{DATA.notice}</textarea></td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td colspan="2" class="center">
					<input type="submit" value="{LANG.banip_confirm}" name="submit"/>
				</td>
			</tr>
		</tbody>
	</table>
</form>
<script type="text/javascript">
//<![CDATA[	
$(document).ready(function(){
	$("#begintime,#endtime").datepicker({
		showOn: "button",
		dateFormat: "dd.mm.yy",
		changeMonth: true,
		changeYear: true,
		showOtherMonths: true,
		buttonImage : nv_siteroot + "images/calendar.gif",
		buttonImageOnly : true
	});
	$('input[name=submit]').click(function(){
		var ip = $('input[name=ip]').val();
		$('input[name=ip]').focus();
		if (ip==''){
			alert('{LANG.banip_error_ip}');
			return false;
		}
		var area = $('select[name=area]').val();
		$('select[name=area]').focus();
		if (area=='0'){
			alert('{LANG.banip_error_area}');
			return false;
		}		
	});
	$('a.deleteone').click(function(){
        if (confirm('{LANG.banip_delete_confirm}')){
        	var url = $(this).attr('href');	
	        $.ajax({        
		        type: 'POST',
		        url: url,
		        data:'',
		        success: function(data){  
		            alert('{LANG.banip_del_success}');
		            window.location='index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}={OP}';
		        }
	        });  
        }
		return false;
	});
});
//]]>
</script>
<!-- END: main -->
