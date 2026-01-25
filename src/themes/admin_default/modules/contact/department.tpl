<!-- BEGIN: main -->
<div class="table-responsive">
    <table class="table table-striped table-bordered list" data-url="{OP_URL}">
        <thead class="bg-primary">
            <tr>
                <th class="text-center text-nowrap" style="width:1%;">{LANG.number}</th>
                <th class="text-center text-nowrap">{LANG.part_row_title}</th>
                <th class="text-center text-nowrap" style="width:1%;">{GLANG.email}</th>
                <th class="text-center text-nowrap" style="width:1%;">{GLANG.phonenumber}</th>
                <th class="text-center text-nowrap w150">{GLANG.status}</th>
                <th class="text-center text-nowrap" style="width:1%;">{LANG.is_default}</th>
                <!-- BEGIN: is_spadmin --><th class="text-center text-nowrap" style="width:1%;">{GLANG.actions}</th><!-- END: is_spadmin -->
            </tr>
        </thead>
        <tbody>
            <!-- BEGIN: row -->
            <tr class="item" data-id="{ROW.id}">
                <td class="text-center align-middle" style="width:1%;">
                    <!-- BEGIN: is_spadmin1 -->
                    <select class="form-control department_cweight" data-default="{ROW.weight}" style="width:fit-content">
                        <!-- BEGIN: option -->
                        <option value="{WEIGHT.value}" {WEIGHT.selected}>{WEIGHT.value}</option>
                        <!-- END: option -->
                    </select>
                    <!-- END: is_spadmin1 -->
                    <!-- BEGIN: is_modadmin1 -->{ROW.weight}<!-- END: is_modadmin1 -->
                </td>
                <td class="align-middle full_name<!-- BEGIN: is_default --> is-default<!-- END: is_default -->">
                    <a href="#" class="department_view">{ROW.full_name}</a>
                </td>
                <td class="align-middle text-nowrap" style="width:1%;">{ROW.email}</td>
                <td class="align-middle text-nowrap" style="width:1%;">{ROW.phone}</td>
                <td class="text-center align-middle w150">
                    <!-- BEGIN: is_spadmin2 -->
                    <select class="form-control department_cstatus" data-default="{ROW.act}">
                        <!-- BEGIN: status -->
                        <option value="{STATUS.key}" {STATUS.selected}>{STATUS.title}</option>
                        <!-- END: status -->
                    </select>
                    <!-- END: is_spadmin2 -->
                    <!-- BEGIN: is_modadmin2 -->{STATUS}<!-- END: is_modadmin2 -->
                </td>
                <td class="text-center align-middle" style="width:1%;">
                    <!-- BEGIN: is_spadmin3 -->
                    <input type="radio" name="is_default" value="{ROW.id}" {ROW.is_default_checked} />
                    <!-- END: is_spadmin3 -->
                    <!-- BEGIN: is_modadmin3 -->
                    <em class="fa fa-check"></em>
                    <!-- END: is_modadmin3 -->
                </td>
                <!-- BEGIN: is_spadmin4 -->
                <td class="text-center align-middle text-nowrap" style="width:1%;">
                    <button type="button" title="{GLANG.edit}" class="btn btn-default btn-sm department_edit"><em class="fa fa-edit fa-lg"></em></button>
                    <button type="button" title="{GLANG.delete}" class="btn btn-default btn-sm department_del"><em class="fa fa-trash-o fa-lg"></em></button>
                </td>
                <!-- END: is_spadmin4 -->
            </tr>
            <!-- END: row -->
        </tbody>
    </table>
</div>
<!-- BEGIN: is_spadmin5 -->
<div class="text-center">
    <button type="button" title="{LANG.department_add}" data-url="{OP_URL}" class="btn btn-primary department_add<!-- BEGIN: show_form --> auto<!-- END: show_form -->">{LANG.department_add}</button>
</div>
<!-- END: is_spadmin5 -->
<!-- BEGIN: is_spadmin6 -->
<!-- Add_Department_Modal -->
<div class="modal fade" id="content" data-backdrop="static" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>
<!-- END: is_spadmin6 -->
<!-- END: main -->

<!-- BEGIN: content -->
<form action="{FORM_ACTION}" method="post" class="form-horizontal department_content">
    <div class="form-group">
        <label class="col-sm-8 col-md-6 control-label">{LANG.part_row_title}</label>
        <div class="col-sm-16 col-md-18">
            <input class="form-control required" type="text" name="full_name" value="{DEPARTMENT.full_name}" maxlength="250" />
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-8 col-md-6 control-label">{LANG.alias}</label>
        <div class="col-sm-16 col-md-18">
            <div class="input-group">
                <input class="form-control" type="text" name="alias" value="{DEPARTMENT.alias}" id="department-alias" />
                <span class="input-group-btn">
                    <button class="btn btn-default department_alias" type="button">
                        <em class="fa fa-retweet fa-fw"></em>
                    </button>
                </span>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-8 col-md-6 control-label">{LANG.note_row_title}</label>
        <div class="col-sm-16 col-md-18">
            {DEPARTMENT.note}
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-8 col-md-6 control-label">{LANG.image}</label>
        <div class="col-sm-16 col-md-18">
            <div class="input-group">
                <input class="form-control" type="text" name="image" value="{DEPARTMENT.image}" id="selectfile" />
                <span class="input-group-btn">
                    <button type="button" data-toggle="selectfile" data-target="selectfile" data-path="{MODULE_UPLOAD}" data-type="image" class="btn btn-info" title="{GLANG.browse_image}"><em class="fa fa-folder-open-o"></em></button>
                </span>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-8 col-md-6 control-label">{GLANG.phonenumber}</label>
        <div class="col-sm-16 col-md-18 field">
            <div class="input-group">
                <input type="text" class="form-control" name="phone" value="{DEPARTMENT.phone}" maxlength="250" />
                <span class="input-group-btn">
                    <button class="btn btn-default help-show" type="button">
                        <em class="fa fa-question fa-fw"></em>
                    </button>
                </span>
            </div>
            <div class="help-block" style="display: none;">{GLANG.phone_note_content}</div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-8 col-md-6 control-label">Fax</label>
        <div class="col-sm-16 col-md-18">
            <input class="form-control" type="text" name="fax" value="{DEPARTMENT.fax}" maxlength="250" />
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-8 col-md-6 control-label">{GLANG.email}</label>
        <div class="col-sm-16 col-md-18 field">
            <div class="input-group">
                <input type="text" class="form-control" name="email" value="{DEPARTMENT.email}" maxlength="100" />
                <span class="input-group-btn">
                    <button class="btn btn-default help-show" type="button">
                        <em class="fa fa-question fa-fw"></em>
                    </button>
                </span>
            </div>
            <div class="help-block" style="display: none;">{GLANG.multi_email_note}</div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-8 col-md-6 control-label">{LANG.address}</label>
        <div class="col-sm-16 col-md-18">
            <input class="form-control" type="text" name="address" value="{DEPARTMENT.address}" maxlength="250" />
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-8 col-md-6 control-label">{LANG.otherContacts}</label>
        <div class="col-sm-16 col-md-18 strs">
            <!-- BEGIN: other -->
            <div class="str" style="display:flex">
                <div class="row" style="flex-grow:1">
                    <div class="col-xs-10">
                        <input type="text" class="form-control" name="other_name[]" value="{OTHER.name}" placeholder="{LANG.otherVar}" maxlength="250" />
                    </div>
                    <div class="col-xs-14">
                        <input type="text" class="form-control" name="other_value[]" value="{OTHER.value}" placeholder="{LANG.otherVal}" maxlength="250" />
                    </div>
                </div>
                <div class="text-nowrap" style="margin-left:10px">
                    <button class="btn btn-default str_add" type="button">
                        <em class="fa fa-plus fa-fix"></em>
                    </button>
                    <button class="btn btn-default str_del" type="button">
                        <em class="fa fa-times fa-fix"></em>
                    </button>
                </div>
            </div>
            <!-- END: other -->
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-8 col-md-6 control-label">{LANG.cats}</label>
        <div class="col-sm-16 col-md-18 strs">
            <!-- BEGIN: cat -->
            <div class="str" style="display:flex">
                <div style="flex-grow:1">
                    <input type="text" class="form-control" name="cats[]" value="{CAT}" maxlength="250" />
                </div>
                <div class="text-nowrap" style="margin-left:10px">
                    <button class="btn btn-default str_add" type="button">
                        <em class="fa fa-plus fa-fix"></em>
                    </button>
                    <button class="btn btn-default str_del" type="button">
                        <em class="fa fa-times fa-fix"></em>
                    </button>
                </div>
            </div>
            <!-- END: cat -->
        </div>
    </div>

    <div class="form-group">
        <label>{LANG.list_admin_row_title}</label>
        <div class="table-responsive">
            <table class="table table-bordered">
                <tbody>
                    <!-- BEGIN: admin -->
                    <tr{ADMIN.suspend}>
                        <td>
                            <img style="vertical-align:middle;" alt="{ADMIN.level_txt}" src="{NV_BASE_SITEURL}themes/{NV_ADMIN_THEME}/images/admin{ADMIN.level}.png" width="38" height="18" />
                            {ADMIN.full_name} ({ADMIN.username}, {ADMIN.email})
                        </td>
                        <td class="align-middle text-center text-nowrap admin-level" style="width:1%">
                            <label><input type="checkbox" name="view_level[]" value="{ADMIN.admid}"{ADMIN.view_level}{ADMIN.disabled} /> {LANG.admin_view_level}</label>
                            <label><input type="checkbox" name="exec_level[]" value="{ADMIN.admid}"{ADMIN.exec_level}{ADMIN.disabled} /> {LANG.admin_exec_level}</label>
                            <label><input type="checkbox" name="reply_level[]" value="{ADMIN.admid}"{ADMIN.reply_level}{ADMIN.disabled} /> {LANG.admin_reply_level}</label>
                            <label><input type="checkbox" name="obt_level[]" value="{ADMIN.admid}"{ADMIN.obt_level} /> {LANG.admin_obt_level}</label>
                        </td>
                    </tr>
                    <!-- END: admin -->
                </tbody>
            </table>
        </div>
    </div>

    <div class="text-right">
        <input type="hidden" name="fc" value="content">
        <input type="hidden" name="id" value="{DEPARTMENT.id}">
        <input type="hidden" name="save" value="1">
        <button type="submit" class="btn btn-primary">{LANG.save}</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">{GLANG.close}</button>
    </div>
</form>
<!-- END: content -->

<!-- BEGIN: view -->
<table class="table table-bordered table-striped">
    <tbody>
        <!-- BEGIN: image -->
        <tr>
            <td class="text-nowrap">{LANG.image}:</td>
            <td><img src="{DEPARTMENT.image}" class="img-thumbnail" alt=""/></td>
        </tr>
        <!-- END: image -->
        <tr>
            <td class="text-nowrap">{LANG.part_row_title}:</td>
            <td>{DEPARTMENT.full_name}</td>
        </tr>
        <tr>
            <td class="text-nowrap">{LANG.note_row_title}:</td>
            <td>{DEPARTMENT.note}</td>
        </tr>
        <!-- BEGIN: phone -->
        <tr>
            <td class="text-nowrap">{GLANG.phonenumber}:</td>
            <td>{DEPARTMENT.phone}</td>
        </tr>
        <!-- END: phone -->
        <!-- BEGIN: fax -->
        <tr>
            <td class="text-nowrap">Fax:</td>
            <td>{DEPARTMENT.fax}</td>
        </tr>
        <!-- END: fax -->
        <!-- BEGIN: email -->
        <tr>
            <td class="text-nowrap">{GLANG.email}:</td>
            <td>{DEPARTMENT.email}</td>
        </tr>
        <!-- END: email -->
        <!-- BEGIN: address -->
        <tr>
            <td class="text-nowrap">{LANG.address}:</td>
            <td>{DEPARTMENT.address}</td>
        </tr>
        <!-- END: address -->
        <!-- BEGIN: other -->
        <tr>
            <td class="text-nowrap">{OTHER.title}:</td>
            <td>{OTHER.value}</td>
        </tr>
        <!-- END: other -->
        <!-- BEGIN: cats -->
        <tr>
            <td class="text-nowrap">{LANG.cats}:</td>
            <td>{DEPARTMENT.cats}</td>
        </tr>
        <!-- END: cats -->
        <tr>
            <td class="text-nowrap">{LANG.your_authority}:</td>
            <td>{DEPARTMENT.your_authority}</td>
        </tr>
    </tbody>
</table>
<!-- END: view -->
