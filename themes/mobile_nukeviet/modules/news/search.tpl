<!-- BEGIN: main -->
<form action="{BASE_URL_SITE}" method="get">
	<input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}" />
	<input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
	<input type="hidden" name="{NV_OP_VARIABLE}" value="{OP_NAME}" />
	<h3 class="ac">{LANG.info_title}</h3>
	{LANG.key_title}
	<br />
	<input type="text" name="q" value="{KEY}"/>
	{LANG.type_title}
	<br />
	<select name="choose">
		<option value="0" {CHECK1}>{LANG.find_all}</option>
		<option value="1" {CHECK1}>{LANG.find_content}</option>
		<option value="2" {CHECK2}>{LANG.find_author}</option>
		<option value="3" {CHECK3}>{LANG.find_resource}</option>
	</select>
	{LANG.search_cat}
	<br />
	<select name="catid">
		<!-- BEGIN: search_cat -->
		<option value="{SEARCH_CAT.catid}" {SEARCH_CAT.select}>{SEARCH_CAT.title}</option>
		<!-- END: search_cat -->
	</select>
	{LANG.finter_title}<span class="smll"> ({LANG.search_date_rule})</span>
	<br />
	<input name="from_date" value="{FROM_DATE}" maxlength="10" type="text"/>
	{LANG.to_date}<span class="smll"> ({LANG.search_date_rule})</span>
	<br />
	<input name="to_date" value="{TO_DATE}" maxlength="10" type="text"/>
	<input type="submit" value="{LANG.search_title}"/>
</form>
<!-- END: main -->
<!-- BEGIN: results -->
<h4>{LANG.search_on} {TITLE_MOD}</h4>
<!-- BEGIN: noneresult -->
{LANG.search_none} : "<strong>{KEY}</strong>" {LANG.search_in_module} <strong>{INMOD}</strong>
<!-- END: noneresult -->
<!-- BEGIN: result -->
<!-- BEGIN: result_img -->
<a href="{LINK}"><img src="{IMG_SRC}" width="{IMG_WIDTH}" class="fl"/></a>
<!-- END: result_img -->
<h3><a href="{LINK}">{TITLEROW}</a></h3>
{CONTENT}
<br />
<span class="smll">{AUTHOR} | {LANG.source_title} : {SOURCE}</span>
<div class="hr clear"></div>
<!-- END: result -->
<!-- BEGIN: pages_result -->
<br />
{VIEW_PAGES}
<!-- END: pages_result -->
<p>
	<em>{LANG.search_sum_title} {NUMRECORD} {LANG.result_title}
	<br />
	{LANG.info_adv}</em>
</p>
<strong>{LANG.search_adv_internet} :</strong>
<form method="get" action="http://www.google.com/search">
	<input type="hidden" name="domains" value="{MY_DOMAIN}"/>
	<img src="http://www.google.com/logos/Logo_25wht.gif"/>
	<input type="text" name="q" value="{KEY}"/>
	<input type="submit" name="sa" value="{LANG.search_title}"/>
	<input type="radio" name="sitesearch" value="" checked="checked"/>
	{LANG.search_on_internet}
	<input type="radio" name="sitesearch" value="{MY_DOMAIN}"/>{LANG.search_on_nuke} {MY_DOMAIN}
</form>
<!-- END: results -->