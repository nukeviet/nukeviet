<link href="{$smarty.const.ASSETS_STATIC_URL}/js/perfect-scrollbar/style{$smarty.const.AUTO_MINIFIED}.css" rel="stylesheet" />
<link href="{$smarty.const.NV_STATIC_URL}themes/{$BLOCK_JS}/css/block.inform.css" rel="stylesheet" />
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/perfect-scrollbar/min.js" charset="utf-8"></script>
<script src="{$smarty.const.NV_STATIC_URL}themes/{$BLOCK_JS}/js/block.inform.js"></script>

<div class="inform-notification dropdown" id="inform-notification" data-refresh-time="{$REFRESH_TIME}" data-url="{$INFORM_MODULE_URL}" data-checkinform-url="{$CHECK_INFORM_URL}" data-userid="{$USERID}" data-usergroups="{$USERGROUPS}" data-csrf="{$CSRF}">
    <a href="#" class="dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <em class="fa fa-bell-o"></em>
        <span class="new-count" style="display:none"></span>
    </a>
    <div class="dropdown-menu inform-box">
        <div class="inform-header">
            <button type="button" class="btn-close">&times;</button>
            <div class="filter-box">
                <input type="hidden" name="aj_filter" value="{$FILTER_DEFAULT}">
{foreach $FILTERS as $key => $vals}
                <button type="button" class="btn {$key}{if $vals.is_active} active{/if}" data-toggle="changeFilter" data-filter="{$key}">{$vals.name}</button>
{/foreach}
            </div>
        </div>
        <div class="inform-content"></div>
        <div class="inform-footer">
            <a href="#" data-toggle="informNotifyRefresh">{$LANG->getGlobal('refresh')}</a>
            <a href="{$INFORM_VIEWALL_URL}">{$LANG->getGlobal('viewall')}</a>
        </div>
    </div>
</div>
