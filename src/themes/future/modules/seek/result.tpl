<input type="hidden" id="hidden_key" value="{$HIDDEN_KEY}">
<div class="mb-4">
    <div class="d-flex align-items-center flex-wrap gap-2 mb-3">
        <h2 class="fw-medium h6 mb-0">{$LANG->getModule('search_on')} &quot;{$MODULE_CUSTOM_TITLE}&quot;</h2>
        <span class="badge bg-info">{$SEARCH_RESULT_NUM}</span>
        {if $MORE ne ''}
        <a href="{$MORE}" class="ms-auto text-decoration-none">
            <i class="fa-solid fa-thumbtack"></i> {$LANG->getModule('view_all_title')}
        </a>
        {/if}
    </div>
    {foreach from=$RESULTS item=result}
    <div class="mb-3 pb-3 border-bottom">
        <h3 class="fs-6 mb-1 fw-medium"><a href="{$result.link}">{$result.title nofilter}</a></h3>
        <div class="text-muted small">{$result.content nofilter}</div>
    </div>
    {/foreach}
    {if $PAGINATION ne ''}
    <div class="d-flex justify-content-center mt-3">
        {$PAGINATION nofilter}
    </div>
    {/if}
</div>
