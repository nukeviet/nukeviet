<!-- BEGIN: main -->
<div class="viewcat">
    <div class="page-header">
        <h1>{CAT_NAME} ({COUNT} {LANG.title_products})</h1>
        <!-- BEGIN: viewdescriptionhtml -->
        <!-- BEGIN: image -->
        <div class="text-center">
            <img src="{IMAGE}" class="img-thumbnail" alt="{CAT_NAME}">
        </div>
        <!-- END: image -->
        <p>{DESCRIPTIONHTML}</p>
        <!-- END: viewdescriptionhtml -->
    </div>
    <!-- BEGIN: displays -->
    <div class="form-group form-inline pull-right">
        <label class="control-label"><select name="sort" id="sort" class="form-control input-sm" onchange="nv_chang_price();">
                <!-- BEGIN: sorts -->
                <option value="{key}"{se}>{value}</option>
                <!-- END: sorts -->
        </select>&nbsp;&nbsp;</label>
        <!-- BEGIN: viewtype -->
        <div class="viewtype">
            <span class="pointer {VIEWTYPE.active}" onclick="nv_chang_viewtype('{VIEWTYPE.index}');" title="{VIEWTYPE.title}"><em class="fa fa-{VIEWTYPE.icon} fa-lg">&nbsp;</em></span>
        </div>
        <!-- END: viewtype -->
    </div>
    <div class="clearfix"></div>
    <!-- END: displays -->
    <div id="shops-content">{CONTENT}</div>
</div>
<!-- END: main -->
