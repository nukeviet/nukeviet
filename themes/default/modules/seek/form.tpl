<!-- BEGIN: main -->
<div id="cse"></div>
<div id="search-form" class="text-center">
	<p><em>{LANG.info_title}</em></p>
	<form action="{DATA.action}" name="form_search" method="get" id="form_search" role="form" class="form-inline">
		<input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}"/>
		<input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}"/>
		<div class="m-bottom">
			<div class="form-group">
				<label class="sr-only" for="search_query">{LANG.key_title}</label>
				<input class="form-control" id="search_query" name="q" value="{DATA.key}" maxlength="{NV_MAX_SEARCH_LENGTH}" placeholder="{LANG.key_title}"/>
			</div>
			<div class="form-group">
				<label class="sr-only" for="search_query_mod">{LANG.type_search}</label>
				<select name="m" id="search_query_mod" class="form-control">
					<option value="all">{LANG.search_on_site}</option>
					<!-- BEGIN: select_option -->
					<option data-adv="{MOD.adv_search}" value="{MOD.value}"{MOD.selected}>{MOD.custom_title}</option>
					<!-- END: select_option -->
				</select>
			</div>
			<div class="form-group">
				<input type="submit" id="search_submit" value="{LANG.search_title}" class="btn btn-primary"/>
				<a href="advSearch" class="advSearch">{LANG.search_title_adv}</a>
			</div>
		</div>
		<div class="radio">
			<label class="radio-inline">
				<input name="l" id="search_logic_and" type="radio"{DATA.andChecked} value="1" /> 
				{LANG.logic_and}
			</label>			
			<label class="radio-inline">
				<input name="l" id="search_logic_or" type="radio"{DATA.orChecked} value="0" />
				{LANG.logic_or}
			</label>			
		</div>
		<input type="hidden" name="page" value="{PAGE}"/>
	</form>
	<!-- BEGIN: search_engine_unique_ID -->
	<div class="text-center search_adv">
		<a href="#" class="IntSearch">{LANG.search_adv_internet}</a>
	</div>
	<script src="http://www.google.com/jsapi" type="text/javascript"></script>
	<script type="text/javascript" >
		google.load('search', '1', {
			language : nv_sitelang
		});
	</script>
	<link rel="stylesheet" href="http://www.google.com/cse/style/look/default.css" type="text/css" />
	<!-- END: search_engine_unique_ID -->
	<hr />
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
	if("all" == b){
		return alert("{LANG.chooseModule}"), $("#form_search #search_query_mod").focus(), !1
	}
	var b = nv_siteroot + "index.php?" + nv_lang_variable + "=" + nv_sitelang + "&" + nv_name_variable + "=" + b + "&" + nv_fc_variable + "=search", a = $("#form_search #search_query").val(), a = strip_tags(a);
	{NV_MIN_SEARCH_LENGTH} <= a.length && {NV_MAX_SEARCH_LENGTH} >= a.length && (a = rawurlencode(a), b = b + "&q=" + a);

	window.location.href = b;
});
$("a.IntSearch").click(function(){
	var a = $("#form_search [name=q]").val();
	$("#search-form").hide();
	$("#search_result").hide();
	var customSearchControl = new google.search.CustomSearchControl('{SEARCH_ENGINE_UNIQUE_ID}');
	customSearchControl.setResultSetSize(google.search.Search.FILTERED_CSE_RESULTSET);
	customSearchControl.draw('cse');
	customSearchControl.execute(a);
});
$("#form_search").submit(function(){
	var a = $("#form_search [name=q]").val(), a = strip_tags(a), b;
	$("#form_search [name=q]").val(a);
	if({NV_MIN_SEARCH_LENGTH} > a.length || {NV_MAX_SEARCH_LENGTH} < a.length){
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