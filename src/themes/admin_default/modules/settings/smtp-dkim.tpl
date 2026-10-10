{if empty($DKIM_LIST)}
<div class="accordion-body">
    <div class="alert alert-info mb-0" role="alert">{$LANG->getModule('DKIM_empty')}</div>
</div>
{else}
<ul class="list-group list-group-flush list-group-accordion">
    {foreach from=$DKIM_LIST item=domain}
    {assign var="is_verified" value=(not empty($DKIM_VERIFIED_LIST) and in_array($domain, $DKIM_VERIFIED_LIST)) nocache}
    <li class="list-group-item" role="button" data-toggle="dkim_read" data-domain="{$domain}">
        <div class="d-flex gap-2 justify-content-between">
            <div class="text-break fw-medium">{$domain}</div>
            <div>
                {$LANG->getModule($is_verified ? 'DKIM_verified' : 'DKIM_unverified')}
                <i class="ico fa-solid fa-{$is_verified ? 'circle-check text-success' : 'circle-question text-info'}" data-icon="fa-{$is_verified ? 'circle-check' : 'circle-question'}"></i>
            </div>
        </div>
    </li>
    {/foreach}
</ul>
{/if}
