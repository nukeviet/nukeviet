{foreach from=$FIELDS key=key item=field}
<div class="row mb-2">
    <div class="col-6">
        <a href="#" data-toggle="fchoose" data-value="${$key}" class="text-break">{$field.name}</a>
    </div>
    <div class="col-6">
        <span class="font-monospace text-break">${$key}</span>
    </div>
</div>
{/foreach}
