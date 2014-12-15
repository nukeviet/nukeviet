<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.core.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.theme.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.css" rel="stylesheet" />
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>

<div class="well">
	<form class="form-inline" action="{NV_BASE_ADMINURL}index.php" method="get">
		<input type="hidden" name ="{NV_NAME_VARIABLE}"value="{MODULE_NAME}" />
		<input type="hidden" name ="{NV_OP_VARIABLE}"value="{OP}" />
		<select class="form-control" name="stype">
			<option value="-">---{LANG.search_type}---</option>
			<!-- BEGIN: stype -->
			<option value="{STYPE.key}"{STYPE.selected}>{STYPE.title}</option>
			<!-- END: stype -->
		</select>
		<input class="form-control" type="text" value="{Q}" maxlength="{NV_MAX_SEARCH_LENGTH}" name="q" placeholder="{LANG.search_key}">
		<select class="form-control" style="width: 150px !important" name="catid">
			<option value="0">---{LANG.search_cat}---</option>
			<!-- BEGIN: catid -->
			<option value="{CATID.catid}"{CATID.selected}>{CATID.title}</option>
			<!-- END: catid -->
		</select>
		<input type="text" name="from" id="from" value="{FROM}" class="form-control" style="width: 100px" placeholder="{LANG.date_from}" readonly="readonly">
		<input type="text" name="to" id="to" value="{TO}" class="form-control" style="width: 100px" placeholder="{LANG.date_to}" readonly="readonly">
		<select class="form-control" name="per_page">
			<option value="">---{LANG.search_per_page}---</option>
			<!-- BEGIN: per_page -->
			<option value="{PER_PAGE.key}"{PER_PAGE.selected}>{PER_PAGE.title}</option>
			<!-- END: per_page -->
		</select>
		<input class="btn btn-primary" type="submit" value="{LANG.search}">
		<br>
		<input type="hidden" name ="checkss" value="{CHECKSESS}" />
		<label class="help-block"><em class="text-danger">{SEARCH_NOTE}</em></label>
	</form>
</div>

<form class="form-inline" name="block_list">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th class="text-center"><input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);" /></th>
					<th style="width:40px">&nbsp;</th>
					<th>
						<!-- BEGIN: no_order_title -->
						<em class="fa fa-sort">&nbsp;</em>
						<!-- END: no_order_title -->
						<!-- BEGIN: order_title -->
						<!-- BEGIN: desc -->
						<em class="fa fa-sort-alpha-desc">&nbsp;</em>
						<!-- END: desc -->
						<!-- BEGIN: asc -->
						<em class="fa fa-sort-alpha-asc">&nbsp;</em>
						<!-- END: asc -->
						<!-- END: order_title -->
						<a href="{BASE_URL_NAME}">{LANG.name}</a>
					</th>
					<th class="text-center">
						<!-- BEGIN: no_order_publtime -->
						<em class="fa fa-sort">&nbsp;</em>
						<!-- END: no_order_publtime -->
						<!-- BEGIN: order_publtime -->
						<!-- BEGIN: desc -->
						<em class="fa fa-sort-amount-desc">&nbsp;</em>
						<!-- END: desc -->
						<!-- BEGIN: asc -->
						<em class="fa fa-sort-amount-asc">&nbsp;</em>
						<!-- END: asc -->
						<!-- END: order_publtime -->
						<a href="{BASE_URL_PUBLTIME}">{LANG.content_publ_date}</a>
					</th>
					<th class="text-center">{LANG.order_product_price}</th>
					<th class="text-center">
						<!-- BEGIN: no_order_hitstotal -->
						<em class="fa fa-sort">&nbsp;</em>
						<!-- END: no_order_hitstotal -->
						<!-- BEGIN: order_hitstotal -->
						<!-- BEGIN: desc -->
						<em class="fa fa-sort-numeric-desc">&nbsp;</em>
						<!-- END: desc -->
						<!-- BEGIN: asc -->
						<em class="fa fa-sort-numeric-asc">&nbsp;</em>
						<!-- END: asc -->
						<!-- END: order_hitstotal -->
						<a href="{BASE_URL_HITSTOTAL}">{LANG.views}</a>
					</th>
					<th class="text-center">
						<!-- BEGIN: no_order_product_number -->
						<em class="fa fa-sort">&nbsp;</em>
						<!-- END: no_order_product_number -->
						<!-- BEGIN: order_product_number -->
						<!-- BEGIN: desc -->
						<em class="fa fa-sort-numeric-desc">&nbsp;</em>
						<!-- END: desc -->
						<!-- BEGIN: asc -->
						<em class="fa fa-sort-numeric-asc">&nbsp;</em>
						<!-- END: asc -->
						<!-- END: order_product_number -->
						<a href="{BASE_URL_PNUMBER}">{LANG.content_product_number1}</a>
					</th>
					<th class="text-center">
						<!-- BEGIN: no_order_num_sell -->
						<em class="fa fa-sort">&nbsp;</em>
						<!-- END: no_order_num_sell -->
						<!-- BEGIN: order_num_sell -->
						<!-- BEGIN: desc -->
						<em class="fa fa-sort-numeric-desc">&nbsp;</em>
						<!-- END: desc -->
						<!-- BEGIN: asc -->
						<em class="fa fa-sort-numeric-asc">&nbsp;</em>
						<!-- END: asc -->
						<!-- END: order_num_sell -->
						<a href="{BASE_URL_NUM_SELL}">{LANG.num_selled}</a></th>
					<th class="text-center">{LANG.status}</th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			<tbody>
				<!-- BEGIN: loop -->
				<tr>
					<td class="text-center"><input type="checkbox" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);" value="{ROW.id}" name="idcheck[]"></td>
					<td><a href="{ROW.imghome}" rel="shadowbox[random]"/ title="{ROW.title}"><img src="{ROW.thumb}" alt="{ROW.title}" width="40"/></a></td>
					<td class="top">
					<p>
						<a target="_blank" href="{ROW.link}">{ROW.title}</a>
					</p>
					<div class="product-info">
						{LANG.order_update}: <span class="other">{ROW.edittime}</span> |
						{LANG.content_admin}: <span class="other">{ROW.admin_id}</span>
					</div></td>
					<td class="text-center">{ROW.publtime}</td>
					<td class="text-right">{ROW.product_price} {ROW.money_unit}</td>
					<td class="text-center">{ROW.hitstotal}</td>
					<td class="text-center">{ROW.product_number}</td>
					<td class="text-center">
						<!-- BEGIN: seller -->
						<a href="{ROW.link_seller}" title="{LANG.report_detail}">{ROW.num_sell} {ROW.product_unit}</a>
						<!-- END: seller -->
						<!-- BEGIN: seller_empty -->
						{ROW.num_sell} {ROW.product_unit}
						<!-- END: seller_empty -->
					</td>
					<td class="text-center">{ROW.status}</td>
					<td class="text-center"> {ROW.link_edit}&nbsp;-&nbsp;{ROW.link_delete} </td>
				</tr>
				<!-- END: loop -->
			</tbody>
			<tfoot>
				<tr align="left">
					<td colspan="10">
					<select class="form-control" name="action" id="action">
						<!-- BEGIN: action -->
						<option value="{ACTION.key}">{ACTION.title}</option>
						<!-- END: action -->
					</select> &nbsp; <input type="button" class="btn btn-primary" onclick="nv_main_action(this.form, '{ACTION_CHECKSESS}','{LANG.msgnocheck}')" value="{LANG.action}"></td>
				</tr>
			</tfoot>
		</table>
	</div>
</form>
<!-- BEGIN: generate_page -->
<br />
<p class="text-center">
	{GENERATE_PAGE}
</p>
<!-- END: generate_page -->
<script type='text/javascript'>
	$(function() {
		$("#from, #to").datepicker({
			showOn : "both",
			dateFormat : "dd/mm/yy",
			changeMonth : true,
			changeYear : true,
			showOtherMonths : true,
			buttonImage : nv_siteroot + "images/calendar.gif",
			buttonImageOnly : true
		});
	});
</script>
<!-- END: main -->