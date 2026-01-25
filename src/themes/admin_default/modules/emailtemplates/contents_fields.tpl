{foreach from=$FIELDS key=key item=field}
<div class="row mb-2">
    <div class="col-xs-12">
        <a href="#" data-toggle="fchoose" data-value="${$key}">{$field.name}</a>
    </div>
    <div class="col-xs-12">
        <span class="text-monospace">${$key}</span>
    </div>
</div>
{/foreach}
