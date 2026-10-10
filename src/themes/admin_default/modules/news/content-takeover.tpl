<div class="alert alert-info text-center" role="alert">
    {$MESSAGE}
    {if not empty($LINK_TAKEOVER)}
    <div class="mt-2">
        <a href="{$LINK_TAKEOVER}" class="btn btn-danger">{$LANG->getModule('dulicate_takeover')}</a>
    </div>
    {/if}
</div>
