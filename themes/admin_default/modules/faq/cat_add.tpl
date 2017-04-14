<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->
<form class="form-inline" action="{FORM_ACTION}" method="post">
    <table class="table table-striped table-bordered table-hover">
        <tbody>
            <tr>
                <td>
                    {LANG.faq_category_cat_name}
                </td>
                <td>
                    <input class="form-control" value="{DATA.title}" name="title" id="title" style="width:300px" maxlength="100" />
                </td>
            </tr>
            <tr>
                <td>
                    {LANG.faq_description}
                </td>
                <td>
                    <input class="form-control" type="text" value="{DATA.description}" name="description" style="width:300px" maxlength="255" />
                </td>
            </tr>
            <tr>
                <td>
                    {LANG.faq_keywords}
                </td>
                <td>
                    <input class="form-control" type="text" value="{DATA.keywords}" name="keywords" style="width:300px" maxlength="255" />
                </td>
            </tr>
            <tr>
                <td>
                    {LANG.faq_category_cat_parent}
                </td>
                <td>
                    <select class="form-control" name="parentid">
                        <!-- BEGIN: parentid -->
                        <option value="{LISTCATS.id}"{LISTCATS.selected}>{LISTCATS.name}</option>
                        <!-- END: parentid -->
                    </select>
                </td>
            </tr>
            <tr>
                <td style="vertical-align:top">
                   {GLANG.groups_view}
                </td>
                <td>
					<!-- BEGIN: groups_views -->
					<div class="row">
						<label><input name="groups_view[]" type="checkbox" value="{groups_views.value}" {groups_views.checked} />{groups_views.title}</label>
					</div>
					<!-- END: groups_views -->
				</td>
            </tr>
            <tr>
                <td colspan="2">
                    <input class="btn btn-primary" type="submit" name="submit" value="{LANG.faq_cat_save}" />
                </td>
            </tr>
        </tbody>
    </table>
</form>
<!-- END: main -->