<!-- BEGIN: main -->
<!-- BEGIN: view -->
<form class="form-inline" action="{NV_BASE_ADMINURL}index.php" method="get">
	<input type="hidden" name="{NV_LANG_VARIABLE}"  value="{NV_LANG_DATA}" />
	<input type="hidden" name="{NV_NAME_VARIABLE}"  value="{MODULE_NAME}" />
	<input type="hidden" name="{NV_OP_VARIABLE}"  value="{OP}" />
	<input class="form-control" type="text" value="{Q}" name="q" maxlength="255" placeholder="{LANG.search_key}" />&nbsp;
	<input class="btn btn-primary" type="submit" value="{LANG.search}" />
</form>
<br>

<form class="form-inline" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<colgroup>
				<col span="2" />
				<col class="w200" />
				<col span="4" class="w150" />
			</colgroup>
			<thead>
				<tr>
					<th>{LANG.title}</th>
					<th>{LANG.coupons}</th>
					<th>{LANG.coupons_discount}</th>
					<th>{LANG.begin_time}</th>
					<th>{LANG.end_time}</th>
					<th>{LANG.status}</th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			<!-- BEGIN: generate_page -->
			<tfoot>
				<tr>
					<td colspan="7">{NV_GENERATE_PAGE}</td>
				</tr>
			</tfoot>
			<!-- END: generate_page -->
			<tbody>
				<!-- BEGIN: loop -->
				<tr>
					<td> <a href="{VIEW.link_view}" title="{VIEW.title}">{VIEW.title}</a> </td>
					<td> {VIEW.code} </td>
					<td> {VIEW.discount}{VIEW.discount_text} </td>
					<td> {VIEW.date_start} </td>
					<td> {VIEW.date_end} </td>
					<td> {VIEW.status} </td>
					<td class="text-center"><i class="fa fa-edit fa-lg">&nbsp;</i> <a href="{VIEW.link_edit}">{LANG.edit}</a> - <em class="fa fa-trash-o fa-lg">&nbsp;</em> <a href="{VIEW.link_delete}" onclick="return confirm(nv_is_del_confirm[0]);">{LANG.delete}</a></td>
				</tr>
				<!-- END: loop -->
			</tbody>
		</table>
	</div>
</form>
<!-- END: view -->

<link rel="stylesheet" type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css">

<!-- BEGIN: error -->
<div class="alert alert-warning">{ERROR}</div>
<!-- END: error -->
<form class="form-inline" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<input type="hidden" name="id" value="{ROW.id}" />
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<caption>{LANG.coupons_add}</caption>
			<colgroup>
				<col class="w250" />
			</colgroup>
			<tbody>
				<tr>
					<td> {LANG.title} <span class="red">*</span></td>
					<td><input class="form-control" type="text" name="title" value="{ROW.title}" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" /></td>
				</tr>
				<tr>
					<td> {LANG.coupons} <span class="red">*</span> </td>
					<td><input class="form-control" type="text" name="code" value="{ROW.code}" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" /></td>
				</tr>
				<tr>
					<td> {LANG.coupons_discount} <span class="red">*</span></td>
					<td>
						<input class="form-control" type="number" name="discount" value="{ROW.discount}" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" />
						<select class="form-control" name="type">
							<!-- BEGIN: select_type -->
							<option value="{OPTION.key}" {OPTION.selected}>{OPTION.title}</option>
							<!-- END: select_type -->
						</select>
					</td>
				</tr>
				<tr>
					<td> {LANG.coupons_total_amount} </td>
					<td><input class="form-control" type="text" name="total_amount" value="{ROW.total_amount}" />&nbsp;<em class="fa fa-info-circle fa-lg text-info" data-toggle="tooltip" title="{LANG.coupons_total_amount_note}">&nbsp;</em></td>
				</tr>
				<tr>
					<td> {LANG.coupons_product} </td>
					<td>
						<div class="message_body" style="overflow: auto">
							<div class="clearfix uiTokenizer uiInlineTokenizer">
	                            <div id="product" class="tokenarea">
	                                <!-- BEGIN: product -->
	                                <span class="uiToken removable" title="{PRODUCT.title}">
	                                    {PRODUCT.title}
	                                    <input type="hidden" autocomplete="off" name="product[]" value="{PRODUCT.id}" />
	                                    <a onclick="$(this).parent().remove();" class="remove uiCloseButton uiCloseButtonSmall" href="javascript:void(0);"></a>
	                                </span>
	                                <!-- END: product -->
	                            </div>
	                            <div class="uiTypeahead">
	                                <div class="wrap">
	                                    <input type="hidden" class="hiddenInput" autocomplete="off" value="" />
	                                    <div class="innerWrap">
	                                        <input id="get_product" type="text" placeholder="{LANG.input_keyword_tags}" class="form-control textInput" style="width: 100%;" />
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
	                	</div>
	                	<span class="help-block">{LANG.coupons_product_note}</span>
					</td>
				</tr>
				<tr>
					<td> {LANG.begin_time} </td>
					<td><input class="form-control" type="text" name="date_start" value="{ROW.date_start}" id="date_start" pattern="^[0-9]{2,2}\/[0-9]{2,2}\/[0-9]{1,4}$" /></td>
				</tr>
				<tr>
					<td> {LANG.end_time} </td>
					<td><input class="form-control" type="text" name="date_end" value="{ROW.date_end}" id="date_end" pattern="^[0-9]{2,2}\/[0-9]{2,2}\/[0-9]{1,4}$" />&nbsp;<em class="fa fa-info-circle fa-lg text-info" data-toggle="tooltip" title="{LANG.coupons_end_time_note}">&nbsp;</em></td>
				</tr>
				<tr>
					<td> {LANG.coupons_uses_per_coupon} </td>
					<td><input class="form-control" type="text" name="uses_per_coupon" value="{ROW.uses_per_coupon}" />&nbsp;<em class="fa fa-info-circle fa-lg text-info" data-toggle="tooltip" title="{LANG.coupons_uses_per_coupon_note}">&nbsp;</em></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div style="text-align: center"><input class="btn btn-primary" name="submit" type="submit" value="{LANG.save}" /></div>
</form>

<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>

<script type="text/javascript">
//<![CDATA[
	$("#date_start,#date_end").datepicker({
		showOn : "both",
		dateFormat : "dd/mm/yy",
		changeMonth : true,
		changeYear : true,
		showOtherMonths : true,
		buttonImage : nv_base_siteurl + "assets/images/calendar.gif",
		buttonImageOnly : true
	});

	$(function () {
		$('[data-toggle="tooltip"]').tooltip();
	});

	$(document).ready(function() {
        $("#get_product").bind("keydown", function(event) {
    		if (event.keyCode === $.ui.keyCode.TAB && $(this).data("ui-autocomplete").menu.active) {
    			event.preventDefault();
    		}
    	}).autocomplete({
    		source : function(request, response) {
    			$.getJSON(script_name + "?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=coupons&get_product=1", {
    				term : extractLast(request.term)
    			}, response);
    		},
    		search : function() {
    			// custom minLength
    			var term = extractLast(this.value);
    			if (term.length < 2) {
    				return false;
    			}
    		},
    		select : function(event, ui) {
    			// add placeholder to get the comma-and-space at the end
           		var html = "<span title=\"" + ui.item.value + "\" class=\"uiToken removable\">" + ui.item.value + "<input type=\"hidden\" value=\"" + ui.item.key + "\" name=\"product[]\" autocomplete=\"off\"><a onclick=\"$(this).parent().remove();\" href=\"javascript:void(0);\" class=\"remove uiCloseButton uiCloseButtonSmall\"></a></span>";
            	$("#product").append( html );

                $(this).val('');
                return false;
    		}
    	});
	});

	function split(val) {
		return val.split(/,\s*/);
	}

	function extractLast(term) {
		return split(term).pop();
	}
//]]>
</script>
<!-- END: main -->