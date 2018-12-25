<!-- BEGIN: main -->
<div class="viewcat">
    <h1 class="pull-left">{TITLE}</h1>
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
    <!-- END: displays -->
    <div class="clearfix"></div>
    <hr>
    <div id="shops-content">{CONTENT}</div>
</div>
<!-- END: main -->
