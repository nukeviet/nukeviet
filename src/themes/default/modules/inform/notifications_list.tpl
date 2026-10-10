<div class="table-responsive notification" data-delete-confirm="{$LANG->getModule('delete_confirm')}">
    <table class="table table-bordered table-striped align-middle mb-0">
        <thead class="table-primary">
            <tr>
                <th class="text-center text-nowrap" style="width:3%"><i class="fa-solid fa-circle-info" title="{$LANG->getModule('status')}"></i></th>
                <th class="text-nowrap" style="width:22%">{$LANG->getModule('receiver')}</th>
                <th>{$LANG->getModule('content')}</th>
                <th class="text-center text-nowrap" style="width:12%">{$LANG->getModule('add_time')}</th>
                <th class="text-center text-nowrap" style="width:12%">{$LANG->getModule('exp_time')}</th>
                <th class="text-center text-nowrap" style="width:5%">{$LANG->getModule('views')}</th>
                <th class="text-center text-nowrap" style="width:8%"></th>
            </tr>
        </thead>
        <tbody>
            {foreach $ITEMS as $item}
            <tr class="notification-item status-{$item.status}" data-id="{$item.id}">
                <td class="text-center">
                    {if $item.status == 'waiting'}
                    <i class="fa-solid fa-hourglass-half text-secondary" title="{$LANG->getModule('waiting')}"></i>
                    {elseif $item.status == 'expired'}
                    <i class="fa-solid fa-ban text-danger" title="{$LANG->getModule('expired')}"></i>
                    {else}
                    <i class="fa-solid fa-circle-check text-success" title="{$LANG->getModule('active')}"></i>
                    {/if}
                </td>
                <td>
                    {if empty($item.receiver_ids)}
                    {$LANG->getModule('to_group_all')}
                    {else}
                    {foreach $item.receiver_ids as $mid}
                    {if isset($MEMBERS[$mid])}
                    <button type="button" class="btn btn-sm btn-outline-secondary member-info me-1 mb-1"
                            tabindex="0"
                            data-toggle="viewUser"
                            data-uid="{$MEMBERS[$mid][0]}"
                            data-username="{$MEMBERS[$mid][1]|escape}"
                            data-fullname="{$MEMBERS[$mid][2]|escape}">
                        {$MEMBERS[$mid][2]|escape}
                    </button>
                    {/if}
                    {/foreach}
                    {/if}
                </td>
                <td>
                    {$item.message.0}
                    {if !empty($item.message.1)}
                    <span class="more text-nowrap">... <u data-toggle="more" role="button">{$LANG->getModule('view_more')}</u></span>
                    <span class="morecontent d-none">{$item.message.1}</span>
                    {/if}
                    {if !empty($item.link)}
                    <div class="inform-link mt-2">
                        <a href="{$item.link}" target="_blank" class="btn btn-sm btn-outline-secondary">
                            {$LANG->getModule('inform_link')}
                        </a>
                    </div>
                    {/if}
                </td>
                <td class="text-center">{$item.add_time_format}</td>
                <td class="text-center">{$item.exp_time_format}</td>
                <td class="text-center">{$item.views}</td>
                <td class="text-center text-nowrap">
                    <button type="button" class="btn btn-sm btn-secondary"
                            data-toggle="inform_action" data-type="edit"
                            data-title="{$LANG->getModule('inform_edit')}"
                            title="{$LANG->getModule('inform_edit')}"
                            aria-label="{$LANG->getModule('inform_edit')}">
                        <i class="fa-solid fa-pencil"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-danger"
                            data-toggle="inform_del"
                            title="{$LANG->getGlobal('delete')}"
                            aria-label="{$LANG->getGlobal('delete')}"
                            data-icon="fa-trash">
                        <i class="fa-solid fa-trash" data-icon="fa-trash"></i>
                    </button>
                </td>
            </tr>
            {foreachelse}
            <tr><td colspan="7" class="text-center text-muted py-4">{$LANG->getModule('no_notifications')}</td></tr>
            {/foreach}
        </tbody>
    </table>
    {if !empty($GENERATE_PAGE)}
    <div class="text-center p-2">
        {$GENERATE_PAGE}
    </div>
    {/if}
</div>
