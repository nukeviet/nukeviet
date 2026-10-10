<form method="post" id="inform-action-form" class="ajax-submit"
      action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}"
      novalidate>
    <input type="hidden" name="action" value="inform_action">
    <input type="hidden" name="id" value="{$DATA.id}">
    <input type="hidden" name="save" value="1">
    <input type="hidden" name="checkss" value="{$CHECKSS}">

    {if !$IS_SPADMIN}
    <input type="hidden" name="sender_role" value="admin">
    <input type="hidden" name="sender_group" value="0">
    <input type="hidden" name="sender_admin" value="{$DATA.sender_admin}">
    {else}
    <div class="mb-3 row">
        <div class="col-md-4 col-form-label text-md-end">{$LANG->getModule('sender')}</div>
        <div class="col-md-8">
            <div class="row g-2">
                <div class="col-md-5">
                    <select name="sender_role" class="form-select"
                            data-from-group-title="{$LANG->getModule('to_members')}"
                            data-from-system-title="{$LANG->getModule('to_users')}">
                        {foreach from=$ROLES item=role}
                        <option value="{$role.key}"{if $role.key == $DATA.sender_role} selected{/if}>{$role.name}</option>
                        {/foreach}
                    </select>
                </div>
                <div class="col-md-7 sender-group-wrap{if $DATA.sender_role != 'group'} d-none{/if}">
                    <select name="sender_group" class="form-select"
                            data-default="{$DATA.sender_group}"
                            data-error="{$LANG->getModule('please_select_group')}"
                            {if $DATA.sender_group_disabled} disabled{/if}>
                        <option value="0">{$LANG->getModule('select_group')}</option>
                        {foreach from=$SENDER_GROUPLIST item=group}
                        <option value="{$group.key}"{if $group.key == $DATA.sender_group} selected{/if}>{$group.name} ({$LANG->getModule('id')} #{$group.key})</option>
                        {/foreach}
                    </select>
                </div>
                <div class="col-md-7 sender-admin-wrap{if $DATA.sender_role != 'admin'} d-none{/if}">
                    <select name="sender_admin" class="form-select"
                            data-default="{$DATA.sender_admin}"
                            {if $DATA.sender_admin_disabled} disabled{/if}>
                        <option value="0">{$LANG->getModule('select_admin')}</option>
                        {foreach from=$SENDER_ADMINLIST item=admin}
                        <option value="{$admin.key}"{if $admin.key == $DATA.sender_admin} selected{/if}>{$admin.name} ({$LANG->getModule('id')} #{$admin.key})</option>
                        {/foreach}
                    </select>
                </div>
            </div>
        </div>
    </div>
    {/if}

    <div class="mb-3 row">
        <div class="col-md-4 col-form-label text-md-end">{$LANG->getModule('receiver')}</div>
        <div class="col-md-8">
            <div class="row g-2">
                <div class="col-md-5">
                    <select name="receiver_type" class="form-select"
                            data-from-group-title="{$LANG->getModule('to_members')}"
                            data-from-system-title="{$LANG->getModule('to_users')}">
                        {foreach from=$RECEIVER_TYPES item=type}
                        <option value="{$type.key}"{if $type.key == $DATA.receiver_type} selected{/if}{if $type.disabled} disabled{/if}>{$type.name}</option>
                        {/foreach}
                    </select>
                </div>
                <div class="col-md-7 receiver-grs-wrap{if $DATA.receiver_type != 'grs'} d-none{/if}">
                    <select name="receiver_grs[]" id="receiver_grs" class="form-select"
                            multiple="multiple"
                            data-placeholder="{$LANG->getModule('select_group')}"
                            {if $DATA.receiver_grs_disabled} disabled{/if}>
                        {foreach from=$RECEIVER_GROUPLIST item=rgroup}
                        <option value="{$rgroup.key}"{if $rgroup.selected} selected{/if}>{$rgroup.name} ({$LANG->getModule('id')} #{$rgroup.key})</option>
                        {/foreach}
                    </select>
                </div>
                <div class="col-md-7 receiver-ids-wrap{if $DATA.receiver_type != 'ids'} d-none{/if}">
                    <select name="receiver_ids[]" id="receiver_ids" class="form-select"
                            multiple="multiple"
                            data-placeholder="{$LANG->getModule('to_all')}"
                            data-input-too-short="{$LANG->getModule('please_enter')}"
                            {if $DATA.receiver_ids_disabled} disabled{/if}>
                        {foreach from=$DATA.receiver_ids key=uid item=uname}
                        <option value="{$uid}" selected>{$uname|escape:'html'}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-3">
        <div class="form-label">{$LANG->getModule('content')}</div>
        <div class="border rounded">
            {foreach from=$SETUP_LANGS item=lang}
            <div class="border-bottom p-0">
                <div class="d-flex align-items-center px-3 py-2 border-bottom">
                    <span class="flex-grow-1 fw-semibold small">{$lang.name}</span>
                    <label class="d-flex align-items-center gap-2 mb-0 small">
                        <input type="radio" name="isdef" value="{$lang.key}"{if $lang.key == $DATA.isdef} checked{/if}>
                        {$LANG->getModule('default')}
                    </label>
                </div>
                <div class="p-2">
                    <textarea name="message[{$lang.key}]" class="form-control" rows="3" maxlength="2000">{$lang.message}</textarea>
                </div>
            </div>
            {/foreach}
        </div>
        <div class="form-text">{$LANG->getModule('default_help')}</div>
    </div>

    <div class="mb-3">
        <div class="form-label">{$LANG->getModule('inform_link')}</div>
        {foreach from=$SETUP_LANGS item=lang}
        <div class="mb-2">
            <label for="link_{$lang.key}" class="form-label text-muted small">{$lang.name}</label>
            <input type="url" id="link_{$lang.key}" name="link[{$lang.key}]" class="form-control" maxlength="500"
                   value="{$lang.link}" autocomplete="off">
        </div>
        {/foreach}
    </div>

    <div class="mb-3 row">
        <div class="col-md-4 col-form-label text-md-end">{$LANG->getModule('add_time')}</div>
        <div class="col-md-8">
            <div class="d-flex flex-wrap align-items-center gap-2">
                <div class="input-group flex-nowrap" style="width:160px">
                    <input type="text" name="add_time" value="{$DATA.add_time_format}"
                           class="form-control datepicker" maxlength="10" autocomplete="off"
                           aria-describedby="btn-add-time">
                    <button class="btn btn-secondary" type="button" id="btn-add-time"
                            data-toggle="focusDate" aria-label="{$LANG->getModule('add_time')}">
                        <i class="fa-regular fa-calendar"></i>
                    </button>
                </div>
                <select name="add_hour" class="form-select" style="width:auto">
                    {for $i=0 to 23}
                    <option value="{$i}"{if $i == $DATA.add_hour} selected{/if}>{$i|string_format:'%02d'}</option>
                    {/for}
                </select>
                <span>:</span>
                <select name="add_min" class="form-select" style="width:auto">
                    {for $i=0 to 59}
                    <option value="{$i}"{if $i == $DATA.add_min} selected{/if}>{$i|string_format:'%02d'}</option>
                    {/for}
                </select>
            </div>
        </div>
    </div>

    <div class="mb-3 row">
        <div class="col-md-4 col-form-label text-md-end">{$LANG->getModule('exp_time')}</div>
        <div class="col-md-8">
            <div class="d-flex flex-wrap align-items-center gap-2">
                <div class="input-group flex-nowrap" style="width:160px">
                    <input type="text" name="exp_time" value="{$DATA.exp_time_format}"
                           class="form-control datepicker" maxlength="10" autocomplete="off"
                           aria-describedby="btn-exp-time">
                    <button class="btn btn-secondary" type="button" id="btn-exp-time"
                            data-toggle="focusDate" aria-label="{$LANG->getModule('exp_time')}">
                        <i class="fa-regular fa-calendar"></i>
                    </button>
                </div>
                <select name="exp_hour" class="form-select" style="width:auto">
                    <option value="-1"></option>
                    {for $i=0 to 23}
                    <option value="{$i}"{if $i == $DATA.exp_hour} selected{/if}>{$i|string_format:'%02d'}</option>
                    {/for}
                </select>
                <span>:</span>
                <select name="exp_min" class="form-select" style="width:auto">
                    <option value="-1"></option>
                    {for $i=0 to 59}
                    <option value="{$i}"{if $i == $DATA.exp_min} selected{/if}>{$i|string_format:'%02d'}</option>
                    {/for}
                </select>
            </div>
            <div class="form-text">{$LANG->getModule('empty_is_unlimited')}</div>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2 border-top pt-3 mt-1">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            {$LANG->getGlobal('cancel')}
        </button>
        <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-floppy-disk"></i> {$LANG->getGlobal('save')}
        </button>
    </div>
</form>
