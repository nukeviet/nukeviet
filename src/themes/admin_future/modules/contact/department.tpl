<!-- BEGIN: main -->
<div class="table-responsive">
    <table class="table table-striped table-bordered list" data-checkss="{$CHECKSS}" data-url="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}">
        <thead class="bg-primary">
            <tr>
                <th class="text-center text-nowrap" style="width:1%;">{$LANG->getModule('number')}</th>
                <th class="text-center text-nowrap">{$LANG->getModule('part_row_title')}</th>
                <th class="text-center text-nowrap" style="width:1%;">{$LANG->getGlobal('email')}</th>
                <th class="text-center text-nowrap" style="width:1%;">{$LANG->getGlobal('phonenumber')}</th>
                <th class="text-center text-nowrap" style="width:1%">{$LANG->getGlobal('status')}</th>
                <th class="text-center text-nowrap" style="width:1%;">{$LANG->getModule('is_default')}</th>
                <!-- BEGIN: is_spadmin -->{if !empty($smarty.const.NV_IS_SPADMIN)}<th class="text-center text-nowrap" style="width:1%;">{$LANG->getGlobal('actions')}</th>{/if}<!-- END: is_spadmin -->
            </tr>
        </thead>
        <tbody>
            <!-- BEGIN: row -->
            {assign var="COUNT" value=$DEPARTMENTS|count}
            {foreach $DEPARTMENTS as $ROW}
            {assign var="ARR_STATUS" value=[$LANG->getGlobal('disable'), $LANG->getGlobal('active'), $LANG->getModule('department_no_home')]}
            <tr class="item" data-id="{$ROW.id}">
                <td class="text-center align-middle" style="width:1%;">
                    {if !empty($smarty.const.NV_IS_SPADMIN)}
                    <!-- BEGIN: is_spadmin1 -->
                    <select class="form-select department_cweight" data-default="{$ROW.weight}" style="width:fit-content">
                        <!-- BEGIN: option -->
                        {for $WEIGHT = 1 to $COUNT}
                        <option value="{$WEIGHT}" {if $WEIGHT == $ROW.weight}selected{/if}>{$WEIGHT}</option>
                        {/for}
                        <!-- END: option -->
                    </select>
                    <!-- END: is_spadmin1 -->
                    {else}
                    <!-- BEGIN: is_modadmin1 -->{$ROW.weight}<!-- END: is_modadmin1 -->
                    {/if}
                </td>
                <td class="align-middle full_name{if !empty($ROW.is_default)} is-default{/if}">
                    <a href="#" class="department_view">{$ROW.full_name}</a>
                </td>
                <td class="align-middle text-nowrap" style="width:1%;">{$ROW.email}</td>
                <td class="align-middle text-nowrap" style="width:1%;">{$ROW.phone|regex_replace:'/(\[|&#91;)[^\]]*(&#93;|\])$/':''}</td>
                <td class="text-center align-middle">
                    {if !empty($smarty.const.NV_IS_SPADMIN)}
                    <!-- BEGIN: is_spadmin2 -->
                    <select class="form-select department_cstatus" data-default="{$ROW.act}" style="width: 150px">
                        {foreach $ARR_STATUS as $KEY => $STATUS}
                        <!-- BEGIN: status -->
                            <option value="$KEY" {if $KEY == $ROW.act}selected{/if}>{$STATUS}</option>
                        <!-- END: status -->
                        {/foreach}
                    </select>
                    <!-- END: is_spadmin2 -->
                    {else}
                    <!-- BEGIN: is_modadmin2 -->{$ARR_STATUS[$ROW.act]}<!-- END: is_modadmin2 -->
                    {/if}
                </td>
                <td class="text-center align-middle" style="width:1%;">
                    <!-- BEGIN: is_spadmin3 -->
                    {if !empty($smarty.const.NV_IS_SPADMIN)}
                    <input type="radio" name="is_default" class="form-check-input" value="{$ROW.id}" {if !empty($ROW.is_default)}checked{/if} aria-label="{$LANG->getModule('is_default_select')}">
                    <!-- END: is_spadmin3 -->
                    {elseif !empty($ROW.is_default)}
                    <!-- BEGIN: is_modadmin3 -->
                    <i class="fa-solid fa-check"></i>
                    {/if}
                    <!-- END: is_modadmin3 -->
                </td>
                <!-- BEGIN: is_spadmin4 -->
                {if !empty($smarty.const.NV_IS_SPADMIN)}
                <td class="text-center align-middle text-nowrap" style="width:1%;">
                    <button type="button" title="{$LANG->getGlobal('edit')}" aria-label="{$LANG->getGlobal('edit')}" class="btn btn-secondary btn-sm department_edit"><i class="fa-solid fa-pencil fa-lg"></i></button>
                    <button type="button" title="{$LANG->getGlobal('delete')}" aria-label="{$LANG->getGlobal('delete')}" class="btn btn-secondary btn-sm department_del"><i class="fa-solid fa-trash fa-lg"></i></button>
                </td>
                {/if}
                <!-- END: is_spadmin4 -->
            </tr>
            {/foreach}
            <!-- END: row -->
        </tbody>
    </table>
</div>
<!-- BEGIN: is_spadmin5 -->
{if !empty($smarty.const.NV_IS_SPADMIN)}
<div class="text-center">
    <button type="button" title="{$LANG->getModule('department_add')}" data-url="{$OP_URL}" class="btn btn-primary department_add{if empty($DEPARTMENTS)} auto{/if}">{$LANG->getModule('department_add')}</button>
</div>
<!-- END: is_spadmin5 -->
<!-- BEGIN: is_spadmin6 -->
<!-- Add_Department_Modal -->
<div class="modal fade" id="content" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="department_add_title" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title" id="department_add_title"></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{$LANG->getGlobal('close')}"></button>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>
<!-- END: is_spadmin6 -->
{/if}
<!-- END: main -->

{* <!-- BEGIN: content -->
<form action="{$FORM_ACTION}" method="post" class="form-horizontal department_content">
    <div class="form-group">
        <label class="col-sm-8 col-md-6 control-label">{$LANG->getModule('part_row_title')}</label>
        <div class="col-sm-16 col-md-18">
            <input class="form-control required" type="text" name="full_name" value="{$DEPARTMENT.full_name}" maxlength="250">
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-8 col-md-6 control-label">{$LANG->getModule('alias')}</label>
        <div class="col-sm-16 col-md-18">
            <div class="input-group">
                <input class="form-control" type="text" name="alias" value="{$DEPARTMENT.alias}" id="department-alias">
                <span class="input-group-btn">
                    <button class="btn btn-secondary department_alias" type="button">
                        <em class="fa fa-retweet fa-fw"></em>
                    </button>
                </span>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-8 col-md-6 control-label">{$LANG->getModule('note_row_title')}</label>
        <div class="col-sm-16 col-md-18">
            {$DEPARTMENT.note}
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-8 col-md-6 control-label">{$LANG->getModule('image')}</label>
        <div class="col-sm-16 col-md-18">
            <div class="input-group">
                <input class="form-control" type="text" name="image" value="{$DEPARTMENT.image}" id="selectfile">
                <span class="input-group-btn">
                    <button type="button" data-toggle="selectfile" data-target="selectfile" data-path="{$MODULE_UPLOAD}" data-type="image" class="btn btn-info" title="{$LANG->getGlobal('browse_image')}"><em class="fa fa-folder-open-o"></em></button>
                </span>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-8 col-md-6 control-label">{$LANG->getGlobal('phonenumber')}</label>
        <div class="col-sm-16 col-md-18 field">
            <div class="input-group">
                <input type="text" class="form-control" name="phone" value="{$DEPARTMENT.phone}" maxlength="250">
                <span class="input-group-btn">
                    <button class="btn btn-secondary help-show" type="button">
                        <em class="fa fa-question fa-fw"></em>
                    </button>
                </span>
            </div>
            <div class="help-block" style="display: none;">{$LANG->getGlobal('phone_note_content')}</div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-8 col-md-6 control-label">Fax</label>
        <div class="col-sm-16 col-md-18">
            <input class="form-control" type="text" name="fax" value="{$DEPARTMENT.fax}" maxlength="250">
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-8 col-md-6 control-label">{$LANG->getGlobal('email')}</label>
        <div class="col-sm-16 col-md-18 field">
            <div class="input-group">
                <input type="text" class="form-control" name="email" value="{$DEPARTMENT.email}" maxlength="100">
                <span class="input-group-btn">
                    <button class="btn btn-secondary help-show" type="button">
                        <em class="fa fa-question fa-fw"></em>
                    </button>
                </span>
            </div>
            <div class="help-block" style="display: none;">{$LANG->getGlobal('multi_email_note')}</div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-8 col-md-6 control-label">{$LANG->getModule('address')}</label>
        <div class="col-sm-16 col-md-18">
            <input class="form-control" type="text" name="address" value="{$DEPARTMENT.address}" maxlength="250">
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-8 col-md-6 control-label">{$LANG->getModule('otherContacts')}</label>
        <div class="col-sm-16 col-md-18 strs">
            <!-- BEGIN: other -->
            <div class="str" style="display:flex">
                <div class="row" style="flex-grow:1">
                    <div class="col-xs-10">
                        <input type="text" class="form-control" name="other_name[]" value="{$OTHER.name}" placeholder="{$LANG->getModule('otherVar')}" maxlength="250">
                    </div>
                    <div class="col-xs-14">
                        <input type="text" class="form-control" name="other_value[]" value="{$OTHER.value}" placeholder="{$LANG->getModule('otherVal')}" maxlength="250">
                    </div>
                </div>
                <div class="text-nowrap" style="margin-left:10px">
                    <button class="btn btn-secondary str_add" type="button">
                        <em class="fa fa-plus fa-fix"></em>
                    </button>
                    <button class="btn btn-secondary str_del" type="button">
                        <em class="fa fa-times fa-fix"></em>
                    </button>
                </div>
            </div>
            <!-- END: other -->
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-8 col-md-6 control-label">{$LANG->getModule('cats')}</label>
        <div class="col-sm-16 col-md-18 strs">
            <!-- BEGIN: cat -->
            <div class="str" style="display:flex">
                <div style="flex-grow:1">
                    <input type="text" class="form-control" name="cats[]" value="{$CAT}" maxlength="250">
                </div>
                <div class="text-nowrap" style="margin-left:10px">
                    <button class="btn btn-secondary str_add" type="button">
                        <em class="fa fa-plus fa-fix"></em>
                    </button>
                    <button class="btn btn-secondary str_del" type="button">
                        <em class="fa fa-times fa-fix"></em>
                    </button>
                </div>
            </div>
            <!-- END: cat -->
        </div>
    </div>

    <div class="form-group">
        <label>{$LANG->getModule('list_admin_row_title')}</label>
        <div class="table-responsive">
            <table class="table table-bordered">
                <tbody>
                    <!-- BEGIN: admin -->
                    <tr{$ADMIN.suspend}>
                        <td>
                            <img style="vertical-align:middle;" alt="{$ADMIN.level_txt}" src="{$NV_BASE_SITEURL}themes/{$NV_ADMIN_THEME}/images/admin{$ADMIN.level}.png" width="38" height="18">
                            {$ADMIN.full_name} ({$ADMIN.username}, {$ADMIN.email})
                        </td>
                        <td class="align-middle text-center text-nowrap admin-level" style="width:1%">
                            <label><input type="checkbox" name="view_level[]" value="{$ADMIN.admid}"{$ADMIN.view_level}{$ADMIN.disabled}> {$LANG->getModule('admin_view_level')}</label>
                            <label><input type="checkbox" name="exec_level[]" value="{$ADMIN.admid}"{$ADMIN.exec_level}{$ADMIN.disabled}> {$LANG->getModule('admin_exec_level')}</label>
                            <label><input type="checkbox" name="reply_level[]" value="{$ADMIN.admid}"{$ADMIN.reply_level}{$ADMIN.disabled}> {$LANG->getModule('admin_reply_level')}</label>
                            <label><input type="checkbox" name="obt_level[]" value="{$ADMIN.admid}"{$ADMIN.obt_level}> {$LANG->getModule('admin_obt_level')}</label>
                        </td>
                    </tr>
                    <!-- END: admin -->
                </tbody>
            </table>
        </div>
    </div>

    <div class="text-right">
        <input type="hidden" name="fc" value="content">
        <input type="hidden" name="id" value="{$DEPARTMENT.id}">
        <input type="hidden" name="save" value="1">
        <button type="submit" class="btn btn-primary">{$LANG->getModule('save')}</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{$LANG->getGlobal('close')}</button>
    </div>
</form>
<!-- END: content -->

<!-- BEGIN: view -->
<table class="table table-bordered table-striped">
    <tbody>
        <!-- BEGIN: image -->
        <tr>
            <td class="text-nowrap">{$LANG->getModule('image')}:</td>
            <td><img src="{$DEPARTMENT.image}" class="img-thumbnail" alt=""></td>
        </tr>
        <!-- END: image -->
        <tr>
            <td class="text-nowrap">{$LANG->getModule('part_row_title')}:</td>
            <td>{$DEPARTMENT.full_name}</td>
        </tr>
        <tr>
            <td class="text-nowrap">{$LANG->getModule('note_row_title')}:</td>
            <td>{$DEPARTMENT.note}</td>
        </tr>
        <!-- BEGIN: phone -->
        <tr>
            <td class="text-nowrap">{$LANG->getGlobal('phonenumber')}:</td>
            <td>{$DEPARTMENT.phone}</td>
        </tr>
        <!-- END: phone -->
        <!-- BEGIN: fax -->
        <tr>
            <td class="text-nowrap">Fax:</td>
            <td>{$DEPARTMENT.fax}</td>
        </tr>
        <!-- END: fax -->
        <!-- BEGIN: email -->
        <tr>
            <td class="text-nowrap">{$LANG->getGlobal('email')}:</td>
            <td>{$DEPARTMENT.email}</td>
        </tr>
        <!-- END: email -->
        <!-- BEGIN: address -->
        <tr>
            <td class="text-nowrap">{$LANG->getModule('address')}:</td>
            <td>{$DEPARTMENT.address}</td>
        </tr>
        <!-- END: address -->
        <!-- BEGIN: other -->
        <tr>
            <td class="text-nowrap">{$OTHER.title}:</td>
            <td>{$OTHER.value}</td>
        </tr>
        <!-- END: other -->
        <!-- BEGIN: cats -->
        <tr>
            <td class="text-nowrap">{$LANG->getModule('cats')}:</td>
            <td>{$DEPARTMENT.cats}</td>
        </tr>
        <!-- END: cats -->
        <tr>
            <td class="text-nowrap">{$LANG->getModule('your_authority')}:</td>
            <td>{$DEPARTMENT.your_authority}</td>
        </tr>
    </tbody>
</table>
<!-- END: view --> *}
