{foreach from=$ARRAY key=module item=mod_vals}
<div class="fs-2 fw-medium mb-2">{$module}</div>
<div class="card border-primary border-4 border-bottom-0 border-start-0 border-end-0 pb-1 mb-4">
    <div class="card-body">
        <div class="table-responsive-sm table-card">
            <table class="table table-sticky mb-0">
                <col style="width: 40%;">
                <col style="width: 30%;">
                <col style="width: 30%;">
                <thead>
                    <tr>
                        <th class="text-bg-primary">{$LANG->getModule('directive')}</th>
                        <th class="text-bg-primary">{$LANG->getModule('local_value')}</th>
                        <th class="text-bg-primary">{$LANG->getModule('master_value')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$mod_vals key=key item=value}
                    <tr>
                        <td class="text-break">{$key}</td>
                        {if not is_array($value)}
                        <td class="text-break" colspan="2">{$value}</td>
                        {elseif isset($value[1])}
                        <td class="text-break">{$value.0}</td>
                        <td class="text-break">{$value.1}</td>
                        {/if}
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>
{/foreach}
