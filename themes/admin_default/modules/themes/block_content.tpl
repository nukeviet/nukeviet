<!-- BEGIN: head -->
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.core.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.theme.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.css" rel="stylesheet" />
<style type="text/css">
.exp_time{line-height:20px}
.exp_time input{float: left}
.exp_time img{float:left;margin:2px}
</style>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript">
//<![CDATA[
var htmlload = '<tr><td align="center" colspan="2"><img src="{NV_BASE_SITEURL}images/load_bar.gif"/></td></tr>';
//]]>
</script>				
<!-- END: head -->
<!-- BEGIN: main -->
<!-- BEGIN: block_group_notice -->
<div class="quote" style="width:98%">
	<blockquote class="error"><span id="message">{LANG.block_group_notice}</span></blockquote>
</div>
<div class="clear"></div>
<!-- END: block_group_notice -->
<!-- BEGIN: error -->
<div id="edit"></div>
<div class="quote" style="width:98%">
	<blockquote class="error"><span id="message">{ERROR}</span></blockquote>
</div>
<!-- END: error -->
<div style="clear:both"></div>
<form method="post" action="{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}&amp;selectthemes={SELECTTHEMES}&amp;blockredirect={BLOCKREDIRECT}">
	<table class="tab1" style="width:100%">
		<col style="width:160px;white-space:nowrap" />
		<col style="width:600px;white-space:nowrap" />
		<tbody class="second">
			<tr>
				<td>{LANG.block_type}:</td>
				<td>
					<select name="module">
						<option value="">{LANG.block_select_type}</option>
						<option value="global"{GLOBAL_SELECTED}>{LANG.block_type_global}</option>
						<!-- BEGIN: module -->
						<option value="{MODULE.key}"{MODULE.selected}>{MODULE.title}</option>
						<!-- END: module -->
					</select>
					<select name="file_name"><option value="">{LANG.block_select}</option></select>
				</td>
			</tr>
		</tbody>
		<tbody id="block_config">
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td>{LANG.block_title}:</td>
				<td><input name="title" type="text" value="{ROW.title}" style="width:300px"/></td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td>{LANG.block_link}:</td>
				<td><input name="link" type="text" value="{ROW.link}" style="width:500px"/></td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td>{LANG.block_tpl}:</td>
				<td>
					<select id="template" name="template">
						<option value="">{LANG.block_default}</option>
						<!-- BEGIN: template -->
						<option value="{TEMPLATE.key}"{TEMPLATE.selected}>{TEMPLATE.title}</option>
						<!-- END: template -->
					</select>
				</td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td>{LANG.block_pos}:</td>
				<td>
					<select name="position">
						<!-- BEGIN: position -->
						<option value="{POSITION.key}"{POSITION.selected}>{POSITION.title}</option>
						<!-- END: position -->
					</select>
				</td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td>{LANG.block_exp_time}:</td>
				<td class="exp_time">
					<input name="exp_time" id="exp_time" value="{ROW.exp_time}" style="width: 90px" maxlength="10" type="text" /> (dd/mm/yyyy)
				</td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td>{LANG.block_active}:</td>
				<td><input type="checkbox" name="active" value="1"{ROW.block_active}/> {LANG.block_yes}</td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td>{LANG.block_group}:</td>
				<td>
					<select name="who_view" style="width:250px" id="who_view" onchange="nv_sh('who_view','groups_list')">
						<!-- BEGIN: who_view -->
						<option value="{WHO_VIEW.key}"{WHO_VIEW.selected}>{WHO_VIEW.title}</option>
						<!-- END: who_view -->
					</select>
				</td>
			</tr>
		</tbody>
		<tbody id="groups_list" style="{SHOW_GROUPS_LIST}">
			<tr>
				<td>{GLANG.groups_view}:</td>
				<td>
					<!-- BEGIN: groups_list -->
					<p><input name="groups_view[]" type="checkbox" value="{GROUPS_LIST.key}"{GROUPS_LIST.selected}/>&nbsp;{GROUPS_LIST.title}</p>
					<!-- END: groups_list -->
				</td>
			</tr>
		</tbody>
		<!-- BEGIN: edit -->
		<tbody>
			<tr>
				<td>{LANG.block_groupbl}:</td>
				<td>
					<span style="color:red;font-weight:bold">{ROW.bid}</span>
					&nbsp;&nbsp;&nbsp;
					<label><input type="checkbox" value="1" name="leavegroup"/>{LANG.block_leavegroup} ({BLOCKS_NUM} {LANG.block_count})</label>
				</td>
			</tr>
		</tbody>
		<!-- END: edit -->
		<tbody class="second">
			<tr>
				<td>{LANG.add_block_module}:</td>
				<td>
					<!-- BEGIN: add_block_module -->
					<label id="labelmoduletype{I}"{SHOWSDISPLAY}>
						<input type="radio" name="all_func" class="moduletype{I}" value="{B_KEY}"{CK}/> {B_VALUE}
					</label>
					<!-- END: add_block_module -->
				</td>
			</tr>
		</tbody>
		<tbody{SHOWS_ALL_FUNC} id="shows_all_func">
			<tr>
				<td style="vertical-align:top">
					{LANG.block_function}:<br /><br />
					<label><input type="button" name="checkmod" value="{LANG.block_check}" style="margin-bottom:5px"/></label>
				</td>
				<td>
					<div class="list-funcs">
						<table border="0" cellpadding="3" cellspacing="3">
							<!-- BEGIN: loopfuncs -->
							<tbody class="funclist" id="idmodule_{M_TITLE}">
								<tr id="wrapmod{M_TITLE}">
									<td style="font-weight:bold" nowrap="nowrap">
										<input{M_CHECKED} type="checkbox" value="{M_TITLE}" class="checkmodule"/> {M_CUSTOM_TITLE}
									</td>
									<!-- BEGIN: fuc -->
									<td nowrap="nowrap">
										<label><input type="checkbox"{SELECTED} name="func_id[]" value="{FUNCID}" /> {FUNCNAME}</label>
									</td>
									<!-- END: fuc -->
								</tr>
							</tbody>
							<!-- END: loopfuncs -->
						</table>
					</div>
				</td>
			</tr>
		</tbody>
	</table>
	<div style="padding:10px;text-align:center">
		<input type="hidden" name="bid" value="{ROW.bid}" />
		<input type="submit" name="confirm" value="{LANG.block_confirm}" />
	</div>
</form>
<!-- BEGIN: hidefunclist -->
<script type="text/javascript">
$(document).ready(function(){
	$("tbody.funclist").css({"display":"none"});
	$("tbody#idmodule_{HIDEFUNCLIST}").css({"display":"block"});
});
</script>
<!-- END: hidefunclist -->
<script type="text/javascript">
//<![CDATA[
<!-- BEGIN: load_block_config -->
$("#block_config").show();
$("#block_config").html(htmlload);
$.get("{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=block_config&bid={ROW.bid}&module={ROW.module}&file_name={ROW.file_name}&nocache="+new Date().getTime(), function(theResponse){
	if (theResponse.length>10){	
		$("#block_config").html(theResponse);
	}else{
		$("#block_config").hide();
	}
});
<!-- END: load_block_config -->
<!-- BEGIN: hide_block_config -->
$("#block_config").hide();
<!-- END: hide_block_config -->
$("select[name=file_name]").load("{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=loadblocks&module={ROW.module}&bid={ROW.bid}&nocache="+new Date().getTime());
$(function(){
	$("#exp_time").datepicker({
		showOn: "button",
		dateFormat: "dd/mm/yy",
		changeMonth: true,
		changeYear: true,
		showOtherMonths: true,
		buttonImage: "{NV_BASE_SITEURL}images/calendar.gif",
		buttonImageOnly: true
	});
});	
$(function(){
	$("select[name=module]").change(function(){
		var type = $("select[name=module]").val();
		$("select[name=file_name]").html("");
		if (type!=""){
			$("#block_config").html("");
			$("#block_config").hide();
			$("select[name=file_name]").load("{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=loadblocks&module="+type+"&nocache="+new Date().getTime());
		}
	});
	$("select[name=file_name]").change(function(){
		var file_name = $("select[name=file_name]").val();
		var type = $("select[name=module]").val();
		if(file_name.substring(0,7)=="global."){
			$("tbody.funclist").css({"display":""});
			$("#labelmoduletype1").css({"display":""});	
		}
		else{
			$("#labelmoduletype1").css({"display":"none"});		
			$("tbody.funclist").css({"display":"none"});
			$("tbody#idmodule_"+type).css({"display":"block"});
			var $radios = $("input:radio[name=all_func]");
	       	$radios.filter("[value=0]").attr("checked", true);
			$("#shows_all_func").show();
		}
		var blok_file_name = "";
		if(file_name!=""){
			var arr_file = file_name.split("|");
			if(parseInt(arr_file[1])==1){
				blok_file_name = arr_file[0];
			}
		}
		if(blok_file_name!=""){
			$("#block_config").show();
			$("#block_config").html(htmlload);
			$.get("{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=block_config&bid={ROW.bid}&module="+type+"&file_name="+blok_file_name+"&nocache="+new Date().getTime(), function(theResponse){
				if (theResponse.length>10){	
					$("#block_config").html(theResponse);
				}
				else{
					$("#block_config").hide();
				}
			});
		}
		else{
			$("#block_config").hide();
		}
	});	
	$("input[name=all_func]").click(function(){
		var module = $("select[name=module]").val();
		var af = $(this).val();
	   	if (af=="0" && module!="global"){
	   		$("#shows_all_func").show();
	   	} else if (module=="global" && af==0){
	   		$("#shows_all_func").show();
	   	} else if (af==1) {
	   		$("#shows_all_func").hide();
	   	}
	});
	$("input[name=leavegroup]").click(function(){
		var lv = $("input[name='leavegroup']:checked").val();
		if(lv=="1"){
			var $radios = $("input:radio[name=all_func]");
	       	$radios.filter("[value=0]").attr("checked", true);
	       	$("#shows_all_func").show();
		}
	});		
	$("input[name=checkmod]").toggle(function(){
		$("input.checkmodule").attr("checked","checked");
		$("input[name='func_id[]']:checkbox").each(function(){
			$("input[name='func_id[]']:visible").attr("checked","checked");			
		});
	},function(){
		$("input.checkmodule").removeAttr("checked");
		$("input[name='func_id[]']:checkbox").each(function(){
			$("input[name='func_id[]']:visible").removeAttr("checked");
		});
	});
	$("input[name='func_id[]']:checkbox").change(function(){
		var numfuc = $("#"+$(this).parent().parent().parent().attr("id")+" input[name='func_id[]']:checkbox").length;
		var fuccheck = $("#"+$(this).parent().parent().parent().attr("id")+" input[name='func_id[]']:checkbox:checked").length;
		if(fuccheck!=numfuc){
			$("#"+$(this).parent().parent().parent().attr("id")+" .checkmodule").removeAttr("checked");
		}else if(numfuc==fuccheck){
			$("#"+$(this).parent().parent().parent().attr("id")+" .checkmodule").attr("checked","checked");
		}
	});
	$("input.checkmodule").change(function(){  $(this).attr('value')
		if($(this).attr('checked')){
			$("#idmodule_"+$(this).attr('value')+" input[name='func_id[]']:checkbox").attr("checked","checked");
		}else{
			$("#idmodule_"+$(this).attr('value')+" input[name='func_id[]']:checkbox").removeAttr("checked");
		}
	});
	$("select[name=who_view]").change(function(){
		var groups = $("select[name=who_view]").val();
		if (groups==3){
			$("#groups_list").show();
		} else {
			$("#groups_list").hide();
		}
	});
	$("input[name=confirm]").click(function(){
		var leavegroup = $("input[name=leavegroup]").is(":checked")?1:0;
		var all_func = $("input[name='all_func']:checked").val();
		if(all_func==0){
	    	var funcid = [];
	    	$("input[name='func_id[]']:checked").each(function(){
	    		funcid.push($(this).val());
	    	});
	    	if (funcid.length<1){
	    		alert("{LANG.block_no_func}");
	    		return false;
	    	}
		}
		var who_view = $("select[name=who_view]").val();
		if (who_view==3){
			var grouplist = [];
			$("input[name='groups_view[]']:checked").each(function(){
				grouplist.push($(this).val());
			});
			if (grouplist.length<1){
				alert("{LANG.block_error_nogroup}");
				return false;
			}
		}
	});
});
//]]>
</script>
<!-- END: main -->
<!-- BEGIN: blockredirect -->
<script type="text/javascript">parent.location="{BLOCKREDIRECT}";</script>
<!-- END: blockredirect -->