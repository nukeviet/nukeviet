<link href="{$smarty.const.NV_BASE_SITEURL}themes/{$TEMPLATE}/css/inform-manager.css" rel="stylesheet" />
<link rel="stylesheet" href="{$smarty.const.ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.css" />
<link rel="stylesheet" href="{$smarty.const.ASSETS_STATIC_URL}/js/select2/select2.min.css">
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.js"></script>
<script src="{$smarty.const.ASSETS_LANG_STATIC_URL}/js/language/jquery.ui.datepicker-{$smarty.const.NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" src="{$smarty.const.ASSETS_STATIC_URL}/js/select2/select2.min.js"></script>
<script src="{$smarty.const.ASSETS_LANG_STATIC_URL}/js/select2/i18n/{$smarty.const.NV_LANG_INTERFACE}.js"></script>
<script src="{$smarty.const.NV_BASE_SITEURL}themes/{$TEMPLATE}/js/inform-manager.js"></script>
<div id="notifications_manager" class="notifications_manager" data-url="{$MANAGER_PAGE_URL}" data-csrf="{$CHECKSS}">
    <div class="manager-heading">
        <div>
            <select class="form-control change-status">
{foreach $FILTERS as $key => $vals}
                <option value="{$key}"{if $vals.sel} selected="selected"{/if}>{$vals.name}</option>
{/foreach}
            </select>
        </div>
        <button type="button" class="btn btn-primary margin-bottom" data-toggle="inform_action" data-type="add" data-title="{$LANG->getModule('inform_add')}">{$LANG->getModule('inform_add')}</button>
    </div>
    <div id="generate_page">{$PAGE_CONTENT}</div>
    <div id="notification-action" class="notification-action" style="display:none">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="pull-right">
                    <button type="button" class="btn btn-primary active btn-xs" data-toggle="notification_action_cancel">{$LANG->getGlobal('cancel')}</button>
                </div>
                <div class="action-title"></div>
            </div>
            <div class="panel-body"></div>
        </div>
    </div>
</div>
