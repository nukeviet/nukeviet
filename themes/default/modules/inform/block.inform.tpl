<!-- BEGIN: main -->
<link href="{ASSETS_STATIC_URL}/js/perfect-scrollbar/style{AUTO_MINIFIED}.css" rel="stylesheet" />
<link href="{NV_STATIC_URL}themes/{BLOCK_JS}/css/block.inform.css" rel="stylesheet" />
<script src="{ASSETS_STATIC_URL}/js/perfect-scrollbar/min.js" charset="utf-8"></script>
<script src="{NV_STATIC_URL}themes/{BLOCK_JS}/js/block.inform.js"></script>

<div class="inform-notification dropdown" id="inform-notification" data-refresh-time="{REFRESH_TIME}" data-url="{INFORM_MODULE_URL}" data-checkinform-url="{CHECK_INFORM_URL}" data-userid="{USERID}" data-usergroups="{USERGROUPS}" data-csrf="{CSRF}">
    <a href="#" class="dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <em class="fa fa-bell-o"></em>
        <span class="new-count" style="display:none"></span>
    </a>
    <div class="dropdown-menu inform-box">
        <div class="inform-header">
            <button type="button" class="btn-close">&times;</button>
            <div class="filter-box">
                <input type="hidden" name="aj_filter" value="{FILTER_DEFAULT}">
                <!-- BEGIN: filter -->
                <button type="button" class="btn {FILTER.key}<!-- BEGIN: default --> active<!-- END: default -->" data-toggle="changeFilter" data-filter="{FILTER.key}">{FILTER.name}</button>
                <!-- END: filter -->
            </div>
        </div>
        <div class="inform-content"></div>
        <div class="inform-footer">
            <a href="#" data-toggle="informNotifyRefresh">{GLANG.refresh}</a>
            <a href="{INFORM_MODULE_URL}">{GLANG.viewall}</a>
        </div>
    </div>
</div>
<!-- END: main -->