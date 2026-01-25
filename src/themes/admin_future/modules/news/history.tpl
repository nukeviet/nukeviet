<div class="table-responsive table-card">
    <table class="table table-striped align-middle mb-2">
        <thead class="text-muted">
            <tr>
                <th style="width: 20%;" class="text-nowrap">{$LANG->getModule('history_time')}</th>
                <th style="width: 30%;" class="text-nowrap">{$LANG->getModule('history_author')}</th>
                <th style="width: 30%;" class="text-nowrap">{$LANG->getModule('history_changefields')}</th>
                <th style="width: 20%;" class="text-nowrap text-center">{$LANG->getModule('function')}</th>
            </tr>
        </thead>
        <tbody>
            {foreach from=$HISTORIES item=row}
            <tr>
                <td>{$row.historytime|dformat}</td>
                <td>
                    {if isset($USERS[$row.admin_id])}{$USERS[$row.admin_id].show_name}{else}#{$row.admin_id}{/if}
                </td>
                <td>{$row.changed_fields}</td>
                <td class="text-center text-nowrap">
                    <a data-toggle="restoreHistory" href="{$BASE_URL}&amp;loadhistory={$NEW_ID}" data-id="{$row.id}" data-msg="{$LANG->getModule('history_restore_confirm')}" class="btn btn-sm btn-info text-nowrap">
                        <i class="fa-solid fa-fw text-center fa-database" data-icon="fa-database"></i> {$LANG->getModule('history_restore')}
                    </a>
                </td>
            </tr>
            {/foreach}
        </tbody>
    </table>
</div>
