<!-- BEGIN: main -->
<!-- BEGIN: empty -->
<div class="alert alert-info">{LANG.api_roles_empty}</div>
<!-- END: empty -->
<!-- BEGIN: data -->
<form>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
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
                        <a href="#apiroledetail{ROW.role_id}" data-toggle="modal">{ROW.role_title}</a> <span class="api-count">{ROW.apitotal}</span>
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
            <tfoot>
                <tr>
                    <td colspan="5"><i class="fa fa-flag" aria-hidden="true"></i>  {LANG.api_role_notice}.</td>
                </tr>
            </tfoot>
        </table>
    </div>
</form>
<!-- END: data -->
<div id="addeditarea">
    <!-- BEGIN: error -->
    <div class="alert alert-danger">{ERROR}</div>
    <!-- END: error -->
    <form method="post" action="{FORM_ACTION}" class="form-horizontal" autocomplete="off">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <strong>{CAPTION}</strong>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <tbody>
                        <tr>
                            <td class="left-col">{LANG.api_roles_title} <span class="text-danger">(*)</span>:</td>
                            <td><input type="text" id="role_title" name="role_title" value="{DATA.role_title}" class="form-control w300"></td>
                        </tr>
                        <tr>
                            <td class="left-col">{LANG.api_roles_description}:</td>
                            <td><textarea type="text" class="form-control w300" id="role_description" name="role_description" rows="2">{DATA.role_description}</textarea></td>
                        </tr>
                        <tr>
                            <th colspan="2" id="apiRoleAll">{LANG.api_roles_allowed}: <span class="total-api-enabled api-count">{TOTAL_API_ENABLED}</span></th>
                        </tr>
                        <tr class="info">
                            <th class="left-col">{LANG.api_cat}</th>
                            <th>{LANG.cat_api_list}</th>
                        </tr>
                        <tr>
                            <td class="root-api-actions left-col">
                                <ul>
                                    <!-- BEGIN: api_tree -->
                                        <li><a data-toggle="apicat" data-cat="{API_TREE.key}" href="#api-child-{API_TREE.key}"<!-- BEGIN: active --> class="active"<!-- END: active -->>{API_TREE.name}<!-- BEGIN: total_api --> <span class="api-count"><span class="total_api">{API_TREE.total_api}</span>/{API_TREE.total}</span><!-- END: total_api --></a></li>
                                        <!-- BEGIN: sub --> <li><a data-toggle="apicat" data-cat="{SUB.key}" href="#api-child-{SUB.key}"<!-- BEGIN: active --> class="active"<!-- END: active -->> &nbsp; &nbsp; {SUB.name}<!-- BEGIN: total_api --> <span class="api-count"><span class="total_api">{SUB.total_api}</span>/{SUB.total}</span><!-- END: total_api --></a></li><!-- END: sub -->
                                    <!-- END: api_tree -->
                                </ul>
                            </td>
                            <td class="child-apis" style="background-color:white;">
                                <!-- BEGIN: api_content -->
                                <div data-toggle="apichid" class="child-apis-item" id="api-child-{API_CONTENT.key}" <!-- BEGIN: active --> style="display: block;"<!-- END: active -->>
                                    <div class="child-apis-item-ctn">
                                        <div class="row">
                                            <!-- BEGIN: api -->
                                            <div class="col-sm-12">
                                                <div class="checkbox">
                                                    <label>
                                                        <input data-toggle="apiroleit" class="form-control" type="checkbox" name="api_{API_CONTENT.input_key}[]" value="{API.cmd}"<!-- BEGIN: checked --> checked="checked"<!-- END: checked -->> {API.name}
                                                    </label>
                                                </div>
                                            </div>
                                            <!-- END: api -->
                                        </div>
                                    </div>
                                    <div class="child-apis-item-tool">
                                        <a href="#api-child-{API_CONTENT.key}" class="btn btn-default" data-toggle="apicheck"><i class="fa fa-check-square-o"></i> {LANG.api_roles_checkall}</a>
                                        <a href="#api-child-{API_CONTENT.key}" class="btn btn-default" data-toggle="apiuncheck"><i class="fa fa-square-o"></i> {LANG.api_roles_uncheckall}</a>
                                    </div>
                                </div>
                                <!-- END: api_content -->
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <!-- BEGIN: add_notice -->
                        <tr>
                            <td colspan="2"><i class="fa fa-flag" aria-hidden="true"></i> {LANG.api_role_notice_lang}</td>
                        </tr>
                        <!-- END: add_notice -->
                        <tr>
                            <td colspan="2">
                                <input type="hidden" name="current_cat" value="{CURRENT_CAT}">
                                <input type="hidden" name="save" value="1">
                                <button type="submit" class="btn btn-primary">{GLANG.save}</button>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

        </div>
    </form>
</div>
<!-- BEGIN: scrolltop -->
<script type="text/javascript">
    $(function() {
        $("html,body").animate({scrollTop: $('#addeditarea').offset().top}, 100);
    });
</script>
<!-- END: scrolltop -->
<!-- END: main -->