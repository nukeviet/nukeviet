<div class="p-4" id="zalo-token-result" data-status="{$RESULT.status}" data-mess="{$RESULT.mess}">
    {if $RESULT.status eq 'success'}
    <div class="alert alert-success d-flex gap-2 align-items-start mb-0" role="alert">
        <i class="fa-solid fa-circle-check mt-1"></i>
        <div>{$LANG->getGlobal('save_success')}</div>
    </div>
    {else}
    <div class="alert alert-danger d-flex gap-2 align-items-start mb-0" role="alert">
        <i class="fa-solid fa-triangle-exclamation mt-1"></i>
        <div>{$RESULT.mess}</div>
    </div>
    {/if}
</div>
