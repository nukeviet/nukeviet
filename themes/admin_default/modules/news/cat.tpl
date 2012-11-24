<!-- BEGIN: main -->
<div id="module_show_list">
	{CAT_LIST}
</div>
<br />

<div id="edit">
<!-- BEGIN: error -->
    <div class="quote" style="width:780px;">
    <blockquote class="error"><span>{ERROR}</span></blockquote>
    </div>
    <div class="clear"></div>
<!-- END: error -->
<!-- BEGIN: content -->
    <form action="{NV_BASE_ADMINURL}index.php" method="post">
    <input type="hidden" name ="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
    <input type="hidden" name ="{NV_OP_VARIABLE}" value="{OP}" />
    <input type="hidden" name ="catid" value="{catid}" />
    <input type="hidden" name ="parentid_old" value="{parentid}" />
    <input name="savecat" type="hidden" value="1" />
    <table summary="" class="tab1">
		<caption>{caption}</caption>  
		<tbody>
			<tr>
				<td align="right"><strong>{LANG.name}: </strong></td>
				<td><input style="width: 600px" name="title" type="text" value="{title}" maxlength="255" id="idtitle"/></td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td valign="top" align="right"><strong>{LANG.alias}: </strong></td>
				<td>
					<input style="width: 550px" name="alias" type="text" value="{alias}" maxlength="255" id="idalias"/>
					<img src="{NV_BASE_SITEURL}images/refresh.png" width="16" style="cursor: pointer; vertical-align: middle;" onclick="get_alias('cat', {catid});" alt="" height="16" />
				</td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td align="right"><strong>Title Site: </strong></td>
				<td><input style="width: 600px" name="titlesite" type="text" value="{titlesite}" maxlength="255"/></td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td align="right"><strong>{LANG.cat_sub}: </strong></td>
				<td>
				<select name="parentid">
					<!-- BEGIN: cat_listsub -->
						<option value="{cat_listsub.value}" {cat_listsub.selected}>{cat_listsub.title}</option>
					<!-- END: cat_listsub -->
				</select>
				</td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td align="right"><strong>{LANG.keywords}: </strong></td>
				<td><input style="width: 600px" name="keywords" type="text" value="{keywords}" maxlength="255" /></td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td valign="top" align="right"><br /><strong>{LANG.description} </strong></td>
				<td>
				<textarea style="width: 600px" name="description" cols="100" rows="5">{description}</textarea>
				</td>
			</tr>
		</tbody>
		<tbody>
		<tr>
			<td valign="top" align="right"><br /><strong>{GLANG.who_view} </strong></td>
			<td>
				<div class="message_body">
					<select name="who_view" id="who_view" onchange="nv_sh('who_view','groups_list')" style="width: 250px;">
						<!-- BEGIN: who_views -->
							<option value="{who_views.value}" {who_views.selected}>{who_views.title}</option>
						<!-- END: who_views -->
					</select>
					<br />
					<div id="groups_list" style="{hidediv}">
						{GLANG.groups_view}:
						<table style="margin-bottom:8px; width:250px;">
							<col valign="top" width="150px" />
								<tr>
									<td>
										<!-- BEGIN: groups_views -->
										<p><input name="groups_view[]" type="checkbox" value="{groups_views.value}" {groups_views.checked} />{groups_views.title}</p>
										<!-- END: groups_views -->
									</td>
								</tr>
						</table>
					</div>
				</div>
			</td>
		</tr>
		</tbody>
    </table>
    <br /><center><input name="submit1" type="submit" value="{LANG.save}" /></center>
</form>
</div>
<!-- BEGIN: getalias -->
<script type="text/javascript">
$("#idtitle").change(function () {
    get_alias( "cat", 0 );
});
</script>
<!-- END: getalias -->
<!-- END: content -->
<!-- END: main -->