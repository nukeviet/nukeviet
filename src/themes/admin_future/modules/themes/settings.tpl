<div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0">
    <div class="card-header fs-5 fw-medium py-2">{$LANG->getModule('settings_utheme')}</div>
    <div class="card-body">
        <form method="post" class="ajax-submit" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" novalidate>
            <div class="mb-2">{$LANG->getModule('settings_utheme_help')}</div>
            <div class="alert alert-info mb-3" role="alert">
                <a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}">{$LANG->getModule('settings_utheme_note')}</a>. {$LANG_MESSAGE}.
            </div>
            <div class="fw-medium mb-2">{$LANG->getModule('settings_utheme_choose')}:</div>
            <div class="mb-3">
                {foreach from=$ARRAY item=theme}
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="user_allowed_theme_{$theme}" name="user_allowed_theme[]" value="{$theme}"{if in_array($theme, $DATA.user_allowed_theme, true) or $theme eq $GCONFIG.site_theme} checked{/if}{if $theme eq $GCONFIG.site_theme} disabled{/if}>
                    <label class="form-check-label text-break" for="user_allowed_theme_{$theme}">{$theme}</label>
                </div>
                {/foreach}
            </div>
            <button type="submit" class="btn btn-primary">{$LANG->getGlobal('submit')}</button>
            <input type="hidden" name="tokend" value="{$smarty.const.NV_CHECK_SESSION}">
        </form>
    </div>
</div>
