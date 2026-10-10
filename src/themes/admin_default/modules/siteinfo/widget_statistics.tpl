<div class="flex-grow-1 flex-shrink-1 d-flex flex-column">
    <div class="p-3 border-bottom border-light-subtle">
        <h5 class="mb-0">{$LANG->getModule('moduleInfo')}</h5>
    </div>
    {if empty($STATS)}
    <div class="m-3 alert alert-info" role="alert">{$LANG->getModule('no_job')}</div>
    {else}
    <div class="flex-grow-1 flex-shrink-1">
        <div class="widget-scroller" data-nv-toggle="scroll">
            <div>
                {foreach from=$STATS item=modinfo}
                <div class="bg-body-tertiary px-3 py-2 fw-medium mb-2">
                    <i class="fa-solid fa-plus fa-sm"></i> {$modinfo.caption}
                </div>
                {foreach from=$modinfo.field item=st}
                <div class="px-3 mb-2 d-flex align-items-center justify-content-between">
                    <div class="me-2 text-truncate">
                        {if not empty($st.link)}
                        <i class="fa-solid fa-minus fa-sm"></i> <a href="{$st.link}" title="{$st.key}">{$st.key}</a>
                        {else}
                        <i class="fa-solid fa-minus fa-sm"></i> {$st.key}
                        {/if}
                    </div>
                    <span class="fw-bold">{$st.value}</span>
                </div>
                {/foreach}
                {/foreach}
            </div>
        </div>
    </div>
    {/if}
</div>
