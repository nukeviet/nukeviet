<!-- BEGIN: main -->
<div id="users">
	<form action="{FORM_ACTION}" method="post">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover">
				<colgroup>
					<col style="width: 260px" />
					<col/>
				</colgroup>
				<tfoot>
					<tr>
						<td colspan="2"><input type="hidden" name="save" value="1"><input type="submit" value="{LANG.config_save}" class="btn btn-primary" /></td>
					</tr>
				</tfoot>
				<tbody>
					<tr>
						<td>{LANG.config_view_type}</td>
						<td>
						<select name="viewtype" class="form-control w200">
							<!-- BEGIN: loop -->
							<option value="{VIEWTYPE.id}" {VIEWTYPE.selected}>{VIEWTYPE.title}</option>
							<!-- END: loop -->
						</select></td>
					</tr>
					<tr>
						<td>{LANG.config_view_type_page}</td>
						<td>
						<select class="form-control w200" name="per_page">
							<!-- BEGIN: per_page -->
							<option value="{PER_PAGE.key}"{PER_PAGE.selected}>{PER_PAGE.title}</option>
							<!-- END: per_page -->
						</select></td>
					</tr>
					<tr>
						<td>{LANG.config_view_related_articles}</td>
						<td>
						<select class="form-control w200" name="related_articles">
							<!-- BEGIN: related_articles -->
							<option value="{RELATED_ARTICLES.key}"{RELATED_ARTICLES.selected}>{RELATED_ARTICLES.title}</option>
							<!-- END: related_articles -->
						</select></td>
					</tr>
					<tr>
						<td>{LANG.first_news}</td>
						<td><input type="checkbox" value="1" name="news_first"{NEWS_FIRST}/></td>
					</tr>
					<tr>
						<td>{LANG.config_facebookapi}</td>
						<td><input class="form-control w200" name="facebookapi" value="{DATA.facebookapi}" /><span class="help-block">{LANG.config_facebookapi_note}</span></td>
					</tr>
                    <tr>
                        <td>{LANG.socialbutton}</td>
                        <td>
                            <!-- BEGIN: socialbutton -->
                            <div><label><input type="checkbox" name="socialbutton[]" value="{SOCIALBUTTON.key}"{SOCIALBUTTON.checked}> {SOCIALBUTTON.title}</label></div>
                            <!-- END: socialbutton -->
                        </td>
                    </tr>
					<tr>
						<td>{LANG.setting_copy_page}</td>
						<td><input type="checkbox" value="1" name="copy_page"{COPY_PAGE}/></td>
					</tr>
					<tr>
						<td>{LANG.config_alias_lower}</td>
						<td><input type="checkbox" value="1" name="alias_lower"{ALIAS_LOWER}/></td>
					</tr>

				</tbody>
			</table>
		</div>
	</form>
</div>
<!-- END: main -->