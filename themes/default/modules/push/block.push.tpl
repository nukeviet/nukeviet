<!-- BEGIN: main -->
<link href="{ASSETS_STATIC_URL}/js/perfect-scrollbar/style{AUTO_MINIFIED}.css" rel="stylesheet" />
<link href="{NV_STATIC_URL}themes/{BLOCK_JS}/css/block.push.css" rel="stylesheet" />
<script src="{ASSETS_STATIC_URL}/js/perfect-scrollbar/min.js" charset="utf-8"></script>
<script src="{NV_STATIC_URL}themes/{BLOCK_JS}/js/block.push.js"></script>

<div class="push-notification dropdown" id="push-notification" data-refresh-time="{REFRESH_TIME}" data-url="{PUSH_MODULE_URL}" data-userid="{USERID}" data-usergroups="{USERGROUPS}" data-csrf="{CSRF}">
    <a href="#" class="dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <em class="fa fa-bell-o"></em>
        <span class="new-count" style="display:none"></span>
    </a>
    <div class="dropdown-menu push-box">
        <div class="push-header">
            <button type="button" class="btn-close">&times;</button>
            <div class="filter-box">
                <input type="hidden" name="aj_filter" value="{FILTER_DEFAULT}">
                <!-- BEGIN: filter -->
                <button type="button" class="btn {FILTER.key}<!-- BEGIN: default --> active<!-- END: default -->" data-toggle="changeFilter" data-filter="{FILTER.key}">{FILTER.name}</button>
                <!-- END: filter -->
            </div>
        </div>
        <div class="push-content"></div>
        <div class="push-footer">
            <a href="#" data-toggle="pushNotifyRefresh">{GLANG.refresh}</a>
            <a href="{PUSH_MODULE_URL}">{GLANG.viewall}</a>
        </div>
    </div>
</div>
<!-- END: main -->