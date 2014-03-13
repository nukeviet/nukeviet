<!-- BEGIN: main -->
<!-- BEGIN: is_ping -->
<form action="{ACTION_FORM}" method="post">
	<table class="tab1">
		<caption> <a href="{URL_SITEMAP}">{LANG.sitemapPing}</a> </caption>
		<tr>
			<td>
			<select name="searchEngine">
				<option value="">{LANG.searchEngineSelect}</option>
				<!-- BEGIN: Engine -->
				<option value="{ENGINE.name}"{ENGINE.selected}>{ENGINE.name}</option>
				<!-- END: Engine -->
			</select>
			<select name="in_module">
				<option value="">{LANG.sitemapModule}</option>
				<!-- BEGIN: Module -->
				<option value="{MODULE_NAME}"{MODULE_SELECTED}>{MODULE_TITLE}</option>
				<!-- END: Module -->
			</select> &nbsp; <input type="submit" name="ping" value="{LANG.sitemapSend}" /></td>
		</tr>
		<!-- BEGIN: info -->
		<tr>
			<td><span style="color: #FF0000;">{INFO}</span></td>
		</tr>
		<!-- END: info -->
	</table>
</form>
<!-- END: is_ping -->
<!-- BEGIN: searchEngineList -->
<form action="{ACTION_FORM}" method="post">
	<table class="tab1">
		<caption> {LANG.searchEngineConfig} </caption>
		<thead>
			<tr>
				<td>{LANG.searchEngineName}</td>
				<td>{LANG.searchEngineValue}</td>
				<td>{LANG.searchEngineActive}</td>
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td><input class="w200" type="text" value="{DATA.name}" name="searchEngineName[]" /></td>
				<td><input class="w400" type="text" value="{DATA.value}" name="searchEngineValue[]" /></td>
				<td>
				<select name="searchEngineActive[]">
					<option value="0">{GLANG.no}</option>
					<option value="1"{DATA.selected}>{GLANG.yes}</option>
				</select></td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
	<p>
		<input type="submit" name="submit" value="{LANG.submit}" />
	</p>
</form>
<!-- END: searchEngineList -->
<!-- END: main -->