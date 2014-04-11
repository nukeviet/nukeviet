<!-- BEGIN: main -->
<form action="{FORMACTION}" method="post">
	<p class="input-group">
	  <span class="input-group-addon"><em class="fa fa-search">&nbsp;</em></span>
	  <input type="text" name="q" value="{keyvalue}" class="form-control" placeholder="{LANG.search_key}">
	</p>
	<p>
		<select class="form-control" name="cat">
			<option value="">{LANG.search_option}</option>
			<!-- BEGIN: loop -->
			<option value="{loop.id}" {loop.select}>{loop.title}</option>
			{subcat}
			<!-- END: loop -->
		</select>
	</p>
	<p>
		<input type="submit" value="Search" class="btn btn-primary center-block" name="submit"/>
	</p>
</form>
<!-- END: main -->