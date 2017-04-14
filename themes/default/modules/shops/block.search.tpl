<!-- BEGIN: main -->
<form id="search_form_shops" action="{NV_BASE_SITEURL}" method="get" role="form" name="frm_search" onsubmit="return onsubmitsearch();">
	<div class="form-group">
		<label>{LANG.keyword}</label>
		<input id="keyword" type="text" value="{value_keyword}" name="keyword" class="form-control input-sm">
	</div>

	<div class="form-group">
		<label>{LANG.price1}</label>
		<input id="price1" type="text" value="{value_price1}" name="price1" class="form-control input-sm">
	</div>

	<div class="form-group">
		<label>{LANG.price2}</label>
		<input id="price2" type="text" value="{price2}" name="price2" class="form-control input-sm">
	</div>

	<div class="form-group">
		<label>{LANG.moneyunit}</label>
		<select name="typemoney" id="typemoney" class="form-control input-sm">
			<option value="0"></option>
			<!-- BEGIN: typemoney -->
			<option {ROW.selected} value="{ROW.code}">{ROW.currency}</option>
			<!-- END: typemoney -->
		</select>
	</div>

	<div class="form-group">
		<label>{LANG.catagories}</label>
		<select name="cata" id="cata" class="form-control input-sm">
			<option value="0"></option>
			<!-- BEGIN: loopcata -->
			<option {ROW.selected} value="{ROW.catid}">{ROW.xtitle}</option>
			<!-- END: loopcata -->
		</select>
	</div>

	<div class="text-center">
		<input type="button" name="submit" id="submit" value="{LANG.search}" onclick="onsubmitsearch('{MODULE_NAME}')" class="btn btn-primary">
	</div>
</form>
<!-- END: main -->