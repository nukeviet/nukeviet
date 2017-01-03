<!-- BEGIN: main -->
<div class="row" style="margin-bottom: 5px">
	<div class="col-xs-24">
		<div class="btn-group pull-left">
			<a href="{FIELD_ADD}" class="btn btn-info">{LANG.captionform_add}</a>
		</div>
		
		<div class="btn-group pull-right">
			<button type="button" class="btn btn-primary btn-xs" id="template">
				{LANG.template}
			</button>
			<button type="button" class="btn btn-primary  btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
				<span class="caret"></span>
				<span class="sr-only"></span>
			</button>
			<ul class="dropdown-menu" role="menu">
				<li>
					<a href="{TEM_ADD}">{LANG.template_add}</a>
				</li>
			</ul>
		</div>
	</div>
</div>
<script type="text/javascript">
	$('#template').click(function() {
		window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=template';
	});
</script>

<!-- BEGIN: data -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<colgroup>
			<col class="w100" />
			<col span="3" />
			<col class="w150" />
		</colgroup>
		<thead>
			<tr class="text-center">
				<th>{LANG.weight}</th>
				<th>{LANG.field_id}</th>
				<th>{LANG.field_title}</th>
				<th>{LANG.field_type}</th>
				<th class="text-center">{LANG.function}</th>
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td class="text-center">
				<select class="form-control" id="id_weight_{ROW.fid}" onchange="nv_chang_field({ROW.fid});">
					<!-- BEGIN: weight -->
					<option value="{WEIGHT.key}"{WEIGHT.selected}>{WEIGHT.title}</option>
					<!-- END: weight -->
				</select></td>
				<td>{ROW.field}</td>
				<td>{ROW.field_lang}</td>
				<td>{ROW.field_type} </td>
				<td class="text-center"><em class="fa fa-edit fa-lg">&nbsp;</em><a href="javascript:void(0);" onclick="nv_edit_field({ROW.fid});">{LANG.field_edit}</a> - <em class="fa fa-trash-o fa-lg">&nbsp;</em><a href="javascript:void(0);" onclick="nv_del_field({ROW.fid})">{LANG.delete}</a></td>
			</tr>
			<!-- END: loop -->
		</tbody>
		<tfoot>
			<tr>
				<td colspan="5" align="right">{NV_GENERATE_PAGE}</td>
			</tr>
		</tfoot>
		<tbody>
	</table>
</div>
<!-- END: data -->
<!-- BEGIN: load -->
<div id="module_show_list">
	&nbsp;
</div>
<link rel="stylesheet" type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css">
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery/jquery.validate.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.validator-{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<!-- BEGIN: error -->
<div class="alert alert-danger">
	{ERROR}
</div>
<!-- END: error -->
<form class="form-inline" action="{FORM_ACTION}" method="post" id="ffields" autocomplete="off">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<caption>
				<em class="fa fa-file-text-o">&nbsp;</em>{CAPTIONFORM}
			</caption>
			<colgroup>
				<col class="w250" />
				<col />
			</colgroup>
			<tbody>
				<!-- BEGIN: field -->
				<tr>
					<td>{LANG.field_id} <span class="require">*</span></td>
					<td><label> <input class="form-control w100" type="text" value="{DATAFORM.field}" name="field" {DATAFORM.fielddisabled}> <span style="color: #DB4156"><i class="fa fa-warning" ></i> {LANG.field_id_note}</span></label></td>
				</tr>
				<!-- END: field -->
				<tr>
					<td>{LANG.field_title}<span class="require"> *</span></td>
					<td><input class="form-control w350 required" type="text" value="{DATAFORM.title}" name="title"></td>
				</tr>

				<tr>
					<td> {LANG.template} <span class="require">*</span></td>
					<td>
					<div class="message_body" style="max-height:150px; overflow: auto">
						<!-- BEGIN: catid -->
						<label> <input type="checkbox" value="{CATS.id}" name="templateid[]" class="news_checkbox" {CATS.checked} {CATS.disabled} required> {CATS.title} </label>
						<!-- END: catid -->
					</div></td>
				</tr>

				<tr>
					<td>{LANG.field_description}:</td>
					<td>					<textarea cols="60" rows="3" name="description" style="width:350px; overflow: hidden;" class="form-control">{DATAFORM.description}</textarea></td>
				</tr>
				<tr>
					<td>{LANG.field_type}:</td>
					<td><!-- BEGIN: field_type -->
					<ul style="padding: 0">
						<!-- BEGIN: loop -->
						<li>
							<label for="f_{FIELD_TYPE.key}"> <input type="radio" {FIELD_TYPE.checked} id="f_{FIELD_TYPE.key}" value="{FIELD_TYPE.key}" name="field_type"> {FIELD_TYPE.value}</label>
						</li>
						<!-- END: loop -->
					</ul><span style="color: #DB4156"><i class="fa fa-warning" ></i> {LANG.field_type_note}<!-- END: field_type --> {FIELD_TYPE_TEXT} </td>
				</tr>
				<tr id="editorfields" {DATAFORM.editordisabled}>
					<td>{LANG.field_size}</td>
					<td><span class="text-middle">{LANG.field_width}</span><input class="form-control" style="width:100px" type="text" value="{DATAFORM.editor_width}" name="editor_width" maxlength="5"><span class="text-middle">{LANG.field_height}</span><input class="form-control" style="width:100px" type="text" value="{DATAFORM.editor_height}" name="editor_height" maxlength="5"></td>
				</tr>
			</tbody>
		</table>
		<table class="table table-striped table-bordered table-hover" id="textfields" {DATAFORM.display_textfields}>
			<caption>
				<em class="fa fa-file-text-o">&nbsp;</em>{LANG.field_options_text}
			</caption>
			<colgroup>
				<col class="w250" />
				<col />
			</colgroup>
			<tbody>
				<tr>
					<td>{LANG.field_match_type}</td>
					<td>
					<table class="table table-striped table-bordered table-hover">
						<!-- BEGIN: match_type -->
						<tr id="li_{MATCH_TYPE.key}">
							<td class="w350"><label for="m_{MATCH_TYPE.key}"> <input type="radio" {MATCH_TYPE.checked} id="m_{MATCH_TYPE.key}" value="{MATCH_TYPE.key}" name="match_type"> {MATCH_TYPE.value}</label></td>
							<td><!-- BEGIN: match_input --><input class="form-control" type="text" value="{MATCH_TYPE.match_value}" name="match_{MATCH_TYPE.key}" {MATCH_TYPE.match_disabled}><!-- END: match_input --></td>
						</tr>
						<!-- END: match_type -->
					</table></td>
				</tr>
				<tr>
					<td>{LANG.field_default_value}:</td>
					<td><input class="form-control w300" maxlength="255" type="text" value="{DATAFORM.default_value}" name="default_value"></td>
				</tr>
				<tr id="max_length">
					<td>{LANG.field_min_length}:</td>
					<td><input class="form-control w100 number"type="text" value="{DATAFORM.min_length}" name="min_length"><span style="margin-left: 50px;">{LANG.field_max_length}:</span><input class="form-control w100 number" type="text" value="{DATAFORM.max_length}" name="max_length"></td>
				</tr>
			</tbody>
		</table>

		<table class="table table-striped table-bordered table-hover" id="numberfields" {DATAFORM.display_numberfields}>
			<caption>
				<em class="fa fa-file-text-o">&nbsp;</em>{LANG.field_options_number}
			</caption>
			<colgroup>
				<col class="w250" />
				<col />
			</colgroup>
			<tbody>
				<tr>
					<td>{LANG.field_number_type}:</td>
					<td><input type="radio" value="1" name="number_type" {DATAFORM.number_type_1}> {LANG.field_integer} <input type="radio" value="2" name="number_type" {DATAFORM.number_type_2}> {LANG.field_real} </td>
				</tr>
				<tr>
					<td>{LANG.field_default_value}:</td>
					<td><input class="form-control w300 required number" maxlength="255" type="text" value="{DATAFORM.default_value_number}" name="default_value_number"></td>
				</tr>
				<tr>
					<td>{LANG.field_min_length}:</td>
					<td><input class="form-control w100 required number" type="text" value="{DATAFORM.min_number}" name="min_number_length" maxlength="11"><span style="margin-left: 50px;">{LANG.field_max_length}:</span><input class="form-control w100 required number" type="text" value="{DATAFORM.max_number}" name="max_number_length" maxlength="11"></td>
				</tr>
			</tbody>
		</table>

		<table class="table table-striped table-bordered table-hover" id="datefields" {DATAFORM.display_datefields}>
			<caption>
				<em class="fa fa-file-text-o">&nbsp;</em>{LANG.field_options_date}
			</caption>
			<colgroup>
				<col class="w250" />
				<col />
			</colgroup>
			<tbody>
				<tr>
					<td>{LANG.field_default_value}:</td>
					<td><label> <input type="radio" value="1" name="current_date" {DATAFORM.current_date_1}> {LANG.field_current_date}</label><label> <input type="radio" value="0" name="current_date" {DATAFORM.current_date_0}> {LANG.field_default_date}</label>&nbsp; <input class="form-control date" style="width:80px" type="text" value="{DATAFORM.default_date}" name="default_date"></td>
				</tr>
				<tr>
					<td>{LANG.field_min_date}:</td>
					<td><input class="form-control datepicker required date" style="width:80px" type="text" value="{DATAFORM.min_date}" name="min_date" maxlength="10"><span style="margin-left: 50px;">{LANG.field_max_date}:</span><input class="form-control datepicker required date" style="width:80px" type="text" value="{DATAFORM.max_date}" name="max_date" maxlength="10"></td>
				</tr>
			</tbody>
		</table>
		<table class="table table-striped table-bordered table-hover" id="choicetypes" {DATAFORM.display_choicetypes} >
			<colgroup>
				<col class="w250" />
				<col />
			</colgroup>
			<tr>
				<td>{LANG.field_choicetypes_title}</td>
				<td><!-- BEGIN: choicetypes_add -->
				<select class="form-control" name="choicetypes">
					<!-- BEGIN: choicetypes -->
					<option {CHOICE_TYPES.selected} value="{CHOICE_TYPES.key}"> {CHOICE_TYPES.value}</option>
					<!-- END: choicetypes -->
				</select><!-- END: choicetypes_add --><!-- BEGIN: choicetypes_add_hidden --> {FIELD_TYPE_SQL} <input type="hidden" name="choicetypes" value="{choicetypes_add_hidden}" /><!-- END: choicetypes_add_hidden --></td>
			</tr>
		</table>
		<table class="table table-striped table-bordered table-hover" id="choicesql" {DATAFORM.display_choicesql} >
			<caption>
				<em class="fa fa-file-text-o">&nbsp;</em>{LANG.field_options_choicesql}
			</caption>
			<colgroup>
				<col class="w250" />
				<col span="2"/>
			</colgroup>
			<thead>
				<tr>
					<th>{LANG.field_options_choicesql_module}</th>
					<th>{LANG.field_options_choicesql_table}</th>
					<th>{LANG.field_options_choicesql_column}</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><span id="choicesql_module">&nbsp;</span></td>
					<td><span id="choicesql_table">&nbsp;</span></td>
					<td><span id="choicesql_column">&nbsp;</span></td>
				</tr>
			</tbody>
		</table>
		<table class="table table-striped table-bordered table-hover" id="choiceitems" {DATAFORM.display_choiceitems} >
			<caption>
				<em class="fa fa-file-text-o">&nbsp;</em>{LANG.field_options_choice}
			</caption>
			<colgroup>
				<col class="w50" />
				<col span="2"/>
				<col class="w100" />
			</colgroup>
			<thead>
				<tr class="text-center">
					<th class="text-center">{LANG.field_number}</th>
					<th>{LANG.field_value}</th>
					<th>{LANG.field_text}</th>
					<th>{LANG.field_default_value}</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="4" ><input type="button" class="btn btn-primary" value="{LANG.field_add_choice}" onclick="nv_choice_fields_additem();" /></td>
				</tr>
			</tfoot>
			<tbody>
				<!-- BEGIN: loop_field_choice -->
				<tr class="text-center">
					<td class="text-center">{FIELD_CHOICES.number}</td>
					<td><input class="form-control validalphanumeric" style="width: 100%" type="text" value="{FIELD_CHOICES.key}" name="field_choice[{FIELD_CHOICES.number}]" /></td>
					<td><input class="form-control" style="width: 100%" type="text" value="{FIELD_CHOICES.value}" name="field_choice_text[{FIELD_CHOICES.number}]" /></td>
					<td><input type="radio" {FIELD_CHOICES.checked} value="{FIELD_CHOICES.number}" name="default_value_choice"></td>
				</tr>
				<!-- END: loop_field_choice -->
			</tbody>
		</table>
	</div>

	<input type="hidden" value="{DATAFORM.fid}" name="fid">
	<input type="hidden" value="{DATAFORM.field}" name="fieldid">
	<input class="btn btn-primary" type="submit" value="{LANG.save}" name="submit">

</form>
<script type="text/javascript">
	var items = '{FIELD_CHOICES_NUMBER}';
	function nv_choice_fields_additem() {
		items++;
		var newitem = '<tr class="text-center">';
		newitem += '	<td>' + items + '</td>';
		newitem += '	<td><input class="form-control w100 validalphanumeric" type="text" value="" name="field_choice[' + items + ']"></td>';
		newitem += '	<td><input class="form-control" clas="w350" type="text" value="" name="field_choice_text[' + items + ']"></td>';
		newitem += '	<td><input type="radio" value="' + items + '" name="default_value_choice"></td>';
		newitem += '	</tr>';
		$('#choiceitems').append(newitem);
	}

	function nv_chang_field(fid) {
		var nv_timer = nv_settimeout_disable('id_weight_' + fid, 5000);
		var new_vid = $('#id_weight_' + fid).val();
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=fields&nocache=' + new Date().getTime(), 'changeweight=1&fid=' + fid + '&new_vid=' + new_vid, function(res) {
			if (res != 'OK') {
				alert(nv_is_change_act_confirm[2]);
			}
			else
			{
				window.location.href = window.location.href;
			}
			clearTimeout(nv_timer);
		});
		return;
	}

	function nv_del_field(fid) {
		if (confirm(nv_is_del_confirm[0])) {
			$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=fields&nocache=' + new Date().getTime(), 'del=1&fid=' + fid, function(res) {
				if (res == 'OK') {
					window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=fields';
				} else {
					alert(nv_is_del_confirm[2]);
				}
			});
		}
		return false;
	}

	function nv_edit_field(fid) {
		window.location.href = script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=fields&fid=' + fid + '#ffields';
	}

	function nv_load_current_date() {
		if ($("input[name=current_date]:checked").val() == 1) {
			$("input[name=default_date]").attr('disabled', 'disabled');
			$("input[name=default_date]").datepicker("destroy");
		} else {
			$("input[name=default_date]").datepicker({
				showOn : "both",
				dateFormat : "dd/mm/yy",
				changeMonth : true,
				changeYear : true,
				showOtherMonths : true,
				buttonImage : nv_base_siteurl + "assets/images/calendar.gif",
				buttonImageOnly : true
			});
			$("input[name=default_date]").removeAttr("disabled");
			$("input[name=default_date]").focus();
		}
	}


	$(document).ready(function() {
		nv_load_current_date();

		$.validator.addMethod('validalphanumeric', function(str) {
			if (str == '') {
				return true;
			}
			var fieldCheck_rule = /^([a-zA-Z0-9_-])+$/;
			return (fieldCheck_rule.test(str) ) ? true : false;
		}, ' required a-z, 0-9, and _ only');

		$.validator.addMethod('validatefield', function(str) {
			if (str == '') {
				return true;
			}
			var fieldCheck_rule = /^([a-zA-Z0-9_])+$/;
			return (fieldCheck_rule.test(str) ) ? true : false;
		}, ' required a-z, and _ only');

		$('#ffields').validate({
			rules : {
				field : {
					required : true,
					validatefield : true
				}
			}
		});
	});

	$("input[name=field_type]").click(function() {
		var field_type = $("input[name='field_type']:checked").val();
		$("#textfields").hide();
		$("#numberfields").hide();
		$("#datefields").hide();
		$("#choicetypes").hide();
		$("#choiceitems").hide();
		$("#choicesql").hide();
		$("#editorfields").hide();
		if (field_type == 'textbox' || field_type == 'textarea' || field_type == 'editor') {
			if (field_type == 'textbox') {
				$("#li_alphanumeric").show();
				$("#li_email").show();
				$("#li_url").show();
			} else {
				$("#li_alphanumeric").hide();
				$("#li_email").hide();
				$("#li_url").hide();
				if (field_type == 'editor') {
					$("#editorfields").show();
				}
			}
			$("#textfields").show();
		} else if (field_type == 'number') {
			$("#numberfields").show();
		} else if (field_type == 'date') {
			$("#datefields").show();
		} else {
			$("#choicetypes").show();
			$("#textfields").hide();
			$("#numberfields").hide();
			$("#datefields").hide();
			nv_users_check_choicetypes("select[name=choicetypes]");
		}
	});

	$("input[name=match_type]").click(function() {
		$("input[name=match_regex]").attr('disabled', 'disabled');
		$("input[name=match_callback]").attr('disabled', 'disabled');
		var match_type = $("input[name='match_type']:checked").val();
		var max_length = $("input[name=max_length]").val();
		if (match_type == 'number') {
			if (max_length == 255) {
				$("input[name=max_length]").val(11);
			}
		} else if (max_length == 11) {
			$("input[name=max_length]").val(255);
		}
		if (match_type == 'regex') {
			$("input[name=match_regex]").removeAttr("disabled");
		} else if (match_type == 'callback') {
			$("input[name=match_callback]").removeAttr("disabled");
		}
	});

	$("input[name=current_date]").click(function() {
		nv_load_current_date();
	});
	$("select[name=choicetypes]").change(function() {
		nv_users_check_choicetypes(this);
	});
	$(".datepicker").datepicker({
		showOn : "both",
		dateFormat : "dd/mm/yy",
		changeMonth : true,
		changeYear : true,
		showOtherMonths : true,
		buttonImage : nv_base_siteurl + "assets/images/calendar.gif",
		buttonImageOnly : true
	});
	function nv_users_check_choicetypes(elemnet) {
		var choicetypes_val = $(elemnet).val();
		if (choicetypes_val == "field_choicetypes_text") {
			$("#choiceitems").show();
			$("#choicesql").hide();
		} else {
			$("#choiceitems").hide();
			$("#choicesql").show();
			nv_load_sqlchoice('module', '');
		}
	}

	function nv_load_sqlchoice(choice_name_select, choice_seltected) {
		var getval = "";
		if (choice_name_select == "table") {
			var choicesql_module = $("select[name=choicesql_module]").val();
			var module_selected = (choicesql_module == "" || choicesql_module == undefined ) ? '{SQL_DATA_CHOICE.0}' : choicesql_module;
			getval = "&module=" + module_selected;
			$("#choicesql_column").html("");
		} else if (choice_name_select == "column") {
			var choicesql_module = $("select[name=choicesql_module]").val();
			var module_selected = (choicesql_module == "" || choicesql_module == undefined ) ? '{SQL_DATA_CHOICE.0}' : choicesql_module;
			var choicesql_table = $("select[name=choicesql_table]").val();
			var table_selected = (choicesql_table == "" || choicesql_table == undefined ) ? '{SQL_DATA_CHOICE.1}' : choicesql_table;
			getval = "&module=" + module_selected + "&table=" + table_selected;
		}
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=fields&nocache=' + new Date().getTime(), 'choicesql=1&choice=' + choice_name_select + getval + '&choice_seltected=' + choice_seltected, function(res) {
			$('#choicesql_' + choice_name_select).html(res);

		});
	}
</script>
<!-- END: load -->
<!-- BEGIN: nv_load_sqlchoice -->
<script type="text/javascript">
	nv_load_sqlchoice('module', '{SQL_DATA_CHOICE.0}');
	nv_load_sqlchoice('table', '{SQL_DATA_CHOICE.1}');
	nv_load_sqlchoice('column', '{SQL_DATA_CHOICE.2}|{SQL_DATA_CHOICE.3}');
</script>
<!-- END: nv_load_sqlchoice -->
<!-- END: main -->
<!-- BEGIN: choicesql -->
<select class="form-control" onchange="nv_load_sqlchoice( '{choicesql_next}', '' )" name="{choicesql_name}">
	<!-- BEGIN: loop -->
	<option {SQL.sl} value="{SQL.key}">{SQL.val}</option>
	<!-- END: loop -->
</select>
<!-- END: choicesql -->
<!-- BEGIN: column -->
{LANG.field_options_choicesql_key}:
<select class="form-control" name="choicesql_column_key" id="choicesql_column_key">
	<!-- BEGIN: loop1 -->
	<option {SQL.sl_key} value="{SQL.key}">{SQL.val}</option>
	<!-- END: loop1 -->
</select>
{LANG.field_options_choicesql_val}:
<select class="form-control" name="choicesql_column_val" id="choicesql_column_val">
	<!-- BEGIN: loop2 -->
	<option {SQL.sl_val} value="{SQL.key}">{SQL.val}</option>
	<!-- END: loop2 -->
</select>
<!-- END: column -->