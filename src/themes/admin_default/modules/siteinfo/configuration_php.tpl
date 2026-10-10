<div class="card border-primary border-4 border-bottom-0 border-start-0 border-end-0 pb-1">
    <div class="card-body">
        <div class="table-responsive-sm table-card">
            <table class="table table-sticky mb-0">
                <col style="width: 33.3333%;">
                <col style="width: 33.3333%;">
                <col style="width: 33.3333%;">
                <thead>
                    <tr>
                        <th class="text-bg-primary">{$LANG->getModule('directive')}</th>
                        <th class="text-bg-primary">{$LANG->getModule('local_value')}</th>
                        <th class="text-bg-primary">{$LANG->getModule('master_value')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$DATA key=key item=row}
                    <tr>
                        <td class="text-break">{$key}</td>
                        {if is_array($row)}
                        <td class="fw-medium text-break">{$row.0}</td>
                        <td class="fw-medium text-break">{$row.1}</td>
                        {else}
                        <td class="text-break">{$row}</td>
                        <td class="text-break">{$row}</td>
                        {/if}
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>
