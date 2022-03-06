<!-- BEGIN: main -->
<style type="text/css">
	.form-group{
		margin-bottom: 4px
	}
</style>
<form action="" method="post">
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="form-group">
				<label class="control-label"><strong>{LANG.title}</strong></label>
				<input type="text" name="title" value="{WAREHOUSE.title}" class="form-control" />
			</div>
			<div class="form-group">
				<label class="control-label"><strong>{LANG.content_note}</strong></label>
				<textarea name="note" class="form-control" rows="4">{WAREHOUSE.note}</textarea>
			</div>
		</div>
	</div>

	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<colgroup>
				<col class="w50" />
				<col />
			</colgroup>
			<thead>
				<tr>
					<th class="text-center">&nbsp;</th>
					<th class="text-center">{LANG.warehouse_quantity}</th>
				</tr>
			</thead>
			<tbody>
				<!-- BEGIN: loop -->
				<tr>
					<td colspan="2"><a href="{DATA.link}" title="{DATA.title}"><strong>{DATA.title}</strong></a></td>
				</tr>
				<tr>
					<td class="text-center">{DATA.no}</td>
					<td>
						<!-- BEGIN: product_number -->
						<div class="form-group">
							<div class="text-right">
								<label class="col-sm-6 control-label"><span class="label label-success">{LANG.content_product_number}</span>&nbsp;<span class="label label-danger" title="{LANG.content_product_number}">{DATA.product_number} {DATA.product_unit}</span></label>
							</div>
							<div class="col-sm-6">
								<input type="number" autocomplete="off" name="data[{DATA.id}][0][quantity]" class="form-control" placeholder="{LANG.warehouse_quantity}" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" />
							</div>
							<div class="col-sm-6">
								<input type="text" name="data[{DATA.id}][0][price]" class="form-control" placeholder="{LANG.warehouse_price}" onkeyup="this.value=FormatNumber(this.value);" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" />
							</div>
							<div class="col-sm-6">
								<select name="data[{DATA.id}][0][money_unit]" class="form-control">
									<!-- BEGIN: money_unit -->
									<option value="{MON.code}" {MON.selected}>{MON.currency}</option>
									<!-- END: money_unit -->
								</select>
							</div>
						</div>
						<!-- END: product_number -->
						<!-- BEGIN: group -->
						<div class="nv-listing">
							<div class="listing-title">
								<div class="row">
								<div class="col-sm-1">

								</div>
								<!-- BEGIN: parent -->
								<div class="col-sm-3 text-center">
									<strong>{PARENT.title}</strong>
								</div>
								<!-- END: parent -->
								</div>
							</div>
							<div class="listing-body">
								<!-- BEGIN: loop -->
								<div class="listing-item">
									<div class="row">
										<div class="col-sm-1">

										</div>
										<!-- BEGIN: items -->
										<div class="col-sm-3">
											<div class="form-group">
												<div <!-- BEGIN: has_error -->class="has-error"<!-- END: has_error -->>
													<select name="data[{DATA.id}][{J}][group][]" class="form-control" <!-- BEGIN: has_requied --> required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" <!-- END: has_requied --> >
														<option value="">{LANG.warehouse_chose_propties} {PARENT.title}</option>
														<!-- BEGIN: loop -->
														<option value="{GROUP.groupid}" {GROUP.selected}>{GROUP.title}</option>
														<!-- END: loop -->
													</select>
												</div>
											</div>
										</div>
										<!-- END: items -->
										<div class="col-sm-3">
											<div class="form-group">
												<input type="number" autocomplete="off" name="data[{DATA.id}][{J}][quantity]" class="form-control" placeholder="{LANG.warehouse_quantity}" />
											</div>
										</div>
										<div class="col-sm-3">
											<div class="form-group">
												<input type="text" name="data[{DATA.id}][{J}][price]" class="form-control" placeholder="{LANG.warehouse_price}" onkeyup="this.value=FormatNumber(this.value);" />
											</div>
										</div>
										<div class="col-sm-3">
											<div class="form-group">
												<select name="data[{DATA.id}][{J}][money_unit]" class="form-control">
													<!-- BEGIN: money_unit -->
													<option value="{MON.code}" {MON.selected}>{MON.currency}</option>
													<!-- END: money_unit -->
												</select>
											</div>
										</div>
									</div>
								</div>
								<!-- END: loop -->
								<button class="btn btn-primary btn-xs btn-add" style="margin-top: 4px"><em class="fa fa-plus-circle">&nbsp;</em>{LANG.warehouse_add_group_propties}</button>
							</div>
						</div>
						<script type="text/javascript">
							$('.btn-add').click(function(){
								var i = $('.listing-item').length;
								var html = '';
								html += '<div class="listing-item">';
								html += '	<div class="row">';
								html += '		<div class="col-sm-1 text-right text-middle">';
								html += '			<a style="cursor:pointer" onclick="$(this).parent().parent().parent().remove();"><em class="fa fa-trash-o fa-lg">&nbsp;</em></a>';
								html += '		</div>';
										<!-- BEGIN: itemsjs -->
								html += '		<div class="col-sm-3">';
								html += '			<div class="form-group">';
								html += '				<div <!-- BEGIN: has_error -->class="has-error"<!-- END: has_error -->>';
								html += '				<select name="data[{DATA.id}][' + i + '][group][]" class="form-control" >';
								html += '					<option value="">{LANG.warehouse_chose_propties} {PARENT.title}</option>';
															<!-- BEGIN: loop -->
								html += '					<option value="{GROUP.groupid}">{GROUP.title}</option>';
															<!-- END: loop -->
								html += '				</select>';
								html += '				</div>';
								html += '			</div>';
								html += '		</div>';
										<!-- END: itemsjs -->
								html += '		<div class="col-sm-3">';
								html += '			<div class="form-group">';
								html += '				<input type="number" autocomplete="off" name="data[{DATA.id}][' + i + '][quantity]" class="form-control" placeholder="{LANG.warehouse_quantity}" />';
								html += '			</div>';
								html += '		</div>';
								html += '		<div class="col-sm-3">';
								html += '			<div class="form-group">';
								html += '				<input type="text" name="data[{DATA.id}][' + i + '][price]" class="form-control" placeholder="{LANG.warehouse_price}" onkeyup="this.value=FormatNumber(this.value);" />';
								html += '			</div>';
								html += '		</div>';
								html += '		<div class="col-sm-3">';
								html += '			<div class="form-group">';
								html += '				<select name="data[{DATA.id}][' + i + '][money_unit]" class="form-control" >';
													<!-- BEGIN: money_unit_js -->
								html += '					<option value="{MON.code}" {MON.selected}>{MON.currency}</option>';
													<!-- END: money_unit_js -->
								html += '				</select>';
								html += '			</div>';
								html += '		</div>';
								html += '	</div>';
								html += '</div>';

								$('.listing-item:last').after( html );

								return false;
							});
						</script>
						<!-- END: group -->
					</td>
				</tr>
				<!-- END: loop -->
			</tbody>
		</table>
	</div>
	<div class="text-center">
		<input type="submit" class="btn btn-primary" value="{LANG.save}" name="submit" />
	</div>
</form>
<script type="text/javascript">
	var inputnumber = '{LANG.error_inputnumber}';
</script>
<!-- END: main -->