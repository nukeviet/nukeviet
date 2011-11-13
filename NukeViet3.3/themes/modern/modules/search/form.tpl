<!-- BEGIN: main -->
<div class="box-border-shadow content-box clearfix">
	<h3 class="title-search">
        {LANG.info_title}
    </h3>
    <form action="{BASE_URL_SITE}" name="form_search" method="get" id="form_search" class="fl">
        <div class="form">
            <div class="clearfix rows">
                    <label>
                        {LANG.key_title}:
                    </label>
                    <input class="input" id="search_query" name="q" value="{SEARCH_QUERY}" maxlength="{NV_MAX_SEARCH_LENGTH}" />
            </div>
            <div class="clearfix rows">
                <label>
                    &nbsp;
                </label>
                    <input name="search_logic" id="search_logic_and" type="radio"{SEARCH_LOGIC_AND_CHECKED} value="AND" />{LANG.logic_and} &nbsp; 
                    <input name="search_logic" id="search_logic_or" type="radio"{SEARCH_LOGIC_OR_CHECKED} value="OR" />{LANG.logic_or}
            </div>            
            <div class="clearfix rows">
                    <label>
                        {LANG.type_search}:
                    </label>
                    <select name="mod" id="search_query_mod">
                        <option value="all">{LANG.search_on_site}</option>
                        <!-- BEGIN: select_option -->
                        <option value="{MOD.value}"{MOD.selected}>{MOD.custom_title}</option>
                        <!-- END: select_option -->
                    </select>
                    <input type="hidden" id="search_checkss" value="{CHECKSS}" />
                    <input class="button" type="submit" id="search_submit" value="{LANG.search_title}" />
			</div>
			<div class="clearfix rows">
				<label>&nbsp;</label>
				<input type="hidden" id="mydomain" value="{MY_DOMAIN}" />
				<input type="hidden" id="confirm_search_on_internet" value="{LANG.search_on_internet}" />
				<a href="javascript:void(0);" onclick="GoUrl({NV_MIN_SEARCH_LENGTH}, {NV_MAX_SEARCH_LENGTH});">{LANG.search_title_adv}</a>
                - <a href="javascript:void(0);" onclick="GoGoogle({NV_MIN_SEARCH_LENGTH}, {NV_MAX_SEARCH_LENGTH});">{LANG.search_adv_internet}</a>
			</div>
		</div>
    </form>
	<div class="fr">
		<form action="http://www.google.com/search" id="cse-search-box">
			<div>
				<input type="hidden" value="{DOMAIN}" name="domains">
				<input type="hidden" value="UTF-8" name="ie"/>
				<input class="input" type="text" size="31" name="q" style="padding: 2px; background: url(&quot;http://www.google.com/cse/intl/en/images/google_custom_search_watermark.gif&quot;) no-repeat scroll left center rgb(255, 255, 255);"/>
				<input class="button" type="submit" value="Search" name="sa"/>
			</div>
			<input type="hidden" name="siteurl" value="{DOMAIN}"/>
		</form>
		<script type="text/javascript" src="http://www.google.com/cse/brand?form=cse-search-box&amp;lang=vi"></script>
	</div>
</div>
<div id="search_result"></div>
<!-- BEGIN: is_key -->
<script type="text/javascript">
    nv_send_search( {NV_MIN_SEARCH_LENGTH}, {NV_MAX_SEARCH_LENGTH} );
</script>
<!-- AND: is_key -->
<!-- END: main -->
