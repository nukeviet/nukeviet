{if $OPT eq 'code'}
<div class="auth-code" data-toggle="methods">
    <div class="method" data-toggle="method">
        <p class="mb-2">{$LANG->getGlobal('2teplogin_totppin_label')} <a class="badge text-bg-secondary" href="#" data-toggle="login2step_change">{$LANG->getGlobal('2teplogin_other_menthod')}</a></p>
        <div class="hstack gap-2">
            <input type="text" class="form-control" placeholder="{$LANG->getGlobal('2teplogin_totppin_placeholder')}" value="" name="nv_totppin" id="nv_totppin" maxlength="6" autocomplete="off" data-error-mess="{$LANG->getGlobal('2teplogin_error_opt')}" aria-label="{$LANG->getGlobal('2teplogin_totppin_placeholder')}">
            <button type="submit" class="btn btn-primary text-nowrap">{$LANG->getGlobal('confirm')}</button>
        </div>
    </div>
    <div class="method d-none" data-toggle="method">
        <p class="mb-2">{$LANG->getGlobal('2teplogin_code_label')} <a class="badge text-bg-secondary" href="#" data-toggle="login2step_change">{$LANG->getGlobal('2teplogin_other_menthod')}</a></p>
        <div class="hstack gap-2">
            <input type="text" class="form-control" placeholder="{$LANG->getGlobal('2teplogin_code_placeholder')}" value="" name="nv_backupcodepin" id="nv_backupcodepin" maxlength="8" autocomplete="off" data-error-mess="{$LANG->getGlobal('2teplogin_error_backup')}" aria-label="{$LANG->getGlobal('2teplogin_code_placeholder')}">
            <button type="submit" class="btn btn-primary text-nowrap">{$LANG->getGlobal('confirm')}</button>
        </div>
    </div>
    <input type="hidden" name="checkss" value="{$smarty.const.NV_CHECK_SESSION}">
    <input type="hidden" name="submit2scode" value="1">
</div>
{elseif $OPT eq 'key'}
<div data-toggle="auth-passkey" class="d-none">
    <button type="button" class="btn btn-secondary w-100" data-toggle=""><i class="fa-solid fa-key" data-icon="fa-key"></i> {$LANG->getGlobal('admin_2step_opt_key')}</button>
</div>
{else}
<a href="{$smarty.const.NV_BASE_ADMINURL}index.php?auth={$OPT}" class="btn btn-info btn-{$OPT}">{$LANG->getGlobal("admin_2step_opt_`$OPT`")}</a>
{/if}
