<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="alert alert-danger">{error}</div>
<!-- END: error -->

<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.core.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.theme.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.menu.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.autocomplete.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.css" rel="stylesheet" />
<script type="text/javascript">var inrow = '{inrow}';</script>
<form class="form-inline" action="" enctype="multipart/form-data" method="post">
	<input type="hidden" value="1" name="save">
	<input type="hidden" value="{rowcontent.id}" name="id">
	<div class="row">
		<table width="100%" style="margin-bottom:0">
			<tr>
				<td valign="top">
				<table class="table table-striped table-bordered table-hover">
					<tbody>
						<tr>
							<td class="150px"><strong>{LANG.name} <span class="require">(*)</span></strong></td>
							<td><input type="text" maxlength="255" value="{rowcontent.title}" name="title" id="idtitle" class="form-control" style="width: 400px" /></td>
						</tr>
						<tr>
							<td><strong>{LANG.alias}: </strong></td>
							<td><input class="form-control" name="alias" type="text" id="idalias" value="{rowcontent.alias}" maxlength="255" style="width: 400px" /> &nbsp; <i class="fa fa-refresh fa-lg" onclick="get_alias();">&nbsp;</i></td>
						</tr>
						<tr>
							<td><strong>{LANG.content_cat} <span class="require">(*)</span></strong></td>
							<td>
							<select class="form-control" name="catid" style="width:300px" onchange="nv_getgroup(this)">
								<!-- BEGIN: rowscat -->
								<option value="{catid_i}" {select} >{xtitle_i} {title_i}</option>
								<!-- END: rowscat -->
							</select>
							</td>
						</tr>
						<tr>
							<td><strong>{LANG.content_sourceid}</strong></td>
							<td>
								<select class="form-control" name="sourceid" style="width: 300px;">
									{sourceid}
								</select><input class="form-control" type="text" maxlength="255" id="AjaxSourceText" value="{rowcontent.sourcetext}" name="sourcetext" style="width:225px;">
							</td>
						</tr>
					</tbody>
				</table>
				<table class="table table-striped table-bordered table-hover">
					<tbody>
						<tr>
							<td><strong>{LANG.content_product_code}: </strong></td>
							<td><input class="form-control" name="product_code" type="text" value="{rowcontent.product_code}" maxlength="255"/></td>
							<td align="right"><strong>{LANG.content_product_product_price}</strong></td>
							<td><input class="form-control" type="text" maxlength="50" value="{rowcontent.product_price}" name="product_price" style="width: 80px;" onkeyup="this.value=FormatNumber(this.value);" id="f_money"/>
							<select class="form-control" name="money_unit">
								<!-- BEGIN: money_unit -->
								<option value="{MON.code}" {MON.select}>{MON.currency}</option>
								<!-- END: money_unit -->
							</select></td>
						</tr>
						<tr>
							<td class="150px"><strong>{LANG.content_product_number}</strong></td>
							<td>
							<!-- BEGIN: edit -->
							<strong>{rowcontent.product_number}</strong> + <input class="form-control" type="text" maxlength="50" value="0" name="product_number" style="width: 50px;" />
							<!-- END: edit -->
							<!-- BEGIN: add -->
							<input class="form-control" type="text" maxlength="50" value="{rowcontent.product_number}" name="product_number" style="width: 50px;" />
							<!-- END: add -->
							<select class="form-control" name="product_unit">
								<!-- BEGIN: rowunit -->
								<option value="{uid}" {uch}>{utitle}</option>
								<!-- END: rowunit -->
							</select></td>
							<td align="right"><strong>{LANG.content_product_discounts}</strong></td>
							<td><input class="form-control" type="text" maxlength="3" value="{rowcontent.product_discounts}" name="product_discounts" style="width: 50px;" /><strong>%</strong></td>
						</tr>
					</tbody>
				</table>
				<table class="table table-striped table-bordered table-hover">
					<tr>
						<td width="110px"><strong>{LANG.content_product_address}</strong></td>
						<td><input class="form-control" type="text" maxlength="255" value="{rowcontent.address}" name="address" style="width:98%;"/></td>
					</tr>
				</table>
				<table class="table table-striped table-bordered table-hover">
					<tbody>
						<tr>
							<td><strong>{LANG.content_homeimg}</strong></td>
						</tr>
						<tr>
							<td><input class="form-control" style="width:400px; margin-right: 5px" type="text" name="homeimg" id="homeimg" value="{rowcontent.homeimgfile}"/><input type="button" value="{LANG.browse_image}" name="selectimg" class="btn btn-info" style="margin-right: 5px" /><input type="button" class="btn btn-info" onclick="nv_add_otherimage();" value="{LANG.add_otherimage}"></td>
						</tr>
					</tbody>
					<tbody id="otherimage">
						<!-- BEGIN: otherimage -->
						<tr>
							<td><input value="{DATAOTHERIMAGE.value}" name="otherimage[]" id="otherimage_{DATAOTHERIMAGE.id}" class="form-control" maxlength="255"><input value="{LANG.browse_image}" name="selectfile" onclick="nv_open_browse( '{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}=upload&popup=1&area=otherimage_{DATAOTHERIMAGE.id}&path={NV_UPLOADS_DIR}/{module_name}&currentpath={CURRENT}&type=file', 'NVImg', 850, 500, 'resizable=no,scrollbars=no,toolbar=no,location=no,status=no' ); return false; " type="button"></td>
						</tr>
						<!-- END: otherimage -->
						<tr>
							<td> {LANG.content_homeimgalt} </td>
						</tr>
						<tr>
							<td><input class="form-control" type="text" maxlength="255" value="{rowcontent.homeimgalt}" name="homeimgalt" style="width:100%" /></td>
						</tr>
					</tbody>
				</table>
				<table class="table table-striped table-bordered table-hover">
					<tbody>
						<tr>
							<td><strong>{LANG.content_hometext} <span class="require">(*)</span></strong> {LANG.content_notehome}</td>
						</tr>
						<tr>
							<td><textarea class="form-control" rows="4" name="hometext" style="width:98%">{rowcontent.hometext}</textarea></td>
						</tr>
						<tr>
							<td><strong>{LANG.content_note}</strong></td>
						</tr>
						<tr>
							<td><textarea class="form-control" rows="4" name="note" style="width:100%">{rowcontent.note}</textarea></td>
						</tr>
						<tr>
							<td><strong>{LANG.content_warranty}</strong></td>
						</tr>
						<tr>
							<td><textarea class="form-control" name="warranty" style="width:100%; height:50px">{rowcontent.warranty}</textarea></td>
						</tr>
						<tr>
							<td><strong>{LANG.content_promotional}</strong></td>
						</tr>
						<tr>
							<td><textarea class="form-control" name="promotional" style="width:100%;height:50px">{rowcontent.promotional}</textarea></td>
						</tr>
					</tbody>
				</table></td>
				<td valign="top" style="width: 280px">
				<div style="margin-left:4px;">
					<!-- BEGIN:listgroup -->
					<table class="table table-striped table-bordered table-hover">
						<tbody>
							<tr>
								<td><strong>{LANG.content_group}</strong></td>
							</tr>
							<tr>
								<td>
								<div style="padding:4px; height:100px;background:#FFFFFF; overflow:auto; text-align:left; border: 1px solid #CCCCCC">
									<ul style="margin:0" id="listgroupid"></ul>
								</div></td>
							</tr>
						</tbody>
					</table>
					<!-- END:listgroup -->
					<!-- BEGIN:block_cat -->
					<table class="table table-striped table-bordered table-hover">
						<tbody>
							<tr>
								<td><strong>{LANG.content_block}</strong></td>
							</tr>
							<tr>
								<td>
								<div style="padding:4px; background:#FFFFFF; text-align:left; border: 1px solid #CCCCCC">
									{row_block}
								</div></td>
							</tr>
						</tbody>
					</table>
					<!-- END:block_cat -->
					<table class="table table-striped table-bordered table-hover">
						<tbody>
							<tr>
								<td style="line-height:16px"><strong>{LANG.content_keywords}</strong>
								<br />
								{LANG.content_keywords_note} <a onclick="create_keywords();" href="javascript:void(0);" style="color:#0099CC">{LANG.content_clickhere}</a></td>
							</tr>
							<tr>
								<td><textarea rows="3" cols="20" id="keywords" name="keywords" style="width: 98%;">{rowcontent.keywords}</textarea></td>
							</tr>
						</tbody>
					</table>
					<table class="table table-striped table-bordered table-hover">
						<tbody>
							<tr>
								<td><strong>{LANG.content_publ_date}</strong><span class="timestamp">{LANG.content_notetime}</span></td>
							</tr>
							<tr>
								<td>
									<input class="form-control" name="publ_date" id="publ_date" value="{publ_date}" style="width: 90px;" maxlength="10" readonly="readonly" type="text" />
									<select class="form-control" name="phour">
										{phour}
									</select> :
									<select class="form-control" name="pmin">
										{pmin}
									</select><input type="button" value="{LANG.del}" style="font-size:11px" onclick="clearobval('publ_date')" />
								</td>
							</tr>
						</tbody>
					</table>
					<table class="table table-striped table-bordered table-hover">
						<tbody>
							<tr>
								<td><strong>{LANG.content_exp_date}</strong><span class="timestamp">{LANG.content_notetime}</span></td>
							</tr>
							<tr>
								<td>
								<div>
									<input class="form-control" name="exp_date" id="exp_date" value="{exp_date}" style="width: 90px;" maxlength="10" readonly="readonly" type="text" />
									<select class="form-control" name="ehour">
										{ehour}
									</select>
									:
									<select class="form-control" name="emin">
										{emin}
									</select>
									<input type="button" value="{LANG.del}" style="font-size:11px;" onclick="clearobval('exp_date')" />
								</div>
								<div style="margin-top: 5px;">
									<input type="checkbox" value="1" name="archive" {archive_checked} />
									<label>{LANG.content_archive}</label>
								</div></td>
							</tr>
						</tbody>
					</table>
					<table class="table table-striped table-bordered table-hover">
						<tbody>
							<tr>
								<td><strong>{LANG.content_extra}</strong></td>
							</tr>
							<tr>
								<td>
								<div style="margin-bottom: 2px;">
									<input type="checkbox" value="1" name="inhome" {inhome_checked}/>
									<label>{LANG.content_inhome}</label>
								</div>
								<div style="margin-bottom: 2px;">
									<input type="checkbox" value="1" name="allowed_rating" {allowed_rating_checked}/>
									<label>{LANG.content_allowed_rating}</label>
								</div>
								<div style="margin-bottom: 2px;">
									<input type="checkbox" value="1" name="allowed_send" {allowed_send_checked}/>
									<label>{LANG.content_allowed_send}</label>
								</div>
								<div style="margin-bottom: 2px;">
									<input type="checkbox" value="1" name="allowed_print" {allowed_print_checked} />
									<label>{LANG.content_allowed_print}</label>
								</div>
								<div style="margin-bottom: 2px;">
									<input type="checkbox" value="1" name="allowed_save" {allowed_save_checked} />
									<label>{LANG.content_allowed_save}</label>
								</div>
								<div style="margin-bottom: 2px;">
									<input type="checkbox" name="showprice" value="1" {ck_showprice}/>{LANG.content_showprice}
								</div></td>
							</tr>
						</tbody>
					</table>
					<table class="table table-striped table-bordered table-hover">
						<tbody>
							<tr>
								<td><strong>{LANG.content_allowed_comm}</strong></td>
							</tr>
							<tr>
								<td>
									<!-- BEGIN: allowed_comm -->
									<div class="row">
										<label><input name="allowed_comm[]" type="checkbox" value="{ALLOWED_COMM.value}" {ALLOWED_COMM.checked} />{ALLOWED_COMM.title}</label>
									</div>
									<!-- END: allowed_comm -->
								</td>
							</tr>
						</tbody>
					</table>
				</div></td>
			</tr>
		</table>
	</div>
	<div class="gray">
		<table class="table table-striped table-bordered table-hover">
			<tbody>
				<tr>
					<td><strong>{LANG.content_bodytext} <span class="require">(*)</span></strong>{LANG.content_bodytext_note}</td>
				</tr>
				<tr>
					<td>
					<div style="padding:2px; background:#CCCCCC; margin:0; display:block; position:relative">
						{edit_bodytext}
					</div></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="text-center" style="margin-top: 10px">
		<!-- BEGIN:status -->
		<input class="btn btn-primary" name="statussave" type="submit" value="{LANG.save}" />
		<!-- END:status -->
		<!-- BEGIN:status0 -->
		<input class="btn btn-primary" name="status0" type="submit" value="{LANG.save_temp}" />
		<input class="btn btn-primary" name="status1" type="submit" value="{LANG.publtime}" />
		<!-- END:status0 -->
	</div>
</form>

<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.menu.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.autocomplete.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>

<script type="text/javascript">
	$(document).ready(function() {
		$("#publ_date,#exp_date").datepicker({
			showOn : "both",
			dateFormat : "dd/mm/yy",
			changeMonth : true,
			changeYear : true,
			showOtherMonths : true,
			buttonImage : nv_siteroot + "images/calendar.gif",
			buttonImageOnly : true
		});

		var cachesource = {};
		$("#AjaxSourceText").autocomplete({
			minLength : 2,
			delay : 500,
			source : function(request, response) {
				var term = request.term;
				if ( term in cachesource) {
					response(cachesource[term]);
					return;
				}
				$.getJSON(script_name + "?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=sourceajax", request, function(data, status, xhr) {
					cachesource[term] = data;
					response(data);
				});
			}
		});
	});

	var file_items = {FILE_ITEMS};
	var file_selectfile = '{LANG.file_selectfile}';
	var nv_base_adminurl = '{NV_BASE_ADMINURL}';
	var file_dir = '{NV_UPLOADS_DIR}/{module_name}';
	var currentpath = "{CURRENT}";

	$("input[name=selectimg]").click(function() {
		var area = "homeimg";
		var path = "{NV_UPLOADS_DIR}/{module_name}";
		var currentpath = "{CURRENT}";
		var type = "image";
		nv_open_browse("{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}=upload&popup=1&area=" + area + "&path=" + path + "&type=" + type + "&currentpath=" + currentpath, "NVImg", 850, 500, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
		return false;
	});

	function clearobval(ob) {
		$("#" + ob + "").val('');
	}

	$("#listgroupid").load('{url_load}');

	function FormatNumber(str) {

		var strTemp = GetNumber(str);
		if (strTemp.length <= 3)
			return strTemp;
		strResult = "";
		for (var i = 0; i < strTemp.length; i++)
			strTemp = strTemp.replace(",", "");
		var m = strTemp.lastIndexOf(".");
		if (m == -1) {
			for (var i = strTemp.length; i >= 0; i--) {
				if (strResult.length > 0 && (strTemp.length - i - 1) % 3 == 0)
					strResult = "," + strResult;
				strResult = strTemp.substring(i, i + 1) + strResult;
			}
		} else {
			var strphannguyen = strTemp.substring(0, strTemp.lastIndexOf("."));
			var strphanthapphan = strTemp.substring(strTemp.lastIndexOf("."), strTemp.length);
			var tam = 0;
			for (var i = strphannguyen.length; i >= 0; i--) {

				if (strResult.length > 0 && tam == 4) {
					strResult = "," + strResult;
					tam = 1;
				}

				strResult = strphannguyen.substring(i, i + 1) + strResult;
				tam = tam + 1;
			}
			strResult = strResult + strphanthapphan;
		}
		return strResult;
	}

	function GetNumber(str) {
		var count = 0;
		for (var i = 0; i < str.length; i++) {
			var temp = str.substring(i, i + 1);
			if (!(temp == "," || temp == "." || (temp >= 0 && temp <= 9))) {
				alert("{LANG.inputnumber}");
				return str.substring(0, i);
			}
			if (temp == " ")
				return str.substring(0, i);
			if (temp == ".") {
				if (count > 0)
					return str.substring(0, ipubl_date);
				count++;
			}
		}
		return str;
	}

	function IsNumberInt(str) {
		for (var i = 0; i < str.length; i++) {
			var temp = str.substring(i, i + 1);
			if (!(temp == "." || (temp >= 0 && temp <= 9))) {
				alert("{LANG.inputnumber}");
				return str.substring(0, i);
			}
			if (temp == ",") {
				alert("{LANG.thaythedaucham}");
				return str.substring(0, i);
			}
		}
		return str;
	}
</script>

<!-- BEGIN:getalias -->
<script type="text/javascript">
	$("#idtitle").change(function() {
		get_alias();
	});
</script>
<!-- END:getalias -->
<!-- END:main -->