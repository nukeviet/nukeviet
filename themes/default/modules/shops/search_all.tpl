<!-- BEGIN: form -->
<div id="formsearch">
	<form action="{NV_BASE_SITEURL}" method="get" name="frm_search" onsubmit="return onsubmitsearch1();">
		<div class="well">
			<div class="row">
				<div class="col-xs-12">
					<div class="form-group">
						<label>{LANG.keyword}</label>
						<input id="keyword1" type="text" value="{value_keyword}" name="keyword" class="form-control">
					</div>
				</div>
				<div class="col-xs-12">
					<div class="form-group">
						<label>{LANG.product_catalogs}</label>
						<select name="cata" id="cata1" class="form-control">
							<option value="0">{LANG.allcatagories}</option>
							<!-- BEGIN: loopcata -->
							<option {ROW.selected} value="{ROW.catid}">{ROW.xtitle}</option>
							<!-- END: loopcata -->
						</select>
					</div>
				</div>
				<div class="col-xs-24 col-sm-8">
					<div class="form-group">
						<label>{LANG.price1}</label>
						<input id="price11" type="text" value="{value_price1}" name="price1" class="form-control">
					</div>
				</div>
				<div class="col-xs-24 col-sm-8">
					<div class="form-group">
						<label>{LANG.price1}</label>
						<input id="price21" size="20" type="text" value="{value_price2}" name="price2" class="form-control">
					</div>
				</div>
				<div class="col-xs-24 col-sm-8">
					<div class="form-group">
						<label>{LANG.moneyunit}</label>
						<select name="typemoney" id="typemoney1" class="form-control">
							<option value="0">{LANG.moneyunit}</option>
							<!-- BEGIN: typemoney -->
							<option {ROW.selected} value="{ROW.code}">{ROW.currency}</option>
							<!-- END: typemoney -->
						</select>
					</div>
				</div>
				<div class="col-xs-24">
					<div class="form-group text-center">
						<input type="submit" class="btn btn-primary" name="submit" id="submit" value="{LANG.search}" onclick="onsubmitsearch1()">
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
<!-- END: form -->
<!-- BEGIN: main -->
{CONTENT}
<!-- END: main -->