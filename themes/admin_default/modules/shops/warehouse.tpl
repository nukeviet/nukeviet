<!-- BEGIN: main -->
<form class="form-horizontal" action="" method="post">
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="form-group">
				<label class="col-sm-3 control-label">{LANG.title}</label>
				<div class="col-sm-21">
					<input type="text" name="title" value="{WAREHOUSE.title}" class="form-control" placeholder="{LANG.title}">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">{LANG.content_note}</label>
				<div class="col-sm-21">
					<textarea name="note" class="form-control" rows="4" placeholder="{LANG.content_note}">{WAREHOUSE.note}</textarea>
				</div>
			</div>

		</div>
	</div>
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<colgroup>
				<col class="w50" />
				<col class="w400" />
				<col />
			</colgroup>
			<thead>
				<tr>
					<th class="text-center">{LANG.setting_stt}</th>
					<th>{LANG.name}</th>
					<th class="text-center">{LANG.warehouse_quantity}</th>
				</tr>
			</thead>
			<tbody>
				<!-- BEGIN: loop -->
				<tr>
					<td class="text-center">{DATA.no}</td>
					<td><a href="{DATA.link}" title="{DATA.title}">{DATA.title}</a></td>
					<td>
						<!-- BEGIN: product_number -->
						<div class="form-group">
							<label class="col-sm-6 control-label"><span class="label label-success">{LANG.content_product_number}</span>&nbsp;<span class="label label-danger" title="{LANG.content_product_number}">{DATA.product_number} {DATA.product_unit}</span></label>
							<div class="col-sm-6">
								<input type="number" autocomplete="off" name="quantity[{DATA.id}][0]" class="form-control" placeholder="{LANG.warehouse_quantity}" />
							</div>
							<div class="col-sm-6">
								<input type="text" name="price[{DATA.id}][0]" class="form-control" placeholder="{LANG.warehouse_price}" onkeyup="this.value=FormatNumber(this.value);" />
							</div>
							<div class="col-sm-6">
								<select name="money_unit[{DATA.id}][0]" class="form-control">
									<!-- BEGIN: money_unit -->
									<option value="{MON.code}" {MON.selected}>{MON.currency}</option>
									<!-- END: money_unit -->
								</select>
							</div>
						</div>
						<!-- END: product_number -->
						<!-- BEGIN: group -->
						<div class="form-group">
							<label class="col-sm-6 control-label"><span class="label label-success">{GROUP.parent_title}: {GROUP.title}</span>&nbsp;<span class="label label-danger" title="{LANG.content_product_number}">{GROUP.pro_quantity} {DATA.product_unit}</span></label>
							<div class="col-sm-6">
								<input type="number" autocomplete="off" name="quantity[{DATA.id}][{GROUP.groupid}]" class="form-control" placeholder="{LANG.warehouse_quantity}" />
							</div>
							<div class="col-sm-6">
								<input type="text" name="price[{DATA.id}][{GROUP.groupid}]" class="form-control" placeholder="{LANG.warehouse_price}" onkeyup="this.value=FormatNumber(this.value);" />
							</div>
							<div class="col-sm-6">
								<select name="money_unit[{DATA.id}][{GROUP.groupid}]" class="form-control">
									<!-- BEGIN: money_unit -->
									<option value="{MON.code}" {MON.selected}>{MON.currency}</option>
									<!-- END: money_unit -->
								</select>
							</div>
						</div>
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