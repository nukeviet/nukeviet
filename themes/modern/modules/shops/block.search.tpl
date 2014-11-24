<!-- BEGIN: main -->
<form action="{NV_BASE_SITEURL}" method="get" name="frm_search" style="background:#F5F5F5" onsubmit="return onsubmitsearch();">
	<div align="center" style="padding:2px">
		{LANG.keyword}
		<br />
		<input id="keyword" type="text" value="{value_keyword}" name="keyword" style="text-align:center">
	</div>
	<div align="center" style="padding:2px">
		{LANG.price1}
		<br />
		<input id="price1" type="text" value="{value_price1}" name="price1" style="text-align:center">
	</div>
	<div align="center" style="padding:2px">
		{LANG.price2}
		<br />
		<input id="price2" size="20" type="text" value="{value_price2}" name="price2" style="text-align:center">
	</div>
	<div align="center" style="padding:2px">
		<select name="typemoney" id="typemoney">
			<option value="0">{LANG.moneyunit}</option>
			<!-- BEGIN: typemoney -->
			<option {ROW.selected} value="{ROW.code}">{ROW.currency}</option>
			<!-- END: typemoney -->
		</select>
	</div>
	<div align="center" style="padding:2px">
		<select name="cata" style="width:70%" id="cata">
			<option value="0">{LANG.allcatagories}</option>
			<!-- BEGIN: loopcata -->
			<option {ROW.selected} value="{ROW.catid}">{ROW.xtitle}</option>
			<!-- END: loopcata -->
		</select>
	</div>
	<div align="center" style="padding:2px">
		<input type="button" name="submit" id="submit" value="{LANG.search}" onclick="onsubmitsearch()">
	</div>
</form>
<!-- END: main -->