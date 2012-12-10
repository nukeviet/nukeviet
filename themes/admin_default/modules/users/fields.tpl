<!-- BEGIN: main -->
<!-- BEGIN: data -->
<table class="tab1">
	<thead>
		<tr align="center">
			<td style="width:60px;">{LANG.weight}</td>
			<td>Field ID</td>
			<td>Title</td>
			<td>Field Type</td>
			<td>Show during register</td>
			<td>Field required</td>
			<td>Show on profile pages</td>
			<td>&nbsp;</td>
		</tr>
	</thead>
	<!-- BEGIN: loop -->
	<tbody {ROW.class}>
		<tr>
			<td align="center">
			<select id="id_weight_{ROW.fid}" onchange="nv_chang_field({ROW.fid});">
				<!-- BEGIN: weight -->
				<option value="{WEIGHT.key}"{WEIGHT.selected}>{WEIGHT.title}</option>
				<!-- END: weight -->
			</select></td>
			<td>{ROW.field}</td>
			<td>{ROW.field_lang}</td>
			<td>{ROW.field_type} </td>
			<td align="center">
			<input type="checkbox" onclick="nv_edit_field({ROW.fid});" {ROW.show_register}/>
			</td>
			<td align="center">
			<input type="checkbox" onclick="nv_edit_field({ROW.fid});" {ROW.required}/>
			</td>
			<td align="center">
			<input type="checkbox" onclick="nv_edit_field({ROW.fid});" {ROW.show_profile}/>
			</td>
			<td><span class="edit_icon"><a href="javascript:void(0);" onclick="nv_edit_field({ROW.fid});">{LANG.field_edit}</a></span> &nbsp;-&nbsp; <span class="delete_icon"><a href="javascript:void(0);" onclick="nv_del_field({ROW.fid})">{LANG.delete}</a></span></td>
		</tr>
	</tbody>
	<!-- END: loop -->
</table>
<!-- END: data -->
<!-- BEGIN: load -->
<div id="module_show_list"></div>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/jquery/jquery.validate.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/jquery.validator-{NV_LANG_INTERFACE}.js"></script>
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.core.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.theme.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.css" rel="stylesheet" />
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<!-- BEGIN: error -->
<div style="width: 780px;" class="quote">
	<blockquote class="error">
		<p>
			<span>{ERROR}</span>
		</p>
	</blockquote>
</div>
<div class="clear"></div>
<!-- END: error -->
<form action="{FORM_ACTION}" method="post" id="ffields">
	<table class="tab1">
		<caption>
			{CAPTIONFORM}
		</caption>
		<colgroup>
			<col style="width: 250px;" />
		</colgroup>
		<!-- BEGIN: field -->
		<tbody>
			<tr>
				<td>Field ID:</td>
				<td>
				<input style="width:100px" type="text" value="{DATAFORM.field}" name="field" {DATAFORM.fielddisabled}>
				This is the unique identifier for this field. It cannot be changed once set </td>
			</tr>
		</tbody>
		<!-- END: field -->
		<tbody class="second">
			<tr>
				<td>Title</td>
				<td>
				<input class="required"  style="width:350px" type="text" value="{DATAFORM.title}" name="title">
				</td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td>Description:</td>
				<td valign="center">				<textarea style="width:350px" cols="60" rows="3" name="description" style="overflow: hidden;">{DATAFORM.description}</textarea></td>
			</tr>
		</tbody>

		<tbody class="second">
			<tr>
				<td>Field is required</td>
				<td>
				<input name="required" value="1" type="checkbox" {DATAFORM.required}>
				Required fields will always be shown during register. </td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td>Show during register</td>
				<td>
				<input name="show_register" value="1" type="checkbox" {DATAFORM.show_register}>
				</td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td>User editable</td>
				<td>
				<input name="user_editable" value="1" type="checkbox" {DATAFORM.user_editable}/>
				</td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td>Editable only once</td>
				<td>
				<input class="" name="user_editable_once" value="1" type="checkbox" {DATAFORM.user_editable_once}>
				</td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td>Show on profile pages</td>
				<td>
				<input name="show_profile" value="1" type="checkbox" {DATAFORM.show_profile}>
				</td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td>Field Type:</td>
				<td><!-- BEGIN: field_type -->
				<ul style="list-style: none">
					<!-- BEGIN: loop -->
					<li>
						<label for="f_{FIELD_TYPE.key}">
							<input type="radio" {FIELD_TYPE.checked} id="f_{FIELD_TYPE.key}" value="{FIELD_TYPE.key}" name="field_type">
							{FIELD_TYPE.value}</label>
					</li>
					<!-- END: loop -->
				</ul> It cannot be changed once set <!-- END: field_type --> {FIELD_TYPE_TEXT} </td>
			</tr>
		</tbody>
		<tbody  class="second" id="classfields" {DATAFORM.classdisabled}>
			<tr>
				<td>html class form</td>
				<td>
				<input class="validalphanumeric" style="width:300px" type="text" value="" name="class" maxlength="50">
				</td>
			</tr>
		</tbody>
		<tbody class="second" id="editorfields" {DATAFORM.editordisabled}>
			<tr>
				<td>Size textarea</td>
				<td> width:
				<input style="width:50px" type="text" value="{DATAFORM.editor_width}" name="editor_width" maxlength="5">
				height:
				<input style="width:50px" type="text" value="{DATAFORM.editor_height}" name="editor_height" maxlength="5">
				</td>
			</tr>
		</tbody>
	</table>
	<table class="tab1" id="textfields" {DATAFORM.display_textfields}>
		<caption>
			Options for Text Fields
		</caption>
		<colgroup>
			<col style="width: 250px;" />
		</colgroup>
		<tbody>
			<tr>
				<td>Value Match Requirements:
				<br>
				Empty values are always allowed. </td>
				<td>
				<ul style="list-style: none;">
					<!-- BEGIN: match_type -->
					<li id="li_{MATCH_TYPE.key}">
						<label for="m_{MATCH_TYPE.key}">
							<input type="radio" {MATCH_TYPE.checked} id="m_{MATCH_TYPE.key}" value="{MATCH_TYPE.key}" name="match_type">
							{MATCH_TYPE.value}</label>
						<!-- BEGIN: match_input -->
						<input type="text" value="{MATCH_TYPE.match_value}" name="match_{MATCH_TYPE.key}" {MATCH_TYPE.match_disabled}>
						<!-- END: match_input -->
					</li>
					<!-- END: match_type -->
				</ul></td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td>Default Value:</td>
				<td>
				<input maxlength="255" style="width:300px" type="text" value="{DATAFORM.default_value}" name="default_value">
				</td>
			</tr>
		</tbody>
		<tbody id="max_length">
			<tr>
				<td>Min Length:</td>
				<td>
				<input class="number" style="width:100px" type="text" value="{DATAFORM.min_length}" name="min_length">
				<span style="margin-left: 50px;">Max Length:</span>
				<input class="number" style="width:100px" type="text" value="{DATAFORM.max_length}" name="max_length">
				</td>
			</tr>
		</tbody>
	</table>

	<table class="tab1" id="numberfields" {DATAFORM.display_numberfields}>
		<caption>
			Options for Number Fields
		</caption>
		<colgroup>
			<col style="width: 250px;" />
		</colgroup>
		<tbody>
			<tr>
				<td>Number Type:</td>
				<td>
				<input type="radio" value="1" name="number_type" {DATAFORM.number_type_1}>
				Integer
				<input type="radio" value="2" name="number_type" {DATAFORM.number_type_2}>
				Real </td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td>Default Value:</td>
				<td>
				<input class="required number" maxlength="255" style="width:300px" type="text" value="{DATAFORM.default_value_number}" name="default_value_number">
				</td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td>Min Value:</td>
				<td>
				<input class="required number" class="number" style="width:100px" type="text" value="{DATAFORM.min_number}" name="min_number_length" maxlength="11">
				<span style="margin-left: 50px;">Max Value:</span>
				<input class="required number" class="number" style="width:100px" type="text" value="{DATAFORM.max_number}" name="max_number_length" maxlength="11">
				</td>
			</tr>
		</tbody>
	</table>

	<table class="tab1" id="datefields" {DATAFORM.display_datefields}>
		<caption>
			Options for Date Fields
		</caption>
		<colgroup>
			<col style="width: 250px;" />
		</colgroup>
		<tbody>
			<tr>
				<td>Default Value:</td>
				<td><label>
					<input type="radio" value="1" name="current_date" {DATAFORM.current_date_1}>
					Use the current date</label><label>
					<input type="radio" value="0" name="current_date" {DATAFORM.current_date_0}>
					Default Date </label>
				<input class="date" style="width:80px" type="text" value="{DATAFORM.default_date}" name="default_date">
				</td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td>Min Date:</td>
				<td>
				<input class="datepicker required date" style="width:80px" type="text" value="{DATAFORM.min_date}" name="min_date" maxlength="10" value="{DATAFORM.min_date}">
				<span style="margin-left: 50px;">Max Date:</span>
				<input class="datepicker required date" style="width:80px" type="text" value="{DATAFORM.max_date}" name="max_date" maxlength="10" value="{DATAFORM.max_date}">
				</td>
			</tr>
		</tbody>
	</table>

	<table class="tab1" id="choiceitems" {DATAFORM.display_choiceitems} >
		<caption>
			Options for Choice Fields
		</caption>
		<colgroup>
			<col style="width: 250px;" />
		</colgroup>
		<thead>
			<tr align="center">
				<td>STT</td>
				<td> Value </td>
				<td> Text</td>
				<td> Default Value</td>
			</tr>
		</thead>
		<!-- BEGIN: loop_field_choice -->
		<tbody {FIELD_CHOICES.class}>
			<tr align="center">
				<td>{FIELD_CHOICES.number}</td>
				<td>
				<input class="validalphanumeric" type="text" value="{FIELD_CHOICES.key}" name="field_choice[{FIELD_CHOICES.number}]" style="width:100px" />
				</td>
				<td>
				<input type="text" value="{FIELD_CHOICES.value}" name="field_choice_text[{FIELD_CHOICES.number}]" style="width:350px" />
				</td>
				<td>
				<input type="radio" {FIELD_CHOICES.checked} value="{FIELD_CHOICES.number}" name="default_value_choice">
				</td>
			</tr>
		</tbody>
		<!-- END: loop_field_choice -->
		<tfoot>
			<tr>
				<td colspan="4" >
				<input style="margin-left: 50px;" type="button" value="Thêm lựa chọn" onclick="nv_choice_fields_additem();" />
				</td>
			</tr>
		</tfoot>
	</table>
	<div style="margin-left: 350px;">
		<input type="hidden" value="{DATAFORM.fid}" name="fid">
		<input type="hidden" value="{DATAFORM.field}" name="fieldid">
		<input style="width: 150px;" type="submit" value="Lưu" name="submit">
	</div>
</form>
<script type="text/javascript">
    var items = '{FIELD_CHOICES_NUMBER}';
    function nv_choice_fields_additem()
    {
        items++;
        var nclass = (items % 2 == 0) ? " class=\"second\"" : "";
        var newitem = "<tbody" + nclass + "><tr align=\"center\">";
        newitem += "	<td>" + items + "</td>";
        newitem += "	<td><input class=\"validalphanumeric\" type=\"text\" value=\"\" name=\"field_choice[" + items + "]\" style=\"width:100px\"></td>";
        newitem += "	<td><input type=\"text\" value=\"\" name=\"field_choice_text[" + items + "]\" style=\"width:350px\"></td>";
        newitem += "	<td><input type=\"radio\" value=\"" + items + "\" name=\"default_value_choice\"></td>";
        newitem += "	</tr>";
        newitem += "</tbody>";
        $("#choiceitems").append(newitem);
    }

    function nv_show_list_field()
    {
        $("#module_show_list").html("<center><img src=\"{NV_BASE_SITEURL}images/load_bar.gif\"</center>").load("{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=fields&qlist=1&nocache=" + new Date().getTime());
        return;
    }

    function nv_chang_field(fid)
    {
        var nv_timer = nv_settimeout_disable('id_weight_' + fid, 5000);
        var new_vid = document.getElementById( 'id_weight_' + fid ).options[document.getElementById('id_weight_' + fid).selectedIndex].value;
        nv_ajax("post", script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=fields&changeweight=1&fid=' + fid + '&new_vid=' + new_vid + '&num=' + nv_randomPassword(8), '', 'nv_chang_field_result');
        return;
    }

    function nv_chang_field_result(res)
    {
        if (res != 'OK')
        {
            alert(nv_is_change_act_confirm[2]);
        }
        clearTimeout(nv_timer);
        nv_show_list_field();
        return;
    }

    function nv_del_field(fid)
    {
        if (confirm(nv_is_del_confirm[0]))
        {
            nv_ajax('post', script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=fields&del=1&fid=' + fid, '', 'nv_del_field_result');
        }
        return false;
    }

    function nv_del_field_result(res)
    {
        if (res == 'OK')
        {
            nv_show_list_field();
        }
        else
        {
            alert(nv_is_del_confirm[2]);
        }
        return false;
    }

    function nv_edit_field(fid)
    {
        window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=fields&fid=' + fid;
    }

    function nv_load_current_date()
    {
        if ($("input[name=current_date]:checked").val() == 1)
        {
            $("input[name=default_date]").attr('disabled', 'disabled');
            $("input[name=default_date]").datepicker("destroy");
        }
        else
        {
            $("input[name=default_date]").datepicker(
            {
                showOn : "button",
                dateFormat : "dd/mm/yy",
                changeMonth : true,
                changeYear : true,
                showOtherMonths : true,
                buttonImage : nv_siteroot + "images/calendar.gif",
                buttonImageOnly : true
            });
            $("input[name=default_date]").removeAttr("disabled");
            $("input[name=default_date]").focus();
        }
    }


    $(document).ready(function()
    {
        if ($("input[name=fid]").val() == 0)
        {
            nv_show_list_field();
        }
        nv_load_current_date();

        $.validator.addMethod('validalphanumeric', function(str)
        {
            if (str == '')
            {
                return true;
            }
            var fieldCheck_rule = /^([a-zA-Z0-9_])+$/;
            return (fieldCheck_rule.test(str) ) ? true : false;
        }, ' required a-z, 0-9, and _ only');

        $('#ffields').validate(
        {
            rules :
            {
                field :
                {
                    required : true,
                    validalphanumeric : true
                }
            }
        });
    });

    $("input[name=field_type]").click(function()
    {
        var field_type = $("input[name='field_type']:checked").val();
        $("#textfields").hide();
        $("#numberfields").hide();
        $("#datefields").hide();
        $("#choiceitems").hide();

        if (field_type == 'textbox' || field_type == 'textarea' || field_type == 'editor')
        {
            if (field_type == 'textbox')
            {
                $("#li_alphanumeric").show();
                $("#li_email").show();
                $("#li_url").show();
            }
            else
            {
                $("#li_alphanumeric").hide();
                $("#li_email").hide();
                $("#li_url").hide();
            }
            $("#textfields").show();
        }
        else if (field_type == 'number')
        {
            $("#numberfields").show();
        }
        else if (field_type == 'date')
        {
            $("#datefields").show();
        }
        else
        {
            $("#choiceitems").show();
            $("#textfields").hide();
            $("#numberfields").hide();
            $("#datefields").hide();
        }
    });
    $("input[name=required],input[name=show_register]").click(function()
    {
        if ($("input[name='required']:checked").val() == 1)
        {
            $("input[name=show_register]").attr("checked", true);
        }
    });
    $("input[name=match_type]").click(function()
    {
        $("input[name=match_regex]").attr('disabled', 'disabled');
        $("input[name=match_callback]").attr('disabled', 'disabled');
        var match_type = $("input[name='match_type']:checked").val();
        var max_length = $("input[name=max_length]").val();
        if (match_type == 'number')
        {
            if (max_length == 255)
            {
                $("input[name=max_length]").val(11);
            }
        }
        else if (max_length == 11)
        {
            $("input[name=max_length]").val(255);
        }
        if (match_type == 'regex')
        {
            $("input[name=match_regex]").removeAttr("disabled");
        }
        else if (match_type == 'callback')
        {
            $("input[name=match_callback]").removeAttr("disabled");
        }
    });

    $("input[name=current_date]").click(function()
    {
        nv_load_current_date();
    });

    $(".datepicker").datepicker(
    {
        showOn : "button",
        dateFormat : "dd/mm/yy",
        changeMonth : true,
        changeYear : true,
        showOtherMonths : true,
        buttonImage : nv_siteroot + "images/calendar.gif",
        buttonImageOnly : true
    }); 
</script>
<!-- END: load -->
<!-- END: main -->