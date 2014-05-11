<!-- BEGIN: main -->
<div id="module_show_list">
	{CAT_LIST}
</div>
<div id="cat-delete-area"></div>
<div id="edit">
	<!-- BEGIN: error -->
	<div class="quote" style="width:98%; margin:auto">
		<blockquote class="error"><span>{error}</span></blockquote>
	</div>
	<div class="clear"></div>
	<!-- END: error -->
	<form class="form-inline" action="" method="post">
		<input type="hidden" name="catid" value="{DATA.catid}" />
		<input type="hidden" name="parentid_old" value="{DATA.parentid}" />
		<input name="savecat" type="hidden" value="1" />
		<table class="table table-striped table-bordered table-hover">
			<caption>{caption}</caption>
			<tbody>
				<tr>
					<td align="right"><strong>{LANG.catalog_name}</strong></td>
					<td><input class="form-control" style="width: 650px" name="title" type="text" value="{DATA.title}" maxlength="255" /></td>
				</tr>
				<tr>
					<td align="right"><strong>{LANG.cat_sub}</strong></td>
					<td>
					<select class="form-control" name="parentid">
						<!-- BEGIN: parent_loop -->
						<option value="{pcatid_i}" {pselect}>{ptitle_i}</option>
						<!-- END: parent_loop -->
					</select></td>
				</tr>
				<tr>
					<td align="right"  width="180px"><strong>{LANG.alias} : </strong></td>
					<td><input class="form-control" style="width: 650px" name="alias" type="text" value="{DATA.alias}" maxlength="255" /></td>
				</tr>
				<tr>
					<td align="right"><strong>{LANG.keywords}: </strong></td>
					<td><input class="form-control" style="width: 650px" name="keywords" type="text" value="{DATA.keywords}" maxlength="255" /></td>
				</tr>
				<tr>
					<td align="right"><strong>{LANG.description}</strong></td>
					<td><textarea style="width: 650px" name="description" cols="100" rows="5">{DATA.description}</textarea></td>
				</tr>
				<tr>
					<td align="right"><strong>{GLANG.groups_view}</strong></td>
					<td>
						<!-- BEGIN: groups_view -->
						<div class="row">
							<label><input name="groups_view[]" type="checkbox" value="{GROUPS_VIEW.value}" {GROUPS_VIEW.checked} />{GROUPS_VIEW.title}</label>
						</div>
						<!-- END: groups_view -->
					</td>
				</tr>
			</tbody>
		</table>
		<br />
		<div class="text-center">
			<input class="btn btn-primary" name="submit1" type="submit" value="{LANG.save}"/>
		</div>
	</form>
</div>
<!-- END: main -->