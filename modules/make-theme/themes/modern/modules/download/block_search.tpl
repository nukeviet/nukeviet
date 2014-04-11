<!-- BEGIN: main -->
<div class="no-bg">
	<div class="header-search">
		<span>Search</span>
	</div>
	<form class="d-search" action="{FORMACTION}" method="post">
		<p><input type="text" class="input" name="q" value="{keyvalue}"/>
		</p>
		<p>
			<select class="cat" name="cat">
				<option value="">{LANG.search_option}</option>
				<!-- BEGIN: loop -->
				<option value="{loop.id}" {loop.select}>{loop.title}</option>
				{subcat}
				<!-- END: loop -->
			</select>
		</p>
		<p><input type="submit" value="Search" class="button" name="submit"/>
		</p>
	</form>
</div>
<!-- END: main -->