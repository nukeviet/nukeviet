<form method="post" class="ajax-submit" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" novalidate>
    <div class="card">
        <div class="card-header fs-5 fw-medium">{$LANG->getModule('rpc_ping')}</div>
        <ul class="list-group list-group-flush">
            {foreach from=$SERVICES key=service_id item=service}
            <li class="list-group-item">
                <div class="form-check">
                    <input class="form-check-input"{if $NO_CONFIG or in_array($service.1, $PRCSERVICE)} checked{/if} id="prcservice_{$service_id}" type="checkbox" data-toggle="checkSingle" name="prcservice[]" value="{$service.1}">
                    <label class="form-check-label" for="prcservice_{$service_id}">
                        {if not empty($service.3)}
                        <img src="{$IMGPATH}/{$service.3}" alt="{$service.1}">
                        {else}
                        <img src="{$IMGPATH}/link.png" alt="{$service.1}">
                        {/if}
                        {$service.1}
                    </label>
                </div>
            </li>
            {/foreach}
        </ul>
        <div class="card-footer border-top">
            <div class="hstack gap-2">
                <input type="hidden" name="checkss" value="{$smarty.const.NV_CHECK_SESSION}">
                <input type="hidden" name="submitprcservice" value="1">
                <input type="checkbox" data-toggle="checkAll" name="checkall" class="form-check-input m-0 align-middle" aria-label="{$LANG->getGlobal('toggle_checkall')}">
                <button type="submit" class="btn btn-primary">{$LANG->getGlobal('save')}</button>
            </div>
        </div>
    </div>
</form>
