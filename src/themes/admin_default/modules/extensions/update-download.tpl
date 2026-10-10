{if $WARNING}
<div class="alert alert-warning mb-0" role="alert" data-toggle="updateExtUnzip">
    {$LANG->getModule('get_update_warning', $LINK_UNZIP)}
</div>
{else}
<div class="alert alert-success mb-0" role="alert" data-toggle="updateExtUnzip">
    {$LANG->getModule('get_update_ok', $LINK_UNZIP)}
</div>
{/if}
