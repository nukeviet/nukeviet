<!-- BEGIN: main -->
<script type="text/javascript" src="{NV_BASE_SITEURL}js/jquery/jquery.validate.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/jquery.validator-{NV_LANG_INTERFACE}.js"></script>
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.core.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.theme.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.css" rel="stylesheet" />
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>

<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post" id="frm">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<caption><em class="fa fa-file-text-o">&nbsp;</em>{LANG.security} </caption>
			<colgroup>
				<col style="width: 40%" />
				<col style="width: 60%" />
			</colgroup>
			<tbody>
				<tr>
					<td><strong>{LANG.is_flood_blocker}</strong></td>
					<td><input type="checkbox" value="1" name="is_flood_blocker" {IS_FLOOD_BLOCKER} /></td>
				</tr>
				<tr>
					<td><strong>{LANG.max_requests_60}</strong></td>
					<td><input type="text" value="{MAX_REQUESTS_60}" name="max_requests_60" style="width: 50px; text-align: right" class="required form-control pull-left"/></td>
				</tr>
				<tr>
					<td><strong>{LANG.max_requests_300}</strong></td>
					<td><input type="text" value="{MAX_REQUESTS_300}" name="max_requests_300" style="width: 50px; text-align: right" class="required form-control pull-left"/></td>
				</tr>
				<tr>
					<td><strong>{LANG.nv_anti_agent}</strong></td>
					<td><input type="checkbox" value="1" name="nv_anti_agent" {ANTI_AGENT} /></td>
				</tr>
				<tr>
					<td><strong>{LANG.proxy_blocker}</strong></td>
					<td>
					<select name="proxy_blocker" class="form-control w200">
						<!-- BEGIN: proxy_blocker -->
						<option value="{PROXYOP}" {PROXYSELECTED}>{PROXYVALUE} </option>
						<!-- END: proxy_blocker -->
					</select></td>
				</tr>
				<tr>
					<td><strong>{LANG.str_referer_blocker}</strong></td>
					<td><input type="checkbox" value="1" name="str_referer_blocker" {REFERER_BLOCKER} /></td>
				</tr>
				<tr>
					<td><strong>{LANG.nv_anti_iframe}</strong></td>
					<td><input type="checkbox" value="1" name="nv_anti_iframe" {ANTI_IFRAME} /></td>
				</tr>
				<tr>
					<td><strong>{LANG.nv_allowed_html_tags}</strong></td>
					<td><textarea name="nv_allowed_html_tags" class="form-control" style="height: 100px" class="required">{NV_ALLOWED_HTML_TAGS}</textarea></td>
				</tr>
			</tbody>
		</table>
	</div>

	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<caption><em class="fa fa-file-text-o">&nbsp;</em>{LANG.captcha} </caption>
			<colgroup>
				<col style="width: 40%" />
				<col style="width: 60%" />
			</colgroup>
			<tbody>
				<tr>
					<td><strong>{LANG.captcha}</strong></td>
					<td>
					<select name="gfx_chk" class="form-control w200">
						<!-- BEGIN: opcaptcha -->
						<option value="{OPTION.value}"{OPTION.select}>{OPTION.text}</option>
						<!-- END: opcaptcha -->
					</select></td>
				</tr>
				<tr>
					<td><strong>{LANG.captcha_type}</strong></td>
					<td>
					<select name="captcha_type" class="form-control w200">
						<!-- BEGIN: captcha_type -->
						<option value="{OPTION.value}"{OPTION.select}>{OPTION.text}</option>
						<!-- END: captcha_type -->
					</select></td>
				</tr>
				<tr>
					<td><strong>{LANG.captcha_num}</strong></td>
					<td>
					<select name="nv_gfx_num" class="form-control w200">
						<!-- BEGIN: nv_gfx_num -->
						<option value="{OPTION.value}"{OPTION.select}>{OPTION.text}</option>
						<!-- END: nv_gfx_num -->
					</select></td>
				</tr>
				<tr>
					<td><strong>{LANG.captcha_size}</strong></td>
					<td><input class="form-control pull-left" style="width:50px;" type="text" value="{NV_GFX_WIDTH}" name="nv_gfx_width" maxlength="3"/> <span class="text-middle pull-left">&nbsp;x&nbsp;</span> <input class="form-control pull-left" style="width:50px;" type="text" value="{NV_GFX_HEIGHT}" name="nv_gfx_height" maxlength="3"/></td>
				</tr>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="2" class="text-center"><input type="submit" class="btn btn-primary w100" name="submitcaptcha" value="{LANG.submit}" /></td>
				</tr>
			</tfoot>
		</table>
	</div>
</form>
<script type="text/javascript">
	$(document).ready(function() {
		$('#frm').validate();
	});
</script>
<!-- BEGIN: listip -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<caption><em class="fa fa-file-text-o">&nbsp;</em>{LANG.banip} </caption>
		<thead>
			<tr class="text-center">
				<th>{LANG.banip_ip}</th>
				<th>{LANG.banip_mask}</th>
				<th>{LANG.banip_area}</th>
				<th>{LANG.banip_timeban}</th>
				<th>{LANG.banip_timeendban}</th>
				<th>{LANG.banip_funcs}</th>
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td class="text-center">{ROW.dbip}</td>
				<td class="text-center">{ROW.dbmask}</td>
				<td class="text-center">{ROW.dbarea}</td>
				<td class="text-center">{ROW.dbbegintime}</td>
				<td class="text-center">{ROW.dbendtime}</td>
				<td class="text-center">
					<em class="fa fa-edit fa-lg">&nbsp;</em> <a class="edit" title="{LANG.banip_edit}" href="{ROW.url_edit}#banip">{LANG.banip_edit}</a>
					<em class="fa fa-trash-o fa-lg">&nbsp;</em> <a class="deleteone" title="{LANG.banip_delete}" href="{ROW.url_delete}">{LANG.banip_delete}</a>
				</td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<!-- END: listip -->
<!-- BEGIN: error -->
<div class="quote">
	<blockquote class="error"> {ERROR} </blockquote>
</div>
<!-- END: error -->
<!-- BEGIN: manual_save -->
<div class="alert alert-danger">{MESSAGE}</div>
<div class="codecontent">
	{CODE}
</div>
<!-- END: manual_save -->
<form action="{NV_BASE_ADMINURL}index.php" method="post" id="banip">
	<input type="hidden" name ="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
	<input type="hidden" name ="{NV_OP_VARIABLE}" value="{OP}" />
	<input type="hidden" name ="cid" value="{DATA.cid}" />
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<caption><em class="fa fa-file-text-o">&nbsp;</em>{BANIP_TITLE}</caption>
			<colgroup>
				<col style="width: 40%" />
				<col style="width: 60%" />
			</colgroup>
			<tfoot>
				<tr>
					<td colspan="2" class="text-center"><input type="submit" value="{LANG.banip_confirm}" name="submit" class="btn btn-primary w100" /></td>
				</tr>
			</tfoot>
			<tbody>
				<tr>
					<td>{LANG.banip_address} (<span style="color:red">*</span>)
					<br/>
					(xxx.xxx.xxx.xxx)</td>
					<td><input class="w200 form-control" type="text" name="ip" value="{DATA.ip}" /></td>
				</tr>
				<tr>
					<td>{LANG.banip_mask}</td>
					<td>
					<select name="mask" class="form-control w200">
						<option value="0">{MASK_TEXT_ARRAY.0}</option>
						<option value="3"{DATA.selected3}>{MASK_TEXT_ARRAY.3}</option>
						<option value="2"{DATA.selected2}>{MASK_TEXT_ARRAY.2}</option>
						<option value="1"{DATA.selected1}>{MASK_TEXT_ARRAY.1}</option>
					</select></td>
				</tr>
				<tr>
					<td>{LANG.banip_area}</td>
					<td>
					<select name="area" id="area" class="form-control w200">
						<option value="0">{BANIP_AREA_ARRAY.0}</option>
						<option value="1"{DATA.selected_area_1}>{BANIP_AREA_ARRAY.1}</option>
						<option value="2"{DATA.selected_area_2}>{BANIP_AREA_ARRAY.2}</option>
						<option value="3"{DATA.selected_area_3}>{BANIP_AREA_ARRAY.3}</option>
					</select></td>
				</tr>
				<tr>
					<td>{LANG.banip_begintime}</td>
					<td><input type="text" name="begintime" class="w150 datepicker form-control pull-left" value="{DATA.begintime}"/></td>
				</tr>
				<tr>
					<td>{LANG.banip_endtime}</td>
					<td><input type="text" name="endtime" class="w150 datepicker form-control pull-left text" value="{DATA.endtime}" /></td>
				</tr>
				<tr>
					<td class="top">{LANG.banip_notice}</td>
					<td><textarea cols="70" rows="5" class="form-control" name="notice" style="width:550px;height:100px">{DATA.notice}</textarea></td>
				</tr>
			</tbody>
		</table>
	</div>
</form>
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

		$('input[name=submit]').click(function() {
			var ip = $('input[name=ip]').val();
			$('input[name=ip]').focus();
			if (ip == '') {
				alert('{LANG.banip_error_ip}');
				return false;
			}
			var area = $('select[name=area]').val();
			$('select[name=area]').focus();
			if (area == '0') {
				alert('{LANG.banip_error_area}');
				return false;
			}
		});
		$('a.deleteone').click(function() {
			if (confirm('{LANG.banip_delete_confirm}')) {
				var url = $(this).attr('href');
				$.ajax({
					type : 'POST',
					url : url,
					data : '',
					success : function(data) {
						alert('{LANG.banip_del_success}');
						window.location = 'index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}={OP}';
					}
				});
			}
			return false;
		});
	});
	//]]>
</script>
<!-- END: main -->