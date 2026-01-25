<div role="alert" class="alert alert-info">{$LANG->getModule('delete_sendmail_info', $USER.username)}</div>
<div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0">
    <div class="card-body pt-4">
        <form class="ajax-submit" method="post" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}&amp;admin_id={$ADMIN_ID}" novalidate>
            <div class="row mb-3">
                <div class="col-12 col-sm-8 col-lg-6 col-xxl-5 offset-sm-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="sendmail" value="1" checked role="switch" id="element_sendmail">
                        <label class="form-check-label" for="element_sendmail">{$LANG->getModule('admin_del_sendmail')}</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="element_adminpass_iavim" class="col-12 col-sm-3 col-form-label text-sm-end">{$LANG->getGlobal('admin_password')} <span class="text-danger">(*)</span></label>
                <div class="col-12 col-sm-8 col-lg-6 col-xxl-5">
                    <input type="password" class="form-control" id="element_adminpass_iavim" name="adminpass_iavim" value="" maxlength="128" autocomplete="off">
                    <div class="invalid-feedback">{$LANG->getGlobal('required_invalid')}</div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="element_reason" class="col-12 col-sm-3 col-form-label text-sm-end">{$LANG->getModule('admin_del_reason')}</label>
                <div class="col-12 col-sm-8 col-lg-6 col-xxl-5">
                    <input type="text" class="form-control" id="element_reason" name="reason" value="" maxlength="255">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-12 col-sm-3 col-form-label text-sm-end">{$LANG->getModule('action_account')}</div>
                <div class="col-12 col-sm-8 col-lg-6 col-xxl-5 pt-0 pt-sm-2">
                    {foreach from=$LIST_ACTION_ACCOUNT key=key item=value}
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="action_account" value="{$key}" id="action_account_{$key}"{if $key eq $ACTION_ACCOUNT} checked{/if}>
                        <label class="form-check-label" for="action_account_{$key}">{$value}</label>
                    </div>
                    {/foreach}
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-8 col-lg-6 col-xxl-5 offset-sm-3">
                    <input type="hidden" name="checkss" value="{$CHECKSS}">
                    <input type="hidden" name="go_del" value="1">
                    <button type="submit" class="btn btn-danger">{$LANG->getModule('nv_admin_del')}</button>
                </div>
            </div>
        </form>
    </div>
</div>
