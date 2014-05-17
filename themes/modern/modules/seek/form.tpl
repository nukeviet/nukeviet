<!-- BEGIN: main -->
<div id="cse" style="width: 100%;display:none">
	Loading
</div>
<script src="http://www.google.com/jsapi" type="text/javascript"></script>
<script type="text/javascript" >
	google.load('search', '1', {
		language : nv_sitelang
	});
</script>
<link rel="stylesheet" href="http://www.google.com/cse/style/look/default.css" type="text/css" />
<div id="id_form_search" class="box-border-shadow content-box clearfix">
	<h3 class="title-search"> {LANG.info_title} </h3>
	<form action="{DATA.action}" name="form_search" method="get" id="form_search">
		<input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}"/>
		<input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}"/>
		<div class="form">
			<div class="clearfix rows">
				<label> {LANG.key_title}: </label>
				<input class="input" id="search_query" name="q" value="{DATA.key}" maxlength="{NV_MAX_SEARCH_LENGTH}" />
				<input name="l" id="search_logic_and" type="radio"{DATA.andChecked} value="1" /><span>{LANG.logic_and}</span>&nbsp; <input name="l" id="search_logic_or" type="radio"{DATA.orChecked} value="0" /><span>{LANG.logic_or}</span>
			</div>
			<div class="clearfix rows">
				<label> {LANG.type_search}: </label>
				<select name="m" id="search_query_mod">
					<option value="all">{LANG.search_on_site}</option>
					<!-- BEGIN: select_option -->
					<option data-adv="{MOD.adv_search}" value="{MOD.value}"{MOD.selected}>{MOD.custom_title}</option>
					<!-- END: select_option -->
				</select>
				<input type="submit" id="search_submit" value="{LANG.search_title}" />&nbsp; <a href="#" class="advSearch">{LANG.search_title_adv}</a>
			</div>
			<!-- BEGIN: search_engine_unique_ID -->
			<div class="clearfix rows">
				<label>&nbsp;</label>
				- <a href="#" class="IntSearch">{LANG.search_adv_internet}</a>
			</div>
			<!-- END: search_engine_unique_ID -->
		</div>
		<input type="hidden" name="page" value="{PAGE}"/>
	</form>
</div>
<script type="text/javascript">
//<![CDATA[
$('#search_query_mod').change(function(){
	var data = $(this).find('option:selected').data();
	if( data.adv == true ){
		$("a.advSearch").show();
	}else if( data.adv == false ){
		$("a.advSearch").hide();
	}else{
		$("a.advSearch").show();
	}
});
$("a.advSearch").click(function(e){
	e.preventDefault();
	var b = $("#form_search #search_query_mod").val();
	if ("all" == b) {
		return alert("{LANG.chooseModule}"), $("#form_search #search_query_mod").focus(), !1
	}
	var b = nv_siteroot + "index.php?" + nv_lang_variable + "=" + nv_sitelang + "&" + nv_name_variable + "=" + b + "&" + nv_fc_variable + "=search", a = $("#form_search #search_query").val(), a = strip_tags(a);
	{NV_MIN_SEARCH_LENGTH} <= a.length && {NV_MAX_SEARCH_LENGTH} >= a.length && (a = rawurlencode(a), b = b + "&q=" + a);
	window.location.href = b;
});
$("a.IntSearch").click(function(){
	var a = $("#form_search [name=q]").val();
	$("#id_form_search").hide();
	$("#cse").show();
	$("#search_result").hide();
	var customSearchControl = new google.search.CustomSearchControl('{SEARCH_ENGINE_UNIQUE_ID}');
	customSearchControl.setResultSetSize(google.search.Search.FILTERED_CSE_RESULTSET);
	customSearchControl.draw('cse');
	customSearchControl.execute(a);
});
$("#form_search").submit(function(){
	var a = $("#form_search [name=q]").val(), a = strip_tags(a), b;
	$("#form_search [name=q]").val(a);
	if({NV_MIN_SEARCH_LENGTH} > a.length || {NV_MAX_SEARCH_LENGTH} < a.length) {
		return $("#form_search [name=q]").select(), !1
	}
	return true;
});
//]]>
</script>
<div id="search_result">
	{SEARCH_RESULT}
</div>
<!-- END: main -->