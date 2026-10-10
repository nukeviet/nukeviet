<div class="flex-grow-1 flex-shrink-1 d-flex flex-column">
    <div class="p-3 border-bottom border-light-subtle">
        <h5 class="mb-0">{$LANG->getModule('pendingInfo')}</h5>
    </div>
    {if empty($PENDINGS)}
    <div class="m-3 alert alert-info" role="alert">{$LANG->getModule('no_job')}</div>
    {else}
    <div class="flex-grow-1 flex-shrink-1">
        <div class="widget-scroller" data-nv-toggle="scroll">
            <div>
                {foreach from=$PENDINGS item=modinfo}
                <div class="bg-body-tertiary px-3 py-2 fw-medium mb-2">
                    <i class="fa-solid fa-plus fa-sm"></i> {$modinfo.caption}
                </div>
                {foreach from=$modinfo.field item=pd}
                <div class="px-3 mb-2 d-flex align-items-center justify-content-between">
                    <div class="me-2 text-truncate">
                        {if not empty($pd.link)}
                        <i class="fa-solid fa-minus fa-sm"></i> <a href="{$pd.link}" title="{$pd.key}">{$pd.key}</a>
                        {else}
                        <i class="fa-solid fa-minus fa-sm"></i> {$pd.key}
                        {/if}
                    </div>
                    <span class="fw-bold">{$pd.value}</span>
                </div>
                {/foreach}
                {/foreach}
            </div>
        </div>
    </div>
    {/if}
</div>
