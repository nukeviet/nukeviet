<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="alert alert-danger">
	{error}
</div>
<!-- END: error -->

<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css">
<link rel="stylesheet" type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css">

<script type="text/javascript">var inrow = '{inrow}';</script>
<form class="form-horizontal" action="" enctype="multipart/form-data" method="post">
	<input type="hidden" value="1" name="save">
	<input type="hidden" value="{rowcontent.id}" name="id">

	<div class="row">
		<div class="col-sm-24 col-md-18">
			<div class="panel panel-default">
				<div class="panel-heading">{LANG.product_info}</div>
				<div class="panel-body">
					<div class="row">
						<div class="form-group">
							<label class="col-md-4 control-label">{LANG.name} <span class="require">(*)</span></label>
							<div class="col-md-20">
								<input type="text" maxlength="255" value="{rowcontent.title}" name="title" id="idtitle" class="form-control" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 control-label">{LANG.alias}</label>
							<div class="col-md-20">
								<div class="input-group">
									<input class="form-control" name="alias" type="text" id="idalias" value="{rowcontent.alias}" maxlength="255" />
									<span class="input-group-btn">
										<button class="btn btn-default" type="button">
											<i class="fa fa-refresh fa-lg" onclick="get_alias('content', {rowcontent.id});">&nbsp;</i>
										</button>
									</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 control-label">{LANG.content_cat}</label>
							<div class="col-md-20">
								<select class="form-control" name="catid" id="catid" onchange="nv_change_catid(this, {rowcontent.id})" style="width: 100%">
									<option value="0" data-label="1"> ---{LANG.content_cat_c}--- </option>
									<!-- BEGIN: rowscat -->
									<option value="{ROWSCAT.catid}" {ROWSCAT.selected} data-label="{ROWSCAT.typeprice}">{ROWSCAT.title}</option>
									<!-- END: rowscat -->
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="form-group">
							<label class="col-md-4 control-label">{LANG.content_product_code}</label>
							<div class="col-md-8">
								<input class="form-control" name="product_code" type="text" value="{rowcontent.product_code}" maxlength="255"/>
							</div>
							<label class="col-md-4 control-label">{LANG.prounit}</label>
							<div class="col-md-8">
								<select class="form-control" name="product_unit">
									<!-- BEGIN: rowunit -->
									<option value="{uid}" {uch}>{utitle}</option>
									<!-- END: rowunit -->
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 control-label">{LANG.weights}</label>
							<div class="col-md-8">
								<div class="row">
									<div class="col-md-14">
										<input class="form-control" type="text" maxlength="50" value="{rowcontent.product_weight}" name="product_weight" onkeyup="this.value=FormatNumber(this.value);" id="f_weight"/>
									</div>
									<div class="col-md-10">
										<select class="form-control" name="weight_unit">
											<!-- BEGIN: weight_unit -->
											<option value="{WEIGHT.code}" {WEIGHT.select}>{WEIGHT.title}</option>
											<!-- END: weight_unit -->
										</select>
									</div>
								</div>
							</div>
							<!-- BEGIN: warehouse -->
							<label class="col-md-4 control-label">{LANG.content_product_number}</label>
							<div class="col-md-8">
								<!-- BEGIN: edit -->
								<div class="input-group">
								      <div class="input-group-addon">{rowcontent.product_number} +</div>
								      <input class="form-control" type="number" min="0" maxlength="50" value="0" name="product_number" />
								</div>
								<!-- END: edit -->
								<!-- BEGIN: add --><input class="form-control" type="number" min="0" maxlength="50" value="{rowcontent.product_number}" name="product_number" /><!-- END: add -->
							</div>
							<!-- END: warehouse -->
						</div>
						<div id="priceproduct">
							<div class="form-group">
								<!-- BEGIN: product_price -->
								<label class="col-md-4 control-label">{LANG.content_product_product_price}</label>
								<div class="col-md-8">
									<input class="form-control" type="text" maxlength="50" value="{rowcontent.product_price}" name="product_price" onkeyup="this.value=FormatNumber(this.value);" id="f_money"/>
								</div>
								<div class="col-md-4">
									<select class="form-control" name="money_unit">
										<!-- BEGIN: money_unit -->
										<option value="{MON.code}" {MON.select}>{MON.currency}</option>
										<!-- END: money_unit -->
									</select>
								</div>
								<!-- END: product_price -->
								<!-- BEGIN: typeprice1 -->
								<div class="col-md-8">
									<select class="form-control" name="discount_id">
										<option value="0"> ---{LANG.content_product_discounts}--- </option>
										<!-- BEGIN: discount -->
										<option value="{DISCOUNT.did}" {DISCOUNT.selected} >{DISCOUNT.title}</option>
										<!-- END: discount -->
									</select>
								</div>
								<!-- END: typeprice1 -->
							</div>

							<!-- BEGIN: typeprice2 -->
							<div class="form-group">
								<label class="col-md-4 control-label">{LANG.content_product_product_price}</label>
								<div class="col-md-15">
									<table id="id_price_config" class="table table-striped table-bordered table-hover">
										<thead>
											<tr>
												<th class="text-center"> {LANG.discount_to} </th>
												<th class="text-center"> {LANG.content_product_product_price} </th>
											</tr>
										</thead>
										<tfoot>
											<tr>
												<td colspan="2">
													<input type="button" value="{LANG.price_config_add}" onclick="nv_price_config_add_item();" class="btn btn-info" />
												</td>
											</tr>
										</tfoot>
										<tbody>
											<!-- BEGIN: loop -->
											<tr>
												<td><input class="form-control" type="number" name="price_config[{PRICE_CONFIG.id}][number_to]" value="{PRICE_CONFIG.number_to}"/></td>
												<td><input class="form-control" type="text" name="price_config[{PRICE_CONFIG.id}][price]" value="{PRICE_CONFIG.price}" onkeyup="this.value=FormatNumber(this.value);" style="text-align: right"/></td>
											</tr>
											<!-- END: loop -->
										</tbody>
									</table>
								</div>
								<div class="col-md-5">
									<select class="form-control" name="money_unit">
										<!-- BEGIN: money_unit -->
										<option value="{MON.code}" {MON.select}>{MON.currency}</option>
										<!-- END: money_unit -->
									</select>
								</div>
							</div>
							<!-- END: typeprice2 -->
						</div>
					</div>
				</div>
			</div>

			<div class="panel panel-default">
				<div class="panel-heading">{LANG.content_homeimg}</div>
				<div class="panel-body">
					<div class="form-group">
						<div class="input-group">
							<input class="form-control" type="text" name="homeimg" id="homeimg" value="{rowcontent.homeimgfile}"/>
							<span class="input-group-btn">
								<button class="btn btn-default" type="button" id="selectimg">
									<em class="fa fa-folder-open-o fa-fix">&nbsp;</em>
								</button>
							</span>
						</div>
					</div>
					<div class="form-group">
						<input class="form-control" type="text" maxlength="255" value="{rowcontent.homeimgalt}" name="homeimgalt" placeholder="{LANG.content_homeimgalt}" />
					</div>

					<div id="otherimage">
						<!-- BEGIN: otherimage -->
						<div class="form-group">
							<div class="input-group">
								<input value="{DATAOTHERIMAGE.value}" name="otherimage[]" id="otherimage_{DATAOTHERIMAGE.id}" class="form-control" maxlength="255">
								<span class="input-group-btn">
									<button class="btn btn-default" type="button" onclick="nv_open_browse( '{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}=upload&popup=1&area=otherimage_{DATAOTHERIMAGE.id}&path={NV_UPLOADS_DIR}/{MODULE_UPLOAD}&currentpath={CURRENT}&type=file', 'NVImg', 850, 500, 'resizable=no,scrollbars=no,toolbar=no,location=no,status=no' ); return false; ">
										<em class="fa fa-folder-open-o fa-fix">&nbsp;</em>
									</button>
								</span>
							</div>
						</div>
						<!-- END: otherimage -->
					</div>
					<input type="button" class="btn btn-info" onclick="nv_add_otherimage();" value="{LANG.add_otherimage}">
				</div>
			</div>

			<div class="panel panel-default">
				<div class="panel-heading">{LANG.content_hometext} <span class="require">(*)</span> {LANG.content_notehome}</div>
				<div class="panel-body">{edit_hometext}</div>
			</div>

			<div class="panel panel-default">
				<div class="panel-heading">{LANG.content_bodytext} <span class="require">(*)</span> {LANG.content_notehome}</div>
				<div class="panel-body">{edit_bodytext}</div>
			</div>

			<!-- BEGIN: files -->
			<div class="panel panel-default">
				<div class="panel-heading">{LANG.download_file}</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-19">
							<select name="files[]" id="files" class="form-control" style="width: 100%" multiple="multiple">
								<!-- BEGIN: loop -->
								<option value="{FILES.id}" {FILES.selected}>{FILES.title}</option>
								<!-- END: loop -->
							</select>
						</div>
						<div class="col-md-1">
							<span class="text-middle">{LANG.download_file_or}</span>
						</div>
						<div class="col-md-4">
							<button class="btn btn-primary" id="add_file">{LANG.download_file_add}</button>
							<div class="modal fade" id="idmodals" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
											<h4>{LANG.download_file_add}</h4>
										</div>
										<div class="modal-body">
											<p class="text-center"><em class="fa fa-spinner fa-spin fa-3x">&nbsp;</em></p>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- END: files -->

			<!-- BEGIN: gift -->
			<div class="panel panel-default">
				<div class="panel-heading">{LANG.content_gift}</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-xs-24 col-sm-12">
							<div class="form-group">
								<textarea class="form-control" name="gift_content" style="height:85px">{rowcontent.gift_content}</textarea>
							</div>
						</div>
						<div class="col-xs-24 col-sm-12">
							<div class="col-xs-24" style="margin-bottom: 5px">
								<div class="row">
									<div class="col-xs-12">
										<div class="form-group">
											<div class="input-group">
												<input type="text" class="form-control" name="gift_from" value="{rowcontent.gift_from}" id="gift_from" readonly="readonly" placeholder="{LANG.date_from}">
												<span class="input-group-btn">
													<button class="btn btn-default" type="button" id="from-btn">
														<em class="fa fa-calendar fa-fix">&nbsp;</em>
													</button> </span>
											</div>
										</div>
									</div>
									<div class="col-xs-6">
										<select class="form-control" name="gift_from_h" style="width: 100%">
											{gift_from_h}
										</select>
									</div>
									<div class="col-xs-6">
										<select class="form-control" name="gift_from_m" style="width: 100%">
											{gift_from_m}
										</select>
									</div>
								</div>
							</div>
							<div class="col-xs-24">
								<div class="row">
									<div class="col-xs-12">
										<div class="form-group">
											<div class="input-group">
												<input type="text" class="form-control" name="gift_to" value="{rowcontent.gift_to}" id="gift_to" readonly="readonly" placeholder="{LANG.date_to}">
												<span class="input-group-btn">
													<button class="btn btn-default" type="button" id="to-btn">
														<em class="fa fa-calendar fa-fix">&nbsp;</em>
													</button> </span>
											</div>
										</div>
									</div>
									<div class="col-xs-6">
										<select class="form-control" name="gift_to_h" style="width: 100%">
											{gift_to_h}
										</select>
									</div>
									<div class="col-xs-6">
										<select class="form-control" name="gift_to_m" style="width: 100%">
											{gift_to_m}
										</select>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- END: gift -->

			<div id="custom_form">{DATACUSTOM_FORM}</div>

			<div class="panel panel-default">
				<div class="panel-heading">{LANG.tag}</div>
				<div class="panel-body">
					<div class="form-group">
						<label class="col-md-4 control-label">{LANG.tag_title}</label>
						<div class="col-md-20">
							<input type="text" maxlength="255" value="{rowcontent.tag_title}" name="tag_title" class="form-control" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label">{LANG.tag_description}</label>
						<div class="col-md-20">
							<textarea class="form-control" name="tag_description">{rowcontent.tag_description}</textarea>
						</div>
					</div>
				</div>
			</div>

		</div>

		<div class="col-sm-24 col-md-6">
			<!-- BEGIN:block_cat -->
			<div class="panel panel-default">
				<div class="panel-heading">{LANG.content_block}</div>
				<div class="panel-body">
					{row_block}
				</div>
			</div>
			<!-- END:block_cat -->

			<div class="panel panel-default">
				<div class="panel-heading">{LANG.content_keywords}</div>
				<div class="panel-body">
					<div class="message_body" style="overflow: auto">
						<div class="clearfix uiTokenizer uiInlineTokenizer">
							<div id="keywords" class="tokenarea">
								<!-- BEGIN: keywords -->
								<span class="uiToken removable" title="{KEYWORDS}"> {KEYWORDS} <input type="hidden" autocomplete="off" name="keywords[]" value="{KEYWORDS}" /> <a onclick="$(this).parent().remove();" class="remove uiCloseButton uiCloseButtonSmall" href="javascript:void(0);"></a> </span>
								<!-- END: keywords -->
							</div>
							<div class="uiTypeahead">
								<div class="wrap">
									<input type="hidden" class="hiddenInput" autocomplete="off" value="" />
									<div class="innerWrap">
										<input id="keywords-search" type="text" placeholder="{LANG.input_keyword_tags}" class="form-control textInput" style="width: 100%;" />
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="panel panel-default">
				<div class="panel-heading">{LANG.content_publ_date} <span class="timestamp">{LANG.content_notetime}</span></div>
				<div class="panel-body">
					<div class="form-group">
						<div class="input-group">
							<input class="form-control" name="publ_date" id="publ_date" value="{publ_date}" maxlength="10" type="text" />
							<span class="input-group-btn">
								<button class="btn btn-default" type="button" id="publ_date-btn">
									<em class="fa fa-calendar fa-fix">&nbsp;</em>
								</button> </span>
						</div>
					</div>
					<div class="row">
						<div class="form-group">
							<div class="col-xs-12">
								<select class="form-control" name="phour">
									{phour}
								</select>
							</div>
							<div class="col-xs-12">
								<select class="form-control" name="pmin">
									{pmin}
								</select>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="panel panel-default">
				<div class="panel-heading">{LANG.content_exp_date} <span class="timestamp">{LANG.content_notetime}</span></div>
				<div class="panel-body">
					<div class="form-group">
						<div class="input-group">
							<input class="form-control" name="exp_date" id="exp_date" value="{exp_date}" maxlength="10" type="text" />
							<span class="input-group-btn">
								<button class="btn btn-default" type="button" id="exp_date-btn">
									<em class="fa fa-calendar fa-fix">&nbsp;</em>
								</button> </span>
						</div>
					</div>
					<div class="row">
						<div class="form-group">
							<div class="col-xs-12">
								<select class="form-control" name="ehour">
									{ehour}
								</select>
							</div>
							<div class="col-xs-12">
								<select class="form-control" name="emin">
									{emin}
								</select>
							</div>
						</div>
					</div>
					<div style="margin-top: 5px;">
						<input type="checkbox" value="1" name="archive" {archive_checked} />
						<label>{LANG.content_archive}</label>
					</div>
				</div>
			</div>

			<div class="panel panel-default">
				<div class="panel-heading">{LANG.content_extra}</div>
				<div class="panel-body">
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
					</div>
				</div>
			</div>

			<div class="panel panel-default">
				<div class="panel-heading">{LANG.content_allowed_comm}</div>
				<div class="panel-body">
					<!-- BEGIN: allowed_comm -->
					<div class="row">
						<label><input name="allowed_comm[]" type="checkbox" value="{ALLOWED_COMM.value}" {ALLOWED_COMM.checked} />{ALLOWED_COMM.title}</label>
					</div>
					<!-- END: allowed_comm -->
				</div>
			</div>
		</div>
	</div>

	<div class="panel panel-default">
		<div class="panel-heading">{LANG.content_group}</div>
		<div class="panel-body">
			<div id="list_group">
				<div id="listgroupid">&nbsp;</div>
			</div>
		</div>
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

<div id="message"></div>

<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}themes/admin_default/js/content.js"></script>

<script type="text/javascript">
	var file_items = '{FILE_ITEMS}';
	var file_selectfile = '{LANG.file_selectfile}';
	var nv_base_adminurl = '{NV_BASE_ADMINURL}';
	var inputnumber = '{LANG.error_inputnumber}';
	var file_dir = '{NV_UPLOADS_DIR}/{MODULE_UPLOAD}';
	var currentpath = "{CURRENT}";

	$(document).ready(function() {
		$("#catid").select2();
	});

	$("#selectimg").click(function() {
		var area = "homeimg";
		var path = "{NV_UPLOADS_DIR}/{MODULE_UPLOAD}";
		var currentpath = "{CURRENT}";
		var type = "image";
		nv_open_browse("{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}=upload&popup=1&area=" + area + "&path=" + path + "&type=" + type + "&currentpath=" + currentpath, "NVImg", 850, 500, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
		return false;
	});

	$('[type="submit"]').hover(function() {
		if ($('[name="keywords[]"]').length == 0) {
			if ($('#message-tags').length == 0) {
				$('#message').html('<div style="margin-top: 10px" id="message-tags" class="alert alert-danger">{LANG.content_tags_empty}.<!-- BEGIN: auto_tags --> {LANG.content_tags_empty_auto}.<!-- END: auto_tags --></div>');
			}
		} else {
			$('#message-tags').remove();
		}
	});

	$.get( '{url_load}', function( data ) {
		if( data != '' ){
			$('#list_group').show();
			$("#listgroupid").html( data );
		}
	});
</script>

<!-- BEGIN: files_js -->
<script type="text/javascript">
	$("#files").select2({
		placeholder: "{LANG.download_file_chose_h}"
	});

	$('#add_file').click(function(){
        $('#idmodals').removeData('bs.modal');
     	$('#idmodals').on('show.bs.modal', function () {
             $('#idmodals .modal-body').load( script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=download&popup=1' );
        }).modal();
		return false;
	});
</script>
<!-- END: files_js -->

<!-- BEGIN:getalias -->
<script type="text/javascript">
	$("#idtitle").change(function() {
		get_alias('content', {rowcontent.id});
	});
</script>
<!-- END:getalias -->

<!-- END:main -->