<!-- BEGIN: main -->
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css">
<link rel="stylesheet" type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css">

<div class="well">
	<form action="{NV_BASE_ADMINURL}index.php" method="get">
		<input type="hidden" name ="{NV_NAME_VARIABLE}"value="{MODULE_NAME}" />
		<input type="hidden" name ="{NV_OP_VARIABLE}"value="{OP}" />
		<div class="row">
			<div class="col-xs-12 col-md-4">
				<div class="form-group">
					<select class="form-control" name="stype">
						<option value="-">---{LANG.search_type}---</option>
						<!-- BEGIN: stype -->
						<option value="{STYPE.key}"{STYPE.selected}>{STYPE.title}</option>
						<!-- END: stype -->
					</select>
				</div>
			</div>
			<div class="col-xs-12 col-md-4">
				<div class="form-group">
					<input class="form-control" type="text" value="{Q}" maxlength="{NV_MAX_SEARCH_LENGTH}" name="q" placeholder="{LANG.search_key}">
				</div>
			</div>
			<div class="col-xs-12 col-md-4">
				<div class="form-group">
					<select class="form-control" name="catid" id="catid">
						<option value="0">---{LANG.search_cat}---</option>
						<!-- BEGIN: catid -->
						<option value="{CATID.catid}"{CATID.selected}>{CATID.title}</option>
						<!-- END: catid -->
					</select>
				</div>
			</div>
			<div class="col-xs-12 col-md-3">
				<div class="form-group">
					<div class="input-group">
						<input type="text" class="form-control" name="from" id="from" value="{FROM}" readonly="readonly" placeholder="{LANG.date_from}">
						<span class="input-group-btn">
							<button class="btn btn-default" type="button" id="from-btn">
								<em class="fa fa-calendar fa-fix">&nbsp;</em>
							</button> </span>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-md-3">
				<div class="form-group">
					<div class="input-group">
						<input type="text" class="form-control" name="to" id="to" value="{TO}" readonly="readonly" placeholder="{LANG.date_to}">
						<span class="input-group-btn">
							<button class="btn btn-default" type="button" id="to-btn">
								<em class="fa fa-calendar fa-fix">&nbsp;</em>
							</button> </span>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-md-3">
				<div class="form-group">
					<select class="form-control" name="per_page">
						<option value="">---{LANG.search_per_page}---</option>
						<!-- BEGIN: per_page -->
						<option value="{PER_PAGE.key}"{PER_PAGE.selected}>{PER_PAGE.title}</option>
						<!-- END: per_page -->
					</select>
				</div>
			</div>
			<div class="col-xs-12 col-md-3">
				<div class="form-group">
					<input class="btn btn-primary" type="submit" value="{LANG.search}">
				</div>
			</div>
		</div>
		<input type="hidden" name ="checkss" value="{CHECKSESS}" />
		<em class="help-block">{SEARCH_NOTE}</em>
	</form>
</div>

<form class="form-inline" name="block_list">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th class="text-center"><input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);" /></th>
					<th style="width:40px">&nbsp;</th>
					<th><!-- BEGIN: no_order_title --><em class="fa fa-sort">&nbsp;</em><!-- END: no_order_title --><!-- BEGIN: order_title --><!-- BEGIN: desc --><em class="fa fa-sort-alpha-desc">&nbsp;</em><!-- END: desc --><!-- BEGIN: asc --><em class="fa fa-sort-alpha-asc">&nbsp;</em><!-- END: asc --><!-- END: order_title --><a href="{BASE_URL_NAME}">{LANG.name}</a></th>
					<th class="text-center"><!-- BEGIN: no_order_publtime --><em class="fa fa-sort">&nbsp;</em><!-- END: no_order_publtime --><!-- BEGIN: order_publtime --><!-- BEGIN: desc --><em class="fa fa-sort-amount-desc">&nbsp;</em><!-- END: desc --><!-- BEGIN: asc --><em class="fa fa-sort-amount-asc">&nbsp;</em><!-- END: asc --><!-- END: order_publtime --><a href="{BASE_URL_PUBLTIME}">{LANG.content_publ_date}</a></th>
					<th class="text-center">{LANG.order_product_price}</th>
					<th class="text-center"><!-- BEGIN: no_order_hitstotal --><em class="fa fa-sort">&nbsp;</em><!-- END: no_order_hitstotal --><!-- BEGIN: order_hitstotal --><!-- BEGIN: desc --><em class="fa fa-sort-numeric-desc">&nbsp;</em><!-- END: desc --><!-- BEGIN: asc --><em class="fa fa-sort-numeric-asc">&nbsp;</em><!-- END: asc --><!-- END: order_hitstotal --><a href="{BASE_URL_HITSTOTAL}">{LANG.views}</a></th>
					<th class="text-center"><!-- BEGIN: no_order_product_number --><em class="fa fa-sort">&nbsp;</em><!-- END: no_order_product_number --><!-- BEGIN: order_product_number --><!-- BEGIN: desc --><em class="fa fa-sort-numeric-desc">&nbsp;</em><!-- END: desc --><!-- BEGIN: asc --><em class="fa fa-sort-numeric-asc">&nbsp;</em><!-- END: asc --><!-- END: order_product_number --><a href="{BASE_URL_PNUMBER}">{LANG.content_product_number1}</a></th>
					<th class="text-center"><!-- BEGIN: no_order_num_sell --><em class="fa fa-sort">&nbsp;</em><!-- END: no_order_num_sell --><!-- BEGIN: order_num_sell --><!-- BEGIN: desc --><em class="fa fa-sort-numeric-desc">&nbsp;</em><!-- END: desc --><!-- BEGIN: asc --><em class="fa fa-sort-numeric-asc">&nbsp;</em><!-- END: asc --><!-- END: order_num_sell --><a href="{BASE_URL_NUM_SELL}">{LANG.num_selled}</a></th>
					<th class="text-center">{LANG.status}</th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			<tbody>
				<!-- BEGIN: loop -->
				<tr>
					<td class="text-center"><input type="checkbox" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);" value="{ROW.id}" name="idcheck[]"></td>
					<td>
						<a href="" title="{ROW.title}" class="open_modal" data-width="{ROW.imghome_info.width}" data-src="{ROW.imghome}"><img src="{ROW.thumb}" alt="{ROW.title}" width="40"/></a>
					</td>
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
					<td class="text-center"><!-- BEGIN: seller --><a href="{ROW.link_seller}" title="{LANG.report_detail}">{ROW.num_sell} {ROW.product_unit}</a><!-- END: seller --><!-- BEGIN: seller_empty --> {ROW.num_sell} {ROW.product_unit} <!-- END: seller_empty --></td>
					<td class="text-center">{ROW.status}</td>
					<td class="text-center">
						<!-- BEGIN: warehouse_icon -->
						<a href="{ROW.link_warehouse}" title="{LANG.warehouse}"><em class="fa fa-cubes fa-lg">
						<!-- END: warehouse_icon -->
						&nbsp;</em></a>&nbsp;&nbsp;&nbsp;<a href="{ROW.link_copy}" title="{LANG.product_copy_note}"><em class="fa fa-copy fa-lg">&nbsp;</em></a>&nbsp;&nbsp;&nbsp;{ROW.link_edit}&nbsp;&nbsp;&nbsp;{ROW.link_delete}
					</td>
				</tr>
				<!-- END: loop -->
			</tbody>
			<tfoot>
				<tr align="left">
					<td colspan="10">
						<div class="row">
							<div class="col-xs-10">
								<select class="form-control" name="action" id="action">
									<!-- BEGIN: action -->
									<option value="{ACTION.key}">{ACTION.title}</option>
									<!-- END: action -->
								</select> &nbsp; <input type="button" class="btn btn-primary" onclick="nv_main_action(this.form, '{ACTION_CHECKSESS}','{LANG.msgnocheck}')" value="{LANG.action}">
							</div>
							<div class="col-xs-14 text-right">
								<!-- BEGIN: generate_page -->
								{GENERATE_PAGE}
								<!-- END: generate_page -->
							</div>
						</div>
					</td>
				</tr>
			</tfoot>
		</table>
	</div>
</form>

<div class="modal fade" id="idmodals" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				&nbsp;
			</div>
			<div class="modal-body">
				<p class="text-center"><em class="fa fa-spinner fa-spin fa-3x">&nbsp;</em></p>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<script type='text/javascript'>
	$(function() {
		$("#catid").select2();

		$('.open_modal').click(function(e){
			e.preventDefault();
     		$('#idmodals .modal-body').html( '<img src="' + $(this).data('src') + '" alt="" class="img-responsive" />' );
     		$('#idmodals').modal('show');
		});

		$("#from, #to").datepicker({
			dateFormat : "dd/mm/yy",
			changeMonth : true,
			changeYear : true,
			showOtherMonths : true,
			showOn : 'focus'
		});
		$('#to-btn').click(function(){
			$("#to").datepicker('show');
		});
		$('#from-btn').click(function(){
			$("#from").datepicker('show');
		});
	});
</script>
<!-- END: main -->