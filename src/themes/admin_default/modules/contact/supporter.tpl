<!-- BEGIN: main -->
<!-- BEGIN: list -->
<div class="table-responsive">
    <table class="table table-striped table-bordered list" data-url="{OP_URL}">
        <thead class="bg-primary">
            <tr>
                <th colspan="2" class="text-center">{LANG.full_name}</th>
                <th class="text-nowrap text-center" style="width: 1%;">{GLANG.phonenumber}</th>
                <th class="text-nowrap text-center" style="width: 1%;">{GLANG.email}</th>
                <th class="text-nowrap text-center" style="width: 1%;">{LANG.active}</th>
                <th class="text-nowrap text-center" style="width: 1%;">{GLANG.actions}</th>
            </tr>
        </thead>
        <!-- BEGIN: department -->
        <tbody>
            <tr>
                <td colspan="6" class="bg-department">
                    <i class="fa fa-folder-open"></i> <!-- BEGIN: href --><a href="{DEPARTMENT.href}">
                        <!-- END: href --><strong>{DEPARTMENT.full_name}</strong><!-- BEGIN: href2 -->
                    </a><!-- END: href2 -->
                </td>
            </tr>
            <!-- BEGIN: loop -->
            <tr class="item" data-id="{SUPPORTER.id}">
                <td class="text-nowrap align-middle" style="width: 80px;">
                    <select class="form-control supporter_cweight" data-default="{SUPPORTER.weight}">
                        <!-- BEGIN: weight -->
                        <option value="{WEIGHT.key}" {WEIGHT.sel}>{WEIGHT.title}</option>
                        <!-- END: weight -->
                    </select>
                </td>
                <td class="align-middle">{SUPPORTER.full_name}</td>
                <td class="text-nowrap align-middle" style="width: 1%;">{SUPPORTER.phone}</td>
                <td class="text-nowrap align-middle" style="width: 1%;">{SUPPORTER.email}</td>
                <td class="text-nowrap text-center align-middle" style="width: 1%;">
                    <input type="checkbox" class="supporter_act" value="1" {SUPPORTER.act_checked} />
                </td>
                <td class="text-nowrap text-center align-middle" style="width: 1%;">
                    <button type="button" class="btn btn-sm btn-default supporter_edit" title="{GLANG.edit}"><i class="fa fa-edit fa-lg"></i></button>
                    <button type="button" class="btn btn-sm btn-default supporter_del" title="{GLANG.delete}"><em class="fa fa-trash-o fa-lg"></em></button>
                </td>
            </tr>
            <!-- END: loop -->
        </tbody>
        <!-- END: department -->
    </table>
</div>
<!-- END: list -->
<div class="text-center">
    <button type="button" data-url="{OP_URL}" class="btn btn-primary supporter_add<!-- BEGIN: show_form --> auto<!-- END: show_form -->">{LANG.supporter_add}</button>
</div>
<!-- Add_Supporter_Modal -->
<div class="modal fade" id="content" data-backdrop="static" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>
<!-- END: main -->

<!-- BEGIN: content -->
<form action="{FORM_ACTION}" method="post" class="form-horizontal supporter_content">
    <div class="form-group">
        <label class="col-sm-8 control-label">{LANG.department_parent}</label>
        <div class="col-sm-16">
            <select class="form-control" name="departmentid">
                <option value="0">{LANG.department_empty}</option>
                <!-- BEGIN: department -->
                <option value="{DEPARTMENT.id}" {DEPARTMENT.sel}>{DEPARTMENT.full_name}</option>
                <!-- END: department -->
            </select>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-8 control-label">{LANG.full_name}</label>
        <div class="col-sm-16">
            <input type="text" class="form-control required" name="full_name" value="{SUPPORTER.full_name}" maxlength="250" />
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-8 control-label">{LANG.supporter_avatar}</label>
        <div class="col-sm-16 field">
            <div class="input-group">
                <input class="form-control" type="text" name="image" value="{SUPPORTER.image}" id="selectfile" />
                <span class="input-group-btn">
                    <button class="btn btn-default selectfile" data-toggle="selectfile" data-target="selectfile" data-path="{MODULE_UPLOAD}" data-type="image" type="button">
                        <em class="fa fa-folder-open-o fa-fix"></em>
                    </button>
                    <button class="btn btn-default help-show" type="button">
                        <em class="fa fa-question fa-fix"></em>
                    </button>
                </span>
            </div>
            <div class="help-block" style="display: none;">{LANG.supporter_avatar_note}</div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-8 control-label">{GLANG.phonenumber}</label>
        <div class="col-sm-16 field">
            <div class="input-group">
                <input type="text" class="form-control required" name="phone" value="{SUPPORTER.phone}" maxlength="250" />
                <span class="input-group-btn">
                    <button class="btn btn-default help-show" type="button">
                        <em class="fa fa-question fa-fix"></em>
                    </button>
                </span>
            </div>
            <div class="help-block" style="display: none;">{GLANG.phone_note_content}</div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-8 control-label">{GLANG.email}</label>
        <div class="col-sm-16">
            <input type="text" class="form-control" name="email" value="{SUPPORTER.email}" maxlength="100" />
        </div>
    </div>

    <div class="form-group">
        <label class="col-xs-24">{LANG.otherContacts}</label>
        <div class="col-xs-24 strs">
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

    <div class="text-right">
        <input type="hidden" name="fc" value="content">
        <input type="hidden" name="id" value="{SUPPORTER.id}">
        <input type="hidden" name="save" value="1">
        <button type="submit" class="btn btn-primary">{LANG.save}</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">{GLANG.close}</button>
    </div>
</form>
<!-- END: content -->
