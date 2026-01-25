{if empty($SAVE_STATUS)}
<div class="alert alert-danger text-center" role="alert">{$LANG->getModule('save_error', "`$smarty.const.NV_LOGS_DIR`/dump_backup")}</div>
{else}
<div class="alert alert-success text-center">
    <p>
        <strong>{$LANG->getModule('save_ok')}</strong>
    </p>
    <strong><a title="{$LANG->getModule('saved_return')}" href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=file">{$LANG->getModule('saved_return')}</a></strong>
</div>
{/if}
