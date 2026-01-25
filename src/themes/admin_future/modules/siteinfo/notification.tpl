{if empty($DATA) and empty($DATA_SEARCH.v)}
<div role="alert" class="alert alert-warning">
    <i class="fa-solid fa-triangle-exclamation"></i> {$LANG->get('notification_empty')}
</div>
{else}
<div class="card">
    <div class="card-body">
        <form action="{$smarty.const.NV_BASE_ADMINURL}index.php" method="get" class="form-inline">
            <input type="hidden" name="{$smarty.const.NV_LANG_VARIABLE}" value="{$smarty.const.NV_LANG_DATA}">
            <input type="hidden" name="{$smarty.const.NV_NAME_VARIABLE}" value="{$MODULE_NAME}">
            <input type="hidden" name="{$smarty.const.NV_OP_VARIABLE}" value="{$OP}">
            <div class="row g-3 flex-nowrap align-items-end">
                <div class="col-auto flex-fill flex-md-grow-0 flex-md-shrink-0">
                    <label class="form-label" for="element_v">{$LANG->getGlobal('status')}:</label>
                    <select class="form-select" id="element_v" name="v">
                        {for $row=0 to 2}
                        <option value="{$row}"{if $row eq $DATA_SEARCH.v} selected="selected"{/if}>{$LANG->get("notification_s`$row`")}</option>
                        {/for}
                    </select>
                </div>
                <div class="flex-grow-0 flex-shrink-1 w-auto">
                    <button type="submit" class="btn btn-primary text-nowrap"><i class="fa-solid fa-magnifying-glass"></i> {$LANG->getModule('log_view')}</button>
                </div>
            </div>
        </form>
    </div>
    <div class="card-body">
        <div class="table-responsive-lg table-card" id="list-items">
            <table class="table table-striped table-sticky mb-0">
                <thead class="text-muted">
                    <tr class="notilist-head">
                        <th style="width:5%;">
                            <input class="form-check-input m-0 align-middle" type="checkbox" data-toggle="checkAll" name="check_all[]" value="yes" aria-label="{$LANG->getGlobal('toggle_checkall')}">
                        </th>
                        <th class="text-nowrap" style="width:50%;">{$LANG->get('moduleContent')}</th>
                        <th class="text-nowrap" style="width:15%;">{$LANG->get('filter_from')}</th>
                        <th class="text-nowrap" style="width:15%;">{$LANG->get('log_time')}</th>
                        <th class="text-end text-nowrap" style="width:15%;">{$LANG->get('actions')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$DATA item=row}
                    <tr class="notilist-items" data-viewed="{$row.view}" {if not $row.view} title="{$LANG->getModule('notification_s1')}"{/if}>
                        <td{if not $row.view} class="item-unread"{/if} data-td="status">
                            <input class="form-check-input m-0 mt-2 align-middle" type="checkbox" data-toggle="checkSingle" name="idcheck[]" value="{$row.id}" aria-label="{$LANG->getGlobal('toggle_checksingle')}">
                        </td>
                        <td>
                            <div class="d-flex">
                                <div class="me-2">
                                    <div class="rounded-circle overflow-hidden image">
                                        {if $row.send_from_id gt 0}
                                        {if not empty($row.photo)}
                                        <img src="{$row.photo}" alt="{$row.send_from}">
                                        {else}
                                        <i class="fa-solid fa-circle-user fa-3x text-muted"></i>
                                        {/if}
                                        {else}
                                        <i class="fa-solid fa-gear fa-3x text-muted"></i>
                                        {/if}
                                    </div>
                                </div>
                                <div class="flex-fill">
                                    {$row.title}
                                    {if not empty($row.link)}
                                    <div class="mt-1">
                                        <a href="{$row.link}" target="_blank" data-toggle="openNotiLink" data-id="{$row.id}"><i class="fa-solid fa-arrow-up-right-from-square" data-icon="fa-arrow-up-right-from-square"></i> {$LANG->getModule('open_link')}</a>
                                    </div>
                                    {/if}
                                </div>
                            </div>
                        </td>
                        <td>
                            {$row.send_from}
                        </td>
                        <td>
                            {$row.add_time}
                        </td>
                        <td class="text-end text-nowrap">
                            {if not $row.view}
                            <a title="{$LANG->get('notification_make_read')}" href="#" class="btn btn-sm btn-secondary" data-toggle="toggleNotification" data-id="{$row.id}"><i class="fa-solid fa-eye" data-icon="fa-eye"></i></a>
                            {else}
                            <a title="{$LANG->get('notification_make_unread')}" href="#" class="btn btn-sm btn-secondary" data-toggle="toggleNotification" data-id="{$row.id}"><i class="fa-solid fa-eye-slash" data-icon="fa-eye-slash"></i></a>
                            {/if}
                            <a title="{$LANG->get('delete')}" href="#" class="btn btn-sm btn-danger" data-toggle="delNotification" data-id="{$row.id}"><i class="fa-solid fa-trash" data-icon="fa-trash"></i></a>
                        </td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer border-top">
        <div class="d-flex flex-wrap justify-content-between align-items-center">
            <div class="d-flex flex-wrap flex-sm-nowrap align-items-center">
                <div class="me-2">
                    <input type="checkbox" data-toggle="checkAll" name="check_all[]" class="form-check-input m-0 align-middle" aria-label="{$LANG->getGlobal('toggle_checkall')}">
                </div>
                <div class="input-group me-1 my-1">
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {$LANG->getGlobal('select_actions')}
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" data-toggle="actionNotifications" data-action="2"><i class="fa-solid fa-eye"></i> {$LANG->get('notification_make_read')}</a></li>
                            <li><a class="dropdown-item" href="#" data-toggle="actionNotifications" data-action="3"><i class="fa-solid fa-eye-slash"></i> {$LANG->get('notification_make_unread')}</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#" data-toggle="actionNotifications" data-action="1"><i class="fa-solid fa-trash text-danger"></i> {$LANG->get('delete')}</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="pagination-wrap">
                {$GENERATE_PAGE}
            </div>
        </div>
    </div>
</div>
{/if}
