<!-- BEGIN: main -->
<!-- BEGIN: empty -->
<div class="alert alert-info">{LANG.api_roles_empty}.</div>
<!-- END: empty -->
<!-- BEGIN: data -->
<div class="alert alert-info">{LANG.api_role_notice}.</div>
<form>
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <caption>
                <i class="fa fa-fw fa-file-o"></i>{LANG.api_roles_list}
            </caption>
            <thead>
                <tr>
                    <th style="width: 25%;">{LANG.api_roles_title}</th>
                    <th style="width: 30%;">{LANG.api_roles_description}</th>
                    <th style="width: 15%;">{LANG.api_addtime}</th>
                    <th style="width: 15%;">{LANG.api_edittime}</th>
                    <th style="width: 15%;" class="text-center">{LANG.funcs}</th>
                </tr>
            </thead>
            <tbody>
                <!-- BEGIN: loop -->
                <tr>
                    <td>
                        <a href="#apiroledetail{ROW.role_id}" data-toggle="apiroledetail" data-title="{LANG.api_roles_detail}: {ROW.role_title}">{ROW.role_title}</a> <strong class="text-danger">({ROW.apitotal})</strong>
                    </td>
                    <td>{ROW.role_description}</td>
                    <td>{ROW.addtime}</td>
                    <td>{ROW.edittime}</td>
                    <td class="text-center">
                        <a href="{ROW.link_edit}" class="btn btn-xs btn-default"><i class="fa fa-fw fa-edit"></i>{GLANG.edit}</a> <a href="#" data-id="{ROW.role_id}" data-toggle="apiroledel" class="btn btn-xs btn-danger"><i class="fa fa-fw fa-trash"></i>{GLANG.delete}</a>
                    </td>
                </tr>
                <!-- END: loop -->
            </tbody>
        </table>
    </div>
</form>
<!-- BEGIN: loop_detail -->
<div id="apiroledetail{ROW.role_id}" class="hidden">
    <!-- BEGIN: cat -->
    <div class="form-group">
        <h2>
            <strong>{CAT_NAME}</strong>:
        </h2>
        <div class="row">
            <!-- BEGIN: loop -->
            <div class="col-xs-12">{API_NAME}</div>
            <!-- END: loop -->
        </div>
    </div>
    <!-- END: cat -->
</div>
<!-- END: loop_detail -->
<!-- END: data -->
<div id="addeditarea">
    <!-- BEGIN: note_lang -->
    <div class="alert alert-info">{LANG.api_role_notice_lang}</div>
    <!-- END: note_lang -->
    <!-- BEGIN: error -->
    <div class="alert alert-danger">{ERROR}</div>
    <!-- END: error -->
    <form method="post" action="{FORM_ACTION}">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <caption>
                    <!-- BEGIN: icon_add -->
                    <i class="fa fa-fw fa-plus-circle"></i>
                    <!-- END: icon_add -->
                    <!-- BEGIN: icon_edit -->
                    <i class="fa fa-fw fa-edit"></i>
                    <!-- END: icon_edit -->
                    {TABLE_CAPTION}
                </caption>
                <tbody>
                    <tr>
                        <td class="w250">
                            <strong>{LANG.api_roles_title}</strong>
                        </td>
                        <td>
                            <input type="text" class="form-control w350" name="role_title" value="{DATA.role_title}" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>{LANG.api_roles_description}</strong>
                        </td>
                        <td>
                            <textarea class="form-control w350" name="role_description" rows="4">{DATA.role_description}</textarea>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <strong id="apiRoleAll">{LANG.api_roles_allowed}<!-- BEGIN: total_api_enabled --> <span>({TOTAL_API_ENABLED})</span> <!-- END: total_api_enabled --></strong>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="root-api-actions">
                                <ul>
                                    <!-- BEGIN: catlev1 -->
                                    <li><a data-toggle="apicat" data-cat="{CAT1_KEY}" href="#api-child-{CAT1_KEY}"{CAT1_ACTIVE}>{CAT1_NAME}<!-- BEGIN: cat_api_enabled --> <span>({CAT_API_ENABLED})</span> <!-- END: cat_api_enabled --></a></li>
                                    <!-- BEGIN: catlev2 -->
                                    <li><a data-toggle="apicat" data-cat="{CAT2_KEY}" href="#api-child-{CAT2_KEY}"{CAT2_ACTIVE}>{CAT2_NAME}<!-- BEGIN: cat_api_enabled --> <span>({CAT_API_ENABLED})</span> <!-- END: cat_api_enabled --></a></li>
                                    <!-- END: catlev2 -->
                                    <!-- END: catlev1 -->
                                </ul>
                            </div>
                        </td>
                        <td>
                            <div class="child-apis">
                                <div class="panel-body">
                                    <!-- BEGIN: catcontents -->
                                    <div data-toggle="apichid" class="child-apis-item" id="api-child-{CTN_KEY}"{CTN_DISPLAY}>
                                        <div class="child-apis-item-ctn">
                                            <div class="row">
                                                <!-- BEGIN: loop -->
                                                <div class="col-sm-12">
                                                    <label><input data-toggle="apiroleit" type="checkbox" name="api_{CTN_KEY}[]" value="{API_CMD}" {API_CHECKED}/>{API_NAME}</label>
                                                </div>
                                                <!-- END: loop -->
                                            </div>
                                        </div>
                                        <div class="child-apis-item-tool">
                                            <hr />
                                            <ul class="list-inline list-unstyled">
                                                <li><i class="fa fa-fw fa-check-circle-o" aria-hidden="true"></i><a href="#api-child-{CTN_KEY}" data-toggle="apicheck">{LANG.api_roles_checkall}</a></li>
                                                <li><i class="fa fa-fw fa-circle-o" aria-hidden="true"></i><a href="#api-child-{CTN_KEY}" data-toggle="apiuncheck">{LANG.api_roles_uncheckall}</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <!-- END: catcontents -->
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>
                            <input type="hidden" name="current_cat" value="{CURRENT_CAT}" /> <input type="submit" name="submit" value="{GLANG.save}" class="btn btn-primary" />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </form>
</div>
<!-- BEGIN: is_submit_form -->
<script type="text/javascript">
$(document).ready(function() {
    $("html,body").animate({scrollTop: $('#addeditarea').offset().top}, 100);
});
</script>
<!-- END: is_submit_form -->
<!-- END: main -->