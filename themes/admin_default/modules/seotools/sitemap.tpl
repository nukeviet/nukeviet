<!-- BEGIN: main -->
<!-- BEGIN: is_ping -->
<form action="{ACTION_FORM}" method="post">
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<caption><em class="fa fa-file-text-o">&nbsp;</em><a href="{URL_SITEMAP}">{LANG.sitemapPing}</a> </caption>
		<tr>
			<td>
			<select name="searchEngine" class="form-control w200 pull-left" style="margin-right: 5px">
				<option value="">{LANG.searchEngineSelect}</option>
				<!-- BEGIN: Engine -->
				<option value="{ENGINE.name}"{ENGINE.selected}>{ENGINE.name}</option>
				<!-- END: Engine -->
			</select>
			<select name="in_module" class="form-control w200 pull-left" style="margin-right: 5px">
				<option value="">{LANG.sitemapModule}</option>
				<!-- BEGIN: Module -->
				<option value="{MODULE_NAME}"{MODULE_SELECTED}>{MODULE_TITLE}</option>
				<!-- END: Module -->
			</select> &nbsp; <input type="submit" name="ping" value="{LANG.sitemapSend}" class="btn btn-primary" /></td>
		</tr>
		<!-- BEGIN: info -->
		<tr>
			<td><span style="color: #FF0000;">{INFO}</span></td>
		</tr>
		<!-- END: info -->
	</table>
</div>
</form>
<!-- END: is_ping -->
<!-- BEGIN: searchEngineList -->
<form action="{ACTION_FORM}" method="post">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<caption><em class="fa fa-file-text-o">&nbsp;</em>{LANG.searchEngineConfig} </caption>
			<thead>
				<tr>
					<th>{LANG.searchEngineName}</th>
					<th>{LANG.searchEngineValue}</th>
					<th>{LANG.searchEngineActive}</th>
				</tr>
			</thead>
			<tbody>
				<!-- BEGIN: loop -->
				<tr>
					<td><input class="w200 form-control" type="text" value="{DATA.name}" name="searchEngineName[]" /></td>
					<td><input class="w400 form-control" type="text" value="{DATA.value}" name="searchEngineValue[]" /></td>
					<td>
					<select name="searchEngineActive[]" class="form-control">
						<option value="0">{GLANG.no}</option>
						<option value="1"{DATA.selected}>{GLANG.yes}</option>
					</select></td>
				</tr>
				<!-- END: loop -->
			</tbody>
		</table>
	</div>
	<p>
		<input type="submit" name="submit" value="{LANG.submit}" class="btn btn-primary" />
	</p>
</form>
<!-- END: searchEngineList -->
<!-- END: main -->