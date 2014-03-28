<!-- BEGIN: main -->
<input type="hidden" id="hidden_key" value="{HIDDEN_KEY}" />
<div class="result-mod">
	<!-- BEGIN: more -->
	<div class="cl-viewall">
		<a href="{MORE}">{LANG.view_all_title}</a>
	</div>
	<!-- END: more -->
	{LANG.search_on} &quot;{MODULE_CUSTOM_TITLE}&quot;: {SEARCH_RESULT_NUM}
</div>
<div class="result-frame">
	<!-- BEGIN: result -->
	<div class="result-title">
		<a href="{RESULT.link}">{RESULT.title}</a>
	</div>
	<div class="result-content">
		{RESULT.content}
	</div>
	<!-- END: result -->
	<!-- BEGIN: generate_page -->
	<div class="acenter">
		{GENERATE_PAGE}
	</div>
	<!-- END: generate_page -->
</div>
<!-- END: main -->