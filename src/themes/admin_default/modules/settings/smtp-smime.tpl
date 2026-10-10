{if empty($CERT_LIST)}
<div class="accordion-body">
    <div class="alert alert-info mb-0" role="alert">{$LANG->getModule('DKIM_empty')}</div>
</div>
{else}
<ul class="list-group list-group-flush list-group-accordion">
    {foreach from=$CERT_LIST key=num item=email}
    <li class="list-group-item" role="button" data-toggle="cert_read" data-email="{$email}">
        <i class="ico fa-solid fa-certificate text-danger" data-icon="fa-certificate"></i> {$email}
    </li>
    {/foreach}
</ul>
{/if}
