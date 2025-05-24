<form action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" method="post" class="form-horizontal department_content ajax-submit">
    <div class="row mb-3">
        <label class="col-sm-4 col-md-3 col-form-label text-sm-end" for="department_full_name">{$LANG->getModule('part_row_title')} <span class="text-danger">(*)</span></label>
        <div class="col-sm-8 col-md-9">
            <input class="form-control required" type="text" name="full_name" id="department_full_name" value="{$DEPARTMENT.full_name}">
        </div>
    </div>

    <div class="row mb-3">
        <label class="col-sm-4 col-md-3 col-form-label text-sm-end" for="department-alias">{$LANG->getModule('alias')}</label>
        <div class="col-sm-8 col-md-9">
            <div class="input-group">
                <input class="form-control" type="text" name="alias" value="{$DEPARTMENT.alias}" id="department-alias" aria-describedby="department-alias-btn">
                <button class="btn btn-secondary department_alias" id="department-alias-btn" type="button" aria-label="{$LANG->getModule('generate_alias')}" title="{$LANG->getModule('generate_alias')}">
                    <i class="fa-solid fa-retweet fa-fw"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-sm-4 col-md-3 col-form-label text-sm-end">{$LANG->getModule('note_row_title')}</div>
        <div class="col-sm-8 col-md-9">
            {$DEPARTMENT.note}
        </div>
    </div>

    <div class="row mb-3">
        <label class="col-sm-4 col-md-3 col-form-label text-sm-end" for="selectfile">{$LANG->getModule('image')}</label>
        <div class="col-sm-8 col-md-9">
            <div class="input-group">
                <input class="form-control" type="text" name="image" value="{$DEPARTMENT.image}" id="selectfile" aria-describedby="dpt-img-btn">
                <button type="button" id="dpt-img-btn" data-toggle="selectfile" data-target="selectfile" data-path="{$MODULE_UPLOAD}" data-type="image" class="btn btn-info" title="{$LANG->getGlobal('browse_image')}" aria-label="{$LANG->getModule('select_image')}"><i class="fa-solid fa-folder-open"></i></button>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <label class="col-sm-4 col-md-3 col-form-label text-sm-end" for="department_phone">{$LANG->getGlobal('phonenumber')}</label>
        <div class="col-sm-8 col-md-9 field">
            <div class="input-group">
                <input type="text" class="form-control" name="phone" id="department_phone" value="{$DEPARTMENT.phone}" aria-describedby="help_phone">
                <button class="btn btn-secondary help-show" type="button" aria-label="{$LANG->getModule('help_show')}" id="help_phone">
                    <i class="fa-solid fa-question fa-fw"></i>
                </button>
            </div>
            <div class="form-text help-block" style="display: none;">{$LANG->getGlobal('phone_note_content')}</div>
        </div>
    </div>

    <div class="row mb-3">
        <label class="col-sm-4 col-md-3 col-form-label text-sm-end" for="department_fax">Fax</label>
        <div class="col-sm-8 col-md-9">
            <input class="form-control" type="text" name="fax" id="department_fax" value="{$DEPARTMENT.fax}">
        </div>
    </div>

    <div class="row mb-3">
        <label class="col-sm-4 col-md-3 col-form-label text-sm-end" for="department_email">{$LANG->getGlobal('email')}</label>
        <div class="col-sm-8 col-md-9 field">
            <div class="input-group">
                <input type="text" class="form-control" name="email" id="department_email" value="{$DEPARTMENT.email}" aria-describedby="help_email">
                <button class="btn btn-secondary help-show" type="button" aria-label="{$LANG->getModule('help_show')}" id="help_email">
                    <i class="fa-solid fa-question fa-fw"></i>
                </button>
            </div>
            <div class="form-text help-block" style="display: none;">{$LANG->getGlobal('multi_email_note')}</div>
        </div>
    </div>

    <div class="row mb-3">
        <label class="col-sm-4 col-md-3 col-form-label text-sm-end" for="department_address">{$LANG->getModule('address')}</label>
        <div class="col-sm-8 col-md-9">
            <input class="form-control" type="text" name="address" id="department_address" value="{$DEPARTMENT.address}">
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-sm-4 col-md-3 col-form-label text-sm-end">{$LANG->getModule('otherContacts')}</div>
        <div class="col-sm-8 col-md-9 strs">
            {foreach $DEPARTMENT.others as $NAME => $VALUE}
            <div class="str d-flex">
                <div class="row g-2 flex-grow-1">
                    <div class="col-5">
                        <input type="text" class="form-control" name="other_name[]" value="{$NAME}" placeholder="{$LANG->getModule('otherVar')}" aria-label="{$LANG->getModule('otherVar')}">
                    </div>
                    <div class="col-7">
                        <input type="text" class="form-control" name="other_value[]" value="{$VALUE}" placeholder="{$LANG->getModule('otherVal')}" aria-label="{$LANG->getModule('otherVal')}">
                    </div>
                </div>
                <div class="text-nowrap ms-2">
                    <button class="btn btn-secondary str_add" type="button" aria-label="{$LANG->getModule('add')}">
                        <i class="fa-solid fa-plus"></i>
                    </button>
                    <button class="btn btn-secondary str_del" type="button" aria-label="{$LANG->getModule('del')}">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>
            </div>
            {/foreach}
        </div>
    </div>

    <div class="row mb-3">
        <label class="col-sm-4 col-md-3 col-form-label text-sm-end">{$LANG->getModule('cats')}</label>
        <div class="col-sm-8 col-md-9 strs">
            {foreach $DEPARTMENT.cats as $CAT}
            <div class="str d-flex">
                <div class="flex-grow-1">
                    <input type="text" class="form-control" name="cats[]" value="{$CAT}" aria-label="{$LANG->getModule('cats')}">
                </div>
                <div class="text-nowrap ms-2">
                    <button class="btn btn-secondary str_add" type="button" aria-label="{$LANG->getModule('add')}">
                        <i class="fa-solid fa-plus"></i>
                    </button>
                    <button class="btn btn-secondary str_del" type="button" aria-label="{$LANG->getModule('del')}">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>
            </div>
            {/foreach}
        </div>
    </div>

    <div class="row mb-3">
        <div class="form-label">{$LANG->getModule('list_admin_row_title')}</div>
        <div class="table-responsive">
            <table class="table table-bordered">
                <tbody>
                    {foreach $MOD_ADMINS as $ADMIN_ID => $ADMIN}
                    <tr{if $ADMIN.is_suspend} class="bg-warning" title="{$LANG->getGlobal('admin_suspend')}"{/if}>
                        <td>
                            <img style="vertical-align:middle;" alt="{$LANG->getGlobal('level'|cat:$ADMIN.level)}" src="{$smarty.const.NV_BASE_SITEURL}themes/{$NV_ADMIN_THEME}/images/admin{$ADMIN.level}.png" width="38" height="18">
                            {$ADMIN.first_name|nv_show_name_user:$ADMIN.last_name:$ADMIN.username} ({$ADMIN.username}, {$ADMIN.email})
                        </td>
                        <td class="align-middle text-center text-nowrap admin-level" style="width:1%">
                            <div class="form-check form-check-inline">
                                <input type="checkbox" name="view_level[]" class="form-check-input" id="view_level_{$ADMIN_ID}" value="{$ADMIN_ID}"{if $ADMIN.level === 1 or (!empty($DEPARTMENT.admins.view_level) and $ADMIN_ID|in_array:$DEPARTMENT.admins.view_level:true)} checked{/if}{if $ADMIN.level === 1} disabled{/if}>
                                <label class="form-check-label" for="view_level_{$ADMIN_ID}">{$LANG->getModule('admin_view_level')}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="checkbox" name="exec_level[]" class="form-check-input" id="exec_level_{$ADMIN_ID}" value="{$ADMIN_ID}"{if $ADMIN.level === 1 or (!empty($DEPARTMENT.admins.exec_level) and $ADMIN_ID|in_array:$DEPARTMENT.admins.exec_level:true)} checked{/if}{if $ADMIN.level === 1} disabled{/if}>
                                <label class="form-check-label" for="exec_level_{$ADMIN_ID}">{$LANG->getModule('admin_exec_level')}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="checkbox" name="reply_level[]" class="form-check-input" id="reply_level_{$ADMIN_ID}" value="{$ADMIN_ID}"{if $ADMIN.level === 1 or (!empty($DEPARTMENT.admins.reply_level) and $ADMIN_ID|in_array:$DEPARTMENT.admins.reply_level:true)} checked{/if}{if $ADMIN.level === 1} disabled{/if}>
                                <label class="form-check-label" for="reply_level_{$ADMIN_ID}">{$LANG->getModule('admin_reply_level')}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="checkbox" name="obt_level[]" class="form-check-input" id="obt_level_{$ADMIN_ID}" value="{$ADMIN_ID}"{if !empty($DEPARTMENT.admins.obt_level) and $ADMIN_ID|in_array:$DEPARTMENT.admins.obt_level:true} checked{/if}>
                                <label class="form-check-label" for="obt_level_{$ADMIN_ID}">{$LANG->getModule('admin_obt_level')}</label>
                            </div>
                        </td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>

    <div class="text-end">
        <input type="hidden" name="fc" value="content">
        <input type="hidden" name="id" value="{$DEPARTMENT.id}">
        <input type="hidden" name="save" value="1">
        <input type="hidden" name="checkss" value="{$CHECKSS}">
        <button type="submit" class="btn btn-primary">{$LANG->getModule('save')}</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{$LANG->getGlobal('close')}</button>
    </div>
</form>
