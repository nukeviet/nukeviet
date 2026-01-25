<link type="text/css" href="{$smarty.const.ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet">
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/select2/select2.min.js"></script>
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/select2/i18n/{$smarty.const.NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" src="{$smarty.const.ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{$smarty.const.ASSETS_STATIC_URL}/js/language/jquery.ui.datepicker-{$smarty.const.NV_LANG_INTERFACE}.js"></script>
<div class="card">
    <div class="card-body">
        <form method="get" action="{$smarty.const.NV_BASE_ADMINURL}index.php">
            <input type="hidden" name="{$smarty.const.NV_LANG_VARIABLE}" value="{$smarty.const.NV_LANG_DATA}">
            <input type="hidden" name="{$smarty.const.NV_NAME_VARIABLE}" value="{$MODULE_NAME}">
            <input type="hidden" name="{$smarty.const.NV_OP_VARIABLE}" value="{$OP}">
            <div class="row g-3 flex-xxl-nowrap">
                <div class="col-md-3 flex-xxl-fill">
                    <input type="text" class="form-control" name="q" value="{$SEARCH.q}" placeholder="{$LANG->getModule('filter_enterkey')}" aria-label="{$LANG->getModule('filter_enterkey')}">
                </div>
                <div class="col-6 col-md-3 flex-xxl-fill">
                    <div class="input-group flex-nowrap">
                        <input type="text" name="from" value="{$SEARCH.from}" class="form-control datepicker" placeholder="{$LANG->getModule('filter_from')}" aria-label="{$LANG->getModule('filter_from')}" aria-describedby="element_from_btn" autocomplete="off">
                        <button class="btn btn-secondary" type="button" id="element_from_btn" data-toggle="focusDate" aria-label="{$LANG->getModule('filter_from')}"><i class="fa-regular fa-calendar"></i></button>
                    </div>
                </div>
                <div class="col-6 col-md-3 flex-xxl-fill">
                    <div class="input-group flex-nowrap">
                        <input type="text" name="to" value="{$SEARCH.to}" class="form-control datepicker" placeholder="{$LANG->getModule('filter_to')}" aria-label="{$LANG->getModule('filter_to')}" aria-describedby="element_to_btn" autocomplete="off">
                        <button class="btn btn-secondary" type="button" id="element_to_btn" data-toggle="focusDate" aria-label="{$LANG->getModule('filter_to')}"><i class="fa-regular fa-calendar"></i></button>
                    </div>
                </div>
                <div class="col-md-3 col-xxl-2">
                    <select class="form-select select2" name="lang" aria-label="{$LANG->getModule('filter_lang')}">
                        <option value="">{$LANG->getModule('filter_lang')}</option>
                        {foreach from=$ARRAY_LANG item=lang}
                        <option value="{$lang.key}"{if $lang.key eq $SEARCH.lang} selected{/if}>{$lang.title}</option>
                        {/foreach}
                    </select>
                </div>
                <div class="col-md-auto col-xxl-2 flex-md-grow-1 flex-md-shrink-1 flex-xxl-grow-0 flex-xxl-shrink-0">
                    <select class="form-select select2" name="user" aria-label="{$LANG->getModule('filter_user')}">
                        {foreach from=$ARRAY_USER item=user}
                        <option value="{$user.key}"{if $user.key eq $SEARCH.user} selected{/if}>{$user.title}</option>
                        {/foreach}
                    </select>
                </div>
                <div class="col-md-auto col-xxl-2 flex-md-grow-1 flex-md-shrink-1 flex-xxl-grow-0 flex-xxl-shrink-0">
                    <select class="form-select select2" name="module" aria-label="{$LANG->getModule('filter_module')}">
                        <option value="">{$LANG->getModule('filter_module')}</option>
                        {foreach from=$ARRAY_MODULE item=module}
                        <option value="{$module.key}"{if $module.key eq $SEARCH.module} selected{/if}>{$module.title}</option>
                        {/foreach}
                    </select>
                </div>
                <div class="flex-grow-0 flex-shrink-1 w-auto">
                    <button type="submit" class="btn btn-primary text-nowrap"><i class="fa-solid fa-filter"></i> {$LANG->getModule('filter_action')}</button>
                </div>
            </div>
        </form>
    </div>
    <div class="card-body">
        <div class="table-responsive-lg table-card" id="list-items">
            <table class="table align-middle table-sticky mb-0">
                <thead class="text-muted">
                    <tr>
                        <th class="text-nowrap" style="width: 1%;">
                            <input type="checkbox" data-toggle="checkAll" class="form-check-input m-0 align-middle" aria-label="{$LANG->getGlobal('toggle_checkall')}">
                        </th>
                        <th class="text-nowrap" style="width: 10%;">
                            <a href="{$BASE_URL_ORDER}{if $ARRAY_ORDER.field neq 'lang' or $ARRAY_ORDER.value neq 'desc'}&amp;of=lang{if $ARRAY_ORDER.field neq 'lang' or empty($ARRAY_ORDER.value)}&amp;ov=asc{else}&amp;ov=desc{/if}{/if}" class="d-flex align-items-center justify-content-between">
                                <span class="me-1">{$LANG->getModule('log_lang')}</span>
                                {if $ARRAY_ORDER.field neq 'lang' or empty($ARRAY_ORDER.value)}<i class="fa-solid fa-sort"></i>{elseif $ARRAY_ORDER.value eq 'asc'}<i class="fa-solid fa-sort-up"></i>{else}<i class="fa-solid fa-sort-down"></i>{/if}
                            </a>
                        </th>
                        <th class="text-nowrap" style="width: 10%;">
                            <a href="{$BASE_URL_ORDER}{if $ARRAY_ORDER.field neq 'module_name' or $ARRAY_ORDER.value neq 'desc'}&amp;of=module_name{if $ARRAY_ORDER.field neq 'module_name' or empty($ARRAY_ORDER.value)}&amp;ov=asc{else}&amp;ov=desc{/if}{/if}" class="d-flex align-items-center justify-content-between">
                                <span class="me-1">{$LANG->getModule('log_module_name')}</span>
                                {if $ARRAY_ORDER.field neq 'module_name' or empty($ARRAY_ORDER.value)}<i class="fa-solid fa-sort"></i>{elseif $ARRAY_ORDER.value eq 'asc'}<i class="fa-solid fa-sort-up"></i>{else}<i class="fa-solid fa-sort-down"></i>{/if}
                            </a>
                        </th>
                        <th class="text-nowrap" style="width: 19.6%;">{$LANG->getModule('log_name_key')}</th>
                        <th class="text-nowrap" style="width: 19.6%;">{$LANG->getModule('log_note')}</th>
                        <th class="text-nowrap" style="width: 19.6%;">{$LANG->getModule('log_username')}</th>
                        <th class="text-nowrap" style="width: 19.6%;">
                            <a href="{$BASE_URL_ORDER}{if $ARRAY_ORDER.field neq 'log_time' or $ARRAY_ORDER.value neq 'desc'}&amp;of=log_time{if $ARRAY_ORDER.field neq 'log_time' or empty($ARRAY_ORDER.value)}&amp;ov=asc{else}&amp;ov=desc{/if}{/if}" class="d-flex align-items-center justify-content-between">
                                <span class="me-1">{$LANG->getModule('log_time')}</span>
                                {if $ARRAY_ORDER.field neq 'log_time' or empty($ARRAY_ORDER.value)}<i class="fa-solid fa-sort"></i>{elseif $ARRAY_ORDER.value eq 'asc'}<i class="fa-solid fa-sort-up"></i>{else}<i class="fa-solid fa-sort-down"></i>{/if}
                            </a>
                        </th>
                        {if $ALLOWED_DELETE}
                        <th class="text-nowrap text-end" style="width: 1%;">{$LANG->getModule('log_feature')}</th>
                        {/if}
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$DATA key=key item=row}
                    <tr>
                        <td>
                            <input type="checkbox" data-toggle="checkSingle" value="{$row.id}" class="form-check-input m-0 align-middle" aria-label="{$LANG->getGlobal('toggle_checksingle')}">
                        </td>
                        <td>{$row.lang}</td>
                        <td>{$row.custom_title}</td>
                        <td class="text-break">{$row.name_key}</td>
                        <td class="text-break">{$row.note_action}</td>
                        <td>{$row.username}</td>
                        <td>{$row.time}</td>
                        {if $ALLOWED_DELETE}
                        <td class="text-nowrap text-center">
                            <a href="#" title="{$LANG->getGlobal('delete')}" aria-label="{$LANG->getGlobal('delete')}" data-toggle="logDelOne" data-id="{$row.id}" data-confirm="{$LANG->getModule('log_del_confirm')}"><i class="fa-solid fa-trash text-danger" data-icon="fa-trash"></i></a>
                        </td>
                        {/if}
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer border-top">
        <div class="d-flex flex-wrap justify-content-between align-items-center">
            <div>
                {if $ALLOWED_DELETE}
                <input type="checkbox" data-toggle="checkAll" class="form-check-input m-0 align-middle" aria-label="{$LANG->getGlobal('toggle_checkall')}">
                <button class="btn btn-outline-danger ms-2 me-1 my-1" data-toggle="logDelMulti" data-ctn="#list-items" data-confirm="{$LANG->getModule('log_del_confirm')}"><i class="fa-solid fa-trash" data-icon="fa-trash"></i> {$LANG->getGlobal('delete')}</button>
                <button class="btn btn-danger me-1 my-1" data-toggle="logTruncate" data-confirm="{$LANG->getModule('log_del_confirm')}" data-url="{$BASE_URL}"><i class="fa-solid fa-ban" data-icon="fa-ban"></i> {$LANG->getModule('log_empty')}</button>
                {/if}
            </div>
            <div class="pagination-wrap">
                {$PAGINATION}
            </div>
        </div>
    </div>
</div>
