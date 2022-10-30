<!-- BEGIN: main -->
<!-- BEGIN: empty -->
<div class="alert alert-info">{LANG.api_roles_empty}.</div>
<!-- END: empty -->
<!-- BEGIN: data -->
<div class="alert alert-info">{LANG.api_role_notice}.</div>
<form>
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <caption><i class="fa fa-file-text-o"></i> {LANG.api_roles_list}</caption>
            <thead>
                <tr>
                    <th style="width: 25%;">{LANG.api_roles_title}</th>
                    <th style="width: 30%;">{LANG.api_roles_description}</th>
                    <th style="width: 15%;">{LANG.api_addtime}</th>
                    <th style="width: 15%;">{LANG.api_edittime}</th>
                    <th style="width: 15%;" class="text-center text-nowrap">{LANG.funcs}</th>
                </tr>
            </thead>
            <tbody>
                <!-- BEGIN: loop -->
                <tr>
                    <td>
                        <a href="#apiroledetail{ROW.role_id}" data-toggle="modal">{ROW.role_title}</a> <strong class="text-danger">({ROW.apitotal})</strong>
                    </td>
                    <td>{ROW.role_description}</td>
                    <td>{ROW.addtime}</td>
                    <td>{ROW.edittime}</td>
                    <td class="text-center text-nowrap">
                        <a href="{ROW.url_edit}" class="btn btn-xs btn-default"><i class="fa fa-pencil"></i> {GLANG.edit}</a>
                        <a href="#" class="btn btn-xs btn-danger" data-id="{ROW.role_id}" data-toggle="apiroledel"><i class="fa fa-trash-alt"></i> {GLANG.delete}</a>
                    </td>
                </tr>
                <!-- START FORFOOTER -->
                <div id="apiroledetail{ROW.role_id}" tabindex="-1" role="dialog" class="modal fade">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" data-dismiss="modal" aria-hidden="true" class="close"><span class="fa fa-times"></span></button>
                                <h3 class="modal-title"><strong>{LANG.api_roles_detail}: {ROW.role_title}</strong></h3>
                            </div>
                            <div class="modal-body">
                                <!-- BEGIN: catsys -->
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <h4><strong>{LANG.api_of_system}: {CAT_DATA.title}</strong></h4>
                                        <div class="row">
                                            <!-- BEGIN: loop -->
                                            <div class="col-sm-12">
                                                <div class="text-truncate m-bottom"><i class="fa fa-genderless"></i> {API_DATA}</div>
                                            </div>
                                            <!-- END: loop -->
                                        </div>
                                    </div>
                                </div>
                                <!-- END: catsys -->

                                <!-- BEGIN: apimod -->
                                <!-- BEGIN: mod -->
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <h4><strong>{MOD_TITLE}<!-- BEGIN: title --> <i class="fa fa-angle-right" aria-hidden="true"></i> {CAT_DATA.title}<!-- END: title --></strong></h4>
                                        <div class="row">
                                            <!-- BEGIN: loop -->
                                            <div class="col-sm-12">
                                                <div class="text-truncate m-bottom" title="{API_DATA}"><i class="fa fa-genderless"></i> {API_DATA}</div>
                                            </div>
                                            <!-- END: loop -->
                                        </div>
                                    </div>
                                </div>
                                <!-- END: mod -->
                                <!-- END: apimod -->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END FORFOOTER -->
                <!-- END: loop -->
            </tbody>
        </table>
    </div>
</form>
<!-- END: data -->
<div id="addeditarea">
    <!-- BEGIN: add_notice -->
    <div class="alert alert-info">{LANG.api_role_notice_lang}.</div>
    <!-- END: add_notice -->
    <!-- BEGIN: error -->
    <div class="alert alert-danger">{ERROR}</div>
    <!-- END: error -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>{CAPTION}</strong>
        </div>
        <div class="panel-body">
            <form method="post" action="{FORM_ACTION}" class="form-horizontal" autocomplete="off">
                <div class="form-group">
                    <label class="col-sm-6 control-label" for="role_title">{LANG.api_roles_title} <span class="text-danger">(*)</span>:</label>
                    <div class="col-sm-18 col-lg-10">
                        <input type="text" id="role_title" name="role_title" value="{DATA.role_title}" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-6 control-label" for="role_description">{LANG.api_roles_description}:</label>
                    <div class="col-sm-18 col-lg-10">
                        <textarea type="text" class="form-control" id="role_description" name="role_description" rows="2">{DATA.role_description}</textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-18 col-sm-offset-6">
                        <strong id="apiRoleAll">{LANG.api_roles_allowed}<!-- BEGIN: total_api_enabled --> <span>({TOTAL_API_ENABLED})</span><!-- END: total_api_enabled --></strong>
                    </div>
                </div>
                <hr />
                <div class="row">
                    <div class="col-sm-6">
                        <div class="root-api-actions">
                            <ul>
                                <!-- BEGIN: api_tree -->
                                <li><a data-toggle="apicat" data-cat="{API_TREE.key}" href="#api-child-{API_TREE.key}"<!-- BEGIN: active --> class="active"<!-- END: active -->>{API_TREE.name}<!-- BEGIN: total_api --> <span>({API_TREE.total_api})</span> <!-- END: total_api --></a></li>
                                <!-- BEGIN: sub -->
                                <li><a data-toggle="apicat" data-cat="{SUB.key}" href="#api-child-{SUB.key}"<!-- BEGIN: active --> class="active"<!-- END: active -->> &nbsp; &nbsp; {SUB.name}<!-- BEGIN: total_api --> <span>({SUB.total_api})</span> <!-- END: total_api --></a></li>
                                <!-- END: sub -->
                                <!-- END: api_tree -->
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-18">
                        <div class="child-apis">
                            <!-- BEGIN: api_content -->
                            <div data-toggle="apichid" class="child-apis-item" id="api-child-{API_CONTENT.key}"<!-- BEGIN: active --> style="display: block;"<!-- END: active -->>
                                <div class="child-apis-item-ctn">
                                    <div class="row">
                                        <!-- BEGIN: api -->
                                        <div class="col-sm-12">
                                            <div class="checkbox">
                                                <label>
                                                    <input data-toggle="apiroleit" class="custom-control-input" type="checkbox" name="api_{API_CONTENT.input_key}[]" value="{API.cmd}"<!-- BEGIN: checked --> checked="checked"<!-- END: checked -->> {API.name}
                                                </label>
                                            </div>
                                        </div>
                                        <!-- END: api -->
                                    </div>
                                </div>
                                <div class="child-apis-item-tool">
                                    <hr />
                                    <ul class="list-inline list-unstyled">
                                        <li class="list-inline-item"><a href="#api-child-{API_CONTENT.key}" data-toggle="apicheck"><i class="fa fa-check-circle text-muted"></i> {LANG.api_roles_checkall}</a></li>
                                        <li class="list-inline-item"><a href="#api-child-{API_CONTENT.key}" data-toggle="apiuncheck"><i class="fa fa-circle text-muted"></i> {LANG.api_roles_uncheckall}</a></li>
                                    </ul>
                                </div>
                            </div>
                            <!-- END: api_content -->
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-18 col-sm-offset-6">
                        <input type="hidden" name="current_cat" value="{CURRENT_CAT}">
                        <input type="hidden" name="save" value="1">
                        <button type="submit" value="submit" class="btn btn-primary">{GLANG.save}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- BEGIN: scrolltop -->
<script type="text/javascript">
$(document).ready(function() {
    $("html,body").animate({scrollTop: $('#addeditarea').offset().top}, 100);
});
</script>
<!-- END: scrolltop -->
<!-- END: main -->
