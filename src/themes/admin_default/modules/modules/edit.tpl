<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="alert alert-danger">{DATA.error}</div>
<!-- END: error -->
<form method="post" action="{DATA.action}">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <tfoot>
                <tr>
                    <td colspan="2" class="text-center">
                        <input type="hidden" name="checkss" value="{DATA.checkss}" />
                        <input name="module_theme" type="hidden" value="{DATA.module_theme}" />
                        <input name="go_add" type="submit" value="{DATA.submit}" class="btn btn-primary" />
                    </td>
                </tr>
            </tfoot>
            <tbody>
                <tr>
                    <td class="w200">{LANG.module_name} <span class="text-danger">(*)</span>:</td>
                    <td><input class="w300 form-control" name="mod_name" id="mod_name" type="text" value="{DATA.mod_name}" maxlength="55" readonly="readonly"/></td>
                </tr>
                <tr>
                    <td>{LANG.custom_title} <span class="text-danger">(*)</span>:</td>
                    <td><input class="w300 form-control" name="custom_title" id="custom_title" type="text" value="{DATA.custom_title}" maxlength="100" /></td>
                </tr>
                <tr>
                    <td>{LANG.admin_title}:</td>
                    <td><input class="w300 form-control" name="admin_title" id="admin_title" type="text" value="{DATA.admin_title}" maxlength="100" /></td>
                </tr>
                <tr>
                    <td>{LANG.theme}:</td>
                    <td>
                    <select name="theme" id="theme" class="form-control w300">
                        <option value="">{DATA.theme.1}</option>
                        <!-- BEGIN: theme -->
                        <option value="{THEME.key}"{THEME.selected}>{THEME.key}</option>
                        <!-- END: theme -->
                    </select></td>
                </tr>
                <!-- BEGIN: mobile -->
                <tr>
                    <td>{DATA.mobile.0}:</td>
                    <td>
                    <select name="mobile" id="mobile" class="form-control w300">
                        <!-- BEGIN: loop -->
                        <option value="{MOBILE.key}"{MOBILE.selected}>{MOBILE.title}</option>
                        <!-- END: loop -->
                    </select></td>
                </tr>
                <!-- END: mobile -->
                <tr>
                    <td>{LANG.site_title}:</td>
                    <td><input class="w300 form-control" name="site_title" id="site_title" type="text" value="{DATA.site_title}" maxlength="255" /></td>
                </tr>
                <tr>
                    <td>{LANG.description}:</td>
                    <td><input class="w300 form-control" name="description" id="description" type="text" value="{DATA.description}" maxlength="255" /></td>
                </tr>
                <tr>
                    <td>{LANG.keywords}:</td>
                    <td><input class="w300 form-control" name="keywords" id="keywords" type="text" value="{DATA.keywords}" maxlength="255" /> {LANG.keywords_info}</td>
                </tr>
                <tr>
                    <td>{GLANG.activate}:</td>
                    <td><input name="act" id="act" type="checkbox" value="1"{ACTIVE} /></td>
                </tr>
                <!-- BEGIN: rss -->
                <tr>
                    <td>{DATA.rss.0}:</td>
                    <td><input name="rss" id="rss" type="checkbox" value="1"{RSS} /></td>
                </tr>
                <!-- END: rss -->
                <!-- BEGIN: sitemap -->
                <tr>
                    <td>{DATA.sitemap.0}:</td>
                    <td><input name="sitemap" id="sitemap" type="checkbox" value="1"{SITEMAP} /></td>
                </tr>
                <!-- END: sitemap -->
                <!-- BEGIN: groups_view -->
                <tr>
                    <td>{DATA.groups_view.0}:</td>
                    <td>
                        <span class="help-block">{LANG.module_groups_view_note}.</span>
                        <!-- BEGIN: loop -->
                        <label><input name="groups_view[]" type="checkbox" value="{GROUPS_VIEW.key}"{GROUPS_VIEW.checked}/> {GROUPS_VIEW.title}</label><br />
                        <!-- END: loop -->
                    </td>
                </tr>
                <!-- END: groups_view -->
            </tbody>
        </table>
    </div>
</form>
<!-- END: main -->
