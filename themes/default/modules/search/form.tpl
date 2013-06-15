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
<div class="sea-frame">
	<form action="{DATA.action}" name="form_search" method="get" id="form_search">
		<div class="title">
			{LANG.info_title}
		</div>
		<div class="form">
			<dl class="clearfix" style="margin-bottom:5px">
				<dd class="fl" style="width:100px;text-align:left;">
					<label> {LANG.key_title}: </label>
				</dd>
				<dt class="fl" style="text-align:left;">
					<input class="intxt" id="search_query" name="q" value="{DATA.key}" maxlength="{NV_MAX_SEARCH_LENGTH}" />
				</dt>
			</dl>
			<dl class="clearfix" style="margin-bottom:5px">
				<dd class="fl" style="width:100px;text-align:left;">
					&nbsp;
				</dd>
				<dt class="fl" style="text-align:left;">
					<input name="l" id="search_logic_and" type="radio"{DATA.andChecked} value="1" />
					{LANG.logic_and} &nbsp;
					<input name="l" id="search_logic_or" type="radio"{DATA.orChecked} value="0" />
					{LANG.logic_or}
				</dt>
			</dl>
			<dl class="clearfix">
				<dd class="fl" style="width:100px;text-align:left;">
					<label> {LANG.type_search}: </label>
				</dd>
				<dt class="fl" style="text-align:left;">
					<select name="m" id="search_query_mod">
						<option value="all">{LANG.search_on_site}</option>
						<!-- BEGIN: select_option -->
						<option value="{MOD.value}"{MOD.selected}>{MOD.custom_title}</option>
						<!-- END: select_option -->
					</select>
					<input type="submit" id="search_submit" value="{LANG.search_title}" />
					&nbsp;&nbsp; <a href="advSearch" class="advSearch">{LANG.search_title_adv}</a>
				</dt>
			</dl>
		</div>
	</form>
	<!-- BEGIN: search_engine_unique_ID -->
	<div class="search_adv" style="text-align:center">
		<a href="#" class="IntSearch">{LANG.search_adv_internet}</a>
	</div>
	<!-- END: search_engine_unique_ID -->
</div>
<script type="text/javascript">
	//<![CDATA[
	$("a.advSearch").click(function() {
		var b = $("#form_search #search_query_mod").val();
		if ("all" == b) {
			return alert("{LANG.chooseModule}"), $("#form_search #search_query_mod").focus(), !1
		}
		var action = $("form[id=form_search]").attr("action");
		action = action.replace("search", b + "/search" );
		var b = action, a = $("#form_search #search_query").val(), a = formatStringAsUriComponent(a);
		var rewrite_endurl = b.split("search");
		{NV_MIN_SEARCH_LENGTH} <= a.length && {NV_MAX_SEARCH_LENGTH} >= a.length && (a = rawurlencode(a), b = rewrite_endurl[0] + "search/" + a + rewrite_endurl[1]);
		  
		window.location.href = b;
		return !1
	});
	$("a.IntSearch").click(function() {
		var a = $("#form_search [name=q]").val();
		$("div.sea-frame").hide();
		$("#cse").show();
		$("#search_result").hide();
		var customSearchControl = new google.search.CustomSearchControl('{SEARCH_ENGINE_UNIQUE_ID}');
		customSearchControl.setResultSetSize(google.search.Search.FILTERED_CSE_RESULTSET);
		customSearchControl.draw('cse');
		customSearchControl.execute(a);
	});
	$("#form_search").submit(function() {
		var a = $("#form_search [name=q]").val(), a = formatStringAsUriComponent(a), b;
		$("#form_search [name=q]").val(a);
		if({NV_MIN_SEARCH_LENGTH} > a.length || {NV_MAX_SEARCH_LENGTH} < a.length) {
			return $("#form_search [name=q]").select(), !1
		}
		var a = "/" + a;
		var mod = $("#search_query_mod").val(); mod = (mod != "all")? "/" + "m-" + mod : "";
		var logic =  ( $("#search_logic_and:checked").val() == 1 )? "/" + "l-1" : "/" + "l-0";
		
		b =  $("form[id=form_search]").attr("action");
		var rewrite_endurl = b.split("search");
		window.location.href = rewrite_endurl[0] + "search" + a + mod + logic + rewrite_endurl[1];

		return !1
	});
	//]]>
</script>
<div id="search_result">
	{SEARCH_RESULT}
</div>
<!-- END: main -->