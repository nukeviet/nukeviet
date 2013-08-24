<!-- BEGIN: mainrefresh -->
<h2 class="ac">{DATA.content}</h2>
<meta http-equiv="refresh" content="5;URL={DATA.urlrefresh}" />
<div class="hr"></div>
<!-- END: mainrefresh -->
<!-- BEGIN: main -->
<form action="{CONTENT_URL}" method="post">
	{LANG.name} <span class="rqe">(*)</span>
	<input name="title" value="{DATA.title}" type="text"/>
	{LANG.alias}
	<input name="alias" value="{DATA.alias}" type="text"/>
	{LANG.content_cat} <span class="rqe">(*)</span>
	<div style="max-height:130px;overflow:auto">
		<!-- BEGIN: catid -->
		<input name="catids[]" value="{DATACATID.value}" type="checkbox"{DATACATID.checked}/>{DATACATID.title}
		<br />
		<!-- END: catid -->
	</div>
	{LANG.content_topic}
	<select name="topicid">
		<!-- BEGIN: topic -->
		<option value="{DATATOPIC.value}"{DATATOPIC.selected}>{DATATOPIC.title}</option>
		<!-- END: topic -->
	</select>
	{LANG.content_homeimg}
	<input name="homeimgfile" value="{DATA.homeimgfile}" type="text"/>
	{LANG.content_homeimgalt}
	<input value="{DATA.homeimgalt}" name="homeimgalt" type="text"/>
	{LANG.imgposition}
	<select name="imgposition">
		<!-- BEGIN: imgposition -->
		<option value="{DATAIMGOP.value}"{DATAIMGOP.selected}>{DATAIMGOP.title}</option>
		<!-- END: imgposition -->
	</select>
	{LANG.content_hometext}
	<textarea name="hometext">{DATA.hometext}</textarea>
	{LANG.content_bodytext} <span class="rqe">(*)</span>
	{HTMLBODYTEXT}

	{LANG.source}<input value="{DATA.sourcetext}" name="sourcetext" type="text"/>
	{LANG.search_author}<input value="{DATA.author}" name="author" type="text"/>
	{LANG.content_keywords}<input value="{DATA.keywords}" name="keywords" type="text"/>
	{LANG.captcha}:<span class="rqe">(*)</span>
	<input type="text" value="" id="fcode_iavim" name="fcode"/>
	<img height="22" src="{NV_BASE_SITEURL}index.php?scaptcha=captcha" id="vimg"/>
	<img src="{CAPTCHA_REFR_SRC}" width="16" height="16" onclick="nv_change_captcha('vimg','fcode_iavim');"/>
	<div class="ac">
		<input type="hidden" name="contentid" value="{DATA.id}"/>
		<input type="hidden" name="checkss" value="{CHECKSS}"/>
		<!-- BEGIN: save_temp -->
		<input type="submit" value="{LANG.save_temp}" name="status0">
		<!-- END: save_temp -->
		<!-- BEGIN: postcontent -->
		<input type="submit" value="{LANG.save_content}" name="status1">
		<!-- END: postcontent -->
	</div>
</form>
<!-- END: main -->