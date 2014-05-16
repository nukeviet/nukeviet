<!-- BEGIN: main -->
<div id="module_show_list">
	{GROUP_LIST}
</div>
<div id="group-delete-area"></div>
<div id="edit">
	<!-- BEGIN: error -->
	<div class="quote" style="width:780px; margin:auto">
		<blockquote class="error"><span>{error}</span></blockquote>
	</div>
	<div class="clear"></div>
	<!-- END: error -->
	<form class="form-inline" action="" method="post">
		<input type="hidden" name ="groupid" value="{DATA.groupid}" />
		<input type="hidden" name ="parentid_old" value="{DATA.parentid}" />
		<input name="savegroup" type="hidden" value="1" />
		<table class="table table-striped table-bordered table-hover">
			<caption>{caption}</caption>
			<tbody>
				<tr>
					<td align="right"><strong>{LANG.group_name}</strong></td>
					<td><input class="form-control" style="width: 650px" name="title" type="text" value="{DATA.title}" maxlength="255" /></td>
				</tr>
				<tr>
					<td align="right"><strong>{LANG.group_sub}</strong></td>
					<td>
					<select class="form-control" name="parentid" onchange="nv_getcatalog(this)">
						<!-- BEGIN: parent_loop -->
						<option value="{pgroup_i}" {pselect}>{ptitle_i}</option>
						<!-- END: parent_loop -->
					</select><span id="vcatid"></span></td>
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
			</tbody>
		</table>
		<br>
		<div class="text-center">
			<input class="btn btn-primary" name="submit1" type="submit" value="{LANG.save}"/>
		</div>
	</form>
</div>
<script type="text/javascript">$('#vcatid').load('{URL}');</script>
<!-- END: main -->