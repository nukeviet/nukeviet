<!-- BEGIN: main -->
<div class="viewtag shops-tag-page shops-tag-page-{MODULE_NAME}" id="category">
    <div class="page-header">
        <h1>{TITLE}</h1>
        <!-- BEGIN: bodytext -->
        <div class="m-bottom margin-bottom">{BODYTEXT}</div>
        <!-- END: bodytext -->
    </div>
    <!-- BEGIN: displays -->
    <div class="form-group text-right s-cat-fillter">
        <select name="sort" id="sort" class="form-control input-sm d-inline-block" onchange="nv_chang_price();">
                <!-- BEGIN: sorts -->
                <option value="{key}"{se}>{value}</option>
                <!-- END: sorts -->
        </select>
        <!-- BEGIN: viewtype -->
        <div class="viewtype d-inline-block">
            <span class="pointer {VIEWTYPE.active}" onclick="nv_chang_viewtype('{VIEWTYPE.index}');" title="{VIEWTYPE.title}"><i class="fa fa-{VIEWTYPE.icon} fa-lg"></i></span>
        </div>
        <!-- END: viewtype -->
    </div>
    <!-- END: displays -->
    <div id="shops-content">
        {CONTENT}
    </div>
</div>
<!-- END: main -->
