<link rel="stylesheet" href="{$smarty.const.ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.css">
<link rel="stylesheet" href="{$smarty.const.ASSETS_STATIC_URL}/js/select2/select2.min.css">
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.js"></script>
<script src="{$smarty.const.ASSETS_LANG_STATIC_URL}/js/language/jquery.ui.datepicker-{$smarty.const.NV_LANG_INTERFACE}.js"></script>
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/select2/select2.min.js"></script>
<script src="{$smarty.const.ASSETS_LANG_STATIC_URL}/js/select2/i18n/{$smarty.const.NV_LANG_INTERFACE}.js"></script>
<script src="{$smarty.const.NV_STATIC_URL}themes/{$INFORM_MANAGER_THEME}/js/inform-manager.js"></script>
<div id="notifications_manager" class="notifications_manager"
     data-url="{$MANAGER_PAGE_URL}"
     data-csrf="{$CHECKSS}"
     data-delete-confirm="{$LANG->getModule('delete_confirm')}"
     data-label-id="{$LANG->getModule('id')}"
     data-label-username="{$LANG->getModule('username')}"
     data-label-fullname="{$LANG->getModule('fullname')}">
    <div class="d-flex align-items-center gap-2 mb-3">
        <div class="flex-grow-1">
            <select class="form-select w-auto change-status">
                {foreach $FILTERS as $filter}
                <option value="{$filter.key}"{if $filter.key == $CURRENT_FILTER} selected{/if}>{$filter.name}</option>
                {/foreach}
            </select>
        </div>
        <button type="button" class="btn btn-primary"
                data-toggle="inform_action" data-type="add"
                data-title="{$LANG->getModule('inform_add')}">
            <i class="fa-solid fa-plus"></i> {$LANG->getModule('inform_add')}
        </button>
    </div>
    <div id="generate_page">{$PAGE_CONTENT}</div>
    <div id="notification-action" class="notification-action d-none">
        <div class="card border-primary">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <div class="action-title fw-medium"></div>
                <button type="button" class="btn btn-sm btn-outline-light" data-toggle="notification_action_cancel">
                    {$LANG->getGlobal('cancel')}
                </button>
            </div>
            <div class="card-body action-body"></div>
        </div>
    </div>
</div>
