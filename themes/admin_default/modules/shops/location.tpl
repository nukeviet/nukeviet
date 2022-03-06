<!-- BEGIN: main -->
{FILE "shipping_menu.tpl"}

<div id="module_show_list">
	{LOCATION_LIST}
</div>
<div id="edit" class="table-responsive">
	<!-- BEGIN: error -->
	<div class="alert alert-warning">{error}</div>
	<!-- END: error -->
	<form class="form-inline" action="{FORM_ACTION}" method="post">
		<input type="hidden" name ="id" value="{DATA.id}" />
		<input type="hidden" name ="parentid_old" value="{DATA.parentid}" />
		<input name="savelocation" type="hidden" value="1" />
		<table class="table table-striped table-bordered table-hover">
			<caption>{CAPTION}</caption>
			<tbody>
				<tr>
					<td align="right"><strong>{LANG.location_name}</strong></td>
					<td><input class="form-control" style="width: 400px" name="title" type="text" value="{DATA.title}" maxlength="255" id="idtitle"  required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')"  /></td>
				</tr>
				<tr>
					<td align="right"><strong>{LANG.location_in}</strong></td>
					<td>
						<select class="form-control" name="parentid" style="width: 200px">
							<!-- BEGIN: parent_loop -->
							<option value="{plocal_i}" {pselect}>{ptitle_i}</option>
							<!-- END: parent_loop -->
						</select>&nbsp;
					</td>
				</tr>
			</tbody>
		</table>
		<div class="text-center">
			<input class="btn btn-primary" name="submit1" type="submit" value="{LANG.save}"/>
		</div>
	</form>
</div>
<!-- END: main -->