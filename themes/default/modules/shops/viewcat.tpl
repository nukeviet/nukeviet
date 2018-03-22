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
        <label class="control-label">{LANG.displays_product}</label> <select name="sort" id="sort" class="form-control input-sm" onchange="nv_chang_price();">
            <!-- BEGIN: sorts -->
            <option value="{key}"{se}>{value}</option>
            <!-- END: sorts -->
        </select> <label class="control-label">{LANG.title_viewnum}</label> <select name="viewtype" id="viewtype" class="form-control input-sm" onchange="nv_chang_viewtype();">
            <!-- BEGIN: viewtype -->
            <option value="{VIEWTYPE.key}"{VIEWTYPE.selected}>{VIEWTYPE.value}</option>
            <!-- END: viewtype -->
        </select>
    </div>
    <!-- END: displays -->
    {CONTENT}
</div>
<!-- END: main -->
