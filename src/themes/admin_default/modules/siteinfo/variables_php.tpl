<div class="card border-primary border-4 border-bottom-0 border-start-0 border-end-0 pb-1">
    <div class="card-body">
        <div class="table-responsive-sm table-card">
            <table class="table table-sticky mb-0">
                <col style="width: 30%;">
                <col style="width: 70%;">
                <thead>
                    <tr>
                        <th class="text-bg-primary">{$LANG->getModule('variable')}</th>
                        <th class="text-bg-primary">{$LANG->getModule('value')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$ARRAY key=key item=value}
                    <tr>
                        {if not in_array($key, $IGNORE_KEYS) and substr($key, 1, 7) != '_COOKIE'}
                        <td class="text-nowrap">{$key}</td>
                        <td class="text-break">{$value}</td>
                        {/if}
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>
