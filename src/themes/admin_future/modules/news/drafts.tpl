<link type="text/css" href="{$smarty.const.ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet">
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/select2/select2.min.js"></script>
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/select2/i18n/{$smarty.const.NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" src="{$smarty.const.ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{$smarty.const.ASSETS_STATIC_URL}/js/language/jquery.ui.datepicker-{$smarty.const.NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" src="{$smarty.const.ASSETS_STATIC_URL}/js/clipboard/clipboard.min.js"></script>

{if empty($ARRAY) and $SEARCH_COUNT le 0}
<div class="alert alert-info">{$LANG->getModule('draft_empty')}</div>
{else}
<div class="card list" data-del-confirm="{$LANG->getModule('draft_del_confirm')}">
    <div class="card-body">
        <form method="get" action="{$smarty.const.NV_BASE_ADMINURL}index.php">
            <input type="hidden" name="{$smarty.const.NV_LANG_VARIABLE}" value="{$smarty.const.NV_LANG_DATA}">
            <input type="hidden" name="{$smarty.const.NV_NAME_VARIABLE}" value="{$MODULE_NAME}">
            <input type="hidden" name="{$smarty.const.NV_OP_VARIABLE}" value="{$OP}">
            <div class="row g-3">
                <div class="col-6 col-md-4 col-xl-3 col-xxl-2">
                    <div class="input-group flex-nowrap">
                        <input type="text" name="f" value="{$SEARCH.from}" class="form-control datepicker" placeholder="{$LANG->getModule('from_date_short')}" aria-label="{$LANG->getModule('from_date_short')}" aria-describedby="element_from_btn" autocomplete="off">
                        <button class="btn btn-secondary" type="button" id="element_from_btn" data-toggle="focusDate" aria-label="{$LANG->getModule('from_date_short')}"><i class="fa-regular fa-calendar"></i></button>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-xl-3 col-xxl-2">
                    <div class="input-group flex-nowrap">
                        <input type="text" name="t" value="{$SEARCH.to}" class="form-control datepicker" placeholder="{$LANG->getModule('to_date_short')}" aria-label="{$LANG->getModule('to_date_short')}" aria-describedby="element_to_btn" autocomplete="off">
                        <button class="btn btn-secondary" type="button" id="element_to_btn" data-toggle="focusDate" aria-label="{$LANG->getModule('to_date_short')}"><i class="fa-regular fa-calendar"></i></button>
                    </div>
                </div>
                <div class="col-md-4 col-xl-6 col-xxl-8">
                    <button type="submit" class="btn btn-primary text-nowrap"><i class="fa-solid fa-filter"></i> {$LANG->getGlobal('view')}</button>
                </div>
            </div>
        </form>
    </div>
    <div class="card-body">
        <div class="table-responsive-lg table-card">
            <table class="table table-striped align-middle table-sticky mb-0">
                <thead class="text-muted">
                    <tr>
                        <th class="text-nowrap" style="width: 1%;">
                            <input type="checkbox" data-toggle="checkAll" name="checkAll[]" class="form-check-input m-0 align-middle" aria-label="{$LANG->getGlobal('toggle_checkall')}">
                        </th>
                        <th class="text-nowrap" style="width: 50%;">{$LANG->getModule('name')}</th>
                        <th class="text-nowrap" style="width: 30%;">{$LANG->getModule('post_time')}</th>
                        <th class="text-nowrap text-center" style="width: 20%;">{$LANG->getModule('function')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$ARRAY key=key item=row}
                    <tr>
                        <td>
                            <input type="checkbox" data-toggle="checkSingle" name="checkSingle[]" value="{$row.id}" class="form-check-input m-0 align-middle" aria-label="{$LANG->getGlobal('toggle_checksingle')}">
                        </td>
                        <td>
                            {$row.title ?: $LANG->getModule('name_empty')}
                            {if not $row.allowed_edit and $row.my_draft}
                            <div class="text-danger small">{$LANG->getModule('draft_not_allowed')}</div>
                            {/if}
                        </td>
                        <td>
                            {$row.time_late|dformat}
                        </td>
                        <td class="text-center">
                            <div class="hstack gap-1 justify-content-center">
                                {if $row.allowed_edit and $row.my_draft}
                                <a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=content{if not empty($row.new_id)}&amp;id={$row.new_id}{/if}&amp;draft_id={$row.id}" class="btn btn-sm btn-secondary text-nowrap"><i class="fa-solid fa-pencil"></i> {$LANG->getModule('draft_continue')}</a>
                                {/if}
                                <button type="button" class="btn btn-sm btn-danger" data-toggle="draft_cancel" data-id="{$row.id}"><i class="fa-solid fa-circle-xmark" data-icon="fa-circle-xmark"></i> {$LANG->getGlobal('cancel')}</button>
                            </div>
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
                    <input type="checkbox" data-toggle="checkAll" name="checkAll[]" class="form-check-input m-0 align-middle" aria-label="{$LANG->getGlobal('toggle_checkall')}">
                </div>
                <div class="input-group me-1 my-1">
                    <select id="element_action" class="form-select fw-150" aria-label="{$LANG->getGlobal('select_actions')}" aria-describedby="element_action_btn">
                        <option value="cancel">{$LANG->getGlobal('cancel')}</option>
                    </select>
                    <button class="btn btn-primary" type="button" id="element_action_btn" data-toggle="actionDrafts">{$LANG->getModule('action')}</button>
                </div>
            </div>
            <div class="pagination-wrap">
                {$PAGINATION}
            </div>
        </div>
    </div>
</div>
{/if}
