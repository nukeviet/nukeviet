{if empty($WIDGETS)}
<div class="alert alert-info" role="alert">{$LANG->getModule('widget_no')}</div>
{else}
<ul class="list-group">
    {foreach from=$WIDGETS key=widget_id item=widget}
    <li class="list-group-item d-flex justify-content-between align-items-center">
        <span>{$widget.data.name}</span>
        <button class="btn btn-primary btn-sm ms-2" data-toggle="setWidget" data-widget-id="{$widget_id}"><i class="fa-solid fa-check" data-icon="fa-check"></i> Chọn</button>
    </li>
    {/foreach}
</ul>
{/if}
